<?php

declare(strict_types=1);

namespace App\Models;

use Additions\Eloquent\Concerns\HasBelongsToOne;
use Additions\Models\Concerns\HasSlug;
use Additions\Models\Concerns\HasUuid;
use App\Observers\CompanyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $logo
 * @property string|null $title
 * @property string|null $description
 * @property string|null $language
 * @property string|null $timezone
 * @property string|null $currency
 * @property string|null $country
 * @property bool|null $status
 * @property bool|null $is_public
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property array<string, mixed>|null $config
 * @property-read User|null $creator
 * @property-read User|null $updator
 */
#[ObservedBy(CompanyObserver::class)]
class Company extends Model
{
    /** @phpstan-ignore-next-line */
    use HasBelongsToOne, HasSlug, HasUuid;

    /** @use HasFactory<\Illuminate\Database\Eloquent\Factories\Factory> */
    use HasFactory;

    /**
     * The model support roles
     */
    public const ROLE_CUSTOMER = 'customer';

    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'logo',
        'title',
        'config',
        'description',
        'language',
        'timezone',
        'currency',
        'country',
        'is_active',
        'plan_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
        'is_msp'    => 'boolean',
    ];

    /**
     * The user who created this company record.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The user who last updated this company record.
     *
     * @return BelongsTo<User, $this>
     */
    public function updator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all user for this compoany
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user')->withPivot('role')->withTimestamps();
    }

    /**
     * Get admin users
     *
     * @return BelongsToMany<User>
     */
    public function admins(): BelongsToMany
    {
        return $this->users()->wherePivot('role', self::ROLE_ADMIN);
    }

    /**
     * Get customers
     *
     * @return BelongsToMany<User>
     */
    public function customers(): BelongsToMany
    {
        return $this->users()->wherePivot('role', self::ROLE_CUSTOMER);
    }

    /**
     * Products assigned to the company.
     *
     * @return BelongsToMany<Product>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'company_product')->withTimestamps();
    }

    /**
     * The plan assigned to the company.
     *
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
