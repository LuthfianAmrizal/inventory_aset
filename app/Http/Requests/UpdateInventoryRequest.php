<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryRequest extends FormRequest
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
            'item_id' => [
                'required',
                Rule::exists('items', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at')
                        ->where(function ($q) {
                            $q->where('is_active', true)
                              ->orWhere('id', $this->route('inventory')?->item_id);
                        });
                }),
            ],
            'qty' => ['required', 'integer', 'min:0', 'max:100000000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('inventories', 'barcode')->ignore($this->route('inventory')),
            ],
            'expired_date' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(['available', 'reserved', 'damaged', 'lost', 'inactive'])],
            'description' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
