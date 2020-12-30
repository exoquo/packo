<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StocksTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_user_can_create_stock()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $product = factory('App\Product')->create();
        $warehouse = factory('App\Warehouse')->create();

        $attributes = [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => $this->faker->randomNumber($nbDigits = 2) - 50,
        ];

        $this->post('/api/stocks', $attributes);

        $this->assertDatabaseHas('stocks', $attributes);

        $this->get('/api/stocks')
            ->assertSee($attributes['product_id'])
            ->assertSee($attributes['warehouse_id'])
            ->assertSee($attributes['quantity']);
    }

    /** @test */
    public function a_user_can_update_stock()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $stock = factory('App\Stock')->create();

        $attributes = [
            'quantity' => $this->faker->randomNumber($nbDigits = 2) - 50,
        ];

        $this->put('/api/stocks/' . $stock->id , $attributes);

        $this->assertDatabaseHas('stocks', $attributes);

        $this->get('/api/stocks')
            ->assertSee($attributes['quantity']);
    }

    /** @test */
    public function a_user_can_create_stocks()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $changed_stocks = [
            [
                'product_id' => factory('App\Product')->create()->id,
                'warehouse_id' => factory('App\Warehouse')->create()->id,
                'quantity' => $this->faker->randomNumber($nbDigits = 2),
            ],
            [
                'product_id' => factory('App\Product')->create()->id,
                'warehouse_id' => factory('App\Warehouse')->create()->id,
                'quantity' => $this->faker->randomNumber($nbDigits = 2),
            ]
        ];

        $this->put('/api/stocks/', $changed_stocks);

        $this->assertDatabaseHas('stocks', $changed_stocks[0]);
        $this->assertDatabaseHas('stocks', $changed_stocks[1]);

        $this->get('/api/stocks')
        ->assertSee($changed_stocks[0]['product_id'])
        ->assertSee($changed_stocks[0]['warehouse_id'])
        ->assertSee($changed_stocks[0]['quantity'])
        ->assertSee($changed_stocks[1]['product_id'])
        ->assertSee($changed_stocks[1]['warehouse_id'])
        ->assertSee($changed_stocks[1]['quantity']);
    }


    /** @test */
    public function a_user_can_update_stocks()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $stocks = factory('App\Stock', 10)->create();

        $attributes = [];
        $new_stock = [];

        foreach($stocks as $stock) {
            $quantity = $this->faker->randomNumber($nbDigits = 2);

            $attributes[] = [
                'product_id' => $stock->product_id,
                'warehouse_id' => $stock->warehouse_id,
                'quantity' => $quantity,
            ];

            $new_stock[] = [
                'product_id' => $stock->product_id,
                'warehouse_id' => $stock->warehouse_id,
                'quantity' => $stock->quantity + $quantity,
            ];
        }

        $this->put('/api/stocks/' , $attributes);

        foreach ($new_stock as  $stock) {
            $this->assertDatabaseHas('stocks', $stock);
            $this->get('/api/stocks')
            ->assertSee($stock['product_id'])
            ->assertSee($stock['warehouse_id'])
            ->assertSee($stock['quantity']);
        }
    }

    /** @test */
    public function if_quantity_of_stock_is_zero_stock_will_be_deleted()
    {
        $this->signIn();

        $this->withoutExceptionHandling();

        $stock1 = factory('App\Stock')->create();
        $stock2 = factory('App\Stock')->create();

        $changed_stocks = [
            [
                'product_id' => $stock1->product_id,
                'warehouse_id' => $stock1->warehouse_id,
                'quantity' => $stock1->quantity * -1,
            ],
            [
                'product_id' => $stock2->product_id,
                'warehouse_id' => $stock2->warehouse_id,
                'quantity' => 0,
            ]
        ];

        $this->put('/api/stocks/', $changed_stocks);

        $changed_stocks[1]['quantity'] = $stock2->quantity;
        $this->assertDatabaseMissing('stocks', $changed_stocks[0]);
        $this->assertDatabaseHas('stocks', $changed_stocks[1]);

        $this->get('/api/stocks')
        ->assertDontSee('"product_id":"'.$changed_stocks[0]['product_id'].'"', false)
        ->assertDontSee('"warehouse_id":"'.$changed_stocks[0]['warehouse_id'].'"', false)
        ->assertDontSee('"quantity":"'.$changed_stocks[0]['quantity'].'"', false)
        ->assertSee('"product_id":"'.$changed_stocks[1]['product_id'].'"', false)
        ->assertSee('"warehouse_id":"'.$changed_stocks[1]['warehouse_id'].'"', false)
        ->assertSee('"quantity":"'.$changed_stocks[1]['quantity'].'"', false);
    }
}
