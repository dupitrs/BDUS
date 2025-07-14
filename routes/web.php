<?php
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SludinajumsController;
use App\Models\Sludinajums;
use App\Http\Controllers\ParskatsController;
use App\Http\Controllers\FiltriController;
use App\Http\Controllers\LietotajiController;
use App\Http\Controllers\ApliecinajumsController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return view('auth.login');
});


Route::get('register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');




Route::get('/dashboard', [SludinajumsController::class, 'dashboard'])->name('dashboard');
// Maršruts lietotājiem (auth), lai var pieteikties
Route::post('/sludinajumi/{id}/pieteikties', [SludinajumsController::class, 'pieteikties'])->name('pieteikties');







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sludinajumi/create', [SludinajumsController::class, 'create'])->name('sludinajumi.create');
    Route::post('/sludinajumi', [SludinajumsController::class, 'store'])->name('sludinajumi.store');
    Route::get('/sludinajumi/{id}/edit', [SludinajumsController::class, 'edit'])->name('sludinajumi.edit');
    Route::put('/sludinajumi/{id}', [SludinajumsController::class, 'update'])->name('sludinajumi.update');
    Route::delete('/sludinajumi/{id}', [SludinajumsController::class, 'destroy'])->name('sludinajumi.destroy');
    Route::put('/admin/sludinajumi/{id}/hide', [SludinajumsController::class, 'hide'])->name('sludinajumi.hide');
    Route::get('/sludinajumi/hidden', [SludinajumsController::class, 'hidden'])->name('sludinajumi.hidden');
    Route::put('/sludinajumi/{id}/unhide', [SludinajumsController::class, 'unhide'])->name('sludinajumi.unhide');
    Route::post('/sludinajumi/{id}/pieteikties', [SludinajumsController::class, 'pieteikties'])->name('sludinajumi.pieteikties');



});

Route::post('/sludinajums/{id}/toggle-pieteikums', [SludinajumsController::class, 'togglePieteikums'])->name('sludinajums.togglePieteikums');




Route::group(['middleware' => function ($request, $next) {
    if (Auth::guard('web')->check() || Auth::guard('admin')->check()) {
        return $next($request);
    }
    abort(403); // vai redirect uz login
}], function () {
    Route::get('/parskats', [ParskatsController::class, 'index'])->name('parskats.index');
    Route::post('/parskats', [ParskatsController::class, 'store'])->name('parskats.store');
    Route::patch('/parskats/{epasts}/{id}/status', [ParskatsController::class, 'updateStatus'])->name('parskats.updateStatus');
    Route::delete('/parskats/{epasts}/{id}', [ParskatsController::class, 'destroy'])->name('parskats.destroy');

});

Route::get('/parskats', [FiltriController::class, 'index'])->name('parskats.index');


Route::middleware(['auth:admin'])->group(function () {
    Route::get('/lietotaji', [LietotajiController::class, 'index'])->name('lietotaji.index');
    Route::delete('/lietotaji/{epasts}', [LietotajiController::class, 'destroy'])->name('lietotaji.destroy');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/apliecinajums', [ApliecinajumsController::class, 'index'])->name('apliecinajums.index');
    Route::post('/admin/apliecinajums/generate', [ApliecinajumsController::class, 'generate'])->name('apliecinajums.generate');
    Route::get('/admin/apliecinajums/vesture', [ApliecinajumsController::class, 'vesture'])->name('apliecinajums.vesture');
    Route::get('/admin/apliecinajums/download/{id}', [ApliecinajumsController::class, 'download'])->name('apliecinajums.download');
    Route::delete('/admin/apliecinajums/delete/{id}', [ApliecinajumsController::class, 'delete'])->name('apliecinajums.delete');
    
});

Route::middleware(['auth:admin'])->get('/apliecinajums', [ApliecinajumsController::class, 'index'])->name('apliecinajums.index');

Route::get('/par-mums', function () {
    return view('par-mums');
})->name('par-mums');



require __DIR__.'/auth.php';
