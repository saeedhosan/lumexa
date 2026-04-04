<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadLestStatus;
use Database\Factories\LeadListFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(LeadListFactory::class)]
class LeadList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lead_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip_code',
        'birth_of_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'id'            => 'int',
        'first_name'    => 'string',
        'last_name'     => 'string',
        'phone'         => 'string',
        'email'         => 'string',
        'address'       => 'string',
        'city'          => 'string',
        'state'         => 'string',
        'zip_code'      => 'string',
        'birth_of_date' => 'date',
        'status'        => LeadLestStatus::class,
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['name', 'full_address'];

    /**
     * Get the lead who owns this model.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the full name by first_name and last_name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(fn (): string => mb_trim(sprintf('%s %s', $this->first_name, $this->last_name)));
    }

    /**
     * Get the full address
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(get: fn (): string => implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
        ])));
    }
}
