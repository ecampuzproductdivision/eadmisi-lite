<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\Registration;
use App\Models\SoalUjian;
use Illuminate\Http\Request;

class TesOnlineController extends Controller
{
    /**
     * Halaman utama Tes Online - menampilkan daftar jalur yang sudah lunas.
     */
    public function index()
    {
        $registrations = Registration::where('user_id', auth()->id())
            ->with(['registrationPath', 'programStudi1', 'programStudi2'])
            ->whereIn('status', ['payment_verified', 'exam_completed', 'reviewed', 'accepted'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Load exam results for each registration
        $examResults = [];
        if ($registrations->isNotEmpty()) {
            $examResults = ExamResult::whereIn('registration_id', $registrations->pluck('id'))
                ->where('status', 'completed')
                ->get()
                ->keyBy('registration_id');
        }

        return view('daftar-pmb.tes-online', compact('registrations', 'examResults'));
    }

    /**
     * Mulai ujian - buat exam result baru untuk jalur tertentu.
     * Questions are fetched from the specific PaketSoal mapped to the applicant's registration path.
     */
    public function start($registrationId = null)
    {
        if (!$registrationId) {
            return redirect()->route('tes-online.index')
                ->with('error', 'Silakan pilih jalur pendaftaran yang akan diikuti tesnya.');
        }

        $registration = Registration::where('user_id', auth()->id())
            ->where('id', $registrationId)
            ->whereIn('status', ['payment_verified'])
            ->with('registrationPath.paketSoal')
            ->first();

        if (!$registration) {
            return redirect()->route('tes-online.index')
                ->with('error', 'Anda belum memiliki akses ke Tes Online. Pastikan pembayaran sudah lunas.');
        }

        // Check if already completed
        $existingExam = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'completed')
            ->first();

        if ($existingExam) {
            return redirect()->route('tes-online.index');
        }

        // Check if there's an in_progress exam
        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            // Get the specific PaketSoal mapped to this registration path
            $paketSoal = $registration->registrationPath?->paketSoal;

            // Fetch questions from the mapped PaketSoal
            $questions = collect();
            if ($paketSoal) {
                $questions = SoalUjian::where('paket_soal_id', $paketSoal->id)
                    ->where('status_aktif', true)
                    ->orderBy('urutan')
                    ->get();
            }

            // Edge case guard: no questions found
            if ($questions->isEmpty()) {
                return redirect()->route('tes-online.index')
                    ->with('error', 'Soal ujian belum siap untuk jalur ini. Silakan hubungi Helpdesk PMB.');
            }

            $examResult = ExamResult::create([
                'registration_id' => $registration->id,
                'total_questions' => $questions->count(),
                'answers' => [],
                'status' => 'in_progress',
            ]);
        }

        return redirect()->route('tes-online.question', ['registrationId' => $registration->id, 'index' => 0]);
    }

    /**
     * Tampilkan soal ujian.
     */
    public function question($registrationId, $index = 0)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->where('id', $registrationId)
            ->whereIn('status', ['payment_verified'])
            ->with('registrationPath.paketSoal')
            ->first();

        if (!$registration) {
            return redirect()->route('tes-online.index');
        }

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('tes-online.index');
        }

        // Fetch questions from the specific mapped PaketSoal
        $paketSoal = $registration->registrationPath?->paketSoal;
        $questions = collect();
        if ($paketSoal) {
            $questions = SoalUjian::where('paket_soal_id', $paketSoal->id)
                ->where('status_aktif', true)
                ->orderBy('urutan')
                ->get();
        }

        if ($questions->isEmpty()) {
            return redirect()->route('tes-online.index')
                ->with('error', 'Soal ujian belum siap untuk jalur ini. Silakan hubungi Helpdesk PMB.');
        }

        $currentQuestion = $questions[$index] ?? null;
        $answers = $examResult->answers ?? [];
        $currentQuestionIndex = (int) $index;

        // Calculate elapsed time
        $elapsedSeconds = 0;
        if ($examResult->created_at) {
            $elapsedSeconds = max(0, now()->diffInSeconds($examResult->created_at, false) * -1);
        }

        return view('daftar-pmb.tes-online', compact(
            'registration', 'examResult', 'questions',
            'currentQuestion', 'currentQuestionIndex', 'answers', 'elapsedSeconds'
        ));
    }

    /**
     * Simpan jawaban dan pindah ke soal berikutnya.
     */
    public function answer(Request $request)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->whereIn('status', ['payment_verified'])
            ->with('registrationPath.paketSoal')
            ->latest()
            ->first();

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('tes-online.index');
        }

        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);

        // Save answer
        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) {
            $answers[$questionId] = $answer;
            $examResult->update([
                'answers' => $answers,
                'duration_seconds' => $elapsedSeconds,
            ]);
        }

        // Find current question index from the mapped package
        $paketSoal = $registration->registrationPath?->paketSoal;
        $questions = collect();
        if ($paketSoal) {
            $questions = SoalUjian::where('paket_soal_id', $paketSoal->id)
                ->where('status_aktif', true)
                ->orderBy('urutan')
                ->get();
        }

        $currentIndex = $questions->search(function ($q) use ($questionId) {
            return $q->id == $questionId;
        });

        $nextIndex = $currentIndex + 1;
        if ($nextIndex >= $questions->count()) {
            $nextIndex = $questions->count() - 1;
        }

        return redirect()->route('tes-online.question', ['registrationId' => $registration->id, 'index' => $nextIndex]);
    }

    /**
     * Selesai ujian - koreksi jawaban menggunakan bobot/weight per soal.
     * Score = akumulasi bobot dari jawaban benar, capped di 100.
     */
    public function submit(Request $request)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->whereIn('status', ['payment_verified'])
            ->with('registrationPath.paketSoal')
            ->latest()
            ->first();

        $examResult = ExamResult::where('registration_id', $registration->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$examResult) {
            return redirect()->route('tes-online.index');
        }

        // Save last answer if any
        $questionId = $request->input('question_id');
        $answer = $request->input('answer');
        $elapsedSeconds = $request->input('elapsed_seconds', 0);

        $answers = $examResult->answers ?? [];
        if ($questionId && $answer) {
            $answers[$questionId] = $answer;
        }

        // Fetch questions from the mapped PaketSoal
        $paketSoal = $registration->registrationPath?->paketSoal;
        $questions = collect();
        if ($paketSoal) {
            $questions = SoalUjian::where('paket_soal_id', $paketSoal->id)
                ->where('status_aktif', true)
                ->orderBy('urutan')
                ->get();
        }

        // ── WEIGHT-BASED SCORING ──
        // Accumulate individual question weights (skor/bobot) for correct answers.
        // Max score is capped at 100.
        $totalScore = 0;
        $correctCount = 0;

        foreach ($questions as $question) {
            $studentAnswer = $answers[$question->id] ?? '';
            if (!empty($studentAnswer) && strtoupper(trim($studentAnswer)) === strtoupper(trim($question->kunci_jawaban))) {
                $correctCount++;
                $totalScore += (float) ($question->skor ?? 0);
            }
        }

        // Enforce max 100 ceiling
        if ($totalScore > 100) {
            $totalScore = 100;
        }

        $totalQuestions = $questions->count();
        $wrongAnswers = $totalQuestions - $correctCount;

        $examResult->update([
            'answers' => $answers,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'wrong_answers' => $wrongAnswers,
            'score' => $totalScore,
            'duration_seconds' => $elapsedSeconds,
            'status' => 'completed',
        ]);

        // Update registration status
        $jalur = $registration->registrationPath;
        if ($jalur && in_array($jalur->metode_pengumuman, ['langsung', 'Langsung (One Day Service)'])) {
            $threshold = $jalur->nilai_ambang_batas ?? 75;
            if ($totalScore >= $threshold) {
                $registration->update([
                    'status' => 'accepted',
                    'updated_at' => now()
                ]);
            } else {
                $registration->update([
                    'status' => 'rejected',
                    'updated_at' => now()
                ]);
            }
        } else {
            $registration->update([
                'status' => 'exam_completed',
                'updated_at' => now()
            ]);
        }

        return redirect()->route('tes-online.index')
            ->with('success', 'Ujian berhasil diselesaikan! Skor: ' . number_format($totalScore, 1));
    }
}