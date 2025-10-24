<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
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
            'title' => ['string', 'required'],
            'description' => ['string', 'required'],
            'user_id' => ['numeric', 'required', 'exists:users,id'],
            'due_date' => ['date', 'required'],
            'parent_task' => ['numeric', 'exists:tasks,id', 'nullable']
        ];
    }
}
