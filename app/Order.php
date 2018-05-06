<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable =[ 'user_id', 'status' ];

    public function orderItem()
    {
        return $this->hasMany(Order_item::class);
    }

    public function getItem()
    {
        return $this
            ->orderItem()
            ->select('products.name','products.price' ,'order_items.*')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->get();
    }
}



