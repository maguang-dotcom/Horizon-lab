<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Engineer\EngineerDashboardController;
use App\Http\Controllers\Engineer\EngineerServiceReportController;
use App\Http\Controllers\Engineer\EngineerSettingsController;
use App\Http\Controllers\Engineer\EngineerWorkspaceController;
use App\Http\Controllers\Facility\FacilityDashboardController;
use App\Http\Controllers\Facility\FacilityReportController;
use App\Http\Controllers\Facility\FacilityServiceRequestController;
use App\Http\Controllers\Facility\FacilitySettingsController;
use App\Http\Controllers\ServiceRequestController;
use App\Models\EngineerProfile;
use App\Models\Facility;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $metrics = collect([
        'facilities' => Schema::hasTable('facilities') ? Facility::query()->count() : 0,
        'engineers' => Schema::hasTable('engineer_profiles')
            ? EngineerProfile::query()->where('is_available', true)->count()
            : 0,
        'resolved_requests' => Schema::hasTable('service_requests')
            ? ServiceRequest::query()->where('status', 'resolved')->count()
            : 0,
    ]);

    return view('welcome', compact('metrics'));
})->name('home');

Route::view('/login', 'Auth.login')->name('login');
Route::get('/facility/login', [LoginController::class, 'showFacilityLoginForm'])->name('facility.login');
Route::post('/facility/login', [LoginController::class, 'loginFacility'])->name('facility.login.submit');
Route::get('/engineer/login', [LoginController::class, 'showEngineerLoginForm'])->name('engineer.login');
Route::post('/engineer/login', [LoginController::class, 'loginEngineer'])->name('engineer.login.submit');
Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('admin.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/engineers', [AdminManagementController::class, 'engineers'])->name('engineers.index');
        Route::get('/service-requests', [AdminManagementController::class, 'serviceRequests'])->name('service-requests.index');
        Route::get('/facilities', [AdminManagementController::class, 'facilities'])->name('facilities.index');
        Route::get('/assignments', [AdminManagementController::class, 'assignments'])->name('assignments.index');
        Route::get('/reports', [AdminManagementController::class, 'reports'])->name('reports.index');
        Route::get('/users', [AdminManagementController::class, 'users'])->name('users.index');
        Route::get('/users/{user}', [AdminManagementController::class, 'showUser'])->name('users.show');
        Route::get('/settings', [AdminManagementController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [AdminManagementController::class, 'updateSettings'])->name('settings.update');
        Route::post('/admins', [AdminManagementController::class, 'storeAdmin'])->name('admins.store');
        Route::delete('/users/{user}', [AdminManagementController::class, 'destroyUser'])->name('users.destroy');
        Route::delete('/engineers/{user}/reject', [AdminManagementController::class, 'rejectEngineer'])->name('engineers.reject');
        Route::post('/engineers/{user}/approve', [AdminDashboardController::class, 'approveEngineer'])->name('engineers.approve');
        Route::post('/service-requests/{serviceRequest}/assign', [AdminDashboardController::class, 'assignEngineer'])->name('service-requests.assign');
        Route::post('/biomedical-service-requests/{biomedicalServiceRequest}/assign', [AdminDashboardController::class, 'assignBiomedicalEngineer'])
            ->name('biomedical-service-requests.assign');
    });

Route::middleware(['auth', 'role:facility'])
    ->get('/dashboard', [FacilityDashboardController::class, 'index'])
    ->name('dashboard');

// Registration
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::view('/engineer-register', 'Auth.engineer-register')->name('engineer-register');
Route::post('/engineer-register', [RegisterController::class, 'registerEngineer'])->name('engineer-register.submit');

// Password reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware('auth')->get('/service-requests/create', [ServiceRequestController::class, 'create'])
    ->name('service-requests.create');

Route::get('/work-with-Us', function () {
    return view('work-With-Us');
})->name('work-with-Us');
Route::get('/about-Us', function () {
    return view('about-Us');
})->name('about-Us');

Route::get('/services-Repair', function () {
    return view('services-repair');
})->name('services-Repair');
Route::get('/products', function () {
    return view('products');
})->name('products.index');

// Sub-pages the product cards link to
Route::view('/products/electric-bed-rental', 'products.electric-bed-rental')->name('products.electric-bed-rental');
Route::view('/products/medical-beds', 'products.medical-beds')->name('products.medical-beds');
Route::view('/products/patient-handling', 'products.patient-handling')->name('products.patient-handling');
Route::view('/products/physiotherapy', 'products.physiotherapy')->name('products.physiotherapy');
Route::view('/products/pressure-care', 'products.pressure-care')->name('products.pressure-care');

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['auth', 'role:facility'])
    ->prefix('facility')
    ->name('facility.')
    ->group(function () {
        Route::post('service-requests', [App\Http\Controllers\Facility\ServiceRequestController::class, 'store'])
            ->name('service-requests.store');
        Route::post('service-requests/{serviceRequest}/assign/{engineerProfileId}', [App\Http\Controllers\Facility\ServiceRequestController::class, 'assign'])
            ->name('service-requests.assign');
    });

// Add inside your existing facility-only route group
// (the one already gated by facility auth middleware).
Route::middleware(['auth', 'role:facility'])
    ->prefix('facility')
    ->name('facility.')
    ->group(function () {

        Route::get('/', [FacilityDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('service-requests/create', [FacilityServiceRequestController::class, 'create'])
            ->name('service-requests.create');

        Route::post('service-requests', [FacilityServiceRequestController::class, 'store'])
            ->name('service-requests.store');

        // Referenced by the sidebar / quick links but not built yet —
        // wire these to real controllers as those screens are built.
        Route::get('service-requests', [FacilityServiceRequestController::class, 'index'])
            ->name('service-requests.index');
        Route::view('equipment', 'HealthyFacility.comingsoon')->name('equipment.index');
        Route::view('providers', 'HealthyFacility.comingsoon')->name('providers.index');
        Route::view('service-records', 'HealthyFacility.comingsoon')->name('service-records.index');
        Route::get('reports', [FacilityReportController::class, 'index'])
            ->name('reports.index');
        Route::get('settings', [FacilitySettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings/profile', [FacilitySettingsController::class, 'updateProfile'])
            ->name('settings.profile.update');
        Route::put('settings/password', [FacilitySettingsController::class, 'updatePassword'])
            ->name('settings.password.update');
    });

// Add inside your existing engineer-only route group.
Route::middleware(['auth', 'role:engineer'])
    ->prefix('engineer')
    ->name('engineer.')
    ->group(function () {
        Route::get('/', [EngineerDashboardController::class, 'index'])->name('dashboard');
        Route::get('assignments', [EngineerWorkspaceController::class, 'assignments'])->name('assignments');
        Route::get('facilities', [EngineerWorkspaceController::class, 'facilities'])->name('facilities');
        Route::get('reports', [EngineerWorkspaceController::class, 'reports'])->name('reports.index');

        Route::get('requests/{serviceRequest}/report', [EngineerServiceReportController::class, 'create'])
            ->name('reports.create');
        Route::post('requests/{serviceRequest}/report', [EngineerServiceReportController::class, 'store'])
            ->name('reports.store');

        Route::get('settings', [EngineerSettingsController::class, 'edit'])->name('settings');
        Route::put('settings/profile', [EngineerSettingsController::class, 'updateProfile'])->name('settings.profile.update');
        Route::put('settings/password', [EngineerSettingsController::class, 'updatePassword'])->name('settings.password.update');
    });
