<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    protected $casts = ['is_published'=>'boolean'];
    public function parent(){ return $this->belongsTo(self::class,'parent_id'); }
    public function children(){ return $this->hasMany(self::class,'parent_id')->orderBy('sort_order'); }
    public function products(){ return $this->hasMany(Product::class)->orderBy('sort_order'); }
}
