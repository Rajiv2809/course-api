<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LessonsRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'set_id' => 'required|exists:sets,id',
            'contents' => 'required|array',
            'contents.*.type' => 'required|in:learn,quiz',
            'contents.*.content' => 'required|string',
            'contents.*.options' => 'sometimes|required_if:contents.*.type,quiz|array',
            'contents.*.options.*.option_text' => 'required_if:contents.*.type,quiz|string',
            'contents.*.options.*.is_correct' => 'required_if:contents.*.type,quiz|boolean'
            
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Invalid field(s) in request',
                'errors' => $validator->errors()
            ],400)
        );
    }
}
