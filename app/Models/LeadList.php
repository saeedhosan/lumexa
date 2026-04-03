<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LeadLestStatus;
use Database\Factories\LeadListFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
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
     * Get the lead who owns this model.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
