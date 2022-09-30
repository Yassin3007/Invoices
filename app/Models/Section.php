<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable =[
        'section_name',
        'description',
        'Created_by'
    ];

    public function product(){
        return $this->belongsToMany(Product::class,'section_products');
    }
}
