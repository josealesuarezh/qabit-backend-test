<?php

namespace Tests\Feature;

use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaitListTest extends TestCase
{
    use RefreshDatabase;

    public function test_required_email_and_products()
    {
        $response = $this->post('api/products/subscribe', []);
        $response->assertStatus(422);

        $response = $this->post('api/products/subscribe', [
            'email' => "tes@test.com"
        ]);
        $response->assertStatus(422);
        $response->assertSee("The product id field is required");

        $product = Products::factory()->create()->first();
        $response = $this->post('api/products/subscribe', $on_wait = [
            'email' => "tes@test.com",
            'product_id' => $product->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('wait_lists', $on_wait);
    }

    public function test_product_must_exist_to_add_it_to_wait_list()
    {
        $response = $this->post('api/products/subscribe', [
            'email' => "tes@test.com",
            'product_id' => 5
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseCount('wait_lists', 0);
    }

    public function test_wait_list_toggle_function()
    {
        $product = Products::factory()->create()->first();
        $email = "test@email.com";

        $response = $this->post('api/products/subscribe', $on_wait = [
            'product_id' => $product->id,
            'email' => $email
        ]);

        $response->assertSee("You have been subscribed successfully");
        $this->assertDatabaseHas('wait_lists', $on_wait);

        $response = $this->post('api/products/subscribe', $on_wait = [
            'product_id' => $product->id,
            'email' => $email
        ]);

        $response->assertSee("You have been unsubscribed successfully");
        $this->assertDatabaseCount('wait_lists', 0);
    }
}
