<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model {
    protected $fillable = ['shop_id','name','phone','country','company','balance','notes','is_active'];
    protected $casts = ['is_active' => 'boolean', 'balance' => 'decimal:4'];
    public function shop() { return $this->belongsTo(Shop::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
}
