<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalAppointment extends Model
{
    protected $casts = [
        'date' => 'datetime',
    ];
    protected $fillable = ['date','title','description'];

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    public function doctor() {
        return $this->belongsTo('App\Models\Doctor');
    }
}
