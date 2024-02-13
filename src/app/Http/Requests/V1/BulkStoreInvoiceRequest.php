<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Checks if the user making this request has the authorization to do so.
     * In this case, it's set to always return true, meaning there's no specific authorization logic applied.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Defines validation rules that apply when this request is made.
     * It sets requirements for each field in a bulk operation, expecting an array of items,
     * each with customerId, amount, status, billedDate, and paidDate.
     * It ensures customerId is an integer, amount is numeric, status is one of specified values,
     * billedDate and paidDate follow a specific date format, with paidDate being nullable.
     */
    public function rules(): array
    {
        return [
            '*.customerId' => ['required', 'integer'],
            '*.amount'  => ['required', 'numeric'],
            '*.status'  => ['required', Rule::in(['B', 'P', 'V', 'b', 'p', 'v'])],
            '*.billedDate'  => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    /**
     * Prepares the incoming request data for validation.
     * This method is automatically called before the rules are applied.
     * It transforms keys from camelCase to snake_case to match database column naming conventions,
     * ensuring the data is in the correct format for validation and later processing.
     */
    protected function prepareForValidation()
    {
        $data = [];

        // Iterates over each item in the request, assuming it's an array of objects.
        // Transforming camelCase keys to snake_case for database consistency,
        // ensuring missing fields default to null to avoid undefined index errors.
        foreach ($this->toArray() as $obj) {
            // Converts camelCase keys to snake_case for consistency with database fields.
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            // Adds the transformed object to the data array.
            $data[] = $obj;
        }

        // Merges the transformed data back into the request, replacing the original input.
        $this->merge($data);
    }
}
