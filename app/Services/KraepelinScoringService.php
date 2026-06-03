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
        $answers = KraepelinAnswer::where('test_session_id', $this->testSession->id)->get();

        if ($answers->isEmpty()) {
            return;
        }

        // ========================================================
        // 1. INISIALISASI MUTLAK 50 KOLOM (Sesuai Standar Excel)
        // ========================================================
        $totalConfigColumns = config('kraepelin.total_columns', 50);
        $perColumn = [];
        $errorPerColumn = []; 

        // Membangun struktur cetakan 50 kolom kosong (Default bernilai 0)
        for ($i = 1; $i <= $totalConfigColumns; $i++) {
            $perColumn[$i] = [
                'answered' => 0,
                'correct'  => 0,
            ];
            $errorPerColumn[$i] = 0;
        }

        // 2. Petakan data jawaban riil dari database ke cetakan 50 kolom
        foreach ($answers as $answer) {
            $col = $answer->column_number;

            // Jika ada kolom di luar batas konfigurasi (misal kolom 51), abaikan
            if (!isset($perColumn[$col])) continue;

            if (!is_null($answer->user_answer)) {
                $perColumn[$col]['answered']++;
                if ($answer->is_correct) {
                    $perColumn[$col]['correct']++;
                } else {
                    $errorPerColumn[$col]++;
                }
            } else {
                $errorPerColumn[$col]++;
            }
        }

        // 3. Validasi & Hitung Persentase Akurasi per Kolom
        foreach ($perColumn as $col => &$data) {
            $data['correct'] = min($data['correct'], $data['answered']);
            $data['accuracy_pct'] = $data['answered'] > 0
                ? round(($data['correct'] / $data['answered']) * 100, 1)
                : 0;
        }
        unset($data);

        // 4. Hitung Total Akumulasi Keseluruhan
        $totalAnswered = array_sum(array_column($perColumn, 'answered'));
        $totalCorrect  = array_sum(array_column($perColumn, 'correct'));
        $totalCorrect  = min($totalCorrect, $totalAnswered); 

        // KUNCI UTAMA: Ambil nilai 'correct' berurutan agar membentuk array tepat 50 elemen
        // Kolom-kolom yang tidak tersentuh (misal kolom 48, 49, 50) akan otomatis bernilai 0
        $correctScores = [];
        for ($i = 1; $i <= $totalConfigColumns; $i++) {
            $correctScores[] = $perColumn[$i]['correct'];
        }

        // 5. KALKULASI 4 INDIKATOR DENGAN ARRAY BERUKURAN UTUH (50 ITEM)
        $pankerScore  = $this->calculatePanker($correctScores);
        $tiankerScore = $this->calculateTianker($errorPerColumn); 
        $hankerScore  = $this->calculateHanker($correctScores);
        $pankerMeanRounded = round($pankerScore, 2); 
        $jankerScore       = $this->calculateJanker($correctScores, $pankerMeanRounded);
        
        $accuracyPct  = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 2) : 0;

        // Mendapatkan Kategori Norma
        $kategoriPanker  = $this->getKategoriPanker($pankerScore);
        $kategoriTianker = $this->getKategoriTianker($tiankerScore);
        $kategoriJanker  = $this->getKategoriJanker($jankerScore);
        $kategoriHanker  = $this->getKategoriHanker($hankerScore);

        // Siapkan raw_data JSON untuk keperluan render grafik batang dashboard admin
        $rawData = [];
        foreach ($perColumn as $col => $data) {
            $rawData[] = [
                'column'       => $col,
                'answered'     => $data['answered'],
                'correct'      => $data['correct'],
                'errors'       => $errorPerColumn[$col] ?? 0,
                'accuracy_pct' => $data['accuracy_pct'],
            ];
        }

        // 6. Simpan / Perbarui Hasil ke Database
        KraepelinResult::updateOrCreate(
            ['test_session_id' => $this->testSession->id],
            [
                'pace_score'      => round($pankerScore, 3), 
                'accuracy_score'  => $accuracyPct,            
                'total_errors'    => $tiankerScore,           
                'endurance_score' => round($hankerScore, 3),  
                'stability_score' => round($jankerScore, 2),  
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'raw_data'        => $rawData,
            ]
        );
    }

    /**
     * PANKER (Kecepatan Kerja): Rata-rata jawaban benar dibagi total kolom mutlak
     */
    private function calculatePanker(array $correctScores): float
    {
        $totalColumns = config('kraepelin.total_columns', 50); 
        if ($totalColumns === 0) return 0;
        return array_sum($correctScores) / $totalColumns;
    }

    /**
     * TIANKER (Ketelitian Kerja): Jumlah total seluruh error dan skip
     */
    private function calculateTianker(array $errorScores): int
    {
        return array_sum($errorScores);
    }

    /**
     * HANKER (Ketahanan Kerja): Gradien tren energi kerja dikali total kolom (50 * b)
     */
    private function calculateHanker(array $correctScores): float
    {
        $n = config('kraepelin.total_columns', 50);
        if (count($correctScores) < 2) return 0;

        $sumX = 0; $sumY = 0; $sumXY = 0; $sumX2 = 0;

        foreach ($correctScores as $index => $y) {
            $x = $index + 1; // Sumbu X mewakili nomor urut kolom (1, 2, 3... 50)
            $sumX += $x;
            $sumY += $y;
            $sumXY += ($x * $y);
            $sumX2 += ($x * $x);
        }

        $numerator   = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = ($n * $sumX2) - ($sumX * $sumX);

        if ($denominator == 0) return 0; 

        $b = $numerator / $denominator; 
        
        return $b * $n; 
    }

    /**
     * JANKER (Keajegan Kerja): Sum Fd / 50 Mutlak
     */
    private function calculateJanker(array $correctScores, float $pankerMean): float
    {
        $totalColumns = config('kraepelin.total_columns', 50); 
        if ($totalColumns === 0) return 0;

        $sumFd = 0.0;
        $pankerRound = round($pankerMean, 2); // Samakan pembulatan dengan Excel (7.38)

        foreach ($correctScores as $score) {
            // Kita hilangkan "if ($score > 0)" agar semua indeks kolom (1 sampai 50) 
            // diproses secara adil dan merata seperti array di Excel.
            $dev = abs($score - $pankerRound);
            
            // Jika skornya 0, pastikan tidak menambah deviasi palsu 
            // (karena di Excel f = 0, maka Fd = 0 * dev = 0)
            if ($score == 0) {
                $dev = 0;
            }

            $sumFd += $dev;
        }

        // Total Sum Fd dibagi 50 mutlak
        return $sumFd / $totalColumns;
    }

    // ========================================================
    // GETTER KATEGORI NORMA (DIADAPTASI DARI TEMPLATE EXCEL)
    // ========================================================

    public function getKategoriPanker(float $nilai): string {
        if ($nilai >= 16.044) return 'Baik Sekali';
        if ($nilai >= 13.362) return 'Baik';
        if ($nilai >= 10.963) return 'Sedang';
        if ($nilai >= 8.116)  return 'Kurang';
        return 'Kurang Sekali';
    }

    public function getKategoriTianker(int $nilai): string {
        if ($nilai === 0) return 'Baik Sekali';
        if ($nilai <= 2)  return 'Baik';
        if ($nilai <= 14) return 'Sedang';
        if ($nilai <= 21) return 'Kurang';
        return 'Kurang Sekali';
    }

    public function getKategoriJanker(float $nilai): string {
        if ($nilai <= 3)  return 'Baik Sekali';
        if ($nilai <= 7)  return 'Baik';
        if ($nilai <= 10) return 'Sedang';
        if ($nilai <= 14) return 'Kurang';
        return 'Kurang Sekali';
    }

    public function getKategoriHanker(float $nilai): string {
        if ($nilai >= 2.497)  return 'Baik Sekali';
        if ($nilai >= 1.015)  return 'Baik';
        if ($nilai >= -0.468) return 'Sedang';
        if ($nilai >= -1.195) return 'Kurang'; 
        return 'Kurang Sekali';
    }
}