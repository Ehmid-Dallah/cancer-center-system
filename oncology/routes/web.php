<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CeoUserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DispensationController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Auth;

use App\Models\Center;
use App\Models\Patient;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
});




Route::middleware(['auth'])->group(function () {
    Route::get('/ceo/create-user-form', function () {
        return view('ceo-create-user');
    })->name('ceo.user.form');

    
    Route::get('/hi', function () {
        return view('hi');
    });
    
});


//الصفحات أو المسارات الخاصة برؤساء الاقسام
Route::middleware(['auth'])->group(function () {
    Route::get('/ceo/dashboard', [CeoUserController::class, 'dashboard'])->name('ceo.dashboard');
    Route::post('/ceo/create-user', [CeoUserController::class, 'store'])->name('ceo.user.store');
    Route::get('/ceo/edit-user/{id}', [CeoUserController::class, 'edit'])->name('ceo.user.edit');
Route::put('/ceo/update-user/{id}', [CeoUserController::class, 'update'])->name('ceo.user.update');
Route::delete('/ceo/delete-user/{id}', [CeoUserController::class, 'destroy'])->name('ceo.user.destroy');

});


//المسارات الخاصة بمدير الهيئة
Route::middleware(['auth', 'isSuperAdmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::post('/superadmin/create-center', [SuperAdminController::class, 'storeCenter'])->name('superadmin.center.store');
    Route::post('/superadmin/create-admin', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admin.store');
    // لمسار الأدمن
Route::get('/superadmin/admin/{id}/edit', [SuperAdminController::class, 'editAdmin'])->name('superadmin.admin.edit');
Route::put('/superadmin/admin/{id}', [SuperAdminController::class, 'updateAdmin'])->name('superadmin.admin.update');
Route::delete('/superadmin/admin/{id}', [SuperAdminController::class, 'destroyAdmin'])->name('superadmin.admin.destroy');

// لمسار المركز
Route::get('/superadmin/center/{id}/edit', [SuperAdminController::class, 'editCenter'])->name('superadmin.center.edit');
Route::put('/superadmin/center/{id}', [SuperAdminController::class, 'updateCenter'])->name('superadmin.center.update');
Route::delete('/superadmin/center/{id}', [SuperAdminController::class, 'destroyCenter'])->name('superadmin.center.destroy');

});



  // لمسار الأدمن
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminUserController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/create-ceo', [AdminUserController::class, 'store'])->name('admin.create.ceo');
     // ✅ Route التعديل
    Route::get('/admin/edit-ceo/{id}', [AdminUserController::class, 'edit'])->name('admin.edit.ceo');
    Route::put('/admin/update-ceo/{id}', [AdminUserController::class, 'update'])->name('admin.update.ceo');

    // ✅ Route الحذف
    Route::delete('/admin/delete-ceo/{id}', [AdminUserController::class, 'destroy'])->name('admin.delete.ceo');
});




Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

//الخاصات بالموظف


Route::middleware(['auth', 'EmployeeMiddleware'])->group(function () {
    Route::get('/employee/dashboard', function () {
        $centers = Center::all();
        $patients = Patient::all();
        return view('employee.dashboard', compact('centers', 'patients'));
    })->name('employee.dashboard');

    Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
    Route::post('/patients/update/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/delete/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
});


//الخاصات بالطبيب
Route::middleware(['auth', 'DoctorMiddleware'])->group(function () {
    Route::get('/doctor/dashboard', [PrescriptionController::class, 'dashboard'])->name('doctor.dashboard');
    Route::post('/prescriptions/search', [PrescriptionController::class, 'searchPatient'])->name('prescriptions.search');
   // Route::get('/prescriptions/search-page', [PrescriptionController::class, 'searchPage'])->name('prescriptions.search.page');

    Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{id}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
Route::put('/prescriptions/{id}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
Route::patch('/prescriptions/{id}/confirm', [PrescriptionController::class, 'confirm'])->name('prescriptions.confirm');


});



//الخاصات بالصيدلاني
Route::middleware(['auth', 'PharmacistsMiddleware'])->group(function () {
    Route::get('/pharmacists/dashboard', [DispensationController::class, 'dashboard'])->name('pharmacists.dashboard');

    Route::post('/pharmacists/search-patient', [DispensationController::class, 'searchPatient'])->name('pharmacists.searchPatient');

    Route::post('/pharmacists/search-prescription', [DispensationController::class, 'searchPrescription'])->name('pharmacists.searchPrescription');

    Route::post('/pharmacists/dispensations', [DispensationController::class, 'store'])->name('dispensations.store');
    Route::get('/pharmacists/dispensations/{id}/edit', [DispensationController::class, 'edit'])->name('dispensations.edit');
Route::post('/pharmacists/dispensations/{id}/update', [DispensationController::class, 'update'])->name('dispensations.update');
Route::post('/pharmacists/dispensations/{id}/delete', [DispensationController::class, 'destroy'])->name('dispensations.destroy');


    Route::post('/pharmacists/drugs', [DrugController::class, 'store'])->name('drugs.store');
     Route::get('/pharmacists/drugs/{id}/edit', [DrugController::class, 'edit'])->name('drugs.edit');
    Route::post('/pharmacists/drugs/{id}/update', [DrugController::class, 'update'])->name('drugs.update');
    Route::post('/pharmacists/drugs/{id}/delete', [DrugController::class, 'destroy'])->name('drugs.destroy');
});

Route::middleware(['auth'])->group(function () {

    // لتحديث الاسم والبريد
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');

    // لتحديث كلمة المرور
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // لحذف الحساب
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

});
 //المسارات الخاصة بالمريض

Route::middleware(['auth', 'CheckRole'])->group(function () {
    Route::get('/patient/dashboard', [PatientController::class, 'myProfile'])->name('patient.dashboard');
});










require __DIR__.'/auth.php';
