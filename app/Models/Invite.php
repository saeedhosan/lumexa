<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\InvitePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(InvitePolicy::class)]
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
