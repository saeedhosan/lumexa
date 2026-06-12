<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Concerns\HasUserType;
use App\Policies\UserPolicy;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

#[UsePolicy(UserPolicy::class)]
#[UseFactory(UserFactory::class)]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasUserType;
    use LogsActivity;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
        'current_company_id',
        'onboarded_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get spatie log name
     */
    public function getLogNameToUse(): ?string
    {
        return class_basename($this);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Primary company (users.company_id).
     *
     * Used when a user has a default or active company.
     *
     * @return BelongsTo<Company, $this>
     */
    public function currentCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    /**
     * All companies the user belongs to via pivot table.
     *
     * @return BelongsToMany<Company>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')->withPivot('role')->withTimestamps();
    }

    /**
     * Determine if user is an admin of the given company.
     */
    public function isAdminOf(Company $company): bool
    {
        return $this->companies()
            ->where('companies.id', $company->id)
            ->wherePivot('role', Company::ROLE_ADMIN)
            ->exists();
    }

    /**
     * Determine if user has acces t the given company.
     */
    public function belongsToCompany(Company $company): bool
    {
        return $this->companies()
            ->whereKey($company->id)
            ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'onboarded_at'      => 'datetime',
            'password'          => 'hashed',
            'status'            => UserStatus::class,
            'type'              => UserType::class,
        ];
    }
}
