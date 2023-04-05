<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorLogin extends Model
{
    protected $guarded = ['id'];


    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
