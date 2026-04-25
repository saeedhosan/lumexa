<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddCompanyMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'string', 'in:admin,customer'],
            'status'   => ['nullable', 'string', 'in:'.implode(',', UserStatus::values())],
            'type'     => ['nullable', 'string', 'in:'.implode(',', UserType::values())],
        ];
    }
}
