<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        $rules = [
            'title' => 'required|string|max:255',
            'publication_year' => 'required|integer|max:' . date('Y'),
            'author_ids' => 'required|array',
            'author_ids.*' => 'integer|exists:authors,id'
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] = 'nullable|string|max:255';
            $rules['publication_year'] = 'nullable|string|max:255';
            $rules['author_ids'] = 'nullable|array';
        }

        return $rules;
    }
}
