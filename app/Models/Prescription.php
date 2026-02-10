<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['date', 'prescription'];

    public function initial_clinical_history() {
        return $this->belongsTo('App\Models\InitialClinicalHistory');
    }

    public function measures() {
        return $this->hasOne('App\Models\Measure');
    }

    public function getFolioAttribute() {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public static function next_folio() {
        $last = Prescription::orderBy('created_at', 'desc')->first();
        $folio = 1;
        if(!is_null($last)) {
            $folio = $last->id + 1;
        }
        return str_pad($folio, 5, '0', STR_PAD_LEFT);
    }
}
