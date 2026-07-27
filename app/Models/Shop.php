<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model {
    use SoftDeletes;

    protected $fillable = [
        'name','name_en','logo','username','license_number','phone','email','city','address',
        'status','balance','notes','attachment','created_by','updated_by',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function users() {
        return $this->hasMany(User::class);
    }
    public function admin() {
        return $this->hasOne(User::class)->where('role','shop_admin');
    }
    public function isActive()    { return $this->status === 'active'; }
    public function isSuspended() { return $this->status === 'suspended'; }
}
