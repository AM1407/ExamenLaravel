<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'user_id', 'started_at', 'completed_at'];

    protected function casts(): array
    {
        return [
            'started_at' => 'immutable_datetime',
            'completed_at' => 'immutable_datetime',
        ];
    }

    /**
     * Get the user that this task is assigned to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all comments on this task
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Mark task as in progress (start time tracking)
     */
    public function startProgress(): void
    {
        if ($this->status === 'pending') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Mark task as completed (stop time tracking)
     */
    public function completeTask(): void
    {
        if ($this->status !== 'completed') {
            $this->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        }
    }

    /**
     * Get time elapsed since task was started
     * Returns human-readable format (e.g., "2 hours ago")
     */
    public function getTimeElapsedAttribute(): ?string
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? Carbon::now();
        return $this->started_at->diffForHumans($endTime, ['absolute' => true]);
    }

    /**
     * Get total hours spent on task
     */
    public function getTotalHoursAttribute(): ?float
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? Carbon::now();
        return round($this->started_at->diffInMinutes($endTime) / 60, 2);
    }

    /**
     * Check if task is overdue (more than 24 hours in progress)
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->started_at || $this->status === 'completed') {
            return false;
        }

        return $this->started_at->addDay()->isPast();
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t H:i');
    }

    /**
     * Get formatted started date
     */
    public function getFormattedStartedDateAttribute(): ?string
    {
        if (!$this->started_at) {
            return null;
        }

        return $this->started_at->format('M d, Y \a\t H:i');
    }

    /**
     * Get formatted completed date
     */
    public function getFormattedCompletedDateAttribute(): ?string
    {
        if (!$this->completed_at) {
            return null;
        }

        return $this->completed_at->format('M d, Y \a\t H:i');
    }

    /**
     * Get days since task was created
     */
    public function getDaysSinceCreatedAttribute(): int
    {
        return $this->created_at->diffInDays(Carbon::now());
    }
}
