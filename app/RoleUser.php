<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = "role_user";

}
