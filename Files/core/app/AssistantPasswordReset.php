<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistantPasswordReset extends Model
{
    protected $table = "assistant_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
