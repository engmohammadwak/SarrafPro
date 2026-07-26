<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    protected $fillable = [
        'shop_id','name','type','country','currency',
        'account_number','crypto_address','crypto_network',
        'attachment','balance','notes','is_active'
    ];
    protected $casts = ['is_active' => 'boolean', 'balance' => 'decimal:4'];

    public function shop() { return $this->belongsTo(Shop::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }

    public static function typeLabel(string $type): string {
        return match($type) {
            'bank'     => 'بنك',
            'exchange' => 'صراف',
            'crypto'   => 'عملة رقمية',
            default    => 'نقدي',
        };
    }

    public static function typeIcon(string $type): string {
        return match($type) {
            'bank'     => 'fa-university',
            'exchange' => 'fa-coins',
            'crypto'   => 'fa-bitcoin-sign',
            default    => 'fa-money-bill-wave',
        };
    }
}
