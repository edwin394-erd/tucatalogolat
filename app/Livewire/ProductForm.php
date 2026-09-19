<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Product;
use App\Models\foto;
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
    public $customVariants = []; // Variantes personalizadas con nombre y valores
    public $allowSizeVariants = false;
    public $allowColorVariants = false;
    public $sizeOptions = [];
    public $colorOptions = [];

    public $categories = [];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:categories,id',
            'visible' => 'required|boolean',
            'description' => 'nullable|string',
            'images' => 'array|max:5',
            'images.*' => 'mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants' => 'array',
            'variants.*.name' => 'nullable|string|max:100',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.price_adjustment' => 'nullable|numeric',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.available' => 'nullable|boolean',
            'customVariants' => 'array',
            'customVariants.*.name' => 'nullable|string|max:100',
            'customVariants.*.values' => 'array',
            'customVariants.*.values.*.value' => 'nullable|string|max:50',
            'customVariants.*.values.*.available' => 'nullable|boolean',
            'sizeOptions' => 'array',
            'sizeOptions.*.value' => 'nullable|string|max:50',
            'sizeOptions.*.price_adjustment' => 'nullable|numeric',
            'sizeOptions.*.available' => 'nullable|boolean',
            'colorOptions' => 'array',
            'colorOptions.*.value' => 'nullable|string|max:50',
            'colorOptions.*.price_adjustment' => 'nullable|numeric',
            'colorOptions.*.available' => 'nullable|boolean',
            'customVariants.*.values.*.price_adjustment' => 'nullable|numeric',
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
            'images.max' => __('messages.images_limit'),
        ];
    }

    public function mount($ItemId = null)
    {
        $user = auth()->user();

        $latestSubscription = $user->subscriptions()->latest('expires_at')->first();
        $maximoProductos = $latestSubscription && $latestSubscription->plan ? $latestSubscription->plan->max_products : 0;
        $this->maximoProductos = $maximoProductos;

        $productosActuales = $user->catalogo->products()->count();
        $this->productosActuales = $productosActuales;

        $this->categories = Category::where('catalogo_id', $user->catalogo->id)->get();
        $this->ItemId = $ItemId;

        if ($this->visible === null && !$this->ItemId) {
            $this->visible = true; // Valor predeterminado
        }

        if ($this->ItemId) {

            $product = Product::with('fotos', 'variants')->find($this->ItemId);

            if ($product) {
                $this->name = $product->name;
                $this->price = $product->price;
                $this->category = $product->category_id;
                $this->precio_descuento = $product->precio_descuento;
                $this->visible = (bool) $product->visible;
                $this->description = $product->description;
                $this->existingImages = $product->fotos->toArray();

                // Solo cargamos aquí las variantes "extra" con nombre propio.
                // Las combinaciones de talla/color se reconstruyen aparte desde
                // $sizeOptions/$colorOptions más abajo — cargar TODAS aquí causaba
                // que save() las duplicara al editar (una copia regenerada por
                // combinación + una copia arrastrada tal cual desde la BD).
                $this->variants = $product->variants
                    ->filter(fn ($variant) => blank($variant->name))
                    ->values()
                    ->toArray();

                $this->allowSizeVariants = $product->variants
                    ->contains(fn ($variant) => blank($variant->name) && filled($variant->size));
                $this->allowColorVariants = $product->variants
                    ->contains(fn ($variant) => blank($variant->name) && filled($variant->color));

                $this->sizeOptions = $product->variants
                    ->filter(fn ($variant) => blank($variant->name) && filled($variant->size))
                    ->map(fn ($variant) => ['value' => $variant->size, 'price_adjustment' => (float) $variant->price_adjustment, 'available' => (bool) $variant->available])
                    ->unique('value')
                    ->values()
                    ->toArray();

                $this->colorOptions = $product->variants
                    ->filter(fn ($variant) => blank($variant->name) && filled($variant->color))
                    ->map(fn ($variant) => ['value' => $variant->color, 'price_adjustment' => (float) $variant->price_adjustment, 'available' => (bool) $variant->available])
                    ->unique('value')
                    ->values()
                    ->toArray();

                $this->customVariants = $product->variants
                    ->filter(fn ($variant) => filled($variant->name))
                    ->groupBy('name')
                    ->map(fn ($variants, $name) => [
                        'name' => $name,
                        'values' => $variants
                            ->map(fn ($variant) => ['value' => $variant->size ?: $variant->color, 'price_adjustment' => (float) $variant->price_adjustment, 'available' => (bool) $variant->available])
                            ->filter(fn ($value) => filled($value['value']))
                            ->unique('value')
                            ->values()
                            ->toArray(),
                    ])
                    ->values()
                    ->toArray();
            }
        }

        if ($this->allowSizeVariants && empty($this->sizeOptions)) {
            $this->sizeOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
        }

        if ($this->allowColorVariants && empty($this->colorOptions)) {
            $this->colorOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
        }
    }

    public function render()
    {
        return view('livewire.product-form');
    }

    // Marca una imagen existente para ser eliminada cuando se guarde el formulario.
    public function markImageForDeletion($imageId)
    {
        if (! in_array($imageId, $this->imagesToDelete, true)) {
            $this->imagesToDelete[] = $imageId;
        }

        $this->existingImages = collect($this->existingImages)->filter(function ($item) use ($imageId) {
            return isset($item['id']) && $item['id'] != $imageId;
        })->values()->toArray();
    }

    // Elimina una imagen nueva (temporal) antes de que se guarde.
    public function removeNewImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function updatedAllowSizeVariants($value)
    {
        if ($value && empty($this->sizeOptions)) {
            $this->sizeOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
        }
    }

    public function updatedAllowColorVariants($value)
    {
        if ($value && empty($this->colorOptions)) {
            $this->colorOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
        }
    }

    public function addSizeOption()
    {
        $this->sizeOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
    }

    public function ensureSizeOption()
    {
        if (empty($this->sizeOptions)) {
            $this->addSizeOption();
        }
    }

    public function removeSizeOption($index)
    {
        unset($this->sizeOptions[$index]);
        $this->sizeOptions = array_values($this->sizeOptions);
    }

    public function addColorOption()
    {
        $this->colorOptions[] = ['value' => '', 'price_adjustment' => 0, 'available' => true];
    }

    public function ensureColorOption()
    {
        if (empty($this->colorOptions)) {
            $this->addColorOption();
        }
    }

    public function removeColorOption($index)
    {
        unset($this->colorOptions[$index]);
        $this->colorOptions = array_values($this->colorOptions);
    }

    public function addVariant()
    {
        $this->variants[] = [
            'name' => '',
            'size' => '',
            'color' => '',
            'price_adjustment' => 0,
            'stock' => 0,
            'available' => true,
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function addCustomVariant()
    {
        $this->customVariants[] = [
            'name' => '',
            'values' => [
                ['value' => '', 'price_adjustment' => 0, 'available' => true],
            ],
        ];
    }

    public function removeCustomVariant($index)
    {
        unset($this->customVariants[$index]);
        $this->customVariants = array_values($this->customVariants);
    }

    public function addCustomVariantValue($variantIndex)
    {
        if (! isset($this->customVariants[$variantIndex]['values'])) {
            $this->customVariants[$variantIndex]['values'] = [];
        }

        $this->customVariants[$variantIndex]['values'][] = [
            'value' => '',
            'price_adjustment' => 0,
            'available' => true,
        ];
    }

    public function removeCustomVariantValue($variantIndex, $valueIndex)
    {
        if (! isset($this->customVariants[$variantIndex]['values'][$valueIndex])) {
            return;
        }

        unset($this->customVariants[$variantIndex]['values'][$valueIndex]);
        $this->customVariants[$variantIndex]['values'] = array_values($this->customVariants[$variantIndex]['values']);
    }

    public function save()
    {
        $this->resetErrorBag();
        $this->validate();

        foreach ($this->customVariants as $index => $customVariant) {
            $parsedName = trim((string) ($customVariant['name'] ?? ''));
            $parsedValues = collect($customVariant['values'] ?? [])
                ->filter(fn ($value) => trim((string) ($value['value'] ?? '')) !== '')
                ->values()
                ->all();

            if ($parsedName === '' && empty($parsedValues)) {
                continue;
            }

            if ($parsedName === '') {
                $this->addError('customVariants.' . $index . '.name', 'El nombre de la variante personalizada es obligatorio.');
                return;
            }

            if (empty($parsedValues)) {
                $this->addError('customVariants.' . $index . '.values', 'Debe agregar al menos un valor a la variante personalizada.');
                return;
            }
        }

        if (count($this->images) + count($this->existingImages) === 0) {
            $this->addError('images', 'El producto debe tener al menos una imagen.');
            return;
        }

        if (count($this->images) + count($this->existingImages) > 5) {
            $this->addError('images', __('messages.images_limit'));
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
                $image = foto::find($imageId);
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

                $img = $manager->read($path)->scale(800);

                $extension = function_exists('imagewebp') ? 'webp' : 'jpg';
                $quality = $extension === 'webp' ? 80 : 90;

                if ($extension === 'webp') {
                    $encoded = $img->toWebp($quality);
                } else {
                    $encoded = $img->toJpeg($quality);
                }

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

        $generatedVariants = [];

        if ($this->allowSizeVariants) {
            foreach ($this->sizeOptions as $sizeOption) {
                $size = trim((string) ($sizeOption['value'] ?? ''));
                if ($size === '') {
                    continue;
                }

                if ($this->allowColorVariants) {
                    foreach ($this->colorOptions as $colorOption) {
                        $color = trim((string) ($colorOption['value'] ?? ''));
                        if ($color === '') {
                            continue;
                        }

                        $generatedVariants[] = [
                            'name' => '',
                            'size' => $size,
                            'color' => $color,
                            'price_adjustment' => (float) ($sizeOption['price_adjustment'] ?? 0) + (float) ($colorOption['price_adjustment'] ?? 0),
                            'stock' => 0,
                            'available' => (bool) ($sizeOption['available'] ?? true) && (bool) ($colorOption['available'] ?? true),
                        ];
                    }
                } else {
                    $generatedVariants[] = [
                        'name' => '',
                        'size' => $size,
                        'color' => '',
                        'price_adjustment' => (float) ($sizeOption['price_adjustment'] ?? 0),
                        'stock' => 0,
                        'available' => (bool) ($sizeOption['available'] ?? true),
                    ];
                }
            }
        }

        if ($this->allowColorVariants && ! $this->allowSizeVariants) {
            foreach ($this->colorOptions as $colorOption) {
                $color = trim((string) ($colorOption['value'] ?? ''));
                if ($color === '') {
                    continue;
                }

                $generatedVariants[] = [
                    'name' => '',
                    'size' => '',
                    'color' => $color,
                    'price_adjustment' => (float) ($colorOption['price_adjustment'] ?? 0),
                    'stock' => 0,
                    'available' => (bool) ($colorOption['available'] ?? true),
                ];
            }
        }

        // Manejar variantes
        $product->variants()->delete(); // Eliminar existentes

        $customVariantRows = [];
        foreach ($this->customVariants as $customVariant) {
            $variantName = trim((string) ($customVariant['name'] ?? ''));
            if ($variantName === '') {
                continue;
            }

            foreach ($customVariant['values'] ?? [] as $value) {
                $valueText = trim((string) ($value['value'] ?? ''));
                if ($valueText === '') {
                    continue;
                }

                $customVariantRows[] = [
                    'name' => $variantName,
                    'size' => $valueText,
                    'color' => '',
                    'price_adjustment' => (float) ($value['price_adjustment'] ?? 0),
                    'stock' => 0,
                    'available' => (bool) ($value['available'] ?? true),
                ];
            }
        }

        foreach (array_merge($generatedVariants, $this->variants, $customVariantRows) as $variant) {
            $name = trim((string) ($variant['name'] ?? ''));
            $size = trim((string) ($variant['size'] ?? ''));
            $color = trim((string) ($variant['color'] ?? ''));
            $available = (bool) ($variant['available'] ?? true);

            if ($name !== '' || $size !== '' || $color !== '') {
                $product->variants()->create([
                    'name' => $name,
                    'size' => $size,
                    'color' => $color,
                    'price_adjustment' => (float) ($variant['price_adjustment'] ?? 0),
                    'stock' => (int) ($variant['stock'] ?? 0),
                    'available' => $available,
                ]);
            }
        }

        session()->flash('message', $this->ItemId ? __('messages.product_updated') : __('messages.product_created'));
        $this->redirectRoute('products');
    }
}