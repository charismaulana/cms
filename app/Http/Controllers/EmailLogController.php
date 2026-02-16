<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailLogController extends Controller
{
    /**
     * Display email logs
     */
    public function index(Request $request): View
    {
        $query = EmailLog::with('emailRecipient')
            ->orderBy('sent_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('sent_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('sent_at', '<=', $request->to_date);
        }

        // Filter by recipient
        if ($request->filled('recipient')) {
            $query->where('recipient_email', 'like', '%' . $request->recipient . '%');
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('email-logs.index', compact('logs'));
    }
}
