<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponCodeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'coupon_name' => 'required|max:50|regex:/[a-zA-Z]/|unique:coupon_codes,coupon_name,' . request()->id,
            'type' => 'required',
            'discount' => 'required|numeric|min:1',
            'expire_at' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_name.regex' => __('messages.coupon_code.coupon_name_regex'),
            'discount.min' => __('messages.coupon_code.coupon_discount_validation'),
        ];
    }
}
