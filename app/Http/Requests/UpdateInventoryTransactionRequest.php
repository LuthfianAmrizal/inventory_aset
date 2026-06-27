<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_type_id' => [
                'required',
                Rule::exists('transaction_types', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')
                        ->where(function ($q) {
                            $q->where('is_active', true)
                              ->orWhere('id', $this->route('inventory_transaction')?->transaction_type_id);
                        });
                }),
            ],
            'budget' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'realization' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'transaction_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'evidence' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
