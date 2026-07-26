<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model {
    protected $fillable = [
        'shop_id','user_id','link_status',
        'name','phone','country','company','balance','notes','is_active'
    ];
    protected $casts = ['is_active' => 'boolean', 'balance' => 'decimal:4'];

    public function shop()  { return $this->belongsTo(Shop::class); }
    public function user()  { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }

    public function isLinked(): bool   { return $this->link_status === 'approved'; }
    public function isPending(): bool  { return $this->link_status === 'pending'; }
    public function isRejected(): bool { return $this->link_status === 'rejected'; }
}
