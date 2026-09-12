<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Cart;
use App\Models\Product;

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
            $this->selectedCategory = $this->categoryId;
            return;
        }

        $this->name = \App\Models\Catalogo::generateHandle($name);
        $this->selectedCategory = $this->categoryId;
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
        $catalogo->load(['categories', 'products.fotos', 'plantilla']);

        if (auth()->check() && auth()->id() === $catalogo->user_id && ! $catalogo->isConfigurationComplete()) {
            session()->flash('message', __('messages.complete_config_before_catalog'));
            $this->redirectRoute('configuracion');
        }

            $this->subscripcionActiva = $catalogo->user->subscriptions->last() && $catalogo->user->subscriptions->last()->expires_at > now();

            if (!$this->subscripcionActiva) {
                return view('livewire.expiro')->extends('layouts.guest')->section('content')
                ->with('catalogo', $catalogo);
            }


        // Filtrado de la colección
        $products = $catalogo->products;

        if ($this->search) {
            $search = trim(preg_replace('/\s+/', ' ', $this->search));
            $searchWords = collect(explode(' ', strtolower($search)))->filter();

            $products = $products->filter(function ($product) use ($searchWords) {
                $haystack = strtolower(($product->name ?? '') . ' ' . ($product->description ?? ''));

                return $searchWords->every(fn($word) => str_contains($haystack, $word));
            });
        }

        if ($this->categoryId) {
            $products = $products->where('category_id', $this->categoryId);
        }

        $products = $products->sortByDesc('created_at')->values();

        // Paginación manual compatible con Livewire
        $perPage = 12;
        $currentPage = $this->paginators['page'] ?? 1; // Recupera la página del estado de Livewire
        $currentItems = $products->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedProducts = new LengthAwarePaginator(
            $currentItems,
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        $catalogo->setRelation('products', $paginatedProducts);
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
