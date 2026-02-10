<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anamnesis extends Model
{
    protected $table = 'anamnesis';
    protected $fillable = ['inherit_family'];

    public function non_pathological() {
        return $this->hasOne('App\Models\NonPathological');
    }

    public function pathological_personal() {
        return $this->hasOne('App\Models\PathologicalPersonal');
    }

    public function gynecological_obstetric_history() {
        return $this->hasOne('App\Models\GynecologicalObstetricHistory');
    }

    public function initial_clinical_history() {
        return $this->belongsTo('App\Models\InitialClinicalHistory');
    }

    public static function get_defaults() {
        return [
            'inherit_family' => __("global.denied")
        ];
    }
}
