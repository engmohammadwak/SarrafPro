<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'license_number',
        'phone',
        'email',
        'address',
        'city',
        'country',
        'logo',
        'status',
        'balance',
        'admin_id',
    ];

    protected $casts = [
        'balance' => 'decimal:4',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'shop_id');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }
}
