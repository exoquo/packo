<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_can_create_a_product()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'name' => 'Test'
        ];

        $this->post('/api/products', $attributes);

        $this->assertDatabaseHas('products', $attributes);

        $this->get('/api/products')->assertSee($attributes['name']);
    }

    /** @test */
    public function a_user_can_view_a_product()
    {
        $this->withoutExceptionHandling();

        $product = factory('App\Product')->create();

        $this->get('/api/products/' . $product->id)
            ->assertSee($product['name']);
    }
}
