<?php

namespace App\Services;

use App\Models\PapiResult;
use App\Models\TestResult;
use App\Models\TestSession;

class PapiScoringService
{
    protected TestSession $session;

    public function __construct(TestSession $session)
    {
        $this->session = $session;
    }

    public function calculate(): PapiResult
    {
        $questions = config('papi_questions');
        $answers   = $this->session->answers->keyBy('question_number');

        // Inisialisasi semua skala = 0
        $scores = array_fill_keys(
            ['n','g','a','l','p','i','t','v','x','s','b','o','r','d','c','z','e','k','f','w'],
            0
        );

        foreach ($questions as $no => $q) {
            if (!isset($answers[$no])) continue;

            $chosen = $answers[$no]->chosen_option; // 'A' atau 'B'
            if ($chosen === 'A') {
                $scale = strtolower($q['A_scale']);
            } else {
                $scale = strtolower($q['B_scale']);
            }

            if (isset($scores[$scale])) {
                $scores[$scale]++;
            }
        }

        // Prefix dengan 'scale_' untuk nama kolom
        $dbData = [];
        foreach ($scores as $scale => $score) {
            $dbData["scale_{$scale}"] = $score;
        }

        return PapiResult::updateOrCreate(
            ['test_session_id' => $this->session->id],
            $dbData
        );
    }
}