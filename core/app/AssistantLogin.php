<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistantLogin extends Model
{
    protected $guarded = ['id'];


    public function assistant()
    {
        return $this->belongsTo(Assistant::class);
    }
}
