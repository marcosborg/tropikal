<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Address extends Model { protected $guarded=[]; protected $casts=['is_default_shipping'=>'boolean','is_default_billing'=>'boolean']; public function user(){return $this->belongsTo(User::class);} }
