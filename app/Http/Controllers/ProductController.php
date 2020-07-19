<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('page')) {
            return Product::paginate(20)->toJson();
        }
        return Product::all()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product = null)
    {
        if ($product && $request->has('file')) {
            $image = $request->file('file');

            $name = Str::slug($product->name).'_'.time();;

            $folder = '/uploads/images/';

            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();

            $this->uploadOne($image, $folder, 'public', $name);
            $this->deleteOne('', 'public', $product->image);

            $product->image = $filePath;
            $product->save();

            return $product->toJson();
        }
        //validate
        Product::create(request(['name', 'barcode', 'image']));
        //redirect
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product->toJson();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function barcode($barcode)
    {
        return Product::firstOrCreate(
            ['barcode'=> $barcode],
            [   'name' => 'Neuer Barcode ohne Produkt',
                'barcode'=> $barcode]
        )->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->name;
        $product->barcode = $request->barcode;

        $product->save();

        return $product->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
