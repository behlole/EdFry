<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPasswordReset extends Model
{
    protected $table = "password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
