<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\InviteFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SaeedHosan\Tenancy\Concerns\HasTenant;

#[UseFactory(InviteFactory::class)]
class Invite extends Model
{
    use HasFactory;
    use HasTenant;

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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
