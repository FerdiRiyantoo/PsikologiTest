<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KraepelinAnswer;
use App\Models\TestSession;
use App\Services\KraepelinScoringService;
use Illuminate\Http\Request;

class KraepelinController extends Controller
{
    private function getColumnDigits(int $columnNumber): array
    {
        $questions = config('kraepelin_questions');
        return $questions[$columnNumber] ?? [];
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

        $config = config('kraepelin');

        // Kolom yang sudah selesai dikerjakan
        $doneColumns = KraepelinAnswer::where('test_session_id', $testSession->id)
            ->distinct('column_number')
            ->pluck('column_number')
            ->toArray();

        // Jika semua kolom selesai
        if (count($doneColumns) >= $config['total_columns']) {
            return $this->finishTest($testSession);
        }

        // Tentukan kolom berikutnya yang belum dikerjakan
        $currentColumn = 1;
        for ($i = 1; $i <= $config['total_columns']; $i++) {
            if (!in_array($i, $doneColumns)) {
                $currentColumn = $i;
                break;
            }
        }

        // Ambil digit untuk kolom saat ini
        $columnDigits = $this->getColumnDigits($currentColumn);

        $progress = round((count($doneColumns) / $config['total_columns']) * 100);

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
        $columnNumber = $request->column_number; // ← harus didefinisikan SEBELUM dipakai
        $columnDigits = $this->getColumnDigits($columnNumber);

        // Hapus jawaban lama untuk kolom ini jika ada
        KraepelinAnswer::where('test_session_id', $testSession->id)
            ->where('column_number', $columnNumber)
            ->delete();

        // Simpan jawaban baru
        foreach ($request->answers as $rowIndex => $userAnswer) {
            $digitA  = $columnDigits[$rowIndex];
            $digitB  = $columnDigits[$rowIndex + 1];
            $correct = strlen((string) $userAnswer) > 0
                && intval($userAnswer) === (($digitA + $digitB) % 10);

            KraepelinAnswer::create([
                'test_session_id' => $testSession->id,
                'column_number'   => $columnNumber,
                'row_number'      => $rowIndex + 1,
                'digit_a'         => $digitA,
                'digit_b'         => $digitB,
                'user_answer'     => strlen((string) $userAnswer) > 0
                    ? intval($userAnswer)
                    : null,
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

        return redirect()->route('test.finish');
    }
}