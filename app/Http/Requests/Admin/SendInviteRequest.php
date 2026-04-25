<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SendInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer'],
            'email'      => ['required', 'email', 'max:255'],
            'role'       => ['required', 'string', 'in:'.Company::ROLE_ADMIN.','.Company::ROLE_CUSTOMER],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $email     = $this->input('email');
            $companyId = $this->input('company_id');

            if ($email && $companyId) {
                $existingUser = User::query()->where('email', $email)->first();

                if (! $existingUser) {
                    $validator->errors()->add('email', 'User not found. Only existing users can be added to a company.');

                    return;
                }

                if ($existingUser->companies()->where('company_id', $companyId)->exists()) {
                    $validator->errors()->add('email', 'This user already has access to this company.');
                }
            }
        });
    }
}
