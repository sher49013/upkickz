<?php

namespace App\Http\Requests\AuthRequests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponser;

class AppUserLoginRequest extends FormRequest
{
    use ApiResponser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:200',
            'password' => 'required|min:8',
            'device_token' => 'required',
            'device_id' => 'required',
            'push_platform_id' => 'required'

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Length must be less than 200 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'device_token.required' => 'Device token is required',
            'device_id.required' => 'Device id is required',
            'push_platform_id.required' => 'Push notification platform is required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse('Validation failed', $validator->errors(), 422, $validator->errors()));
    }
}
