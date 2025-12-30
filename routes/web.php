<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribeTransactionController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

//domain.com/details
Route::get('/detail/{course:slug}', [FrontController::class, 'details'])->name('front.details');
Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('front.category');
Route::get('/pricing', [FrontController::class, 'pricing'])->name('front.pricing');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //must logged in before create a transaction
    Route::get('/checkout/{course}', [FrontController::class, 'checkout'])->name('front.checkout');

    Route::post('/checkout/store/{course}', [FrontController::class, 'checkout_store'])->name('front.checkout.store');

    //domain.com/learning/100/5
    Route::get('/learning/{course}/{courseVideoId}', [FrontController::class, 'learning'])->name('front.learning');
    Route::post(
        '/materi/{video}/complete',
        [FrontController::class, 'completeMateri']
    )->name('materi.complete');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->middleware('role:owner');

        Route::resource('teachers', TeacherController::class)
            ->middleware('role:owner');

        Route::resource('courses', CourseController::class)
            ->middleware('role:owner|teacher');

        Route::resource('subscribe_transactions', SubscribeTransactionController::class)
            ->middleware('role:owner');

        Route::get('/add/video/save/{course:id}', [CourseVideoController::class, 'create'])
            ->middleware('role:teacher|owner')->name('course.add_video');

        Route::post('/add/video/save/{course:id}', [CourseVideoController::class, 'store'])
            ->middleware('role:teacher|owner')->name('course.add_video.save');

        Route::resource('course_videos', CourseVideoController::class)
            ->middleware('role:owner|teacher');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/certificate/{user}', [CertificateController::class, 'index'])->name('certificate');
    Route::get('/certificate/download/{id}', [CertificateController::class, 'download'])->name('certificate.download');
});

require __DIR__ . '/auth.php';
