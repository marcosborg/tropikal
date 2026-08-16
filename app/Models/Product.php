<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts = ['features'=>'array','price'=>'decimal:2','is_featured'=>'boolean','is_published'=>'boolean'];
    public function category(){ return $this->belongsTo(Category::class); }
    public function images(){ return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function documents(){ return $this->hasMany(ProductDocument::class)->orderBy('sort_order'); }
    public function variants(){ return $this->hasMany(ProductVariant::class)->orderBy('sort_order'); }
    public function quoteRequests(){ return $this->hasMany(QuoteRequest::class); }
}
