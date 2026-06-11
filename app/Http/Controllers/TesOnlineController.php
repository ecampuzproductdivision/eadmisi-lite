<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\Registration;
use App\Models\RegistrationPath;
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
            $questions = ExamQuestion::orderBy('order')->get();
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

        $questions = ExamQuestion::orderBy('order')->get();
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

        // Find current question index
        $questions = ExamQuestion::orderBy('order')->get();
        $currentIndex = $questions->search(function ($q) use ($questionId) {
            return $q->id == $questionId;
        });

        $nextIndex = $currentIndex + 1;
        if ($nextIndex >= $questions->count()) {
            $nextIndex = $questions->count() - 1;
        }

        return redirect()->route('tes-online.question', $nextIndex);
    }

    /**
     * Selesai ujian - koreksi jawaban.
     */
    public function submit(Request $request)
    {
        $registration = Registration::where('user_id', auth()->id())
            ->whereIn('status', ['payment_verified'])
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

        // Grade the exam
        $questions = ExamQuestion::orderBy('order')->get();
        $correctAnswers = 0;

        foreach ($questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) {
                $correctAnswers++;
            }
        }

        $totalQuestions = $questions->count();
        $wrongAnswers = $totalQuestions - $correctAnswers;
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $examResult->update([
            'answers' => $answers,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'wrong_answers' => $wrongAnswers,
            'score' => $score,
            'duration_seconds' => $elapsedSeconds,
            'status' => 'completed',
        ]);

        // Update registration status
        \Illuminate\Support\Facades\DB::table('registrations')
            ->where('id', $registration->id)
            ->update(['status' => 'exam_completed', 'updated_at' => now()]);

        return redirect()->route('tes-online.index')
            ->with('success', 'Ujian berhasil diselesaikan!');
    }
}