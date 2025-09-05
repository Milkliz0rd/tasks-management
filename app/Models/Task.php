<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * The attributes that should be cast
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constants for statuses
     */
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    /**
     * Constants for priorities
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * Getter for all available statuses
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
        ];
    }

    /**
     * Getter for all available priorities
     *
     * @return array
     */
    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
        ];
    }

    /**
     * Scope to filter by status
     *
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /** 
     * Scope to filter by status Pending
     * 
     * @param Builder $query (The Laravel method to build the promise)
     * @return Builder
    */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status','pending'); //ask to return only task with satuts pending
    }

    /** 
     * Scope to filter by priority Low
     * 
     * @param Builder $query (The Laravel method to build the promise)
     * @return Builder
    */
    public function scopeLow(Builder $query): Builder
    {
        return $query->where('priority','low'); //ask to return only task with priority low
    }

    /** 
     * Scope for recent task
     * 
     * @param Builder $query (The Laravel method to build the promise)
     * @return Builder
    */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->where('created_at', '>', today()->toDateString());
    }


    /**
     * Scope for late tasks
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', Carbon::now()->toDateString())
            ->where('status', '!=', self::STATUS_COMPLETED);
    }

    /**
     * Scope for text search
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor to check if the task is late
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Accessor to format the due date
     *
     * @return string|null
     */
    public function getFormattedDueDateAttribute(): ?string
    {
        return $this->due_date ? $this->due_date->format('d/m/Y') : null;
    }

    /**
     * Mutator to ensure status is valid
     *
     * @param string $value
     */
    public function setStatusAttribute(string $value): void
    {
        if (in_array($value, self::getStatuses())) {
            $this->attributes['status'] = $value;
        }
    }

    /**
     * Mutator to ensure priority is valid
     *
     * @param string $value
     */
    public function setPriorityAttribute(string $value): void
    {
        if (in_array($value, self::getPriorities())) {
            $this->attributes['priority'] = $value;
        }
    }
}
