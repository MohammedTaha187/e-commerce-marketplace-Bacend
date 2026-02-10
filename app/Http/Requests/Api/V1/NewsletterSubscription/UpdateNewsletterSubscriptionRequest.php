<?php

namespace App\Http\Requests\Api\V1\NewsletterSubscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsletterSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_subscribed' => 'required|boolean',
        ];
    }
}
