<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLeaderTaskCompletion extends Model
{
    use HasFactory;

    protected $table = 'team_leader_task_completions';

    protected $fillable = [
        'user_id',
        'task_hash',
        'task_text',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Normalized hash used to identify a task line regardless of case/whitespace.
     */
    public static function hashFor(string $taskLine): string
    {
        return sha1(mb_strtolower(trim($taskLine)));
    }

    /**
     * Build a structured task list for a user from the raw (newline-separated)
     * task string, merging in persisted completion state.
     *
     * @return array{items: array<int, array{text:string,hash:string,completed:bool,completed_at:\Illuminate\Support\Carbon|null}>, total:int, completed:int}
     */
    public static function buildTaskList(int $userId, ?string $rawTasks): array
    {
        $lines = [];

        if ($rawTasks) {
            foreach (preg_split('/\r\n|\r|\n/', (string) $rawTasks) as $line) {
                $line = trim($line);
                if ($line !== '') {
                    $lines[] = $line;
                }
            }
        }

        if (empty($lines)) {
            return ['items' => [], 'total' => 0, 'completed' => 0];
        }

        $hashes = array_map([self::class, 'hashFor'], $lines);

        $completions = self::where('user_id', $userId)
            ->whereIn('task_hash', $hashes)
            ->get()
            ->keyBy('task_hash');

        $items = [];
        $completed = 0;

        foreach ($lines as $line) {
            $hash = self::hashFor($line);
            $rec = $completions->get($hash);

            $isCompleted = $rec && $rec->is_completed;
            if ($isCompleted) {
                $completed++;
            }

            $items[] = [
                'text'         => $line,
                'hash'         => $hash,
                'completed'    => $isCompleted,
                'completed_at' => $rec->completed_at ?? null,
            ];
        }

        return [
            'items'     => $items,
            'total'     => count($lines),
            'completed' => $completed,
        ];
    }
}
