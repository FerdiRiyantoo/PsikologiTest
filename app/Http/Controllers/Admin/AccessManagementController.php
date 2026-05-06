<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Mail\AccessCodeMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
        $code = strtoupper(Str::random(8));

        $accessRequest->update([
            'status'      => 'approved',
            'access_code' => $code,
            'approved_at' => now(),
        ]);

        try {
            \Mail::to($accessRequest->email)
                ->send(new \App\Mail\AccessCodeMail($accessRequest));

            return back()->with('success',
                "Akses disetujui. Kode <strong>{$code}</strong> telah dikirim ke {$accessRequest->email}");

        } catch (\Exception $e) {
            // Log error tapi jangan crash — tampilkan kode manual
            \Log::error('Mail error: ' . $e->getMessage());

            return back()->with('success',
                "Akses disetujui. Kode: <strong>{$code}</strong> 
                <span class='text-danger'>(email gagal terkirim — sampaikan kode secara manual)</span>");
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

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'      => 'required|in:approve,reject,delete',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'integer',
        ]);

        $ids     = $request->selected_ids;
        $action  = $request->action;

        switch ($action) {
            case 'approve':
                foreach ($ids as $id) {
                    $ar = AccessRequest::find($id);
                    if ($ar && $ar->status === 'pending') {
                        $code = strtoupper(Str::random(8));
                        $ar->update([
                            'status'      => 'approved',
                            'access_code' => $code,
                            'approved_at' => now(),
                        ]);
                        \Mail::to($ar->email)->send(new AccessCodeMail($ar));
                    }
                }
                $msg = count($ids) . ' permintaan disetujui dan kode dikirim via email.';
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