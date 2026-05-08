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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function startProgress(): void
    {
        if ($this->status === 'pending') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => Carbon::now(),
            ]);
        }
    }

    public function completeTask(): void
    {
        if ($this->status !== 'completed') {
            $this->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        }
    }

    public function getTimeElapsedAttribute(): ?string
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? Carbon::now();

        $minutes = (int) $this->started_at->diffInMinutes($endTime);
        $hours   = (int) $this->started_at->diffInHours($endTime);
        $days    = (int) $this->started_at->diffInDays($endTime);

        if ($days >= 7) {
            $weeks = (int) floor($days / 7);
            return $weeks === 1 ? '1 week' : "$weeks weeks";
        } elseif ($days > 0) {
            return $days === 1 ? '1 day' : "$days days";
        } elseif ($hours > 0) {
            return $hours === 1 ? '1 hour' : "$hours hours";
        } else {
            return $minutes === 1 ? '1 minute' : "$minutes minutes";
        }
    }

    public function getTotalHoursAttribute(): ?float
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->completed_at ?? Carbon::now();
        return round($this->started_at->diffInMinutes($endTime) / 60, 2);
    }

    // overdue = started more than 24 hours ago and not yet completed
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->started_at || $this->status === 'completed') {
            return false;
        }

        return $this->started_at->addDay()->isPast();
    }

    public function getFormattedCreatedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y \a\t H:i');
    }

    public function getFormattedStartedDateAttribute(): ?string
    {
        if (!$this->started_at) {
            return null;
        }

        return $this->started_at->format('M d, Y \a\t H:i');
    }

    public function getFormattedCompletedDateAttribute(): ?string
    {
        if (!$this->completed_at) {
            return null;
        }

        return $this->completed_at->format('M d, Y \a\t H:i');
    }

    public function getDaysSinceCreatedAttribute(): int
    {
        return $this->created_at->diffInDays(Carbon::now());
    }
}
