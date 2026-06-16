<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\InvitePolicy;
use Database\Factories\InviteFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use SaeedHosan\Tenancy\Concerns\HasTenant;

#[UsePolicy(InvitePolicy::class)]
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
        ];
    }
}
