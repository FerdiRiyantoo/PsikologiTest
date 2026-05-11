<?php

namespace App\Services;

use App\Models\KraepelinAnswer;
use App\Models\KraepelinResult;
use App\Models\TestSession;

class KraepelinScoringService
{
    protected TestSession $testSession;

    public function __construct(TestSession $testSession)
    {
        $this->testSession = $testSession;
    }

    public function calculate(): void
    {
        // 1. Ambil seluruh jawaban dari sesi ini
        $answers = KraepelinAnswer::where('test_session_id', $this->testSession->id)->get();

        if ($answers->isEmpty()) {
            return;
        }

        // 2. Kelompokkan per kolom
        $perColumn = [];

        foreach ($answers as $answer) {
            $col = $answer->column_number;

            if (!isset($perColumn[$col])) {
                $perColumn[$col] = [
                    'answered' => 0,
                    'correct'  => 0,
                ];
            }

            // Hanya hitung jika user benar-benar mengisi jawaban
            if (!is_null($answer->user_answer)) {
                $perColumn[$col]['answered']++;

                // Jawaban benar TIDAK BOLEH melebihi yang dijawab
                if ($answer->is_correct) {
                    $perColumn[$col]['correct']++;
                }
            }
        }

        // Urutkan berdasarkan nomor kolom
        ksort($perColumn);

        // 3. Validasi: pastikan correct <= answered di setiap kolom
        foreach ($perColumn as $col => &$data) {
            // Sanitasi: correct tidak boleh lebih dari answered
            $data['correct'] = min($data['correct'], $data['answered']);

            // Hitung akurasi per kolom
            $data['accuracy_pct'] = $data['answered'] > 0
                ? round(($data['correct'] / $data['answered']) * 100, 1)
                : 0;
        }
        unset($data); // putus referensi

        // 4. Hitung total keseluruhan
        $totalAnswered = array_sum(array_column($perColumn, 'answered'));
        $totalCorrect  = array_sum(array_column($perColumn, 'correct'));

        // Sanitasi global: total correct tidak boleh > total answered
        $totalCorrect = min($totalCorrect, $totalAnswered);

        // 5. Siapkan array nilai per kolom untuk kalkulasi indikator
        $correctPerColumn  = array_column($perColumn, 'correct');
        $answeredPerColumn = array_column($perColumn, 'answered');
        $totalColumns      = count($perColumn);

        // 6. Hitung 4 indikator
        $paceScore      = $this->calculatePace($totalCorrect, $totalColumns);
        $accuracyScore  = $this->calculateAccuracy($totalCorrect, $totalAnswered);
        $enduranceScore = $this->calculateEndurance($correctPerColumn);
        $stabilityScore = $this->calculateStability($correctPerColumn, $paceScore);

        // 7. Susun raw_data sesuai format yang dibutuhkan view & PDF
        $rawData = [];
        foreach ($perColumn as $col => $data) {
            $rawData[] = [
                'column'       => $col,
                'answered'     => $data['answered'],
                'correct'      => $data['correct'],
                'accuracy_pct' => $data['accuracy_pct'],
            ];
        }

        // 8. Simpan hasil
        KraepelinResult::updateOrCreate(
            ['test_session_id' => $this->testSession->id],
            [
                'pace_score'      => $paceScore,
                'accuracy_score'  => $accuracyScore,
                'endurance_score' => $enduranceScore,
                'stability_score' => $stabilityScore,
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'raw_data'        => $rawData,
            ]
        );
    }

    // Kecepatan: rata-rata jawaban benar per kolom
    private function calculatePace(int $totalCorrect, int $totalColumns): float
    {
        if ($totalColumns === 0) return 0;
        return round($totalCorrect / $totalColumns, 2);
    }

    // Ketelitian: persentase jawaban benar dari yang dijawab
    private function calculateAccuracy(int $totalCorrect, int $totalAnswered): float
    {
        if ($totalAnswered === 0) return 0;
        return round(($totalCorrect / $totalAnswered) * 100, 2);
    }

    // Ketahanan: selisih rata-rata paruh kedua vs paruh pertama
    // Positif = meningkat, Negatif = menurun, 0 = stabil
    private function calculateEndurance(array $correctPerColumn): float
    {
        $total = count($correctPerColumn);
        if ($total < 2) return 0;

        $midPoint   = (int) floor($total / 2);
        $firstHalf  = array_slice($correctPerColumn, 0, $midPoint);
        $secondHalf = array_slice($correctPerColumn, $midPoint);

        $avgFirst  = count($firstHalf)  > 0 ? array_sum($firstHalf)  / count($firstHalf)  : 0;
        $avgSecond = count($secondHalf) > 0 ? array_sum($secondHalf) / count($secondHalf) : 0;

        return round($avgSecond - $avgFirst, 2);
    }

    // Keajegan: standar deviasi jawaban benar per kolom
    // Makin kecil = makin stabil / ajeg
    private function calculateStability(array $correctPerColumn, float $mean): float
    {
        $total = count($correctPerColumn);
        if ($total < 2) return 0;

        $sumOfSquares = 0;
        foreach ($correctPerColumn as $val) {
            $sumOfSquares += pow(($val - $mean), 2);
        }

        // Gunakan sample standard deviation (n-1)
        $variance = $sumOfSquares / ($total - 1);
        return round(sqrt($variance), 2);
    }
}