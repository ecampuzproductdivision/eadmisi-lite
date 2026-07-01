<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOnboardingProgress extends Model
{
    protected $table = 'user_onboarding_progress';

    protected $fillable = [
        'user_id',
        'has_completed_welcome',
        'tutorials_progress',
        'is_dismissed',
    ];

    protected function casts(): array
    {
        return [
            'has_completed_welcome' => 'boolean',
            'tutorials_progress' => 'array',
            'is_dismissed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the default tutorials progress structure.
     */
    public static function defaultTutorialsProgress(): array
    {
        return [
            'onboarding' => [
                'completed_steps' => [],
                'is_completed' => false,
            ],
        ];
    }

    /**
     * Check if a specific tutorial is completed.
     */
    public function isTutorialCompleted(string $tutorialId): bool
    {
        $progress = $this->tutorials_progress ?? [];
        return isset($progress[$tutorialId]['is_completed']) && $progress[$tutorialId]['is_completed'] === true;
    }

    /**
     * Get completed steps for a specific tutorial.
     */
    public function getTutorialCompletedSteps(string $tutorialId): array
    {
        $progress = $this->tutorials_progress ?? [];
        return $progress[$tutorialId]['completed_steps'] ?? [];
    }

    /**
     * Check if all tutorials are completed.
     */
    public function areAllTutorialsCompleted(): bool
    {
        $progress = $this->tutorials_progress ?? [];
        if (empty($progress)) return false;
        foreach ($progress as $tutorial) {
            if (!isset($tutorial['is_completed']) || $tutorial['is_completed'] !== true) {
                return false;
            }
        }
        return true;
    }
}