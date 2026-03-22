<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SaeedHosan\Additions\Models\Concerns\HasUuid;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use HasUuid;

    public const DEFAULT_VERSION = '1.0.0';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'icon',
        'logo',
        'code',
        'about',
        'is_active',
        'is_default',
        'version',
        'provider',
        'features',
        'settings',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'uuid'       => 'string',
        'name'       => 'string',
        'slug'       => 'string',
        'icon'       => 'string',
        'logo'       => 'string',
        'code'       => 'string',
        'about'      => 'string',
        'is_active'  => 'boolean',
        'is_default' => 'boolean',
        'version'    => 'string',
        'provider'   => 'string',
        'features'   => 'array',
        'settings'   => 'array',
    ];

    /**
     * The companies that use this product.
     *
     * @return BelongsToMany<Company>
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_product')->withTimestamps();
    }

    /**
     * The plans that include this product.
     *
     * @return BelongsToMany<Plan>
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_products')->withTimestamps();
    }

    /**
     * User who created the product.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the product.
     *
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope active products.
     */
    protected function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope default product.
     */
    protected function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }
}
