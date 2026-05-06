<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\TestSession;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_requests'  => AccessRequest::count(),
            'pending'         => AccessRequest::where('status', 'pending')->count(),
            'approved'        => AccessRequest::where('status', 'approved')->count(),
            'rejected'        => AccessRequest::where('status', 'rejected')->count(),
            'completed_tests' => TestSession::where('status', 'completed')->count(),
            'in_progress'     => TestSession::where('status', 'in_progress')->count(),
        ];

        $recent = AccessRequest::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent'));
    }
}