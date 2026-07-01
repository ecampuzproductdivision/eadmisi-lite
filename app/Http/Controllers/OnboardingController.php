<?php

namespace App\Http\Controllers;

use App\Models\UserOnboardingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * All tutorial definitions with their steps.
     * Each tutorial has an id, title, and list of steps.
     * Add new tutorials here to register them in the system.
     */
    public static function getTutorials(): array
    {
        return [
            'onboarding' => [
                'id' => 'onboarding',
                'title' => 'Onboarding',
                'steps' => [
                    ['id' => 'sidebar', 'label' => 'Navigasi Sidebar'],
                    ['id' => 'search', 'label' => 'Pencarian Fitur (Ctrl+K)'],
                    ['id' => 'profile', 'label' => 'Menu Profile & Pengaturan'],
                    ['id' => 'sidebar-toggle', 'label' => 'Toggle Sidebar'],
                    ['id' => 'theme', 'label' => 'Mode Tampilan (Light/Dark)'],
                    ['id' => 'periode', 'label' => 'Periode Aktif'],
                    ['id' => 'notifications', 'label' => 'Notifikasi'],
                    ['id' => 'content', 'label' => 'Konten Dashboard'],
                ],
            ],
        ];
    }

    /**
     * Get onboarding progress for the current user.
     */
    public function progress()
    {
        $progress = $this->getOrCreateProgress();
        $tutorials = self::getTutorials();

        return response()->json([
            'has_completed_welcome' => $progress->has_completed_welcome,
            'is_dismissed' => $progress->is_dismissed,
            'tutorials_progress' => $progress->tutorials_progress ?? UserOnboardingProgress::defaultTutorialsProgress(),
            'tutorials' => $tutorials,
        ]);
    }

    /**
     * Mark welcome modal as completed.
     */
    public function completeWelcome()
    {
        $progress = $this->getOrCreateProgress();
        $progress->update(['has_completed_welcome' => true]);
        return response()->json(['success' => true]);
    }

    /**
     * Mark a specific step in a tutorial as completed.
     * Request: { tutorial_id: string, step: string }
     */
    public function completeStep(Request $request)
    {
        $request->validate([
            'step' => 'required|string',
            'tutorial_id' => 'required|string',
        ]);

        $tutorialId = $request->tutorial_id;
        $stepId = $request->step;
        $tutorials = self::getTutorials();

        // Validate tutorial exists
        if (!isset($tutorials[$tutorialId])) {
            return response()->json(['success' => false, 'message' => 'Tutorial not found'], 404);
        }

        $progress = $this->getOrCreateProgress();
        $tutorialsProgress = $progress->tutorials_progress ?? UserOnboardingProgress::defaultTutorialsProgress();

        // Ensure tutorial entry exists
        if (!isset($tutorialsProgress[$tutorialId])) {
            $tutorialsProgress[$tutorialId] = [
                'completed_steps' => [],
                'is_completed' => false,
            ];
        }

        // Add step if not already completed
        $completedSteps = $tutorialsProgress[$tutorialId]['completed_steps'];
        if (!in_array($stepId, $completedSteps)) {
            $completedSteps[] = $stepId;
        }

        // Check if all steps in this tutorial are completed
        $allStepIds = array_column($tutorials[$tutorialId]['steps'], 'id');
        $isCompleted = empty(array_diff($allStepIds, $completedSteps));

        $tutorialsProgress[$tutorialId] = [
            'completed_steps' => $completedSteps,
            'is_completed' => $isCompleted,
        ];

        $progress->update(['tutorials_progress' => $tutorialsProgress]);

        return response()->json([
            'success' => true,
            'tutorials_progress' => $tutorialsProgress,
            'tutorial_completed' => $isCompleted,
            'all_completed' => $progress->areAllTutorialsCompleted(),
        ]);
    }

    /**
     * Dismiss onboarding entirely.
     */
    public function dismiss()
    {
        $progress = $this->getOrCreateProgress();
        $progress->update([
            'is_dismissed' => true,
            'has_completed_welcome' => true,
        ]);
        return response()->json(['success' => true]);
    }

    /**
     * Reset onboarding (re-enable).
     */
    public function reset()
    {
        $progress = $this->getOrCreateProgress();
        $progress->update([
            'has_completed_welcome' => false,
            'tutorials_progress' => UserOnboardingProgress::defaultTutorialsProgress(),
            'is_dismissed' => false,
        ]);
        return response()->json(['success' => true]);
    }

    /**
     * Get or create onboarding progress for the current user.
     */
    private function getOrCreateProgress()
    {
        return UserOnboardingProgress::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'has_completed_welcome' => false,
                'tutorials_progress' => UserOnboardingProgress::defaultTutorialsProgress(),
                'is_dismissed' => false,
            ]
        );
    }
}