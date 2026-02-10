<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitialClinicalHistory extends Model
{
    protected $table = 'initial_clinical_history';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['current_condition','diagnostical_impression','treatment_plan','interconsultation','treatment'];

    public function anamnesis() {
        return $this->hasOne('App\Models\Anamnesis');
    }

    public function physical_exploration() {
        return $this->hasOne('App\Models\PhysicalExploration');
    }

    public function patient() {
        return $this->belongsTo('App\Models\Patient');
    }

    public function studies() {
        return $this->hasMany('App\Models\Study')
        ->orderBy('studies.id', 'desc');
    }

    public function tracings() {
        return $this->hasMany('App\Models\Tracing')
        ->orderBy('tracings.id', 'desc');
    }

    public function prescriptions() {
        return $this->hasMany('App\Models\Prescription')
        ->orderBy('prescriptions.id', 'desc');
    }

    public function getFolioAttribute() {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public static function get_defaults() {
        return array(
            'current_condition' => "",
            'diagnostical_impression' => "",
            'treatment_plan' => "",
            'interconsultation' => "",
            'treatment' => ""
        );
    }
}
