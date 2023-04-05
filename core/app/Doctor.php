<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ver_code_send_at' => 'datetime',
        'serial_or_slot' => 'object',
        'speciality' => 'object',
    ];

    public function login_logs()
    {
        return $this->hasMany(DoctorLogin::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function sector(){

        return $this->belongsTo(Sector::class);
    }

    public function location(){

        return $this->belongsTo(Location::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

    public function educationDetails()
    {
        return $this->hasMany(Education::class);
    }

    public function experienceDetails()
    {
        return $this->hasMany(Experience::class);
    }

    public function socialIcons()
    {
        return $this->hasMany(SocialIcon::class);
    }
    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }
}
