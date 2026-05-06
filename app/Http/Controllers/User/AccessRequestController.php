<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\TestSession;
use Illuminate\Http\Request;

class AccessRequestController extends Controller
{
    public function landing()
    {
        return view('user.landing');
    }   

    public function index()
    {
        return view('user.home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                    => 'required|string|max:100',
            'email'                   => 'required|email|unique:access_requests,email',
            'phone'                   => 'nullable|string|max:20',
            'tempat_lahir'            => 'required|string|max:100',
            'tanggal_lahir'           => 'required|date',
            'usia'                    => 'required|integer',
            'alamat'                  => 'required|string',
            'pendidikan_terakhir'     => 'required|string|max:50',
            'jurusan'                 => 'required|string|max:100',
            'posisi_jabatan_terakhir' => 'required|string|max:100',
            'tanggal_tes'             => 'required|date',
            'jenis_tes'               => 'required|string',
        ]);

        AccessRequest::create($request->only([
            'name', 
            'email', 
            'phone', 
            'tempat_lahir', 
            'tanggal_lahir', 
            'usia', 
            'alamat', 
            'pendidikan_terakhir', 
            'jurusan', 
            'posisi_jabatan_terakhir', 
            'tanggal_tes',
            'jenis_tes'
        ]));

        return back()->with('success', 'Form kandidat berhasil dikirim! Tunggu proses selanjutnya.');
    }

    public function enterCode()
    {
        return view('user.enter-code');
    }

    public function panduan()
    {
        return view('user.panduan');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['access_code' => 'required|string']);

        $accessRequest = AccessRequest::where('access_code', $request->access_code)
            ->where('status', 'approved')
            ->first();

        if (!$accessRequest) {
            return back()->with('error', 'Kode akses tidak valid atau belum disetujui.');
        }

        $testSession = TestSession::firstOrCreate(
            ['access_request_id' => $accessRequest->id],
            ['status' => 'not_started']
        );

        if ($testSession->status === 'completed') {
            return back()->with('error', 'Tes sudah pernah dikerjakan.');
        }

        session(['test_session_id' => $testSession->id]);

        // Routing otomatis berdasarkan jenis tes
        $jenisTes = strtolower($accessRequest->jenis_tes ?? 'papi');

        if ($jenisTes === 'krempelin' || $jenisTes === 'kraepelin') {
            return redirect()->route('kraepelin.index');
        }

        return redirect()->route('test.index');
    }
}