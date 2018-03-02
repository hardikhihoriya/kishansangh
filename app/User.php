<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'password', 'remember_token' ];

    protected $table = "users";

    /**
     * Relations between users and roles table
     * It's Many-To-Many Relations
     * @var array
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || 
                false;
        }
        return $this->hasRole($roles) || 
            false;
    }
    
    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
    
    public function scopeSearchName($query, $value) {
        return $query->Where(DB::raw('CONCAT(first_name," ", middle_name," ", last_name)') , 'LIKE' , "%$value%");
    }

    public function scopeSearchRegistrationNo($query, $value) {
        return $query->orWhere('registration_no', 'LIKE', "%$value%");
    }
    
    public function scopeSearchEmail($query, $value) {
        return $query->orWhere('email', 'LIKE', "%$value%");
    }

    public function scopeSearchPhone($query, $value) {
        return $query->orWhere('phone_no', 'LIKE', "%$value%");
    }

    public function scopeSearchBirthDate($query, $value) {
        return $query->orWhere('birth_date', 'LIKE', "%$value%");
    }

    public function scopeSearchGender($query, $value) {
        return $query->orWhere('gender', 'LIKE', "%$value%");
    }
    
    public function scopeSearchWalletAmount($query, $value) {
        return $query->orWhere('customer_wallet', 'LIKE', "%$value%");
    }

    public function scopeSearchVendorName($query, $value) {
        return $query->orWhere('vendor_name', 'LIKE', "%$value%");
    }
    
    public function generateRegistrationNo($postData) {
        return strtoupper($postData['middle_name'][0]) . strtoupper($postData['first_name'][0]) . strtoupper($postData['last_name'][0]) . str_random(5) . date('H'). str_random(5) . date('i'). str_random(5) . date('s') . substr($postData['phone_no'], -4);
    }
    
}