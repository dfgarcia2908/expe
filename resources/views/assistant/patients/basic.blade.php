@extends('layouts.sisgec')

@section('content')
    <div class="row align-items-center">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="c-grey-900 mT-10 mB-30">{{ __("global.patient") }} > {{ $patient->full_name }}</h2>
            <div>
                <a href="{{ route("patients.edit", $patient->id) }}" class="btn btn-success">
                    {{ __("global.edit_patient") }}
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            @if(!$patient->initial_clinical_history)
            <div class="bgc-white p-20 bd mb-3">
                <div class="alert alert-warning">
                    <strong>Advertencia:</strong> Este paciente no tiene historial clínico completo. Solo se muestra información básica.
                </div>
            </div>
            @endif

            <div class="bgc-white p-20 bd mt-3">
                <h4 class="c-grey-900"><i class="fa fa-user"></i> {{ __("global.identification_card") }}</h4>
                <div class="profile-list-info">
                    <ul>
                        <li><strong>{{ __("person.name") }}:</strong> {{ $patient->name }}</li>
                        <li><strong>{{ __("person.lastname") }}:</strong> {{ $patient->lastname }}</li>
                        @if($patient->nickname)
                        <li><strong>{{ __("person.nickname") }}:</strong> {{ $patient->nickname }}</li>
                        @endif
                        <li><strong>{{ __("person.sex") }}:</strong> {{ $patient->sex == 1 ? __("global.woman") : __("global.man") }}</li>
                        @if($patient->birthdate)
                        <li><strong>{{ __("person.birthdate") }}:</strong> {{ $patient->birthdate }} ({{ $patient->age . " " . __("person.years") }})</li>
                        @endif
                        @if($patient->email)
                        <li><strong>{{ __("person.email") }}:</strong> {{ $patient->email }}</li>
                        @endif
                        @if($patient->phone)
                        <li><strong>{{ __("person.phone") }}:</strong> {{ $patient->phone }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
