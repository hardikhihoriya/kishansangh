<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table= "roles";

    /**
     * Relations between users and roles table
     * It's Many-To-Many Relations
     * @var array
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
