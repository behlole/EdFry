<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $guarded = ['id'];

    public function doctorts()
    {
        return $this->belongsTo(Doctor::class);
    }
}
