<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Plan::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name'                    => ['required', 'string', 'max:255'],
            'description'             => ['nullable', 'string'],
            'is_active'               => ['boolean'],
            'is_default'              => ['boolean'],
            'trial_days'              => ['nullable', 'integer', 'min:0'],
            'max_users'               => ['nullable', 'integer', 'min:1'],
            'price_monthly'           => ['nullable', 'numeric', 'min:0'],
            'price_yearly'            => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['nullable', 'string', 'max:10'],
            'stripe_price_id_monthly' => ['nullable', 'string', 'max:255'],
            'stripe_price_id_yearly'  => ['nullable', 'string', 'max:255'],
            'features'                => ['nullable', 'json'],
            'settings'                => ['nullable', 'json'],
        ];
    }
}
