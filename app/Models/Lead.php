<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadStatus;
use App\Models\Scopes\CompanyScope;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SaeedHosan\Additions\Models\Concerns\HasUuid;

#[UseFactory(LeadFactory::class)]
class Lead extends Model
{
    use HasFactory;
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'company_id',
        'title',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id'         => 'int',
        'uuid'       => 'string',
        'title'      => 'string',
        'status'     => LeadStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who owns this model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company that associated with model.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the available lead list for this model
     */
    public function leadList(): HasMany
    {
        return $this->hasMany(LeadList::class);
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope());
    }
}
