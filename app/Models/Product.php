<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
      'Product_name', 'section_id', 'description'
    ];

    public function section(){
        return $this ->belongsTo(Section::class , 'section_id');
    }
}
