<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'quantity' => 0
    ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Get the product that owns the stock item.
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    /**
     * Get the warehouse that owns the stock item.
     */
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }
}
