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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('accessRequest', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_tes')) {
            $query->whereHas('accessRequest', function ($q) use ($request) {
                $q->where('jenis_tes', $request->jenis_tes);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('completed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('completed_at', '<=', $request->date_to);
        }

        $sortBy    = $request->get('sort_by', 'completed_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'durasi') {
            $query->orderByRaw(
                $sortOrder === 'asc'
                    ? 'TIMESTAMPDIFF(SECOND, started_at, completed_at) ASC'
                    : 'TIMESTAMPDIFF(SECOND, started_at, completed_at) DESC'
            );
        } else {
            $sortBy = in_array($sortBy, ['completed_at','started_at']) ? $sortBy : 'completed_at';
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $sessions = $query->paginate(15)->withQueryString();

        return view('admin.results.index', compact('sessions', 'sortBy', 'sortOrder'));
    }

    public function show($id)
    {
        $session  = TestSession::with([
            'accessRequest', 'result', 'kraepelinResult'
        ])->findOrFail($id);

        $jenisTes = strtolower($session->accessRequest->jenis_tes ?? 'papi');

        if (in_array($jenisTes, ['krempelin', 'kraepelin'])) {
            return view('admin.results.kraepelin_show', [
                'session' => $session,
                'result'  => $session->kraepelinResult,
            ]);
        }

        return view('admin.results.show', compact('session'));
    }

    public function exportPdf($id)
    {
        $session  = TestSession::with([
            'accessRequest', 'result', 'kraepelinResult'
        ])->findOrFail($id);

        $jenisTes = strtolower($session->accessRequest->jenis_tes ?? 'papi');
        $name     = str_replace(' ', '-', strtolower($session->accessRequest->name));

        // Pilih template PDF berdasarkan jenis tes
        if (in_array($jenisTes, ['krempelin', 'kraepelin'])) {
            $pdf = Pdf::loadView('admin.results.kraepelin_pdf', [
                'session' => $session,
                'result'  => $session->kraepelinResult,
            ])->setPaper('a4', 'portrait');

            return $pdf->download("hasil-kraepelin-{$name}.pdf");
        }

        $pdf = Pdf::loadView('admin.results.pdf', compact('session'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("hasil-papi-{$name}.pdf");
    }
}