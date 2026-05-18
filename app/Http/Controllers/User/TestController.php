<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use App\Models\PapiResult;
use App\Models\TestSession;
use App\Services\PapiScoringService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected int $perPage = 10;

    public function index(Request $request)
    {
        $sessionId   = session('test_session_id');
        $testSession = TestSession::with('answers')->findOrFail($sessionId);

        if ($testSession->status === 'completed') {
            return redirect()->route('test.result');
        }

        if ($testSession->status === 'not_started') {
            $testSession->update(['status' => 'in_progress', 'started_at' => now()]);
        }

        $questions = config('papi_questions');
        $answered  = $testSession->answers->pluck('question_number')->toArray();
        $total     = count($questions);

        // Tentukan halaman saat ini
        $page       = max(1, (int) $request->get('page', 1));
        $totalPages = (int) ceil($total / $this->perPage);
        $page       = min($page, $totalPages);

        // Ambil 10 soal untuk halaman ini
        $offset         = ($page - 1) * $this->perPage;
        $pageQuestions  = array_slice($questions, $offset, $this->perPage, true);

        // Cek apakah semua soal di halaman ini sudah dijawab
        $pageNumbers    = array_keys($pageQuestions);
        $pageAnswered   = array_intersect($answered, $pageNumbers);

        $progress = ($total > 0) ? (count($answered) / $total) * 100 : 0;

        return view('user.test', compact(
            'testSession',
            'pageQuestions',
            'answered',
            'progress',
            'page',
            'totalPages',
            'total',
            'questions'
        ));
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|in:A,B',
            'page'      => 'required|integer|min:1',
        ]);

        $sessionId   = session('test_session_id');
        $testSession = TestSession::findOrFail($sessionId);
        $questions   = config('papi_questions');
        $total       = count($questions);

        // Simpan semua jawaban dari halaman ini
        foreach ($request->answers as $questionNumber => $chosenOption) {
            TestAnswer::updateOrCreate(
                [
                    'test_session_id' => $testSession->id,
                    'question_number' => (int) $questionNumber,
                ],
                ['chosen_option' => $chosenOption]
            );
        }

        $totalAnswered = $testSession->answers()->count();
        $nextPage      = (int) $request->page + 1;
        $totalPages    = (int) ceil($total / 10);

        // Jika sudah semua soal dijawab
        if ($totalAnswered >= $total) {
            $scoring = new PapiScoringService($testSession);
            $scoring->calculate();
            $testSession->update(['status' => 'completed', 'completed_at' => now()]);
            session(['completed_session_id' => $testSession->id]);
            session()->forget('test_session_id');
            return redirect()->route('test.finish');
        }

        // Lanjut ke halaman berikutnya
        return redirect()->route('test.index', ['page' => $nextPage]);
    }

    public function result()
    {
        $sessionId = session('completed_session_id');

        if (!$sessionId) {
            abort(403, 'Sesi tidak ditemukan.');
        }

        $testSession = TestSession::with(['papiResult', 'accessRequest'])->findOrFail($sessionId);

        return view('user.result', compact('testSession'));
    }

    public function finish()
    {
        $sessionId = session('completed_session_id');

        if (!$sessionId) {
            return redirect()->route('home');
        }

        $testSession = TestSession::with([
        'accessRequest',
        'result',           // hasil PAPI
        'kraepelinResult',  // hasil Kraepelin
    ])->findOrFail($sessionId);

        return view('user.finish', compact('testSession'));
    }
}