<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'name_en', 'license_number', 'phone', 'email',
        'address', 'city', 'country', 'logo', 'status', 'balance', 'admin_id',
    ];

    protected $casts = [
        'balance' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'    => 'نشط',
            'suspended' => 'موقوف',
            'pending'   => 'معلق',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active'    => 'green',
            'suspended' => 'red',
            'pending'   => 'yellow',
            default     => 'gray',
        };
    }
}
