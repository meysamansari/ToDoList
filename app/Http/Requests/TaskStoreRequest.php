<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStoreRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'nullable',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|in:todo,over_due,doing,done,cancelled',
            'category_id' => [
                'nullable',
                'exists:categories,id',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('deleted_at', null)
                        ->where('user_id', auth()->id());
                }),
            ],
        ];
    }
}
