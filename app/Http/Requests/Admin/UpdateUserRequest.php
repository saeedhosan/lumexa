<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', User::class);
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name'               => ['required', 'string', 'max:255'],
            'password'           => ['nullable', 'confirmed', 'min:8'],
            'status'             => ['required', 'string', 'in:'.implode(',', UserStatus::values())],
            'current_company_id' => ['nullable', 'integer'],
        ];

        if ($user->isSuper()) {
            $rules['type'] = ['required', 'string', 'in:'.implode(',', UserType::values())];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $companyId = $this->input('current_company_id');

            if ($companyId) {
                $user = $this->user();

                if ($user->isSuper()) {
                    if (! \App\Models\Company::where('id', $companyId)->exists()) {
                        $validator->errors()->add('current_company_id', 'The selected company is invalid.');
                    }
                } else {
                    if (! $user->companies()->where('companies.id', $companyId)->exists()) {
                        $validator->errors()->add('current_company_id', 'You do not have access to this company.');
                    }
                }
            }
        });
    }
}
