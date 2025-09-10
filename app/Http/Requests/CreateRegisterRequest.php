<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\RecaptchaV3Async;
use App\Services\RecaptchaV2Async;
use Illuminate\Support\Facades\Http;


class CreateRegisterRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = User::$rules;
        if (getSuperAdminSettingValue('phone_number_required') == 1) {
            $rules['contact'] = 'required';
        }
        $rules['password'] = 'required|same:password_confirmation|min:8';
        $rules['term_policy_check'] = 'required';
        $rules['contact'] = [
            'required',
            function ($attribute, $value, $fail) {
                $region = request('region_code');
                if ($region == '91') {
                    // India: 10 digits, starts with 6-9
                    if (!preg_match('/^[6-9]\d{9}$/', $value)) {
                        $fail('Invalid Indian mobile number. It must be 10 digits and start with 6-9.');
                    }
                } else {
                    // E.164 International: optional +, 8-15 digits, does not start with zero
                    if (!preg_match('/^\+?[1-9]\d{7,14}$/', $value)) {
                        $fail('Invalid phone number for the selected country.');
                    }
                }
            }
        ];
        if (getSuperAdminSettingValue('captcha_enable')) {
            $rules['g-recaptcha-response'] = ['required', function ($attribute, $value, $fail) {
                if(getRecaptchaVersion() == 1) {
                            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret'   => config('services.hcaptcha.secret'), // store in config/services.php
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);
                    // if (!verifyRecaptcha($value)) {
                    //     $fail(__('messages.placeholder.invalid_captcha'));
                    // }
                } else {
                    $recaptchaService = new RecaptchaV3Async();
                    $promise = $recaptchaService->verifyAsync($value);
                    $promise->then(function ($isValid) use ($fail) {
                        if (!$isValid) {
                            $fail(__('messages.placeholder.invalid_captcha'));
                        }
                    })->wait();
                }
            }];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'term_policy_check.required' => __('messages.placeholder.agree_term'),
            'g-recaptcha-response.required' =>  __('messages.placeholder.required_captcha'),
        ];
    }
}
