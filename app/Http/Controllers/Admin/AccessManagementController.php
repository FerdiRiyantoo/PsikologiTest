<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\TestSession;
use Illuminate\Support\Str;

class AccessManagementController extends Controller
{
    public function index()
    {
        $requests = AccessRequest::latest()->paginate(15);
        return view('admin.requests.index', compact('requests'));
    }

    public function approve($id)
    {
        $accessRequest = AccessRequest::findOrFail($id);
        $code         = strtoupper(Str::random(8));
        $magicToken   = Str::random(64);

        $accessRequest->update([
            'status'                  => 'approved',
            'access_code'             => $code,
            'approved_at'             => now(),
            'magic_token'             => $magicToken,
            'magic_token_expires_at'  => now()->addDays(7), // berlaku 7 hari
        ]);

        // Buat test session langsung
        TestSession::firstOrCreate(
            ['access_request_id' => $accessRequest->id],
            ['status' => 'not_started']
        );

        try {
            \Mail::to($accessRequest->email)
                 ->send(new \App\Mail\AccessCodeMail($accessRequest));

            return back()->with('success',
                "Akses disetujui. Kode <strong>{$code}</strong> & magic link
                 telah dikirim ke <strong>{$accessRequest->email}</strong>"
            );
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
            return back()->with('success',
                "Akses disetujui. Kode: <strong>{$code}</strong>
                 <span class='text-danger'>(email gagal — sampaikan manual)</span>"
            );
        }
    }

    public function reject($id)
    {
        AccessRequest::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Permintaan ditolak.');
    }

    public function destroy($id)
    {
        AccessRequest::findOrFail($id)->delete();
        return back()->with('success', 'Permintaan berhasil dihapus.');
    }

    public function bulkAction(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'action'       => 'required|in:approve,reject,delete',
            'selected_ids' => 'required|array',
        ]);

        $ids    = $request->selected_ids;
        $action = $request->action;

        switch ($action) {
            case 'approve':
                foreach ($ids as $id) {
                    $ar = AccessRequest::find($id);
                    if ($ar && $ar->status === 'pending') {
                        $code       = strtoupper(Str::random(8));
                        $magicToken = Str::random(64);
                        $ar->update([
                            'status'                 => 'approved',
                            'access_code'            => $code,
                            'approved_at'            => now(),
                            'magic_token'            => $magicToken,
                            'magic_token_expires_at' => now()->addDays(7),
                        ]);
                        TestSession::firstOrCreate(
                            ['access_request_id' => $ar->id],
                            ['status' => 'not_started']
                        );
                        try {
                            \Mail::to($ar->email)
                                 ->send(new \App\Mail\AccessCodeMail($ar));
                        } catch (\Exception $e) {
                            \Log::error('Mail error: ' . $e->getMessage());
                        }
                    }
                }
                $msg = count($ids) . ' permintaan disetujui dan email dikirim.';
                break;

            case 'reject':
                AccessRequest::whereIn('id', $ids)->update(['status' => 'rejected']);
                $msg = count($ids) . ' permintaan ditolak.';
                break;

            case 'delete':
                AccessRequest::whereIn('id', $ids)->delete();
                $msg = count($ids) . ' permintaan dihapus.';
                break;
        }

        return back()->with('success', $msg);
    }
}