<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LppmVerificationController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\FinalReportController;
use App\Http\Controllers\ReportExportController;
use App\Http\Controllers\UserController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/quick-login/{id}', [AuthController::class, 'quickLogin'])->name('quick.login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Skema Hibah (Tahap 1 SOP & SKPL-F-04)
    Route::resource('schemes', SchemeController::class);

    // Pengusulan & Pengelolaan Proposal (Tahap 2 SOP & SKPL-F-06 s.d. F-11)
    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/members', [ProposalController::class, 'addMember'])->name('proposals.members.add');
    Route::delete('proposals/{proposal}/members/{member}', [ProposalController::class, 'removeMember'])->name('proposals.members.remove');
    Route::post('proposal-members/{member}/confirm', [ProposalController::class, 'confirmMembership'])->name('proposals.members.confirm');
    Route::post('proposals/{proposal}/rabs', [ProposalController::class, 'addRab'])->name('proposals.rabs.add');
    Route::delete('proposals/{proposal}/rabs/{rab}', [ProposalController::class, 'removeRab'])->name('proposals.rabs.remove');
    Route::post('proposals/{proposal}/documents', [ProposalController::class, 'uploadDocument'])->name('proposals.documents.upload');
    Route::post('proposals/{proposal}/outputs', [ProposalController::class, 'addOutput'])->name('proposals.outputs.add');
    Route::post('proposals/{proposal}/submit', [ProposalController::class, 'submit'])->name('proposals.submit');
    Route::get('proposals/{proposal}/print-formulir', [ProposalController::class, 'printFormulir'])->name('proposals.print.formulir');

    // Admin LPPM Workflows (Tahap 3, 4, 5, 6 SOP)
    Route::prefix('lppm')->name('lppm.')->group(function () {
        Route::post('proposals/{proposal}/administrative-verify', [LppmVerificationController::class, 'administrativeVerification'])->name('verify.administrative');
        Route::post('proposals/{proposal}/assign-reviewer', [LppmVerificationController::class, 'assignReviewer'])->name('reviewers.assign');
        Route::delete('proposals/{proposal}/remove-reviewer/{assignment}', [LppmVerificationController::class, 'removeReviewer'])->name('reviewers.remove');
        Route::get('ranking', [LppmVerificationController::class, 'rankingIndex'])->name('ranking');
        Route::post('proposals/{proposal}/determine-winner', [LppmVerificationController::class, 'determineWinner'])->name('determine.winner');
        Route::post('contracts/{contract}/sign-lppm', [LppmVerificationController::class, 'signContractLppm'])->name('contracts.sign');
    });

    // Reviewer Internal (Tahap 4 SOP & SKPL-F-15, Lampiran 5)
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewerController::class, 'index'])->name('index');
        Route::get('proposals/{proposal}', [ReviewerController::class, 'show'])->name('show');
        Route::post('proposals/{proposal}', [ReviewerController::class, 'store'])->name('store');
        Route::get('proposals/{proposal}/review/{review}/print', [ReviewerController::class, 'printRubrik'])->name('print');
    });

    // Bagian Keuangan: Pencairan Dana Tahap I & II (Tahap 7 & 12 SOP & SKPL-F-22, F-23)
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::post('disbursements/{disbursement}/process', [FinanceController::class, 'process'])->name('disbursements.process');
    });

    // Monitoring & Evaluasi (Monev) (Tahap 9 SOP & Lampiran 6 & 8)
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        Route::get('proposals/{proposal}', [MonitoringController::class, 'show'])->name('show');
        Route::post('proposals/{proposal}/progress-report', [MonitoringController::class, 'storeProgressReport'])->name('progress.store');
        Route::post('progress-reports/{report}/evaluate', [MonitoringController::class, 'evaluateMonev'])->name('evaluate');
        Route::get('proposals/{proposal}/progress-reports/{report}/print-ba', [MonitoringController::class, 'printBeritaAcara'])->name('print.ba');
        Route::get('proposals/{proposal}/print-sptb', [MonitoringController::class, 'printSptb'])->name('print.sptb');
    });

    // Laporan Akhir & Validasi Luaran (Tahap 10 & 11 SOP & Lampiran 7 & 8)
    Route::prefix('final-reports')->name('final-reports.')->group(function () {
        Route::get('/', [FinalReportController::class, 'index'])->name('index');
        Route::get('proposals/{proposal}', [FinalReportController::class, 'show'])->name('show');
        Route::post('proposals/{proposal}', [FinalReportController::class, 'store'])->name('store');
        Route::post('outputs/{output}/update-proof', [FinalReportController::class, 'updateOutputProof'])->name('outputs.update');
        Route::post('final-reports/{report}/validate', [FinalReportController::class, 'validateLuaran'])->name('validate');
        Route::get('proposals/{proposal}/final-reports/{report}/print-ba', [FinalReportController::class, 'printValidasi'])->name('print.ba');
        Route::get('proposals/{proposal}/print-sptb', [FinalReportController::class, 'printSptb'])->name('print.sptb');
    });

    // Arsip & Ekspor Rekap Data (Tahap 13 SOP & SKPL-F-30)
    Route::get('reports', [ReportExportController::class, 'index'])->name('reports.index');

    // Manajemen Pengguna (Admin TI - SKPL-F-01, F-02)
    Route::resource('users', UserController::class);
});
