<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $guarded = [];
   //one to many relation client has many orders
   public function client(){
       return $this->belongsTo(Client::class);
   }
   //many to many relation
    public function products(){
       return $this->belongsToMany(Product::class,'product_order')->withPivot('quantity');//to return quantity with orders
    }
}
