<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', \App\Models\User::class);
    }

    public function rules(): array
    {

        return [
            'name'               => ['required', 'string', 'max:255'],
            'password'           => ['nullable', 'confirmed'],
            'status'             => ['required', 'string', 'in:'.implode(',', UserStatus::values())],
            'type'               => ['required', 'string', 'in:'.implode(',', UserType::values())],
            'current_company_id' => ['nullable', 'exists:companies,id'],
        ];
    }
}
