<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $company_id
 * @property int $invited_by
 * @property string $email
 * @property string $role
 * @property \Carbon\Carbon $accepted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Company $company
 * @property-read User $inviter
 */
class Invite extends Model
{
    protected $fillable = [
        'company_id',
        'invited_by',
        'email',
        'role',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
