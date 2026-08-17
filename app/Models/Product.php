<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts = ['features'=>'array','price'=>'decimal:2','compare_at_price'=>'decimal:2','is_featured'=>'boolean','is_promotion'=>'boolean','promotion_starts_at'=>'datetime','promotion_ends_at'=>'datetime','is_published'=>'boolean','is_purchasable'=>'boolean','track_stock'=>'boolean'];
    public function purchasable(): bool { return $this->is_published && $this->is_purchasable && ($this->price !== null || $this->variants()->whereNotNull('price')->exists()) && (! $this->track_stock || $this->stock_quantity > 0); }
    public function startingPrice(): ?float { $variantPrice = $this->variants->whereNotNull('price')->min(fn ($variant) => (float) $variant->price); return $this->price !== null ? (float) $this->price : $variantPrice; }
    public function scopeActivePromotion($query) { return $query->where('is_promotion', true)->where(fn ($q) => $q->whereNull('promotion_starts_at')->orWhere('promotion_starts_at', '<=', now()))->where(fn ($q) => $q->whereNull('promotion_ends_at')->orWhere('promotion_ends_at', '>=', now())); }
    public function promotionIsActive(): bool { return $this->is_promotion && (! $this->promotion_starts_at || $this->promotion_starts_at->isPast()) && (! $this->promotion_ends_at || $this->promotion_ends_at->isFuture()); }
    public function category(){ return $this->belongsTo(Category::class); }
    public function images(){ return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function documents(){ return $this->hasMany(ProductDocument::class)->orderBy('sort_order'); }
    public function variants(){ return $this->hasMany(ProductVariant::class)->orderBy('sort_order'); }
    public function quoteRequests(){ return $this->hasMany(QuoteRequest::class); }
}
