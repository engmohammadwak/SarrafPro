<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $fillable = ['shop_id','name','phone','id_number','nationality','notes','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function shop() { return $this->belongsTo(Shop::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
}
