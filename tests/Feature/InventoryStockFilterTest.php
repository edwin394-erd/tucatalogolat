<?php

namespace Tests\Feature;

use App\Livewire\InventoryEntry;
use App\Models\Catalogo;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class InventoryStockFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_product_selector_only_shows_products_that_manage_stock(): void
    {
        $user = User::create([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'user',
            'country' => 'México',
            'city' => 'Ciudad',
            'address' => 'Dirección',
            'telephone' => '123456789',
        ]);

        $catalog = Catalogo::create([
            'name' => 'Mi catálogo',
            'name_handle' => 'mi-catalogo',
            'user_id' => $user->id,
            'description' => 'Desc',
            'design_configured' => true,
        ]);

        $category = \App\Models\Category::create([
            'catalogo_id' => $catalog->id,
            'name' => 'Categoría prueba',
        ]);

        $managed = Product::create([
            'catalogo_id' => $catalog->id,
            'category_id' => $category->id,
            'name' => 'Producto con stock',
            'description' => 'desc',
            'price' => 100,
            'stock' => 10,
            'manage_stock' => true,
            'visible' => true,
        ]);

        Product::create([
            'catalogo_id' => $catalog->id,
            'category_id' => $category->id,
            'name' => 'Producto sin stock',
            'description' => 'desc',
            'price' => 200,
            'stock' => 5,
            'manage_stock' => false,
            'visible' => true,
        ]);

        $this->actingAs($user);

        $component = new InventoryEntry();
        $method = new ReflectionMethod($component, 'formData');
        $method->setAccessible(true);

        $data = $method->invoke($component);

        $this->assertSame([$managed->id], $data['products']->pluck('id')->all());
        $this->assertTrue($data['products']->first()->manage_stock);
    }
}
