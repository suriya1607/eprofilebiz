<?php

namespace App\Repositories;

use App\Models\UserSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class UserRepository
 */
class UserSettingRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return UserSetting::class;
    }

    public function update($input, $id)
    {
        $inputArr = Arr::except($input, ['_token', 'sectionName']);
        $inputArr['subscription_model_time'] = isset($inputArr['subscription_model_time']) ? $inputArr['subscription_model_time'] : '5';
        $inputArr['enable_pwa'] = isset($inputArr['enable_pwa']) ? '1' : '0';
        $inputArr['enable_attachment_for_inquiry'] = isset($inputArr['enable_attachment_for_inquiry']) ? '1' : '0';
        $inputArr['ask_details_before_downloading_contact'] = isset($inputArr['ask_details_before_downloading_contact']) ? '1' : '0';
        $inputArr['product_order_send_mail_customer'] = isset($inputArr['product_order_send_mail_customer']) ? '1' : '0';
        $inputArr['product_order_send_mail_user'] = isset($inputArr['product_order_send_mail_user']) ? '1' : '0';

        foreach ($inputArr as $key => $value) {
            /** @var UserSetting $setting */
            $setting = UserSetting::where('key', $key)->where('user_id', $id)->first();
            if (! $setting) {
                $setting = UserSetting::create([
                    'user_id' => $id,
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                $setting->update(['value' => $value]);
            }
            if (in_array($key, ['pwa_icon'])) {
                $this->fileUpload($setting, $value);
                continue;
            }
        }

        return $setting;
    }

    public function paymentMethodUpdate($input, $id)
    {
        $inputArr = Arr::except($input, ['_token', 'sectionName']);
        $inputArr['stripe_enable'] = isset($inputArr['stripe_enable']) ? $inputArr['stripe_enable'] : '0';
        $inputArr['paytack_enable'] = isset($inputArr['paytack_enable']) ? $inputArr['paytack_enable'] : '0';
        $inputArr['flutterwave_enable'] = isset($inputArr['flutterwave_enable']) ? $inputArr['flutterwave_enable'] : '0';
        $inputArr['phonepe_enable'] = isset($inputArr['phonepe_enable']) ? $inputArr['phonepe_enable'] : '0';
        $inputArr['paypal_enable'] = isset($inputArr['paypal_enable']) ? $inputArr['paypal_enable'] : '0';
        $inputArr['rozorpay_enable'] = isset($inputArr['rozorpay_enable']) ? $inputArr['rozorpay_enable'] : '0';
        $inputArr['manual_payment_guide'] = isset($inputArr['manual_payment_guide']) ? $inputArr['manual_payment_guide'] : null;
        $inputArr['manually_payment'] = isset($inputArr['manually_payment']) ? $inputArr['manually_payment'] : '0';
        $inputArr['notifation_enable'] = isset($inputArr['notifation_enable']) ? $inputArr['notifation_enable'] : '0';
        $inputArr['payfast_enable'] = isset($inputArr['payfast_enable']) ? $inputArr['payfast_enable'] : '0';

        foreach ($inputArr as $key => $value) {
            /** @var UserSetting $setting */
            $setting = UserSetting::where('key', $key)->where('user_id', $id)->first();
            if (! $setting) {
                $setting = UserSetting::create([
                    'user_id' => $id,
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                $setting->update(['value' => $value]);
            }
        }
        return $setting;
    }

    public function updateAPI($input, $id)
    {
        try {
            DB::beginTransaction();

            $inputArr = Arr::except($input, ['_token', 'sectionName']);

            // Add debug statement
            info('Input Array: ' . json_encode($inputArr));

            // Your existing logic...

            foreach ($inputArr as $key => $value) {
                /** @var UserSetting $setting */
                $setting = UserSetting::where('key', $key)->where('user_id', $id)->first();
                if (!$setting) {
                    UserSetting::create([
                        'user_id' => $id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                } else {
                    $setting->update(['value' => $value]);
                }
                if ($setting) {
                    if (in_array($key, ['pwa_icon'])) {
                        $this->fileUpload($setting, $value);
                        continue;
                    }
                }

                // Add debug statement
                info("Key: $key, Value: $value, User ID: $id, Setting: " . json_encode($setting));
            }

            DB::commit();

            return $setting;
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception
            info('Exception: ' . $e->getMessage());

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }


    public function fileUpload($setting, $file)
    {

        // Delete old media
        if ($setting->hasMedia(UserSetting::LOGO_PATH)) {
            $oldMedia = $setting->getFirstMedia(UserSetting::LOGO_PATH)->getFullUrl();
            $setting->update(['value' => $oldMedia]);
            $setting->newClearMediaCollection($file, UserSetting::LOGO_PATH);
        }

        $media = $setting->newAddMedia($file)->toMediaCollection(UserSetting::LOGO_PATH);
        $setting->update(['value' => $media->getFullUrl()]);
    }
}
