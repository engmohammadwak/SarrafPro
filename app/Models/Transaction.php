<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = [
        'shop_id','performed_by','customer_id','agent_id','account_id',
        'type','currency_from','currency_to','amount','rate','amount_result',
        'fee','reference','notes','status'
    ];
    protected $casts = [
        'amount' => 'decimal:4', 'rate' => 'decimal:6',
        'amount_result' => 'decimal:4', 'fee' => 'decimal:4'
    ];
    public function shop()        { return $this->belongsTo(Shop::class); }
    public function performer()   { return $this->belongsTo(User::class, 'performed_by'); }
    public function customer()    { return $this->belongsTo(Customer::class); }
    public function agent()       { return $this->belongsTo(Agent::class); }
    public function account()     { return $this->belongsTo(Account::class); }
}
