<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string|null $level
 * @property int|null $user_id
 * @property string|null $message
 * @property array<string, mixed>|null $context
 * @property array<string, mixed>|null $sources
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $user
 */
class Log extends Model
{
    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory> */
    use HasFactory;

    // Log level constants
    public const INFO = 'info';

    public const DEBUG = 'debug';

    public const SUCCESS = 'success';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'level',
        'user_id',
        'message',
        'context',
        'sources',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model and attach event listeners.
     */
    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function (self $log): void {
            if (empty($log->user_id) && Auth::check()) {
                /** @phpstan-ignore-next-line */
                $log->user_id = Auth::id();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level'   => 'string',
            'message' => 'string',
            'context' => 'array',
            'sources' => 'array',
        ];
    }
}
