<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SaeedHosan\Useful\Models\Concerns\HasSlug;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

#[UseFactory(PlanFactory::class)]
class Plan extends Model
{
    use HasFactory;
    use HasSlug;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'is_default',
        'trial_days',
        'max_users',
        'price_monthly',
        'price_yearly',
        'currency',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly',
        'features',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'is_active'     => 'boolean',
        'is_default'    => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly'  => 'decimal:2',
        'features'      => 'array',
        'settings'      => 'array',
    ];

    /**
     * Get spatie log name
     */
    public function getLogNameToUse(): ?string
    {
        return class_basename($this);
    }

    /**
     * The services that belong to the plan.
     *
     * @return BelongsToMany<Service, self>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'plan_services');
    }

    /**
     * The companies that belong to the plan.
     *
     * @return BelongsToMany<Company, self>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_plan');
    }
}
