<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model {
    use SoftDeletes;

    protected $fillable = [
        'shop_id', 'user_id', 'link_status',
        'name', 'phone', 'phone2', 'country', 'company',
        'notes', 'admin_notes', 'attachment',
        'balance', 'is_active',
    ];

    public function shop()  { return $this->belongsTo(Shop::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
