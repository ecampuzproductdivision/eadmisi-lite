<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use Illuminate\Http\Request;

class CrmLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = CrmLead::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('whatsapp', 'like', "%{$s}%")
                  ->orWhere('pertanyaan', 'like', "%{$s}%");
            });
        }

        $leads = \App\Helpers\SortHelper::apply($query, ['nama', 'created_at', 'status'], 'created_at', 'desc')->paginate(15);

        $totalLeads = CrmLead::count();
        $newLeads = CrmLead::where('status', 'New')->count();
        $inProgressLeads = CrmLead::where('status', 'In Progress')->count();
        $convertedLeads = CrmLead::where('status', 'Converted')->count();
        $respondedLeads = CrmLead::where('status', 'Responded')->count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('crm-leads.partials.lead_rows', compact('leads'))->render(),
                'next_page' => $leads->nextPageUrl(),
                'has_more' => $leads->hasMorePages(),
            ]);
        }

        return view('crm-leads.index', compact(
            'leads', 'totalLeads', 'newLeads', 'inProgressLeads', 'convertedLeads', 'respondedLeads'
        ));
    }

    public function updateStatus(Request $request, CrmLead $crmLead)
    {
        $request->validate(['status' => 'required|in:' . implode(',', CrmLead::STATUSES)]);
        $crmLead->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui.']);
    }

    public function updateNotes(Request $request, CrmLead $crmLead)
    {
        $request->validate(['catatan_admin' => 'nullable|string']);
        $crmLead->update(['catatan_admin' => $request->catatan_admin]);

        return response()->json(['success' => true, 'message' => 'Catatan berhasil disimpan.']);
    }

    public function show(CrmLead $crmLead)
    {
        return response()->json($crmLead);
    }

    public function destroy(CrmLead $crmLead)
    {
        $crmLead->delete();
        return response()->json(['success' => true, 'message' => 'Lead berhasil dihapus.']);
    }

    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'whatsapp' => 'required|string|max:50',
            'pertanyaan' => 'required|string',
        ]);

        CrmLead::create([
            'nama' => $validated['nama'],
            'whatsapp' => $validated['whatsapp'],
            'pertanyaan' => $validated['pertanyaan'],
            'status' => 'New',
        ]);

        return redirect()->route('pmb.landing', ['#tanya-dulu'])
            ->with('success', 'Terima kasih! Pertanyaan Anda telah kami terima. Tim kami akan menghubungi Anda melalui WhatsApp.');
    }
}