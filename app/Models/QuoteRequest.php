<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $guarded=[]; protected $casts=['notified_at'=>'datetime']; public function product(){ return $this->belongsTo(Product::class); }
}
