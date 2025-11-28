<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AdminApplicantController;
use Illuminate\Support\Facades\Route;
use App\Models\PaymentItem;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::middleware(['auth', 'redirect_role'])->group(function () {
    Route::get('/dashboard', [ApplicantController::class, 'dashboard'])->name('dashboard');
    Route::get('/pendaftaran', [ApplicantController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [ApplicantController::class, 'store'])->name('pendaftaran.store');
    Route::put('/pendaftaran/{id}', [ApplicantController::class, 'update'])->name('pendaftaran.update');
    Route::get('/dokumen', [ApplicantController::class, 'documents'])->name('dokumen.index');
    Route::post('/dokumen/upload', [ApplicantController::class, 'uploadFile'])->name('dokumen.upload');
    Route::delete('/dokumen/{id}', [ApplicantController::class, 'deleteFile'])->name('dokumen.delete');
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/api', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.api');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
    Route::get('/payment/create-multiple', function() {
        $applicant = App\Models\Applicant::where('user_id', auth()->id())->first();
        $paymentItems = App\Models\PaymentItem::where('is_active', true)->get();
        return view('payment.create-multiple', compact('applicant', 'paymentItems'));
    })->name('payment.create-multiple');
    Route::post('/payment', [App\Http\Controllers\PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/{id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{id}/pay', [App\Http\Controllers\PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/{id}/receipt', [App\Http\Controllers\PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::post('/payment/{id}/upload-proof', [App\Http\Controllers\PaymentController::class, 'uploadProof'])->name('payment.upload-proof');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/status', [App\Http\Controllers\StatusController::class, 'index'])->name('status.index');
    Route::get('/cetak/kartu', [App\Http\Controllers\CetakController::class, 'kartuPendaftaran'])->name('cetak.kartu');
    Route::get('/cetak/bukti/{id}', [App\Http\Controllers\CetakController::class, 'buktiPembayaran'])->name('cetak.bukti');
    

});

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminPanitiaController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/applicants', [AdminApplicantController::class, 'index'])->name('admin.applicants.index');
    Route::get('/applicants/{id}', [AdminApplicantController::class, 'show'])->name('admin.applicants.show');
    Route::patch('/applicants/{id}/verify', [AdminApplicantController::class, 'verify'])->name('admin.applicants.verify');
    Route::get('/applicants/export/excel', [AdminApplicantController::class, 'exportExcel'])->name('admin.applicants.export.excel');
    Route::get('/applicants/export/pdf', [AdminApplicantController::class, 'exportPdf'])->name('admin.applicants.export.pdf');
    
    // User Management
    Route::get('/users', [App\Http\Controllers\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::delete('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    
    // Admin Panitia Routes
    Route::get('/panitia/master-data', [App\Http\Controllers\AdminPanitiaController::class, 'masterData'])->name('admin.panitia.master-data');
    Route::get('/panitia/data-pendaftar', [App\Http\Controllers\AdminPanitiaController::class, 'dataPendaftar'])->name('admin.panitia.data-pendaftar');
    Route::get('/panitia/monitoring-berkas', [App\Http\Controllers\AdminPanitiaController::class, 'monitoringBerkas'])->name('admin.panitia.monitoring-berkas');
    Route::get('/panitia/monitoring-pembayaran', [App\Http\Controllers\AdminPanitiaController::class, 'monitoringPembayaran'])->name('admin.panitia.monitoring-pembayaran');
    Route::get('/panitia/peta-sebaran', [App\Http\Controllers\AdminPanitiaController::class, 'petaSebaran'])->name('admin.panitia.peta-sebaran');
    Route::get('/panitia/manajemen-akun', [App\Http\Controllers\AdminPanitiaController::class, 'manajemenAkun'])->name('admin.panitia.manajemen-akun');
    Route::post('/panitia/create-user', [App\Http\Controllers\AdminPanitiaController::class, 'createUser'])->name('admin.panitia.create-user');
    Route::get('/panitia/export/excel', [App\Http\Controllers\AdminPanitiaController::class, 'exportExcel'])->name('admin.panitia.export.excel');
    Route::get('/panitia/export/pdf', [App\Http\Controllers\AdminPanitiaController::class, 'exportPdf'])->name('admin.panitia.export.pdf');
    Route::get('/panitia/map-data', [App\Http\Controllers\AdminPanitiaController::class, 'getMapData'])->name('admin.panitia.map-data');
    Route::get('/panitia/test-map-data', [App\Http\Controllers\AdminPanitiaController::class, 'testMapData'])->name('admin.panitia.test-map-data');
    Route::get('/panitia/pengaturan-sistem', [App\Http\Controllers\AdminPanitiaController::class, 'pengaturanSistem'])->name('admin.panitia.pengaturan-sistem');
    Route::get('/panitia/log-aktivitas', [App\Http\Controllers\AdminPanitiaController::class, 'logAktivitas'])->name('admin.panitia.log-aktivitas');
    Route::post('/panitia/backup-data', [App\Http\Controllers\AdminPanitiaController::class, 'backupData'])->name('admin.panitia.backup-data');
    Route::post('/panitia/send-notification', [App\Http\Controllers\AdminPanitiaController::class, 'sendNotification'])->name('admin.panitia.send-notification');
    Route::post('/panitia/reset-password', [App\Http\Controllers\AdminPanitiaController::class, 'resetPassword'])->name('admin.panitia.reset-password');
    Route::put('/majors/{id}', [App\Http\Controllers\AdminPanitiaController::class, 'updateMajor'])->name('admin.majors.update');
    Route::put('/waves/{id}', [App\Http\Controllers\AdminPanitiaController::class, 'updateWave'])->name('admin.waves.update');
    Route::get('/workflow-status', [App\Http\Controllers\AdminPanitiaController::class, 'workflowStatus'])->name('admin.workflow-status');
    Route::post('/final-decision/{id}', [App\Http\Controllers\AdminPanitiaController::class, 'finalDecision'])->name('admin.final-decision');
    Route::get('/keputusan-akhir', [App\Http\Controllers\AdminPanitiaController::class, 'keputusanAkhir'])->name('admin.keputusan-akhir');

});

// Test route untuk debugging
Route::get('/test-coordinates', function() {
    $applicants = App\Models\Applicant::with(['user', 'major'])->limit(5)->get();
    return response()->json([
        'applicants' => $applicants->map(function($app) {
            return [
                'name' => $app->user->name ?? $app->nama_lengkap ?? 'N/A',
                'kabupaten' => $app->kabupaten,
                'provinsi' => $app->provinsi,
                'major' => $app->major->name ?? 'N/A'
            ];
        })
    ]);
})->middleware('auth');

Route::prefix('executive')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ExecutiveDashboardController::class, 'index'])->name('executive.dashboard');
    Route::get('/analytics', [App\Http\Controllers\ExecutiveDashboardController::class, 'analytics'])->name('executive.analytics');
    Route::get('/geographic', function() { return view('geographic.simple'); })->name('executive.geographic');
    Route::get('/map-data', [App\Http\Controllers\GeographicController::class, 'mapData'])->name('executive.map-data');
    
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/applicants/pdf', [App\Http\Controllers\ReportController::class, 'exportApplicantsPdf'])->name('reports.applicants.pdf');
    Route::get('/reports/applicants/excel', [App\Http\Controllers\ReportController::class, 'exportApplicantsExcel'])->name('reports.applicants.excel');
    Route::get('/reports/payments/pdf', [App\Http\Controllers\ReportController::class, 'exportPaymentsPdf'])->name('reports.payments.pdf');
    Route::get('/reports/statistics/pdf', [App\Http\Controllers\ReportController::class, 'exportStatisticsPdf'])->name('reports.statistics.pdf');
});

// Dashboard Kepala Sekolah (admin & kepsek bisa akses)
Route::prefix('kepsek')->middleware(['auth', 'admin_or_kepsek'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\KepsekDashboardController::class, 'index'])->name('kepsek.dashboard');
    Route::get('/laporan-rekapitulasi', [App\Http\Controllers\KepsekDashboardController::class, 'laporanRekapitulasi'])->name('kepsek.laporan-rekapitulasi');
    Route::get('/grafik-peta', [App\Http\Controllers\KepsekDashboardController::class, 'grafikPetaSebaran'])->name('kepsek.grafik-peta');
    Route::get('/export-pdf', [App\Http\Controllers\KepsekDashboardController::class, 'exportLaporanPdf'])->name('kepsek.export-pdf');
    Route::get('/export-excel', [App\Http\Controllers\KepsekDashboardController::class, 'exportLaporanExcel'])->name('kepsek.export-excel');
    Route::get('/riwayat-aktivitas', [App\Http\Controllers\KepsekDashboardController::class, 'riwayatAktivitas'])->name('kepsek.riwayat-aktivitas');
    Route::post('/final-decision/{id}', [App\Http\Controllers\KepsekDashboardController::class, 'finalDecision'])->name('kepsek.final-decision');
    Route::post('/applicant/{id}/final-decision', [App\Http\Controllers\KepsekDashboardController::class, 'finalDecision'])->name('kepsek.applicant.final-decision');
    Route::get('/master-data', [App\Http\Controllers\AdminPanitiaController::class, 'masterData'])->name('kepsek.master-data');
});

// Dashboard Keuangan (admin & keuangan bisa akses)
Route::prefix('keuangan')->middleware(['auth', 'admin_or_keuangan'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');
    Route::get('/daftar-pembayaran', [App\Http\Controllers\KeuanganController::class, 'daftarPembayaran'])->name('keuangan.daftar-pembayaran');
    Route::get('/payments', [App\Http\Controllers\KeuanganController::class, 'payments'])->name('keuangan.payments');
    Route::post('/payments/{id}/verify', [App\Http\Controllers\KeuanganController::class, 'verifyPayment'])->name('keuangan.verify');
    Route::get('/rekap', [App\Http\Controllers\KeuanganController::class, 'rekapKeuangan'])->name('keuangan.rekap');
    Route::get('/export-excel', [App\Http\Controllers\KeuanganController::class, 'exportExcel'])->name('keuangan.export-excel');
    Route::get('/export-pdf', [App\Http\Controllers\KeuanganController::class, 'exportPdf'])->name('keuangan.export-pdf');
    Route::get('/log-aktivitas', [App\Http\Controllers\KeuanganController::class, 'logAktivitas'])->name('keuangan.log-aktivitas');
    Route::get('/master-data', [App\Http\Controllers\AdminPanitiaController::class, 'masterData'])->name('keuangan.master-data');
});

// Verifikator
Route::prefix('verifikator')->middleware(['auth', 'is_verifikator'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\VerifikatorController::class, 'index'])->name('verifikator.dashboard');
    Route::get('/daftar-pendaftar', [App\Http\Controllers\VerifikatorController::class, 'daftarPendaftar'])->name('verifikator.daftar-pendaftar');
    Route::get('/applicant/{id}', [App\Http\Controllers\VerifikatorController::class, 'show'])->name('verifikator.show');
    Route::post('/applicant/{id}/verify', [App\Http\Controllers\VerifikatorController::class, 'verify'])->name('verifikator.verify');
    Route::get('/laporan', [App\Http\Controllers\VerifikatorController::class, 'laporanVerifikasi'])->name('verifikator.laporan');
    Route::get('/log-aktivitas', [App\Http\Controllers\VerifikatorController::class, 'logAktivitas'])->name('verifikator.log-aktivitas');
    Route::get('/debug-data', [App\Http\Controllers\VerifikatorController::class, 'debugData'])->name('verifikator.debug-data');
    Route::get('/master-data', [App\Http\Controllers\AdminPanitiaController::class, 'masterData'])->name('verifikator.master-data');
});

// Workflow Routes - Integrasi antar role
Route::middleware('auth')->group(function () {
    Route::post('/workflow/verify-applicant/{applicant}', [App\Http\Controllers\WorkflowController::class, 'verifyApplicant'])
        ->middleware('is_verifikator')
        ->name('workflow.verify-applicant');
    
    Route::post('/workflow/verify-payment/{payment}', [App\Http\Controllers\WorkflowController::class, 'verifyPayment'])
        ->middleware('admin_or_keuangan')
        ->name('workflow.verify-payment');
    
    Route::post('/workflow/final-decision/{applicant}', [App\Http\Controllers\WorkflowController::class, 'finalDecision'])
        ->middleware('admin_or_kepsek')
        ->name('workflow.final-decision');
});

require __DIR__.'/auth.php';
