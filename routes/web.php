<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\TracingController;
use App\Http\Controllers\StudiesController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::post('/options/save', [HomeController::class, 'options'])->name('options.save');

    Route::prefix('settings')->group(function() {
        Route::get('/', [DoctorController::class, 'settings'])->name('doctor.settings');
        Route::post('/update', [DoctorController::class, 'update'])->name('doctor.update');
    });

    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/patients/new', [PatientController::class, 'create'])->name('patients.new');
    Route::get('/patients/edit/{id}', [PatientController::class, 'edit'])->name('patients.edit');
    Route::post('/patients/save', [PatientController::class, 'store'])->middleware('prevent.duplicates')->name('patients.save');
    Route::post('/patients/update', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patient/{id}', [PatientController::class, 'show'])->name('patient');
    Route::get('/patient/delete/{id}', [PatientController::class, 'destroy'])->name('patient.remove');
    Route::get('/patient/{id}/download', [PatientController::class, 'download'])->name('patient.download');

    Route::get('/assistants', [AssistantController::class, 'index'])->name('assistants');
    Route::get('/assistants/new', [AssistantController::class, 'create'])->name('assistants.new');
    Route::get('/assistants/edit/{id}', [AssistantController::class, 'edit'])->name('assistants.edit');
    Route::post('/assistants/save', [AssistantController::class, 'store'])->name('assistants.save');
    Route::post('/assistants/update', [AssistantController::class, 'update'])->name('assistants.update');
    Route::get('/assistant/{id}', [AssistantController::class, 'show'])->name('assistant');
    Route::get('/assistant/delete/{id}', [AssistantController::class, 'destroy'])->name('assistant.remove');

    Route::get('/medical-appointment/{id}', [MedicalAppointmentController::class, 'show'])->name('medical_appointment');
    Route::get('/medical-appointments', [MedicalAppointmentController::class, 'index'])->name('medical_appointments');
    Route::post('/medical-appointments/save', [MedicalAppointmentController::class, 'store'])->name('medical_appointments.save');
    Route::post('/medical-appointments/update', [MedicalAppointmentController::class, 'update'])->name('medical_appointments.update');
    Route::get('/medical-appointment/{id}/remove', [MedicalAppointmentController::class, 'destroy'])->name('medical_appointments.remove');

    Route::get('/evolution-note/new/{id?}', [TracingController::class, 'create'])->name('evolution_note.new');
    Route::post('/evolution-note/save', [TracingController::class, 'store'])->name('evolution_note.save');
    Route::get('/evolution-note/edit/{id}', [TracingController::class, 'edit'])->name('evolution_note.edit');
    Route::post('/evolution-note/update', [TracingController::class, 'update'])->name('evolution_note.update');
    Route::get('/evolution-note/remove/{id}', [TracingController::class, 'destroy'])->name('evolution_note.remove');
    Route::get('/evolution-note/{id}', [TracingController::class, 'show'])->name('evolution_note');
    Route::get('/evolution-note/{id}/download', [TracingController::class, 'download'])->name('evolution_note.download');

    Route::get('/attachments', [StudiesController::class, 'create']);
    Route::post('/attachments/save', [StudiesController::class, 'store']);
    Route::get('/attachments/delete/{id}', [StudiesController::class, 'destroy']);
    Route::get('/attachments/download/{filename}', [StudiesController::class, 'download']);

    Route::get('/prescription/new', [PrescriptionController::class, 'create'])->name('prescription.new');
    Route::post('/prescription/save', [PrescriptionController::class, 'store'])->name('prescription.save');
    Route::get('/prescription/edit/{id}', [PrescriptionController::class, 'edit'])->name('prescription.edit');
    Route::post('/prescription/update', [PrescriptionController::class, 'update'])->name('prescription.update');
    Route::get('/prescription/{id}', [PrescriptionController::class, 'index'])->name('prescription');
    Route::get('/prescription/remove/{id}', [PrescriptionController::class, 'destroy'])->name('prescription.remove');
    Route::get('/prescription/{id}/download', [PrescriptionController::class, 'download'])->name('prescription.download');

    Route::prefix('statistics')->group(function() {
        Route::get('/patients/{metric?}/{lapse?}', [PatientController::class, 'statistics'])->name('statistics.patients');
        Route::get('/appointments/{metric?}/{lapse?}', [MedicalAppointmentController::class, 'statistics'])->name('statistics.appointments');
        Route::get('/monthly_statistics/{lapse}', [HomeController::class, 'monthly_statistics'])->name('statistics.monthly_statistics');
    });
});

Route::get('/attachments/show/{filename}', [StudiesController::class, 'show']);
Route::get('/medical-appointments.json', [MedicalAppointmentController::class, 'json'])->name('medical_appointments.json');

Route::get('/test', function() {
    return response('Ok', 200);
});
