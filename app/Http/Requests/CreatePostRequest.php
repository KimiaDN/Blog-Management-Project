<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'title' => ['string', 'required', 'min:10', 'max:150'],
            'body' => ['string', 'required', 'min:10', 'max:65535'],
            'tags' => ['array', 'required', 'min:1', 'max:200'],
            'tags.*' => ['string', 'min:2', 'max:50'],
        ];
    }
}
