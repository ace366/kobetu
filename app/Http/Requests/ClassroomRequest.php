<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認証はルート側で担保。ここは true にしておく。
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('classroom')?->id ?? null;

        return [
            'code'        => ['required','string','max:20', Rule::unique('classrooms','code')->ignore($id)],
            'name'        => ['required','string','max:100'],
            'postal_code' => ['nullable','string','max:8','regex:/^\d{3}-?\d{4}$/'], // 123-4567 or 1234567
            'address'     => ['nullable','string','max:255'],
            'tel'         => ['nullable','string','max:20','regex:/^0\d{1,4}-?\d{1,4}-?\d{3,4}$/'],
            'email'       => ['nullable','string','max:191','email'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code'        => '教室番号',
            'name'        => '教室名',
            'postal_code' => '郵便番号',
            'address'     => '住所',
            'tel'         => '電話番号',
            'email'       => 'メールアドレス',
        ];
    }
}
