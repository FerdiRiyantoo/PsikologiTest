<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = TestSession::with('accessRequest')
            ->where('status', 'completed');

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('completed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('completed_at', '<=', $request->date_to);
        }

        // Filter berdasarkan nama peserta
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('accessRequest', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting berdasarkan durasi pengerjaan
        $sortBy    = $request->get('sort_by', 'completed_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'durasi') {
            // Sort by durasi (selisih started_at dan completed_at dalam detik)
            $query->orderByRaw(
                $sortOrder === 'asc'
                    ? 'TIMESTAMPDIFF(SECOND, started_at, completed_at) ASC'
                    : 'TIMESTAMPDIFF(SECOND, started_at, completed_at) DESC'
            );
        } else {
            $allowedSort = ['completed_at', 'started_at'];
            $sortBy = in_array($sortBy, $allowedSort) ? $sortBy : 'completed_at';
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $sessions = $query->paginate(15)->withQueryString();

        return view('admin.results.index', compact('sessions', 'sortBy', 'sortOrder'));
    }

    public function show($id)
    {
        $session = TestSession::with([
            'accessRequest', 
            'papiResult', 
            'kraepelinResult'
        ])->findOrFail($id);

        return view('admin.results.show', compact('session'));
    }

    public function exportPdf($id)
    {
        $session = TestSession::with(['accessRequest', 'result'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.results.pdf', compact('session'))
            ->setPaper('a4', 'portrait');
        $filename = 'hasil-papi-' . str_replace(' ', '-', strtolower($session->accessRequest->name)) . '.pdf';
        return $pdf->download($filename);
    }
}