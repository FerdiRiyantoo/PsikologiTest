<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KraepelinAnswer;
use App\Models\TestSession;
use App\Services\KraepelinScoringService;
use Illuminate\Http\Request;

class KraepelinController extends Controller
{
    // Generate deret angka acak untuk satu sesi
    // (disimpan di session agar konsisten selama tes)
    private function getOrGenerateDigits(int $sessionId): array
    {
        $key = "kraepelin_digits_{$sessionId}";

        if (session()->has($key)) {
            return session($key);
        }

        $config  = config('kraepelin');
        $digits  = [];

        for ($col = 1; $col <= $config['total_columns']; $col++) {
            $digits[$col] = [];
            // +1 karena kita butuh N+1 angka untuk N pasang
            for ($row = 0; $row <= $config['rows_per_column']; $row++) {
                $digits[$col][$row] = rand(1, 9);
            }
        }

        session([$key => $digits]);
        return $digits;
    }

    public function index()
    {
        $sessionId   = session('test_session_id');
        $testSession = TestSession::findOrFail($sessionId);

        if ($testSession->status === 'completed') {
            return redirect()->route('test.finish');
        }

        if ($testSession->status === 'not_started') {
            $testSession->update([
                'status'     => 'in_progress',
                'started_at' => now(),
            ]);
        }

        $config  = config('kraepelin');
        $digits  = $this->getOrGenerateDigits($sessionId);

        // Kolom yang sudah selesai
        $doneColumns = KraepelinAnswer::where('test_session_id', $testSession->id)
            ->distinct('column_number')
            ->pluck('column_number')
            ->toArray();

        // Kolom selanjutnya
        $currentColumn = 1;
        for ($i = 1; $i <= $config['total_columns']; $i++) {
            if (!in_array($i, $doneColumns)) {
                $currentColumn = $i;
                break;
            }
        }

        // Jika semua kolom selesai
        if (count($doneColumns) >= $config['total_columns']) {
            return $this->finishTest($testSession);
        }

        $progress = round((count($doneColumns) / $config['total_columns']) * 100);
        $columnDigits = $digits[$currentColumn];

        return view('user.kraepelin', compact(
            'testSession', 'currentColumn', 'columnDigits',
            'config', 'doneColumns', 'progress'
        ));
    }

    public function saveColumn(Request $request)
    {
        $request->validate([
            'column_number' => 'required|integer',
            'answers'       => 'required|array',
        ]);

        $sessionId    = session('test_session_id');
        $testSession  = TestSession::findOrFail($sessionId);
        $digits       = $this->getOrGenerateDigits($sessionId);
        $columnNumber = $request->column_number;
        $columnDigits = $digits[$columnNumber];

        // Hapus jawaban lama untuk kolom ini jika ada
        KraepelinAnswer::where('test_session_id', $testSession->id)
            ->where('column_number', $columnNumber)
            ->delete();

        // Simpan jawaban baru
        foreach ($request->answers as $rowIndex => $userAnswer) {
            $digitA   = $columnDigits[$rowIndex];
            $digitB   = $columnDigits[$rowIndex + 1];
            $correct  = intval($userAnswer) === (($digitA + $digitB) % 10);

            KraepelinAnswer::create([
                'test_session_id' => $testSession->id,
                'column_number'   => $columnNumber,
                'row_number'      => $rowIndex + 1,
                'digit_a'         => $digitA,
                'digit_b'         => $digitB,
                'user_answer'     => strlen((string)$userAnswer) > 0 ? intval($userAnswer) : null,
                'is_correct'      => $correct,
            ]);
        }

        $config      = config('kraepelin');
        $doneColumns = KraepelinAnswer::where('test_session_id', $testSession->id)
            ->distinct('column_number')
            ->pluck('column_number')
            ->count();

        // Jika semua kolom selesai
        if ($doneColumns >= $config['total_columns']) {
            return $this->finishTest($testSession);
        }

        return redirect()->route('kraepelin.index');
    }

    private function finishTest(TestSession $testSession)
    {
        $scoring = new KraepelinScoringService($testSession);
        $scoring->calculate();

        $testSession->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        session(['completed_session_id' => $testSession->id]);
        session()->forget('test_session_id');
        session()->forget("kraepelin_digits_{$testSession->id}");

        return redirect()->route('test.finish');
    }
}
?>