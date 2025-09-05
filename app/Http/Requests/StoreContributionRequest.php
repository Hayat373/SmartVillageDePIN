<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContributionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // All users can contribute
    }

    public function rules()
    {
        return [
            'resource_type' => 'required|in:energy,bandwidth,water,storage,computing',
            'amount' => 'required|numeric|min:0.01|max:1000000',
            'village_name' => 'required|string|max:255',
            'contribution_date' => 'sometimes|date',
        ];
    }

    public function messages()
    {
        return [
            'resource_type.required' => 'Please select a resource type',
            'resource_type.in' => 'Invalid resource type selected',
            'amount.required' => 'Please specify the amount contributed',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Amount must be at least 0.01',
            'amount.max' => 'Amount cannot exceed 1,000,000',
            'village_name.required' => 'Please specify your village name',
        ];
    }
}