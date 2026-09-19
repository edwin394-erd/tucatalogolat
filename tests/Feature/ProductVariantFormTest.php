<?php

namespace Tests\Feature;

use App\Livewire\ProductForm;
use App\Models\Catalogo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductVariantFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_variant_rows_include_visibility_and_custom_name_fields(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 1',
            'telephone' => '1234567890',
        ]);

        Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Test',
            'name_handle' => 'tienda-test',
        ]);

        $this->actingAs($user);

        Livewire::test(ProductForm::class)
            ->set('allowSizeVariants', true)
            ->assertSet('sizeOptions.0.value', '')
            ->set('sizeOptions.0.value', 'M')
            ->set('sizeOptions.0.available', true)
            ->set('allowColorVariants', true)
            ->assertSet('colorOptions.0.value', '')
            ->set('colorOptions.0.value', 'Rojo')
            ->set('colorOptions.0.available', false)
            ->call('addVariant')
            ->set('variants.0.name', 'Material')
            ->set('variants.0.size', '')
            ->set('variants.0.color', 'Azul')
            ->set('variants.0.available', false)
            ->set('variants.0.stock', 5)
            ->assertSet('sizeOptions.0.value', 'M')
            ->assertSet('colorOptions.0.value', 'Rojo')
            ->assertSet('colorOptions.0.available', false)
            ->assertSet('variants.0.available', false)
            ->assertSet('variants.0.stock', 5);
    }

    public function test_custom_variants_can_define_name_and_values(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'custom@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 2',
            'telephone' => '9876543210',
        ]);

        Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Custom',
            'name_handle' => 'tienda-custom',
        ]);

        $this->actingAs($user);

        Livewire::test(ProductForm::class)
            ->call('addCustomVariant')
            ->set('customVariants.0.name', 'Talla')
            ->call('addCustomVariantValue', 0)
            ->set('customVariants.0.values.0.value', 'Grande')
            ->set('customVariants.0.values.0.available', true)
            ->call('addCustomVariantValue', 0)
            ->set('customVariants.0.values.1.value', 'Mediano')
            ->set('customVariants.0.values.1.available', false)
            ->assertSet('customVariants.0.name', 'Talla')
            ->assertSet('customVariants.0.values.0.value', 'Grande')
            ->assertSet('customVariants.0.values.1.available', false);
    }

    public function test_custom_variant_requires_name_and_value_before_saving(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'custom-required@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 3',
            'telephone' => '1112223333',
        ]);

        $catalogo = Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Requerida',
            'name_handle' => 'tienda-requerida',
        ]);

        $category = \App\Models\Category::create([
            'catalogo_id' => $catalogo->id,
            'name' => 'Ropa',
        ]);

        $this->actingAs($user);

        Livewire::test(ProductForm::class)
            ->set('name', 'Camisa')
            ->set('price', 199)
            ->set('category', $category->id)
            ->set('description', 'Prueba')
            ->call('addCustomVariant')
            ->set('customVariants.0.name', 'Talla')
            ->set('customVariants.0.values.0.value', '')
            ->call('save')
            ->assertHasErrors(['customVariants.0.values']);
    }

    public function test_custom_variant_does_not_trigger_standard_size_group(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'custom-size-group@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 4',
            'telephone' => '4445556666',
        ]);

        $catalogo = Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Grupo',
            'name_handle' => 'tienda-grupo',
        ]);

        $category = \App\Models\Category::create([
            'catalogo_id' => $catalogo->id,
            'name' => 'Ropa',
        ]);

        $product = \App\Models\Product::create([
            'catalogo_id' => $catalogo->id,
            'category_id' => $category->id,
            'name' => 'Camiseta',
            'price' => 199,
            'description' => 'Prueba',
            'visible' => true,
        ]);

        $product->variants()->createMany([
            ['name' => 'Dorsal', 'size' => 'Messi', 'color' => '', 'price_adjustment' => 0, 'stock' => 0, 'available' => true],
            ['name' => 'Dorsal', 'size' => 'Ronaldo', 'color' => '', 'price_adjustment' => 0, 'stock' => 0, 'available' => true],
        ]);

        $this->actingAs($user);

        Livewire::test(ProductForm::class, ['ItemId' => $product->id])
            ->assertSet('customVariants.0.name', 'Dorsal')
            ->assertSet('customVariants.0.values.0.value', 'Messi')
            ->assertSet('allowSizeVariants', false)
            ->assertSet('allowColorVariants', false);
    }

    public function test_deleting_custom_variant_and_value_persists_after_save(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'delete-custom@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 5',
            'telephone' => '5556667777',
        ]);

        $catalogo = Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Borrar',
            'name_handle' => 'tienda-borrar',
        ]);

        $category = \App\Models\Category::create([
            'catalogo_id' => $catalogo->id,
            'name' => 'Ropa',
        ]);

        $product = \App\Models\Product::create([
            'catalogo_id' => $catalogo->id,
            'category_id' => $category->id,
            'name' => 'Camiseta',
            'price' => 199,
            'description' => 'Prueba',
            'visible' => true,
        ]);

        $product->variants()->createMany([
            ['name' => 'Dorsal', 'size' => 'Messi', 'color' => '', 'price_adjustment' => 0, 'stock' => 0, 'available' => true],
            ['name' => 'Dorsal', 'size' => 'Ronaldo', 'color' => '', 'price_adjustment' => 0, 'stock' => 0, 'available' => true],
        ]);

        \App\Models\foto::create([
            'imageable_id' => $product->id,
            'imageable_type' => \App\Models\Product::class,
            'url' => 'products/test.jpg',
        ]);

        $this->actingAs($user);

        Livewire::test(ProductForm::class, ['ItemId' => $product->id])
            ->set('existingImages', [[ 'id' => 1, 'url' => 'products/test.jpg' ]])
            ->call('removeCustomVariantValue', 0, 1)
            ->call('removeCustomVariant', 0)
            ->set('name', 'Camiseta')
            ->set('price', 199)
            ->set('category', $category->id)
            ->set('visible', true)
            ->set('description', 'Prueba')
            ->call('save');

        $this->assertDatabaseCount('product_variants', 0);
    }

    public function test_cart_keeps_two_selected_variants_as_separate_lines(): void
    {
        $user = User::create([
            'name' => 'Cart User',
            'email' => 'cart-variants@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'country' => 'Mexico',
            'city' => 'Monterrey',
            'address' => 'Calle 6',
            'telephone' => '6667778888',
        ]);

        $catalogo = Catalogo::create([
            'user_id' => $user->id,
            'name' => 'Tienda Carrito',
            'name_handle' => 'tienda-carrito',
        ]);

        $category = \App\Models\Category::create([
            'catalogo_id' => $catalogo->id,
            'name' => 'Ropa',
        ]);

        $product = \App\Models\Product::create([
            'catalogo_id' => $catalogo->id,
            'category_id' => $category->id,
            'name' => 'Camiseta',
            'price' => 199,
            'description' => 'Prueba',
            'visible' => true,
        ]);

        $blue = $product->variants()->create([
            'size' => 'M',
            'color' => 'Azul',
            'price_adjustment' => 0,
            'stock' => 0,
            'available' => true,
        ]);
        $red = $product->variants()->create([
            'size' => 'M',
            'color' => 'Rojo',
            'price_adjustment' => 0,
            'stock' => 0,
            'available' => true,
        ]);
        $dorsal = $product->variants()->create([
            'name' => 'Dorsal',
            'size' => 'Ronaldo',
            'color' => '',
            'price_adjustment' => 0,
            'stock' => 0,
            'available' => true,
        ]);

        $response = $this->postJson('/tienda-carrito/cart-sync', [
            'items' => [
                $product->id . ':' . $blue->id => 1,
                $product->id . ':' . $red->id => 1,
                $product->id . ':' . $blue->id . '-' . $dorsal->id => 1,
            ],
            'products' => [
                $product->id . ':' . $blue->id => $product->id,
                $product->id . ':' . $red->id => $product->id,
                $product->id . ':' . $blue->id . '-' . $dorsal->id => $product->id,
            ],
            'variants' => [
                $product->id . ':' . $blue->id => $blue->id,
                $product->id . ':' . $red->id => $red->id,
                $product->id . ':' . $blue->id . '-' . $dorsal->id => $blue->id,
            ],
            'selections' => [
                $product->id . ':' . $blue->id => [$blue->id],
                $product->id . ':' . $red->id => [$red->id],
                $product->id . ':' . $blue->id . '-' . $dorsal->id => [$blue->id, $dorsal->id],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('cart_items', 3);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'variant_id' => $blue->id, 'quantity' => 1]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'variant_id' => $red->id, 'quantity' => 1]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'variant_id' => $blue->id, 'variant_description' => 'M Azul']);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'variant_id' => $red->id, 'variant_description' => 'M Rojo']);
        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'variant_id' => $blue->id, 'variant_description' => 'M Azul / Dorsal: Ronaldo']);

    }
}
