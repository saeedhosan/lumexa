<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'title'       => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo'        => ['nullable', 'string', 'url', 'max:500'],
            'country'     => ['nullable', 'string', 'max:255'],
            'language'    => ['nullable', 'string', 'max:10'],
            'timezone'    => ['nullable', 'string', 'max:100'],
            'currency'    => ['nullable', 'string', 'max:10'],
        ];
    }
}
