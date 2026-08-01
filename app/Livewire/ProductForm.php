<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Product;
use App\Models\Foto;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductForm extends Component
{
    use WithFileUploads;

    public $ItemId;
    public $name;
    public $price;
    public $category;
    public $precio_descuento;
    public $description;
    public $images = []; // Nuevas imágenes temporales
    public $existingImages = []; // Imágenes guardadas en la base de datos
    public $imagesToDelete = []; // IDs de imágenes que se marcaron para eliminar
    public $visible;
    public $maximoProductos;
    public $productosActuales;
    public $variants = []; // Variantes del producto

    public $categories = [];

    public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category' => 'required|exists:categories,id',
        'visible' => 'required|boolean',
        'description' => 'nullable|string',
        'images' => 'array',
        'images.*' => 'mimes:jpeg,png,jpg,gif,webp|max:2048',
        'variants' => 'array',
        'variants.*.size' => 'nullable|string|max:50',
        'variants.*.color' => 'nullable|string|max:50',
        'variants.*.price_adjustment' => 'nullable|numeric',
        'variants.*.stock' => 'nullable|integer|min:0',
    ];
}

public function messages(): array
{
    return [
        'name.required' => __('messages.product_name_required'),
        'price.required' => __('messages.price_required'),
        'price.numeric' => __('messages.price_numeric'),
        'category.required' => __('messages.category_required'),
        'category.exists' => __('messages.category_exists'),
        'precio_descuento.required' => __('messages.precio_descuento_required'),
        'images.*.mimes' => __('messages.images_mimes'),
        'images.*.max' => __('messages.images_max'),
    ];
}
    
    public function mount($ItemId = null)
    {
        $user = auth()->user();
      
        $maximoProductos = $user->subscriptions->last() ? $user->subscriptions->last()->plan->max_products : 0;
        $this->maximoProductos = $maximoProductos;
    
        $productosActuales = $user->catalogo->products()->count();
        $this->productosActuales = $productosActuales;

        

        $this->categories = Category::where('catalogo_id', $user->catalogo->id)->get();
        $this->ItemId = $ItemId;

        if($this->visible === null && !$this->ItemId){ 
            $this->visible = true; // Valor predeterminado
        }


        if ($this->ItemId) {
            
            $product = Product::with('fotos', 'variants')->find($this->ItemId);
            
            if ($product) {
                $this->name = $product->name;
                $this->price = $product->price;
                $this->category = $product->category_id;
                $this->precio_descuento = $product->precio_descuento;
                $this->visible = (bool)$product->visible;
                $this->description = $product->description;
                $this->existingImages = $product->fotos->toArray();
                $this->variants = $product->variants->toArray();
            }
        }
    }
    
    public function render()
    {

          return view('livewire.product-form');
    }
        

    

    // Marca una imagen existente para ser eliminada cuando se guarde el formulario.
    public function markImageForDeletion($imageId)
    {
        $this->imagesToDelete[] = $imageId;
        $this->existingImages = collect($this->existingImages)->filter(function ($item) use ($imageId) {
            return $item['id'] != $imageId;
        })->values()->toArray();
    }
    
    // Elimina una imagen nueva (temporal) antes de que se guarde.
    public function removeNewImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function addVariant()
    {
        $this->variants[] = ['size' => '', 'color' => '', 'price_adjustment' => 0, 'stock' => 0];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }
 
    
  public function save()
{
    $this->validate();

    if (count($this->images) + count($this->existingImages) === 0) {
        $this->addError('images', 'El producto debe tener al menos una imagen.');
        return;
    }

    // Datos base para crear o actualizar

    $data = [
        'name' => $this->name,
        'price' => $this->price,
        'category_id' => $this->category,
        'precio_descuento' => $this->precio_descuento,
        'visible' => (int) $this->visible,
        'description' => $this->description,
    ];

    if ($this->ItemId) {
        $product = Product::find($this->ItemId);
        $product->update($data);

        foreach ($this->imagesToDelete as $imageId) {
            $image = Foto::find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->url);
                $image->delete();
            }
        }
    } else {
        $data['catalogo_id'] = auth()->user()->catalogo->id;
        $product = Product::create($data);
    }

    // Optimización y guardado de nuevas imágenes
    try {
        $manager = ImageManager::gd();
    } catch (\Throwable $exception) {
        \Log::error('ProductForm image manager initialization failed', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);

        $this->addError('images', __('messages.image_processing_error'));
        return;
    }

    foreach ($this->images as $image) {
        try {
            $path = $image->getRealPath() ?: $image->getPathname();
            if (! $path) {
                throw new \RuntimeException('Uploaded image has no accessible temporary path.');
            }

            $img = $manager->read($path)
                           ->scale(800);

            $extension = function_exists('imagewebp') ? 'webp' : 'jpg';
            $quality = $extension === 'webp' ? 80 : 90;
            $encoded = $img->encodeByExtension($extension, $quality);

            $name = 'products/' . uniqid() . '.' . $extension;
            $saved = Storage::disk('public')->put($name, (string) $encoded);

            if (! $saved) {
                throw new \RuntimeException('Failed to write image to public disk: ' . $name);
            }

            $product->fotos()->create([
                'url' => $name,
                'imageable_type' => Product::class,
            ]);
        } catch (\Throwable $exception) {
            \Log::error('ProductForm image save failed', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'image_name' => $name ?? null,
                'image_path' => $path ?? null,
                'image_extension' => $extension ?? null,
            ]);

            $this->addError('images', __('messages.image_processing_error'));
            return;
        }
    }

    // Manejar variantes
    $product->variants()->delete(); // Eliminar existentes
    foreach ($this->variants as $variant) {
        if (!empty($variant['size']) || !empty($variant['color'])) {
            $product->variants()->create($variant);
        }
    }

    session()->flash('message', $this->ItemId ? __('messages.product_updated') : __('messages.product_created'));
    $this->redirectRoute('products');
}
}