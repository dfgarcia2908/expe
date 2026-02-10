<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function medical_appointments() {
        return $this->hasMany('App\Models\MedicalAppointment');
    }

    public function assistants() {
        return $this->hasMany('App\Models\Assistant');
    }
}
