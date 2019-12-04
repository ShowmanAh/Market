<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Dimsav\Translatable\Translatable;
    protected $guarded = [];
    //record nedded translate
    public $translatedAttributes = ['name','description'];
    protected $appends = ['image_path','profit_percent'];
    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);

    }//end of image path
    //return gained
    public function getProfitPercentAttribute(){
        $profit = $this->sale_price - $this->purchase_price;
        $profit_percent = $profit * 100 / $this->purchase_price;
        return number_format($profit_percent, 2);
    }
    // one to many
    public function category()
    {
        return $this->belongsTo(Category::class);

    }//end fo category
    //many to many relation
    public function orders(){
        return $this->belongsToMany(Order::class,'product_order');
    }
}
