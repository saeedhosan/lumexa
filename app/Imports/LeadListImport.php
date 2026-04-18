<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\LeadListStatus;
use App\Models\Lead;
use App\Models\LeadList;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class LeadListImport implements ToModel, WithHeadingRow
{
    public function __construct(
        public Lead $lead
    ) {}

    public function model(array $row): LeadList
    {
        $birthDate = null;
        if (isset($row['birth_of_date']) && $row['birth_of_date']) {
            try {
                $birthDate = Date::parse($row['birth_of_date']);
            } catch (Throwable) {
                $birthDate = null;
            }
        }

        return new LeadList([
            'lead_id'       => $this->lead->id,
            'first_name'    => $row['first_name'] ?? null,
            'last_name'     => $row['last_name']  ?? null,
            'phone'         => $row['phone']      ?? null,
            'email'         => $row['email']      ?? null,
            'address'       => $row['address']    ?? null,
            'city'          => $row['city']       ?? null,
            'state'         => $row['state']      ?? null,
            'zip_code'      => $row['zip_code']   ?? null,
            'birth_of_date' => $birthDate,
            'status'        => LeadListStatus::cleaned,
        ]);
    }
}
