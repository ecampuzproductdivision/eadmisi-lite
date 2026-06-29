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

            // Determine status label, badge class, and action button
            if ($reg->status === 'rejected') {
                $statusLabel = 'Ditolak';
                $badgeBg = 'bg-danger';
                $badgeText = 'text-white';
                $actionLabel = null;
                $actionUrl = null;
            } elseif ($reg->status === 'accepted') {
                $statusLabel = 'Diterima';
                $badgeBg = 'bg-success';
                $badgeText = 'text-white';
                $actionLabel = 'Lihat Detail';
                $actionUrl = route('daftar-pmb.review', $pathObj?->code);
            } elseif ($reg->status === 'reviewed') {
                $statusLabel = 'Direview';
                $badgeBg = 'bg-secondary';
                $badgeText = 'text-white';
                $actionLabel = null;
                $actionUrl = null;
            } elseif ($reg->status === 'exam_completed') {
                $statusLabel = 'Ujian Selesai';
                $badgeBg = 'bg-primary';
                $badgeText = 'text-white';
                $actionLabel = 'Lihat Detail';
                $actionUrl = route('daftar-pmb.review', $pathObj?->code);
            } elseif ($reg->status === 'payment_verified') {
                $statusLabel = 'Pembayaran Terverifikasi';
                $badgeBg = 'bg-success';
                $badgeText = 'text-dark';
                $actionLabel = 'Lanjutkan';
                $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
            } elseif ($reg->status === 'payment_pending') {
                $statusLabel = 'Menunggu Pembayaran';
                $badgeBg = 'bg-warning';
                $badgeText = 'text-dark';
                $actionLabel = 'Selesaikan Pembayaran';
                $actionUrl = route('tagihan.index');
            } elseif ($reg->status === 'documents_uploaded' || $reg->status === 'submitted') {
                if ($isPaymentLocked) {
                    $statusLabel = 'Menunggu Pembayaran';
                    $badgeBg = 'bg-warning';
                    $badgeText = 'text-dark';
                    $actionLabel = 'Selesaikan Pembayaran';
                    $actionUrl = route('tagihan.index');
                } elseif (!$isStep3Completed) {
                    if ($totalUploadedDocs == 0) {
                        $statusLabel = 'Belum Upload';
                        $badgeBg = 'bg-warning';
                        $badgeText = 'text-dark';
                        $actionLabel = 'Lengkapi Berkas Persyaratan';
                        $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
                    } else {
                        $statusLabel = 'Belum Lengkap';
                        $badgeBg = 'bg-warning';
                        $badgeText = 'text-dark';
                        $actionLabel = 'Lengkapi Berkas Persyaratan';
                        $actionUrl = route('daftar-pmb.steps', $pathObj?->code);
                    }
                } elseif ($pathObj && $pathObj->is_ujian_online && !$hasExamBeenTaken) {
                    $statusLabel = 'Menunggu Ujian';
                    $badgeBg = 'bg-info';
                    $badgeText = 'text-dark';
                    $actionLabel = 'Mulai Tes Online';
                    $actionUrl = route('tes-online.start', $reg->id);
                } else {
                    $statusLabel = 'Menunggu Verifikasi';
                    $badgeBg = 'bg-success';
                    $badgeText = 'text-dark';
                    $actionLabel = 'Lihat Detail';
                    $actionUrl = route('daftar-pmb.review', $pathObj?->code);
                }
            } else {
                $statusLabel = 'Menunggu';
                $badgeBg = 'bg-secondary';
                $badgeText = 'text-white';
                $actionLabel = null;
                $actionUrl = null;
            }

            return (object) [
                'id' => $reg->id,
                'pathName' => $pathObj?->name ?? 'Unknown Path',
                'pathCode' => $pathObj?->code,
                'prodi1' => $reg->programStudi1?->nama ?? '-',
                'prodi2' => $reg->programStudi2?->nama ?? '-',
                'createdAt' => $reg->created_at,
                'statusLabel' => $statusLabel,
                'badgeBg' => $badgeBg,
                'badgeText' => $badgeText,
                'actionLabel' => $actionLabel,
                'actionUrl' => $actionUrl,
            ];
        });

        return view('home', [
            'isCalonMahasiswa' => true,
            'hasRegistrations' => $hasRegistrations,
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
                $q->whereIn('status', ['submitted', 'documents_uploaded', 'payment_pending', 'payment_verified', 'exam_completed', 'reviewed', 'accepted']);
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