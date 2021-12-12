<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\ProductVariant;
use Database\Factories\ProductsFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDatabase;


    public function test_add_product_variant()
    {
        $product = Products::factory()->create();

        $productVariant = ProductVariant::factory()->raw();

        $response  = $this->post("api/products/$product->id/product_variant", $productVariant);

        $response->assertStatus(200)
            ->assertSee("Product Variant saved successfully");

        $this->assertDatabaseHas('product_variants',$productVariant);

        $this->assertCount(1, $product->variants);

        $productVariant = ProductVariant::firstOrFail();

        $this->assertEquals($product->variants->first(), $productVariant);

    }

    public function test_destroy_product_variant()
    {
        $product = Products::factory()->create();
        $productVariant = ProductVariant::factory()->create([
            'product_id' => $product->id
        ]);

        $this->post("api/products/product_variant/$productVariant->id")
            ->assertStatus(200)
            ->assertSee('Product Variant deleted successfully');

        $this->assertDatabaseCount('product_variants',0);
    }
}
