<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\EmailRecipientController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RealizationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Password change for first login
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');
});

// Protected routes with first login check
Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckFirstLogin::class, \App\Http\Middleware\SetLocale::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contract edit routes (protected by CanEditContract middleware)
    Route::middleware(\App\Http\Middleware\CanEditContract::class)->group(function () {
        Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
        Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
        Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
        Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
        Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
        Route::post('/contracts/{contract}/prognosa', [ContractController::class, 'updatePrognosa'])->name('contracts.update-prognosa');

        // Amendment routes
        Route::post('/contracts/{contract}/amendments', [\App\Http\Controllers\AmendmentController::class, 'store'])->name('amendments.store');
        Route::put('/amendments/{amendment}', [\App\Http\Controllers\AmendmentController::class, 'update'])->name('amendments.update');
        Route::delete('/amendments/{amendment}', [\App\Http\Controllers\AmendmentController::class, 'destroy'])->name('amendments.destroy');
    });

    // Contracts (view for all) - these must come AFTER the create route
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');

    // Realization routes (protected by CanEditContract middleware)
    Route::middleware(\App\Http\Middleware\CanEditContract::class)->group(function () {

        // Realizations
        Route::get('/realizations', [RealizationController::class, 'index'])->name('realizations.index');
        Route::get('/realizations/create/{contract}', [RealizationController::class, 'create'])->name('realizations.create');
        Route::post('/realizations', [RealizationController::class, 'store'])->name('realizations.store');
        Route::get('/realizations/{realization}/edit', [RealizationController::class, 'edit'])->name('realizations.edit');
        Route::put('/realizations/{realization}', [RealizationController::class, 'update'])->name('realizations.update');
        Route::delete('/realizations/{realization}', [RealizationController::class, 'destroy'])->name('realizations.destroy');
    });

    // Admin only routes
    Route::middleware(\App\Http\Middleware\AdminOnly::class)->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // Email Recipients
        Route::resource('email-recipients', EmailRecipientController::class);

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/send-reminder', [SettingsController::class, 'sendReminder'])->name('settings.send-reminder');

        // Email Logs
        Route::get('/email-logs', [EmailLogController::class, 'index'])->name('email-logs.index');

        // API: Get contracts by IDs (for email log modals)
        Route::post('/api/contracts-by-ids', function (\Illuminate\Http\Request $request) {
            $ids = $request->input('ids', []);
            $contracts = \App\Models\Contract::with('amendments')
                ->whereIn('id', $ids)
                ->get()
                ->map(function ($contract) {
                    return [
                        'id' => $contract->id,
                        'contract_number' => $contract->contract_number,
                        'title' => $contract->title,
                        'vendor_name' => $contract->vendor_name,
                        'current_status' => $contract->current_status,
                        'is_bridging' => $contract->amendments->where('is_bridging', true)->count() > 0,
                        'end_date' => $contract->end_date ? $contract->end_date->format('d M Y') : '-',
                        'effective_end_date' => \Carbon\Carbon::parse($contract->effective_end_date)->format('d M Y'),
                        'field_label' => $contract->field_label,
                        'fungsi_label' => $contract->fungsi_label,
                    ];
                });
            return response()->json($contracts);
        })->name('api.contracts-by-ids');
    });
});

require __DIR__ . '/auth.php';
