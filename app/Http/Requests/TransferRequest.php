<?php

namespace App\Http\Requests;

use App\Rules\CardNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'source' => ['required', 'numeric', new CardNumberRule()],
            'destination' => ['required', 'numeric', new CardNumberRule(), 'different:source'],
            'amount' => ['required', 'numeric', 'gte:' . config('card.minimum_amount'), 'lte:' . config('card.maximum_amount')],
        ];
    }
}
