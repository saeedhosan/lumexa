<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'           => ['required', 'confirmed'],
            'status'             => ['required', 'string', 'in:'.implode(',', UserStatus::values())],
            'current_company_id' => ['nullable', 'exists:companies,id'],
        ];

        if ($user->isSuper()) {
            $rules['type'] = ['required', 'string', 'in:'.implode(',', UserType::values())];
        }

        return $rules;
    }
}
