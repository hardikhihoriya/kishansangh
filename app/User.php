<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        return $query->Where(concat_ws(' ',first_name, middle_name, last_name), 'LIKE', "%$value%");
    }

    public function scopeRegistrationNo($query, $value) {
        return $query->orWhere('registration_no', 'LIKE', "%$value%");
    }
    
    public function scopeEmail($query, $value) {
        return $query->orWhere('email', 'LIKE', "%$value%");
    }

    public function scopePhone($query, $value) {
        return $query->orWhere('phone_no', 'LIKE', "%$value%");
    }

    public function scopeBirthDate($query, $value) {
        return $query->orWhere('birth_date', 'LIKE', "%$value%");
    }

    public function scopeGender($query, $value) {
        return $query->orWhere('gender', 'LIKE', "%$value%");
    }
    
    public function generateRegistrationNo() {
        return strtoupper($this->middle_name[0]) . strtoupper($this->first_name[0]) . strtoupper($this->last_name[0]) . str_random(5) . date('H'). str_random(5) . date('i'). str_random(5) . date('s') . substr($this->phone_no, -1, 4);
    }
}