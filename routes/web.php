<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LoadModalController;
use App\Http\Controllers\DatatableController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::get('/patients', [PatientsController::class, 'index'])->name('patient.index');
    Route::post('/patients', [PatientsController::class, 'store'])->name('patient.store');
    Route::put('/patients/{id}', [PatientsController::class, 'update'])->name('patient.update');
    Route::delete('/patients/{id}', [PatientsController::class, 'destroy'])->name('patient.destroy');
    Route::post('/patients/sync', [PatientsController::class, 'syncPatients'])->name('sync.patients');
    Route::get('/patients/data', [PatientsController::class, 'getPatients'])->name('patients.data');
    Route::get('/patients/data/proof/{id}', [ActivityController::class, 'getPatientsProof'])->name('patients.data.proof');

    Route::put('/profile/{id}', [AuthController::class, 'update'])->name('profile.update');
    Route::put('/change-password/{id}', [AuthController::class, 'changePassword'])->name('change.password');
    Route::prefix('kegiatan')
        ->name('activity.')
        ->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::post('/', [ActivityController::class, 'store'])->name('store');
            Route::post('/detail', [ActivityController::class, 'storeDetail'])->name('storeDetail');
            Route::put('/{id}', [ActivityController::class, 'update'])->name('update');
            Route::delete('/{id}', [ActivityController::class, 'destroy'])->name('destroy');
            Route::delete('/detail/{id}', [ActivityController::class, 'deleteDetail'])->name('deleteDetail');
            Route::get('/detail/{id}', [ActivityController::class, 'getDetail'])->name('getDetail');
            Route::post('/print', [ActivityController::class, 'printPDF'])->name('print');
            Route::get('/get-dates', [ActivityController::class, 'getDates'])->name('getDates');
        });
    Route::prefix('kegiatanku')
        ->name('activityEmployee.')
        ->group(function () {
            Route::get('/', [ActivityController::class, 'indexEmployee'])->name('index');
            Route::post('/', [ActivityController::class, 'storeProof'])->name('store');
            Route::post('/patient', [ActivityController::class, 'storePatientProof'])->name('storePatientProof');
            Route::post('/deleteProof', [ActivityController::class, 'deletePatientProof'])->name('deleteProof');
            Route::get('/print/{id}', [ActivityController::class, 'printPDFProof'])->name('print');
            Route::get('/print/surat-tugas/{id}', [ActivityController::class, 'printSuratTugas'])->name('print.st');
			Route::get('/proof/{activityDetail}', [ActivityController::class, 'proof'])->name('proof');
			Route::post('/proof/{activityDetail}/edit', [ActivityController::class, 'editProof'])->name('editProof');
			Route::get('/deleteActivityPatient/{activityPatient}', [ActivityController::class, 'deleteActivityPatient'])->name('deleteActivityPatient');
        });
    Route::prefix('kegiatan-monitoring')
        ->name('activityMonitoring.')
        ->group(function () {
            Route::get('/', [ActivityController::class, 'monitoring'])->name('index');
            Route::post('/', [ActivityController::class, 'storeCheckActivity'])->name('store');
        });
    Route::prefix('master')
        ->name('master.')
        ->group(function () {
            Route::get('profesi', [MasterController::class, 'profession'])->name('profession');
            Route::post('profesi', [MasterController::class, 'storeProfession'])->name('add-profession');
            Route::put('profesi/{id}', [MasterController::class, 'updateProfession'])->name('update-profession');
            Route::delete('profesi/{id}', [MasterController::class, 'destroyProfession'])->name('delete-profession');
            Route::get('jabatan', [MasterController::class, 'position'])->name('position');
            Route::post('jabatan', [MasterController::class, 'storePosition'])->name('add-position');
            Route::put('jabatan/{id}', [MasterController::class, 'updatePosition'])->name('update-position');
            Route::delete('jabatan/{id}', [MasterController::class, 'destroyPosition'])->name('delete-position');
            Route::get('pangkat', [MasterController::class, 'rank'])->name('rank');
            Route::post('pangkat', [MasterController::class, 'storeRank'])->name('add-rank');
            Route::put('pangkat/{id}', [MasterController::class, 'updateRank'])->name('update-rank');
            Route::delete('pangkat/{id}', [MasterController::class, 'destroyRank'])->name('delete-rank');
            Route::get('program', [MasterController::class, 'program'])->name('program');
            Route::post('program', [MasterController::class, 'storeProgram'])->name('add-program');
            Route::put('program/{id}', [MasterController::class, 'updateProgram'])->name('update-program');
            Route::delete('program/{id}', [MasterController::class, 'destroyProgram'])->name('delete-program');
            Route::get('layanan', [MasterController::class, 'service'])->name('service');
            Route::post('layanan', [MasterController::class, 'storeService'])->name('add-service');
            Route::put('layanan/{id}', [MasterController::class, 'updateService'])->name('update-service');
            Route::delete('layanan/{id}', [MasterController::class, 'destroyService'])->name('delete-service');
        });

	Route::prefix('loadModal')
        ->name('loadModal.')
        ->group(function () {
            Route::get('/editBuktiKegiatan/{activity}', [LoadModalController::class, 'editBuktiKegiatan'])->name('editBuktiKegiatan');
			Route::get('/editPatientActivityModal/{activity}', [LoadModalController::class, 'editPatientActivityModal'])->name('editPatientActivityModal');
        });

	Route::prefix('dt')
		->name('dt.')
		->group(function () {
			Route::get('/patients', [DatatableController::class, 'patients'])->name('patients');
		});
});

Route::get('provinces', [DependentDropdownController::class, 'provinces'])->name('provinces');
Route::get('cities/{provinceId}', [DependentDropdownController::class, 'citiesData'])->name('cities');
Route::get('districts/{cityId}', [DependentDropdownController::class, 'districtsData'])->name('districts');
Route::get('villages/{districtId}', [DependentDropdownController::class, 'villagesData'])->name('villages');
