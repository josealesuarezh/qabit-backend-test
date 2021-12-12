<?php

namespace Tests\Feature;

use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_products()
    {
        $product = Products::factory()->create();
        ProductVariant::factory(3)->create([
            'product_id' => $product->id
        ]);

        $loadedList = $product->load('lowestPriceVariation');

        $response = $this->get("api/products")
            ->assertStatus(200)
            ->assertSee("Products retrieved successfully");

        $returnedList = json_decode($response->getContent())->data[0];

        $this->assertEquals(
            $loadedList->toArray()['lowest_price_variation'],
            (array)$returnedList->lowest_price_variation
        );
    }

    public function test_create_products()
    {
        $product = Products::factory()->raw();
        $productVariant = ProductVariant::factory()->raw();

        $this->post("api/products", array_merge($product, $productVariant))
            ->assertStatus(200)
            ->assertSee("Products saved successfully");

        $this->assertDatabaseHas('products', $product);
        $this->assertDatabaseHas('product_variants', $productVariant);

        $product = Products::firstorFail();
        $productVariant = ProductVariant::firstorFail();

        $this->assertEquals($product->variants()->first(), $productVariant);
    }

    public function test_update_product()
    {
        $product = Products::factory()->create();

        $this->patch("api/products/$product->id", $attributes = [
            "name" => "new name",
            "description" => "new description"
        ])->assertStatus(200)->assertSee("Products updated successfully");

        $this->assertDatabaseHas("products", $attributes);

        $productVariant = ProductVariant::factory()->create([
            "product_id" => $product->id
        ]);

        $updatedProductVariant = ProductVariant::factory()->raw();

        $this->patch("api/products/$product->id", array_merge(
            $attributes,
            $updatedProductVariant,
            ["variantId" => $productVariant->id]
        ))
            ->assertStatus(200)
            ->assertSee("Products updated successfully");

        $this->assertDatabaseHas("product_variants", $updatedProductVariant);
    }

    public function test_show_product()
    {
        $product = Products::factory()->create();
        ProductVariant::factory(3)->create([
            "product_id" => $product->id
        ]);

        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertSee("Products retrieved successfully")
            ->assertSimilarJson([
                "data" => $product->load("variants")->toArray(),
                "message" => "Products retrieved successfully",
                "success" => true
            ]);
    }

    public function test_destroy_products()
    {
        $product = Products::factory()->create();
        $productVariants = ProductVariant::factory()->create([
            "product_id" => $product->id
        ]);

        $this->delete("api/products/$product->id")
            ->assertStatus(200)
            ->assertSee("Products deleted successfully");

        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertDatabaseMissing('product_variants', $productVariants->toArray());
    }
}
