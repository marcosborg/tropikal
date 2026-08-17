<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReturnRequest extends Model { protected $guarded=[]; protected $casts=['requested_amount'=>'decimal:2','refunded_amount'=>'decimal:2','resolved_at'=>'datetime']; public function order(){return $this->belongsTo(Order::class);} public function user(){return $this->belongsTo(User::class);} public function items(){return $this->hasMany(ReturnRequestItem::class);} }
