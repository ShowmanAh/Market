<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];
    // phone as array  perhapse two phone
    protected $casts = [
        'phone' => 'array',
    ];
//return upercase client name
public function getNameAttribute($value){
    return ucfirst($value);
}
//one to many
public function orders(){
    return $this->hasMany(Order::class);
}


}
