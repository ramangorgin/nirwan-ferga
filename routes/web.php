<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\SessionMaterialController;
use App\Http\Controllers\AttendanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Courses
    |--------------------------------------------------------------------------
    */
    Route::resource('courses', CourseController::class);

    // CourseController@enroll(EnrollmentStoreRequest $request, Course $course)
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])
        ->name('courses.enroll');


    /*
    |--------------------------------------------------------------------------
    | Class Sessions
    |--------------------------------------------------------------------------
    */
    Route::resource('class-sessions', ClassSessionController::class);


    /*
    |--------------------------------------------------------------------------
    | Enrollments
    |--------------------------------------------------------------------------
    */
    Route::resource('enrollments', EnrollmentController::class);

    // EnrollmentController@createManual()
    Route::get('enrollments/manual/create', [EnrollmentController::class, 'createManual'])
        ->name('enrollments.manual.create');

    // EnrollmentController@storeManual(EnrollmentManualStoreRequest $request)
    Route::post('enrollments/manual', [EnrollmentController::class, 'storeManual'])
        ->name('enrollments.manual.store');


    /*
    |--------------------------------------------------------------------------
    | Discount Codes (JSON)
    |--------------------------------------------------------------------------
    */
    Route::post('discount-codes/validate', [DiscountCodeController::class, 'validateCode'])
        ->name('discount-codes.validate');

    Route::resource('discount-codes', DiscountCodeController::class)
        ->parameters(['discount-codes' => 'discount_code']);


    /*
    |--------------------------------------------------------------------------
    | Session Materials
    |--------------------------------------------------------------------------
    */
    Route::post('session-materials', [SessionMaterialController::class, 'store'])
        ->name('session-materials.store');

    Route::patch('session-materials/{sessionMaterial}', [SessionMaterialController::class, 'update'])
        ->name('session-materials.update');

    Route::delete('session-materials/{sessionMaterial}', [SessionMaterialController::class, 'destroy'])
        ->name('session-materials.destroy');

    /*
    |--------------------------------------------------------------------------
    | Attendances
    |--------------------------------------------------------------------------
    */
    Route::post('class-sessions/{class_session}/attendances', [AttendanceController::class, 'upsert'])
    ->name('class-sessions.attendances.upsert');
});

require __DIR__.'/auth.php';
