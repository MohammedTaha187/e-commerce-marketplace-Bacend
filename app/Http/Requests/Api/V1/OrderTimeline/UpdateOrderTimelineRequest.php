<?php

namespace App\Http\Requests\Api\V1\OrderTimeline;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderTimelineRequest extends FormRequest
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
            'status' => 'sometimes|string',
            'description' => 'nullable|string',
        ];
    }
}
