<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'name' => ['required'],
                // The Rule::in method is used to validate that the value of the type field is one of the specified values
                'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
                // The email field is required and must be a valid email address
                'email' => ['required', 'email'],
                'address' => ['required'],
                'city' => ['required'],
                'state' => ['required'],
                'postalCode' => ['required'],
            ];
        }

        if ($method == 'PATCH') {

            // Add the 'sometimes' rule to each field to ensure that the field is only required when it is present in the request
            return [
                'name' => ['sometimes', 'required'],
                'type' => ['sometimes', 'required', Rule::in(['I', 'B', 'i', 'b'])],
                'email' => ['sometimes', 'required', 'email'],
                'address' => ['sometimes', 'required'],
                'city' => ['sometimes', 'required'],
                'state' => ['sometimes', 'required'],
                'postalCode' => ['sometimes', 'required'],
            ];
        }

        return [];
    }
    protected function prepareForValidation()
    {
        // If the postalCode field is present in the request, merge it into the request as postal_code to match the column name in the database
        if ($this->postalCode) {
            $this->merge([
                'postal_code' => $this->postalCode,
            ]);}
    }
}
