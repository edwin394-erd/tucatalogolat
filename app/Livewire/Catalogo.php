<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CatalogVisit;
use App\Models\Catalogo as CatalogoModel;

class Catalogo extends Component
{
    use WithPagination;

    public $name;
    public $search = '';
    public $categoryId = null;
    public $scrollToTop = false;
    public $selectedCategory = null;
    public $cartItemCount = 0;

    public $subscripcionActiva = false;

    protected $paginationTheme = 'tailwind';

    // Eliminamos 'page' de aquí para que no entre en conflicto con el trait
    protected $queryString = [
        'search' => ['except' => ''],
        'categoryId' => ['except' => null]
    ];

    public function mount($name)
    {
        $catalogo = \App\Models\Catalogo::resolveByName($name);

        if ($catalogo) {
            $resolvedHandle = $catalogo->name_handle;
            if ($resolvedHandle !== $name) {
                $this->redirectRoute('catalogo', $resolvedHandle);
                return;
            }

            $this->name = $resolvedHandle;
            $this->registerVisit($catalogo);
            $this->selectedCategory = $this->categoryId;
            return;
        }

        $this->name = \App\Models\Catalogo::generateHandle($name);
        $resolvedCatalogo = \App\Models\Catalogo::resolveByName($this->name);
        if ($resolvedCatalogo) {
            $this->registerVisit($resolvedCatalogo);
        }
        $this->selectedCategory = $this->categoryId;
    }

    private function registerVisit(CatalogoModel $catalogo): void
    {
        $sessionId = session()->getId();
        $alreadyVisited = CatalogVisit::where('catalogo_id', $catalogo->id)
            ->where('session_id', $sessionId)
            ->where('visited_at', '>=', now()->startOfDay())
            ->exists();

        if (! $alreadyVisited) {
            CatalogVisit::create([
                'catalogo_id' => $catalogo->id,
                'session_id' => $sessionId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'visited_at' => now(),
            ]);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $catalogo = \App\Models\Catalogo::resolveByName($this->name) ?? \App\Models\Catalogo::where('name_handle', $this->name)->firstOrFail();
        $catalogo->load(['categories', 'plantilla']);

        if (auth()->check() && auth()->id() === $catalogo->user_id && ! $catalogo->isConfigurationComplete()) {
            session()->flash('message', __('messages.complete_config_before_catalog'));
            $this->redirectRoute('configuracion');
        }

            $latestSubscription = $catalogo->user->subscriptions()->latest('expires_at')->first();
            $this->subscripcionActiva = $latestSubscription && $latestSubscription->expires_at && $latestSubscription->expires_at->isFuture();

            if (!$this->subscripcionActiva) {
                return view('livewire.expiro')->extends('layouts.guest')->section('content')
                ->with('catalogo', $catalogo);
            }


        $products = $catalogo->products()
            ->with(['fotos', 'variants'])
            ->when($this->search, function ($query) {
                $searchWords = collect(preg_split('/\s+/', trim($this->search)))
                    ->filter()
                    ->map(fn ($word) => strtolower($word));

                foreach ($searchWords as $word) {
                    $query->where(function ($productQuery) use ($word) {
                        $productQuery->whereRaw('LOWER(name) LIKE ?', ["%{$word}%"])
                            ->orWhereRaw('LOWER(description) LIKE ?', ["%{$word}%"]);
                    });
                }
            })
            ->when($this->categoryId, fn ($query) => $query->where('category_id', $this->categoryId))
            ->latest()
            ->paginate(30);

        $catalogo->setRelation('products', $products);
        $this->cartItemCount = Cart::findCurrent($catalogo->id)?->count ?? 0;

        if ($catalogo->plantilla->id === 1) {
            $view = 'livewire.catalogo';
        } elseif ($catalogo->plantilla->id === 2) {
            $view = 'livewire.catalogo2';
        } elseif ($catalogo->plantilla->id === 3) {
            $view = 'livewire.catalogo3';
        } else {
            $view = 'livewire.catalogo';
        }

        return view($view, compact('catalogo'))->extends('layouts.catalogo1');
    }

    public function filterByCategory($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->resetPage();
        $this->selectedCategory = $categoryId; // Actualizamos la categoría seleccionada

    }

    public function clearCategoryFilter()
    {
        $this->categoryId = null;
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        $catalogo = \App\Models\Catalogo::where('name_handle', \App\Models\Catalogo::generateHandle($this->name))->firstOrFail();
        $product = Product::where('catalogo_id', $catalogo->id)->findOrFail($productId);

        $cart = Cart::current($catalogo->id);
        $cart->addProduct($product);

        $this->cartItemCount = $cart->count;
        session()->now('message', __('messages.added_to_cart'));
    }
}
