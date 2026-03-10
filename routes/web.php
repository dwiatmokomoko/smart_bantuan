<?php

use App\Http\Controllers\feature\bo\auth\AuthenticationController;
use App\Http\Controllers\feature\bo\criteria\CriteriaController;
use App\Http\Controllers\feature\bo\dashboard\DashboardController;
use App\Http\Controllers\feature\bo\data_testing\DataTestingController;
use App\Http\Controllers\feature\bo\data_training\DataTrainingController;
use App\Http\Controllers\feature\bo\sub_criteria\SubCriteriaController;
use App\Http\Controllers\feature\bo\user\UserController;
use App\Http\Controllers\feature\bo\submission\SubmissionController;
use App\Http\Controllers\feature\bo\user\FoUserController;

use App\Http\Controllers\feature\fo\home\Home_foController;
use App\Http\Controllers\feature\fo\about\About_foController;
use App\Http\Controllers\feature\fo\contact\Contact_foController;
use App\Http\Controllers\feature\fo\count\CountController;
use App\Http\Controllers\feature\fo\count\PreEligibilityController;
use App\Http\Controllers\feature\fo\ourteam\Ourteam_foController;
use App\Http\Controllers\feature\fo\diagnosis\DiagnosisController;
use App\Http\Controllers\feature\fo\auth\UserAuthController;
use App\Http\Controllers\feature\fo\berkas\BerkasController;
use App\Http\Controllers\feature\fo\pengajuan\PengajuanController;
use App\Http\Controllers\feature\fo\pengajuan\PengajuanDetailController;
use Illuminate\Support\Facades\Route;




//bo (admin)
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/login', [AuthenticationController::class, 'index'])->name('admin.login');
        Route::post('/login', [AuthenticationController::class, 'auth'])->name('admin.auth');
    });

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.home');
        Route::post('/logout', [AuthenticationController::class, 'logout'])->name('admin.logout');

        Route::resource('/criteria', CriteriaController::class);
        Route::get('criterias', [CriteriaController::class, 'datas'])->name("criterias.data");

        Route::resource('/sub-criteria', SubCriteriaController::class);
        Route::get('sub-criterias', [SubCriteriaController::class, 'datas'])->name("sub-criterias.data");

        Route::resource('/data-training', DataTrainingController::class);
        Route::get('data-trainings', [DataTrainingController::class, 'datas'])->name("data-trainings.data");

        Route::resource('/data-testing', DataTestingController::class);
        Route::get('data-testings', [DataTestingController::class, 'datas'])->name("data-testings.data");

        Route::resource('/user', UserController::class);
        Route::get('users', [UserController::class, 'datas'])->name("users.data");




        // Data USER (warga) – hanya yang registrasi di FO (tabel users)
        Route::get('/fo-users', [FoUserController::class, 'index'])->name('fo-users.index');
        Route::get('/fo-users/data', [FoUserController::class, 'datas'])->name('fo-users.data');
        Route::get('/fo-users/{id}/edit', [FoUserController::class, 'edit'])->name('fo-users.edit');        // opsional
        Route::delete('/fo-users/{id}', [FoUserController::class, 'destroy'])->name('fo-users.destroy');



        Route::get('/submissions', [SubmissionController::class, 'index'])->name('admin.submissions.index');
        Route::get('/submissions/data', [SubmissionController::class, 'data'])
            ->name('admin.submissions.data');
        Route::get('/submissions/{id}', [SubmissionController::class, 'show'])->name('admin.submissions.show');
        Route::post('/submissions/{id}/status', [SubmissionController::class, 'updateStatus'])
            ->name('admin.submissions.updateStatus');
        Route::post('/submissions/{id}/status', [SubmissionController::class, 'updateStatus'])
            ->name('admin.submissions.status');
    });
});

//fo (user)
Route::get('/', [Home_foController::class, 'index'])->name('fo.home.index');
Route::get('/about', [About_foController::class, 'index'])->name('fo.about.index');
Route::get('/contact', [Contact_foController::class, 'index'])->name('fo.contact.index');

// Route::get('/pra-kelayakan', function () {
//     return view('feature.fo.count.pre_eligibility');
// })->name('pre-eligibility.form');

// Route::post('/pra-kelayakan/check', [PreEligibilityController::class, 'check'])->name('pre-eligibility.check');
Route::middleware('auth:web')->group(function () {

});

Route::get('/count', [CountController::class, 'index'])->name('fo.count.index');
Route::post('/count', [CountController::class, 'predict'])->name('fo.count.predict');
Route::get('/ourteam', [Ourteam_foController::class, 'index'])->name('fo.ourteam.index');
Route::get('/diagnosis/register', [DiagnosisController::class, 'showRegistrationForm'])->name('fo.diagnosis.register');
Route::post('/diagnosis/register', [DiagnosisController::class, 'register'])->name('fo.diagnosis.register.submit');
Route::get('/diagnosis', [DiagnosisController::class, 'showDiagnosisForm'])->name('feature.fo.diagnosis.index');
Route::post('/diagnosis/submit', [DiagnosisController::class, 'submit'])->name('feature.fo.diagnosis.submit');

// Pra-kelayakan (public)
Route::get('/pra-kelayakan', fn() => view('feature.fo.count.pre_eligibility'))->name('pre-eligibility.form');
Route::post('/pra-kelayakan/check', [PreEligibilityController::class, 'check'])->name('pre-eligibility.check');

// Hanya boleh akses COUNT jika sudah login
Route::middleware('auth:web')->group(function () {
    Route::get('/count', [CountController::class, 'index'])->name('fo.count.index');
    Route::post('/count', [CountController::class, 'predict'])->name('fo.count.predict');
    Route::get('/count/result', [CountController::class, 'result'])->name('fo.count.result');

    Route::get('/berkas/upload', [BerkasController::class, 'create'])
        ->name('fo.berkas.create');
    Route::post('/berkas/upload', [BerkasController::class, 'store'])
        ->name('fo.berkas.store');
    Route::get('/pengajuan/riwayat', [PengajuanController::class, 'history'])
        ->name('fo.pengajuan.history');
    Route::get('/pengajuan/{ticket}/kriteria', [PengajuanDetailController::class, 'kriteria'])
        ->name('fo.pengajuan.kriteria');
    Route::get('/pengajuan/{ticket}/berkas', [PengajuanDetailController::class, 'berkas'])
        ->name('fo.pengajuan.berkas');
});

// Group User (auth web)
Route::prefix('user')->name('user.')->group(function () {
    // Guest user
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [UserAuthController::class, 'login'])->name('login.post');

        // REGISTER hanya jika lolos pra-kelayakan
        Route::middleware('pre.eligible')->group(function () {
            Route::get('register', [UserAuthController::class, 'showRegisterForm'])->name('register');
            Route::post('register', [UserAuthController::class, 'register'])->name('register.post');
        });
    });

    // Sudah login
    Route::middleware('auth:web')->group(function () {
        Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');
    });
});

