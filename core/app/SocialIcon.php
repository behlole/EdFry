<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialIcon extends Model
{
    protected $guarded = ['id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
