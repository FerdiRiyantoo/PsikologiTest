<?php

namespace App\Services;

use App\Models\TestSession;
use App\Models\KraepelinAnswer;
use App\Models\KraepelinResult;

class KraepelinScoringService
{
    protected TestSession $testSession;

    public function __construct(TestSession $testSession)
    {
        $this->testSession = $testSession;
    }

    public function calculate()
    {
        // 1. Ambil seluruh jawaban dari sesi ini
        $answers = KraepelinAnswer::where('test_session_id', $this->testSession->id)->get();

        if ($answers->isEmpty()) {
            return;
        }

        // 2. Siapkan wadah untuk agregasi per kolom
        $correctPerColumn = [];
        $attemptedPerColumn = [];
        
        $totalAnswered = $answers->count();
        $totalCorrect = $answers->where('is_correct', true)->count();

        // 3. Kelompokkan data per kolom
        foreach ($answers as $answer) {
            $col = $answer->column_number;
            
            if (!isset($attemptedPerColumn[$col])) {
                $attemptedPerColumn[$col] = 0;
                $correctPerColumn[$col] = 0;
            }

            $attemptedPerColumn[$col]++;
            if ($answer->is_correct) {
                $correctPerColumn[$col]++;
            }
        }

        // Susun ulang array agar index berurutan dari 1 sampai akhir
        ksort($correctPerColumn);
        $correctAnswersArray = array_values($correctPerColumn);
        $totalColumns = count($correctAnswersArray);

        // 4. Kalkulasi 4 Indikator Utama
        $paceScore = $this->calculatePace($totalCorrect, $totalColumns);
        $accuracyScore = $this->calculateAccuracy($totalCorrect, $totalAnswered);
        $enduranceScore = $this->calculateEndurance($correctAnswersArray);
        $stabilityScore = $this->calculateStability($correctAnswersArray, $paceScore);

        // 5. Simpan ke database KraepelinResult
        KraepelinResult::updateOrCreate(
            ['test_session_id' => $this->testSession->id],
            [
                'pace_score'      => $paceScore,
                'accuracy_score'  => $accuracyScore,
                'endurance_score' => $enduranceScore,
                'stability_score' => $stabilityScore,
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'raw_data'        => [
                    'correct_per_column'   => $correctPerColumn,
                    'attempted_per_column' => $attemptedPerColumn
                ]
            ]
        );
    }

    private function calculatePace($totalCorrect, $totalColumns): float
    {
        if ($totalColumns === 0) return 0;
        return round($totalCorrect / $totalColumns, 2);
    }

    private function calculateAccuracy($totalCorrect, $totalAnswered): float
    {
        if ($totalAnswered === 0) return 0;
        return round(($totalCorrect / $totalAnswered) * 100, 2);
    }

    private function calculateEndurance(array $correctAnswersArray): float
    {
        $total = count($correctAnswersArray);
        if ($total < 2) return 0;

        $midPoint = (int) floor($total / 2);
        
        $firstHalf = array_slice($correctAnswersArray, 0, $midPoint);
        $secondHalf = array_slice($correctAnswersArray, $midPoint);

        $avgFirst = count($firstHalf) > 0 ? array_sum($firstHalf) / count($firstHalf) : 0;
        $avgSecond = count($secondHalf) > 0 ? array_sum($secondHalf) / count($secondHalf) : 0;

        // Ketahanan: Tren rata-rata paruh kedua dibandingkan paruh pertama
        return round($avgSecond - $avgFirst, 2);
    }

    private function calculateStability(array $correctAnswersArray, $mean): float
    {
        $total = count($correctAnswersArray);
        if ($total < 2) return 0;

        $sumOfSquares = 0;
        foreach ($correctAnswersArray as $val) {
            $sumOfSquares += pow(($val - $mean), 2);
        }

        // Menggunakan Standard Deviation (Simpangan Baku)
        $variance = $sumOfSquares / ($total - 1);
        return round(sqrt($variance), 2);
    }
}

?>