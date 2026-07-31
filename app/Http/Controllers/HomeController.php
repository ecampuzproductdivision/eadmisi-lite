<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodeHelper;
use App\Models\CrmLead;
use App\Models\ExamResult;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\RegistrationPath;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ── Check if user is CALON_MAHASISWA ──
        $isCalonMahasiswa = $user->roles->contains('role_code', 'CALON_MAHASISWA');

        if ($isCalonMahasiswa) {
            return $this->studentDashboard();
        }

        // ── Admin Dashboard (original) ──
        return $this->adminDashboard();
    }

    /**
     * Student Dashboard: Multi-path application cards
     */
    private function studentDashboard()
    {
        $userId = auth()->id();

        // Check if user has any registrations
        $registrationCount = Registration::where('user_id', $userId)->count();
        $hasRegistrations = $registrationCount > 0;

        // Fetch all registrations for this user
        $registrations = Registration::where('user_id', $userId)
            ->with([
                'registrationPath.templateBerkas.syaratDokumens',
                'programStudi1',
                'programStudi2',
                'documents',
                'payments',
                'examResults',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Build unified status for each registration
        $registrationCards = $registrations->map(function ($reg) {
            $pathObj = $reg->registrationPath;

            // Payment gate
            $paidInvoice = $reg->payments->firstWhere('transaction_status', 'success');
            $hasPaidInvoice = $paidInvoice !== null;
            $isPaymentLocked = !$hasPaidInvoice;

            // Document check
            $totalRequiredDocs = 0;
            $totalUploadedDocs = $reg->documents->count();
            if ($pathObj && $pathObj->templateBerkas) {
                $totalRequiredDocs = $pathObj->templateBerkas->syaratDokumens()
                    ->where('status_wajib', true)
                    ->count();
            }

            // Exam check
            $hasExamBeenTaken = false;
            if ($pathObj && $pathObj->is_ujian_online) {
                $hasExamBeenTaken = $reg->examResults
                    ->where('status', 'completed')
                    ->isNotEmpty();
            }

            $isStep3Completed = ($totalRequiredDocs == 0) || ($totalUploadedDocs >= $totalRequiredDocs);

            // ── UNIFIED STATUS PIPELINE (Lazy Invoice Architecture) ──
            // STEP 1: Financial State Guard
            $hasPaidInvoice = $reg->payments->firstWhere('transaction_status', 'success');
            $isPaymentLocked = !$hasPaidInvoice;

            // ── PRIORITY: Re-registration Lunas state ──
            if ($reg->status_kelulusan === 'Lulus' && $reg->status_registrasi_ulang === 'sudah_registrasi_lunas') {
                $statusLabel = 'Sudah Melakukan Registrasi Ulang';
                $badgeBg = 'bg-success';
                $badgeText = 'text-white';
                $subBadge = $reg->nim
                    ? '<span class="badge bg-info text-white mt-1 d-inline-block" style="font-size:0.65rem;">NIM: ' . $reg->nim . '</span>'
                    : '';
                $actionLabel = 'Lihat Detail';
                $actionUrl = route('pendaftaran.show', $reg->id);
            } elseif (in_array($reg->status, ['rejected', 'Gagal']) || in_array($reg->status_kelulusan, ['Tidak Lulus', 'Gagal'])) {
                $statusLabel = 'Ditolak / Tidak Lulus';
                $badgeBg = 'bg-danger';
                $badgeText = 'text-white';
                $subBadge = '';
                $actionLabel = null;
                $actionUrl = null;
            } elseif (in_array($reg->status, ['accepted', 'Lulus']) || $reg->status_kelulusan === 'Lulus') {
                $statusLabel = 'Lulus Seleksi (Registrasi Ulang)';
                $badgeBg = 'bg-success';
                $badgeText = 'text-white';
                $subBadge = '';
                $actionLabel = 'Registrasi Ulang';
                $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
            } elseif ($reg->status === 'Menunggu Verifikasi Registrasi Ulang') {
                $statusLabel = 'Menunggu Pembayaran Registrasi Ulang';
                $badgeBg = 'bg-info';
                $badgeText = 'text-dark';
                $subBadge = '';
                $actionLabel = 'Bayar Sekarang';
                $actionUrl = route('tagihan.index');
            } elseif ($reg->status === 'registered') {
                $statusLabel = 'Terregistrasi (Aktif)';
                $badgeBg = 'bg-success';
                $badgeText = 'text-white';
                $subBadge = $reg->nim ? '<span class="badge bg-info text-white mt-1 d-inline-block" style="font-size:0.65rem;">NIM: ' . $reg->nim . '</span>' : '';
                $actionLabel = 'Lihat Detail';
                $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
            } elseif ($reg->status === 'reviewed') {
                $statusLabel = 'Direview';
                $badgeBg = 'bg-secondary';
                $badgeText = 'text-white';
                $subBadge = '';
                $actionLabel = null;
                $actionUrl = null;
            } elseif ($reg->status === 'exam_completed') {
                if ($pathObj && $pathObj->gunakan_wawancara) {
                    if ($reg->status_wawancara === 'menunggu_penjadwalan_wawancara') {
                        $statusLabel = 'Menunggu Penjadwalan Wawancara';
                        $badgeBg = 'bg-warning';
                        $badgeText = 'text-dark';
                    } else {
                        $statusLabel = 'Proses Seleksi Wawancara';
                        $badgeBg = 'bg-info';
                        $badgeText = 'text-dark';
                    }
                } else {
                    $statusLabel = 'Ujian Selesai';
                    $badgeBg = 'bg-primary';
                    $badgeText = 'text-white';
                }
                $subBadge = '';
                $actionLabel = 'Lihat Detail';
                $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
            } elseif ($reg->status === 'payment_pending') {
                // Already has pending invoice — treat as locked
                $isPaymentLocked = true;
            }

            // Cascade: only if status not yet resolved
            if (!isset($statusLabel)) {
                // STEP 1: Financial State Guard
                if ($isPaymentLocked) {
                    $statusLabel = 'Menunggu Pembayaran';
                    $badgeBg = 'bg-danger';
                    $badgeText = 'text-white';
                    $subBadge = '';
                    $actionLabel = 'Selesaikan Pembayaran';
                    $actionUrl = route('tagihan.index');
                } else {
                    // Payment is cleared
                    $subBadge = '<span class="badge bg-success text-white mt-1 d-inline-block" style="font-size:0.65rem;">Pembayaran Terverifikasi</span>';

                    // STEP 2: Document Upload Phase
                    $totalRequiredDocs = 0;
                    $totalUploadedDocs = $reg->documents->count();
                    if ($pathObj && $pathObj->templateBerkas) {
                        $totalRequiredDocs = $pathObj->templateBerkas->syaratDokumens()
                            ->where('status_wajib', true)
                            ->count();
                    }
                    $isStep3Completed = ($totalRequiredDocs == 0) || ($totalUploadedDocs >= $totalRequiredDocs);

                    if ($totalRequiredDocs > 0 && !$isStep3Completed) {
                        $statusLabel = 'Belum Unggah Berkas';
                        $badgeBg = 'bg-warning';
                        $badgeText = 'text-dark';
                        $actionLabel = 'Lengkapi Berkas';
                        $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
                    }
                    // STEP 3: Online CBT Exam Phase
                    elseif ($pathObj && $pathObj->is_ujian_online && !$hasExamBeenTaken) {
                        $statusLabel = 'Menunggu Ujian';
                        $badgeBg = 'bg-info';
                        $badgeText = 'text-dark';
                        $actionLabel = 'Mulai Ujian Online';
                        $actionUrl = route('tes-online.start', $reg->id);
                    }
                    // STEP 4: Final Verification
                    else {
                        $statusLabel = 'Menunggu Verifikasi Berkas';
                        $badgeBg = 'bg-secondary';
                        $badgeText = 'text-white';
                        $actionLabel = 'Lihat Detail';
                        $actionUrl = route('daftar-pmb.review', $pathObj?->code);
                    }
                }
            }

            return (object) [
                'id' => $reg->id,
                'noPendaftaran' => $reg->no_pendaftaran,
                'pathName' => $pathObj?->name ?? 'Unknown Path',
                'pathCode' => $pathObj?->code,
                'prodi1' => $reg->programStudi1?->nama_prodi ?? $reg->programStudi1?->nama ?? '-',
                'prodi2' => $reg->programStudi2?->nama_prodi ?? $reg->programStudi2?->nama ?? '-',
                'createdAt' => $reg->created_at,
                'statusLabel' => $statusLabel,
                'badgeBg' => $badgeBg,
                'badgeText' => $badgeText,
                'subBadge' => $subBadge ?? '',
                'actionLabel' => $actionLabel,
                'actionUrl' => $actionUrl,
            ];
        });

        // Check if any registration has reached lunas state
        $hasLunasStatus = $registrations->contains(function ($reg) {
            return $reg->status_kelulusan === 'Lulus' && $reg->status_registrasi_ulang === 'sudah_registrasi_lunas';
        });

        return view('home', [
            'isCalonMahasiswa' => true,
            'hasRegistrations' => $hasRegistrations,
            'hasLunasStatus' => $hasLunasStatus,
            'registrationCount' => $registrationCount,
            'registrationCards' => $registrationCards,
        ]);
    }

    /**
     * Admin Dashboard: statistics, charts, recent data
     */
    private function adminDashboard()
    {
        $activePeriodeId = PeriodeHelper::getActiveId();

        // ── Stat Cards ──
        $totalPendaftar = Registration::when($activePeriodeId, function ($q) use ($activePeriodeId) {
            $q->whereHas('registrationPath', fn($q) => $q->where('periode_id', $activePeriodeId));
        })->count();

        $totalLunas = Payment::where('transaction_status', 'success')
            ->when($activePeriodeId, function ($q) use ($activePeriodeId) {
                $q->whereHas('registration', function ($r) use ($activePeriodeId) {
                    $r->whereHas('registrationPath', fn($p) => $p->where('periode_id', $activePeriodeId));
                });
            })->count();

        $pendingWawancara = Wawancara::where(function ($q) {
                $q->whereNull('status_wawancara')
                  ->orWhere('status_wawancara', 'Belum Wawancara');
            })
            ->when($activePeriodeId, function ($q) use ($activePeriodeId) {
                $q->whereHas('pendaftaran.registrationPath', fn($p) => $p->where('periode_id', $activePeriodeId));
            })->count();

        $totalCrmLeads = CrmLead::count();

        // ── Recent lists ──
        $recentRegistrations = Registration::with(['registrationPath', 'user'])
            ->when($activePeriodeId, function ($q) use ($activePeriodeId) {
                $q->whereHas('registrationPath', fn($p) => $p->where('periode_id', $activePeriodeId));
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentLeads = CrmLead::where('status', 'New')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ── Chart data: new registrations per day (last 14 days) ──
        $registrationTrend = Registration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->when($activePeriodeId, function ($q) use ($activePeriodeId) {
                $q->whereHas('registrationPath', fn($p) => $p->where('periode_id', $activePeriodeId));
            })
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $trendLabels = [];
        $trendData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->format('d M');
            $trendData[] = isset($registrationTrend[$date]) ? (int) $registrationTrend[$date]->total : 0;
        }

        // ── Chart data: registration path distribution ──
        $pathDistribution = RegistrationPath::withCount(['registrations' => function ($q) {
                $q->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted', 'Menunggu Verifikasi Registrasi Ulang', 'registered']);
            }])
            ->when($activePeriodeId, function ($q) use ($activePeriodeId) {
                $q->where('periode_id', $activePeriodeId);
            })
            ->where('is_active', true)
            ->orderBy('registrations_count', 'desc')
            ->get();

        $pathLabels = $pathDistribution->pluck('name')->toArray();
        $pathData = $pathDistribution->pluck('registrations_count')->toArray();

        return view('home', compact(
            'totalPendaftar',
            'totalLunas',
            'pendingWawancara',
            'totalCrmLeads',
            'recentRegistrations',
            'recentLeads',
            'trendLabels',
            'trendData',
            'pathLabels',
            'pathData'
        ) + ['isCalonMahasiswa' => false]);
    }
}