<?php

namespace App\Http\Controllers;

use App\Helpers\PeriodeHelper;
use App\Models\CrmLead;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationPath;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
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

        // Fill missing dates with 0
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
        ));
    }
}