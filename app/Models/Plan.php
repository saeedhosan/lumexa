<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;

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
     * The products that belong to the plan.
     *
     * @return BelongsToMany<Product, self>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'plan_products');
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
