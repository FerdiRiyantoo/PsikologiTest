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
            return redirect()->route('kraepelin.instruksi');
        }

        $config = config('kraepelin');

        $doneColumns = KraepelinAnswer::where('test_session_id', $testSession->id)
            ->distinct('column_number')
            ->pluck('column_number')
            ->toArray();

        if (count($doneColumns) >= $config['total_columns']) {
            return $this->finishTest($testSession);
        }

        $currentColumn = 1;
        for ($i = 1; $i <= $config['total_columns']; $i++) {
            if (!in_array($i, $doneColumns)) {
                $currentColumn = $i;
                break;
            }
        }

        $columnDigits = $this->getColumnDigits($currentColumn);
        $progress     = round((count($doneColumns) / $config['total_columns']) * 100);

        // Hitung sisa waktu berdasarkan waktu mulai (started_at)
        $elapsed      = now()->diffInSeconds($testSession->started_at);
        $totalTime    = $config['total_time'];           // 750 detik
        $timeLeft     = max(0, $totalTime - $elapsed);   // sisa waktu global

        // Jika waktu sudah habis, paksa selesai
        if ($timeLeft <= 0) {
            return $this->finishTest($testSession);
        }

        // Waktu untuk kolom saat ini
        // Estimasi: setiap kolom mendapat jatah time_per_column detik
        // Sisa waktu kolom = sisa waktu global dibagi sisa kolom
        $remainingColumns  = $config['total_columns'] - count($doneColumns);
        $timeForThisColumn = $remainingColumns > 0
            ? min($config['time_per_column'], (int) ceil($timeLeft / $remainingColumns))
            : $config['time_per_column'];

        // Gunakan time_per_column tetap, tapi tampilkan juga total countdown
        $columnTimeLeft = $config['time_per_column'];

        return view('user.kraepelin', compact(
            'testSession', 'currentColumn', 'columnDigits',
            'config', 'doneColumns', 'progress',
            'timeLeft', 'columnTimeLeft'
        ));
    }

    public function instruksi()
    {
        $sessionId   = session('test_session_id');
        $testSession = TestSession::with('accessRequest')->findOrFail($sessionId);

        if ($testSession->status !== 'not_started') {
            return redirect()->route('kraepelin.index');
        }

        $config = config('kraepelin');

        return view('user.instruksi-kraepelin', compact('testSession', 'config'));
    }

    public function startTest(Request $request)
    {
        $sessionId   = session('test_session_id');
        $testSession = TestSession::findOrFail($sessionId);

        $testSession->update([
            'status'     => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('kraepelin.index');
    }

    public function saveColumn(Request $request)
    {
        $request->validate([
            'column_number' => 'required|integer',
            'answers'       => 'required|array',
        ]);

        $sessionId    = session('test_session_id');
        $testSession  = TestSession::findOrFail($sessionId);
        $columnNumber = $request->column_number; 
        $columnDigits = $this->getColumnDigits($columnNumber);
        $rawAnswers   = $request->answers;

        // ---- VALIDASI PROTEKSI SKIP SOAL (BACKEND) ----
        $sanitizedAnswers = [];
        $hasEmptyFound = false;

        // Iterasi berdasarkan konfigurasi total baris per kolom
        $totalRows = config('kraepelin.rows_per_column', count($rawAnswers));
        
        for ($rowIndex = 0; $rowIndex < $totalRows; $rowIndex++) {
            // Cek apakah user mengisi indeks ini dan nilainya tidak kosong
            $userAnswer = $rawAnswers[$rowIndex] ?? null;
            $isFilled = (strlen((string) $userAnswer) > 0);

            if ($hasEmptyFound && $isFilled) {
                // Jika sudah ada baris atas yang kosong, tapi baris bawah terisi, buang/kosongkan!
                // Ini untuk mencegah user curang melompati soal (error dari sisi client).
                $sanitizedAnswers[$rowIndex] = null;
            } else {
                $sanitizedAnswers[$rowIndex] = $isFilled ? intval($userAnswer) : null;
                
                // Menandai jika baris kosong ditemukan, agar baris selanjutnya dianggap invalid jika terisi
                if (!$isFilled) {
                    $hasEmptyFound = true;
                }
            }
        }

        // Hapus jawaban lama untuk kolom ini jika ada
        KraepelinAnswer::where('test_session_id', $testSession->id)
            ->where('column_number', $columnNumber)
            ->delete();

        // Simpan jawaban yang sudah di-sanitasi
        foreach ($sanitizedAnswers as $rowIndex => $userAnswer) {
            $digitA  = $columnDigits[$rowIndex] ?? 0;
            $digitB  = $columnDigits[$rowIndex + 1] ?? 0;
            
            $correct = !is_null($userAnswer) 
                && $userAnswer === (($digitA + $digitB) % 10);

            KraepelinAnswer::create([
                'test_session_id' => $testSession->id,
                'column_number'   => $columnNumber,
                'row_number'      => $rowIndex + 1,
                'digit_a'         => $digitA,
                'digit_b'         => $digitB,
                'user_answer'     => $userAnswer, 
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