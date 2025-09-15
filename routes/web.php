<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolController;
use App\Models\User;
use App\Http\Controllers\StudentCalendarController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\StudentOffDayAdminController;

Route::middleware(['web','auth'])->prefix('staff')->name('staff.')->group(function(){
    // 祝日・特別休暇
    Route::get('holidays', [HolidayController::class,'index'])->name('holidays.index');
    Route::get('holidays/create', [HolidayController::class,'create'])->name('holidays.create');
    Route::post('holidays', [HolidayController::class,'store'])->name('holidays.store');
    Route::get('holidays/{holiday}/edit', [HolidayController::class,'edit'])->name('holidays.edit');
    Route::put('holidays/{holiday}', [HolidayController::class,'update'])->name('holidays.update');
    Route::delete('holidays/{holiday}', [HolidayController::class,'destroy'])->name('holidays.destroy');
    Route::post('holidays/bulk', [HolidayController::class,'bulkStore'])->name('holidays.bulk');
    Route::post('holidays/import', [HolidayController::class,'importFromApi'])->name('holidays.import');


    // 生徒の個別休
    Route::get('student-off-days', [StudentOffDayAdminController::class,'index'])->name('student_off_days.index');
    Route::get('student-off-days/create', [StudentOffDayAdminController::class,'create'])->name('student_off_days.create');
    Route::post('student-off-days', [StudentOffDayAdminController::class,'store'])->name('student_off_days.store');
    Route::get('student-off-days/{student_off_day}/edit', [StudentOffDayAdminController::class,'edit'])->name('student_off_days.edit');
    Route::put('student-off-days/{student_off_day}', [StudentOffDayAdminController::class,'update'])->name('student_off_days.update');
    Route::delete('student-off-days/{student_off_day}', [StudentOffDayAdminController::class,'destroy'])->name('student_off_days.destroy');
    Route::post('student-off-days/bulk', [StudentOffDayAdminController::class,'bulkStore'])->name('student_off_days.bulk');
});
Route::middleware(['web','auth'])->group(function(){
    Route::get('/staff/calendars', [StudentCalendarController::class, 'index'])->name('staff.calendars.index');
    Route::get('/staff/calendars/pdf', [StudentCalendarController::class, 'pdf'])->name('staff.calendars.pdf');
});

// =======================
// 生徒ログイン
// =======================
use App\Http\Controllers\StudentAuthController;

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ダッシュボード（ログイン必須）: 一覧を表示
Route::get('/dashboard', function () {
    $users = User::orderBy('id')->paginate(10);
    return view('dashboard', compact('users'));
})->middleware(['auth', 'verified'])->name('dashboard');

// =======================
// ユーザー新規登録（ログイン不要=guest）
// ※ ここで /register に「register.create」「register.store」名を付与
// =======================
Route::middleware('guest')->group(function () {
    Route::get('/register', [SettingsController::class, 'createUser'])->name('register.create');
    Route::post('/register', [SettingsController::class, 'storeUser'])->name('register.store');
});

// =======================
// 設定（ユーザー管理）※ログイン必須
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/create', [SettingsController::class, 'createUser'])->name('createUser');
    Route::get('/settings/users', [SettingsController::class, 'listUsers'])->name('settings.listUsers');
    Route::get('/settings/users/{id}/edit', [SettingsController::class, 'editUser'])->name('settings.editUser');
    Route::patch('/settings/users/{id}', [SettingsController::class, 'updateUser'])->name('settings.updateUser');
});

// =======================
// 学校検索 API（公開）
// =======================
Route::get('/api/schools', [SchoolController::class, 'search'])->name('schools.search');

// =======================
// 生徒登録（ログイン必須）
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
});

// =======================
// 認証（独自AuthController）
// =======================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =======================
// ホーム
// =======================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// =======================
// プロフィール（ログイン必須）
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze/Jetstream のデフォルト認証ルート（※ register 重複を避けるため後述の auth.php を必ず修正）
require __DIR__.'/auth.php';
