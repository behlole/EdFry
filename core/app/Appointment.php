<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    protected $guarded = ['id'];


    public function doctor(){

        return $this->belongsTo(Doctor::class);
    }

    public function relationStaff(){

        return $this->belongsTo(Staff::class,'staff');
    }

    public function relationAssistant(){

        return $this->belongsTo(Assistant::class,'assistant');
    }

    public function relationAdmin(){

        return $this->belongsTo(Admin::class,'admin');
    }

    public function relationUser()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
    public function deleteRelationStaff(){

        return $this->belongsTo(Staff::class,'d_staff');
    }

    public function deleteRelationAssistant(){

        return $this->belongsTo(Assistant::class,'d_assistant');
    }

    public function deleteRelationAdmin(){

        return $this->belongsTo(Admin::class,'d_admin');
    }

    public function deleteRelationDoctor(){

        return $this->belongsTo(Doctor::class,'d_doctor');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->orderBy('id','desc');
    }

    public function scopeNewAppointment()
    {
        return $this->where('try',1)->where('is_complete',0)->where('d_status',0);
    }
}
