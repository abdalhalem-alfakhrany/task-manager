<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'sometimes'],
            'description' => ['string', 'sometimes'],
            'user_id' => ['numeric', 'sometimes', 'exists:users,id'],
            'due_date' => ['date', 'sometimes'],
            'parent_task' => ['numeric', 'exists:tasks,id', 'sometimes']
        ];
    }
}
