<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRegisterRequest;
use App\Mail\VerifyMail;
use App\Mail\NewUserRegisteredMail;
use App\Models\AffiliateUser;
use App\Models\MultiTenant;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RegisteredUserController extends AppBaseController
{
    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $registerImage = Setting::where('key', 'register_image')->value('value');

        if (!getSuperAdminSettingValue('register_enable')) {
            return redirect()->back();
        }
        $sharedUser = null;
        if ($request->has('id')) {
            $sharedUser = User::find($request->id);
        }

        return view('auth.register', ['registerImage' => $registerImage,'sharedUser' => $sharedUser,]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateRegisterRequest $request): RedirectResponse
    {
        $referral_code = $request->input('referral-code');
        $referral_user = '';
        if ($referral_code) {
            $referral_user = User::where('affiliate_code', $referral_code)->first();
        }
        if($request->shareduser){
            return $this->updateinviteuser($request);
        }
        try {
            DB::beginTransaction();

            $tenant = MultiTenant::create(['tenant_username' => $request->first_name]);
            $userDefaultLanguage = Setting::where('key', 'user_default_language')->first()->value ?? 'en';

            $email = $request->email;
            $emailParts = explode('@', $email);
            $emailDomain = strtolower(end($emailParts));

            $tld = strtolower(substr(strrchr($emailDomain, '.'), 1));

            $blockedSetting = Setting::where('key', 'block_email_domains')->first();
            $blockedDomains = [];

            if ($blockedSetting && $blockedSetting->value) {
                $blockedDomains = explode(',', strtolower($blockedSetting->value));
                $blockedDomains = array_map(function ($val) {
                    return ltrim(trim($val), '.');
                }, $blockedDomains);
            }

            // Check if the TLD is in the blocked list
            if (in_array($tld, $blockedDomains)) {
                Flash::error(__('messages.placeholder.registration_using_email_domain_not_allowed', [
                    'domain' => '.' . $tld,
                ]));
                return redirect()->back()->withInput();
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'region_code' =>  $request->region_code,
                'contact' =>  $request->contact,
                'language' => $userDefaultLanguage,
                'steps' => 0,
                'email_verified_at' => getSuperAdminSettingValue('user_verified_email') == 0 ? Carbon::now() : null,
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
                'affiliate_code' => generateUniqueAffiliateCode(),
                'user_type' => $request->user_type,
                'company_type' => $request->has('company_type') ? 1 : 0
            ])->assignRole(Role::ROLE_ADMIN);

            $plan = Plan::whereIsDefault(true)->first();
            $customFields = $plan->planCustomFields;
            if ($plan->custom_select == 1 && $customFields->isNotEmpty()) {
                  $vcardsOfNo = $customFields->first()->custom_vcard_number;
                  $PlanPrice = $customFields->first()->custom_vcard_price;
              } else {
                  $vcardsOfNo = $plan->no_of_vcards;
                  $PlanPrice = $plan->price;
              }
            Subscription::create([
                'plan_id' => $plan->id,
                'plan_amount' => $plan->price,
                'plan_frequency' => $plan->frequency,
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addDays($plan->trial_days),
                'trial_ends_at' => Carbon::now()->addDays($plan->trial_days),
                'status' => Subscription::ACTIVE,
                'tenant_id' => $tenant->id,
                'no_of_vcards' => $vcardsOfNo,
            ]);

            if ($referral_user) {
                $affiliateUser = new AffiliateUser();
                $affiliateUser->affiliated_by = $referral_user->id;
                $affiliateUser->user_id = $user->id;
                $affiliateUser->amount = 0;
                $affiliateUser->save();
            }

            DB::commit();

            $token = Password::getRepository()->create($user);
            $data['url'] = config('app.url') . '/verify-email/' . $user->id . '/' . $token;
            $data['user'] = $user;

            if (getSuperAdminSettingValue('user_verified_email')) {
                Mail::to($user->email)->send(new VerifyMail($data));
            }
            if (getSuperAdminSettingValue('register_mail')) {
                Mail::to(getSuperAdminSettingValue('email'))->send(new NewUserRegisteredMail($user));
            }
            if (getSuperAdminSettingValue('user_verified_email')) {
            Flash::success(__('messages.placeholder.registered_success'));
            }else{
            Flash::success(__('messages.placeholder.user_registered_success'));
            }

            return redirect(route('login'));
        } catch (\Exception $e) {
            DB::rollBack();

            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function checkEmail($email): JsonResponse {
        $user = User::where('email', $email)->first();
        if ($user) {
            return $this->sendError('Email already exists.', 200);
        }
        return $this->sendSuccess('Email not exists.');
    }
     public function updateinviteuser($request){
        $user = User::find($request->shareduser);
        if($user){
            $tenant = MultiTenant::create(['tenant_username' => $request->first_name]);
            $userDefaultLanguage = Setting::where('key', 'user_default_language')->first()->value ?? 'en';
            $user->update([
            'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                // 'email' => $request->email,
                'region_code' =>  $request->region_code,
                'contact' =>  $request->contact,
                'language' => $userDefaultLanguage,
                'steps' => 1,
                'email_verified_at' =>  Carbon::now(),
                'password' => Hash::make($request->password),
                'tenant_id' => $tenant->id,
        ]);
        $plan = Plan::whereIsDefault(true)->first();
         $vcardOfNo = $plan->no_of_vcards;
                  $planPrice = $plan->price;

                  if ($plan->custom_select == 1) {
                      $customFields = $plan->planCustomFields;
                      if ($customFields->isNotEmpty()) {
                          $vcardOfNo = $customFields->first()->custom_vcard_number;
                          $planPrice = $customFields->first()->custom_vcard_price;
                      }
                  }
                $subscription = new Subscription();
                $subscription->plan_id = $plan->id; // no specific plan
                $subscription->starts_at = Carbon::now();
                $subscription->ends_at = Carbon::now()->addYears(100); // unlimited
                $subscription->plan_amount = 0; // price 0
                $subscription->plan_frequency = Plan::UNLIMITED;
                $subscription->trial_ends_at = Carbon::now()->addYears(100);
                $subscription->no_of_vcards = 0; // no vcards
                $subscription->tenant_id = $tenant->id;
                $subscription->status = Subscription::ACTIVE;
                $subscription->saveQuietly();
                Auth::login($user);

        return redirect()->route('vcards.index');
        }
        Flash::error('User not found.');
    return redirect()->back();

     }
}
