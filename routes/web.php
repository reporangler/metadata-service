<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DefaultController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageGroupController;
use App\Http\Controllers\RepositoryController;

Route::get('/', [DefaultController::class, 'healthz']);
Route::options('/{path}', [DefaultController::class, 'cors'])->where('path', '.*');

Route::middleware(['cors', 'auth:token'])->group(function () {
    $repoPattern = '[a-z][a-z0-9\-\.]+';
    $pgPattern = '[a-z][a-z0-9\-\.]+';

    // Package routes
    Route::prefix('package')->group(function () use ($repoPattern) {
        Route::get('/{repository}', [PackageController::class, 'packages'])->where('repository', $repoPattern);
        Route::post('/{repository}', [PackageController::class, 'create'])->where('repository', $repoPattern);
        Route::get('/{repository}/by-name/{name}', [PackageController::class, 'packagesByName'])->where(['repository' => $repoPattern, 'name' => '.+']);
        Route::delete('/{repository}/{id}', [PackageController::class, 'deleteById'])->where(['repository' => $repoPattern, 'id' => '[0-9]+']);
    });

    // Package group routes
    Route::prefix('package-group')->group(function () use ($pgPattern) {
        Route::get('/', [PackageGroupController::class, 'getList']);
        Route::get('/{name}', [PackageGroupController::class, 'getByName'])->where('name', $pgPattern);
        Route::get('/{id}', [PackageGroupController::class, 'getById'])->where('id', '[0-9]+');
        Route::post('/', [PackageGroupController::class, 'create']);
        Route::put('/', [PackageGroupController::class, 'update']);
        Route::delete('/{id}', [PackageGroupController::class, 'deleteById'])->where('id', '[0-9]+');
    });

    // Repository routes
    Route::prefix('repository')->group(function () use ($repoPattern) {
        Route::get('/', [RepositoryController::class, 'getList']);
        Route::get('/{id}', [RepositoryController::class, 'getById'])->where('id', '[0-9]+');
        Route::get('/{repository}', [RepositoryController::class, 'getByName'])->where('repository', $repoPattern);
        Route::post('/', [RepositoryController::class, 'create']);
        Route::put('/{id}', [RepositoryController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/{id}', [RepositoryController::class, 'deleteById'])->where('id', '[0-9]+');
    });
});
