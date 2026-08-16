<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded=[]; protected $casts=['is_feature_approved'=>'boolean']; public function product(){ return $this->belongsTo(Product::class); }
}
