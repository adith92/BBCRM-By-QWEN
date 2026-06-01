<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. Root redirect logic
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('gm')) {
            return redirect('/dashboard/gm');
        } elseif ($user->hasRole('sales')) {
            return redirect('/fleet');
        } elseif ($user->hasRole('finance')) {
            return redirect('/invoices');
        } elseif ($user->hasRole('ops')) {
            return redirect('/bookings');
        }
    }
    return redirect('/login');
});

// 2. Login View Route
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return view('login');
})->name('login');

// 3. Logout POST Route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// 4. Protected Role-Based Dashboard Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard/gm', function () {
        return view('dashboards.gm');
    })->middleware('role:gm');

    Route::get('/fleet', function () {
        return view('fleet.index');
    })->middleware('role:sales|gm|ops');

    Route::get('/fleet/{vehicle}', function (\App\Models\Vehicle $vehicle) {
        return view('fleet.detail', compact('vehicle'));
    })->middleware('role:sales|gm|ops');

    Route::get('/invoices', function () {
        return view('invoices.index');
    })->middleware('role:finance|gm');

    Route::get('/invoices/{invoice}', function (\App\Models\Invoice $invoice) {
        return view('invoices.detail', compact('invoice'));
    })->middleware('role:finance|gm');

    Route::get('/clients/{client}', function (\App\Models\Client $client) {
        return view('clients.detail', compact('client'));
    })->middleware('role:sales|gm|ops|finance');

    Route::get('/bookings', function () {
        return view('dashboards.ops');
    })->middleware('role:ops|gm');

    Route::get('/bookings/create', function () {
        return view('bookings.create');
    })->middleware('role:ops|gm');

    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->middleware('role:gm|finance|sales|ops');
    Route::get('/reports/download/{type}', [\App\Http\Controllers\ReportController::class, 'download'])->middleware('role:gm|finance|sales|ops');

});
