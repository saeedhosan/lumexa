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
            'first_name'    => $this->sanitizeCsvValue($row['first_name'] ?? null),
            'last_name'     => $this->sanitizeCsvValue($row['last_name'] ?? null),
            'phone'         => $this->sanitizeCsvValue($row['phone'] ?? null),
            'email'         => $this->sanitizeCsvValue($row['email'] ?? null),
            'address'       => $this->sanitizeCsvValue($row['address'] ?? null),
            'city'          => $this->sanitizeCsvValue($row['city'] ?? null),
            'state'         => $this->sanitizeCsvValue($row['state'] ?? null),
            'zip_code'      => $this->sanitizeCsvValue($row['zip_code'] ?? null),
            'birth_of_date' => $birthDate,
            'status'        => LeadListStatus::cleaned,
        ]);
    }

    private function sanitizeCsvValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        if (str_starts_with($value, '=') || str_starts_with($value, '+') || str_starts_with($value, '-') || str_starts_with($value, '@')) {
            return "'".$value;
        }

        return $value;
    }
}
