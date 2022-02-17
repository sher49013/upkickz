<?php

namespace App\Http\Requests\AuthRequests;

use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponser;

class AppUserSignupRequest extends FormRequest
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
            'name' => 'required|max:250',
            'email' => 'required|email|max:200|unique:users',
            'password' => 'required|min:8|confirmed',
            'device_token' => 'required',
            'device_id' => 'required',
            'push_platform_id' => 'required'

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Length must be less than 250 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Length must be less than 200 characters',
            'email.unique' => 'This email is already associated with another account',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password and confirm password does not match',
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
