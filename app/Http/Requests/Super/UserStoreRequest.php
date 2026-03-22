<?php

declare(strict_types=1);

namespace App\Http\Requests\Super;

use App\Enums\Access;
use App\Enums\UserStatus;
use App\Enums\UserType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::has(Access::super);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'           => ['required', 'confirmed', Password::defaults()],
            'status'             => ['required', 'string', 'in:'.implode(',', UserStatus::values())],
            'type'               => ['required', 'string', 'in:'.implode(',', UserType::values())],
            'current_company_id' => ['nullable', 'exists:companies,id'],
        ];
    }
}
