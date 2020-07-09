<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('page')) {
            return Stock::with(['warehouse', 'product'])
                ->groupBy('product_id')
                ->paginate(20)
                ->toJson();
        }
        return Stock::with(['warehouse', 'product'])
            ->get()
            ->groupBy('product_id')
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Stock::create(request(['product_id', 'warehouse_id', 'quantity']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        return $stock->with(['warehouse', 'product'])->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock = null)
    {
        if ($stock) {
            $validatedData = $request->validate(
                [
                    'product_id' => 'exists:App\Product,id',
                    'warehouse_id' => 'exists:App\Warehouse,id',
                    'quantity' => 'required|integer',
                ]
            );

            $stock->update($validatedData);
        } else {
            foreach ($request->all() as $stock) {
                tap(
                    Stock::firstOrNew(
                        [
                            'product_id' => $stock['product_id'],
                            'warehouse_id' => $stock['warehouse_id']
                        ]
                    ),
                    function ($instance) use ($stock) {
                        $stock['quantity'] += $instance->quantity;

                        if ($stock['quantity'] <= 0) {
                            $instance->delete();
                        } else {
                            $instance->fill($stock)->save();
                        }
                    }
                );
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
