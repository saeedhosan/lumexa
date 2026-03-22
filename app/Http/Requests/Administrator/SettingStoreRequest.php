<?php

declare(strict_types=1);

namespace App\Http\Requests\Administrator;

use App\Enums\Access;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SettingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::has(Access::administrator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'APP_NAME'    => 'required|string|max:255',
            'APP_URL'     => 'required|url',
            'APP_TITLE'   => 'required|string|max:255',
            'APP_ENV'     => 'required|string|in:local,production,testing',
            'APP_DEBUG'   => 'nullable|bool',
            'APP_LOGO'    => 'nullable|image|max:1024',
            'APP_FACICON' => 'nullable|image|max:512',
        ];
    }

    /**
     * Get attributes
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'APP_NAME'    => 'app name',
            'APP_URL'     => 'app URL',
            'APP_TITLE'   => 'app title',
            'APP_ENV'     => 'app environment',
            'APP_DEBUG'   => 'app debug',
            'APP_LOGO'    => 'app logo',
            'APP_FACICON' => 'app favicon',
        ];
    }
}
