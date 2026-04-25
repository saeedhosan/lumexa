<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Company;

class UpdateCompany
{
    public function handle(Company $company, array $data): Company
    {
        $company->update([
            'name'        => $data['name'],
            'title'       => $data['title']       ?? null,
            'description' => $data['description'] ?? null,
            'logo'        => $data['logo']        ?? null,
            'country'     => $data['country']     ?? null,
            'language'    => $data['language']    ?? null,
            'timezone'    => $data['timezone']    ?? null,
            'currency'    => $data['currency']    ?? null,
            'updated_by'  => auth()->id(),
        ]);

        return $company->fresh();
    }
}
