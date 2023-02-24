<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['first_name' => "string", 'last_name' => "string", 'login' => "string", 'phone' => "string", 'password' => "string"])] public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'login' => 'required',
            'phone' => 'required|min:12',
            'password' => 'required|min:3|confirmed',
        ];
    }
}
