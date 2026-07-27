<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','username','email','password','role','shop_id','pin_code',
        'created_by','updated_by','notes','attachment',
    ];

    protected $hidden = ['password','pin_code','remember_token'];

    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
    public function hasPin(): bool { return !empty($this->pin_code); }
    public function isAgent()      { return $this->role === 'agent'; }
    public function isAdmin()      { return $this->role === 'shop_admin'; }
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
}
