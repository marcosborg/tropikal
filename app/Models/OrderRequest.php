<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    protected $guarded = [];
    protected $casts = ['notified_at' => 'datetime'];
    public function items() { return $this->hasMany(OrderRequestItem::class); }
}
