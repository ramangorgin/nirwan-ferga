<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->route('user')?->id ?? null;

        return [
            'name' => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','max:255',"unique:users,email,{$id}"],
            'phone' => ['sometimes','nullable','string','max:30',"unique:users,phone,{$id}"],

            'password' => ['sometimes','nullable','string','min:6','confirmed'],

            'role' => ['sometimes','in:admin,teacher,student'],
            'status' => ['sometimes','in:active,deactive,ban,suspended,pending'],

            'gender' => ['sometimes','nullable','in:male,female,other'],
            'birthdate' => ['sometimes','nullable','string','max:32'],
            'country' => ['sometimes','nullable','string','max:255'],
            'city' => ['sometimes','nullable','string','max:255'],
            'timezone' => ['sometimes','string','max:64'],

            'avatar' => ['sometimes','nullable','image','max:5120'],
        ];
    }
}