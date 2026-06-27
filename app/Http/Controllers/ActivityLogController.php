<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.logs.partials.log_rows', compact('logs'))->render(),
                'next_page' => $logs->nextPageUrl(),
                'has_more' => $logs->hasMorePages(),
            ]);
        }

        return view('settings.logs.index', compact('logs', 'modules', 'actions'));
    }
}