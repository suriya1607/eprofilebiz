<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\ChangePasswordMail;
use App\Models\AffiliateUser;
use App\Models\EmailVerification;
use App\Models\MultiTenant;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vcard;
use App\Models\Product;
use App\Models\VcardBlog;
use App\Models\NfcOrders;
use App\Models\NfcOrderTransaction;
use App\Models\Testimonial;
use App\Models\Withdrawal;
use App\Models\WithdrawalTransaction;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Modules\SlackIntegration\Entities\SlackIntegration;
use Modules\SlackIntegration\Notifications\SlackNotification;

class UserController extends AppBaseController
{
    public UserRepository $userRepo;

    /**
     * UserController constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): \Illuminate\View\View
    {
        return view('users.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): \Illuminate\View\View
    {
        return view('users.create');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $input = $request->all();
        $this->userRepo->store($input);

        // Send Slack Notification after successful payment initialization
        $userFullName = implode(' ', [$request->first_name, $request->last_name]);

        if (moduleExists('SlackIntegration')) {
            $slackIntegration = SlackIntegration::first();

            if ($slackIntegration && $slackIntegration->new_user_registration_notification == 1 && !empty($slackIntegration->webhook_url)) {
                $message = "🔔 New User Registered !!\n{$userFullName} registered to " . getAppName() . " successfully.";
                $slackIntegration->notify(new SlackNotification($message));
            }
        }

        Flash::success(__('messages.flash.user_create'));

        return redirect(route('users.index'));
    }

    /**
     * @return Application|Factory|View
     */
    public function show(Request $request, User $user): \Illuminate\View\View
    {
        if (! empty($user) && $user->getRoleNames()[0] == 'admin') {
            return view('users.show', compact('user'));
        }
        abort(404);
    }

    /**
     * @return Application|Factory|View
     */
    public function edit(User $user): \Illuminate\View\View
    {
        $subscription = Subscription::with(['plan'])
            ->whereTenantId($user->tenant_id)
            ->where('status', Subscription::ACTIVE)->latest()->first();

        return view('users.edit', compact('user', 'subscription'));
    }

    public function emailVerified(User $user): JsonResponse
    {
        DB::table('users')->where('id', $user->id)->update(['email_verified_at' => Carbon::now()]);

        // $affiliateUser = AffiliateUser::withoutGlobalScope('verifiedUser')
        //     ->whereIsVerified(false)
        //     ->whereUserId($user->id)
        //     ->first();

        //     // if ($affiliateUser) {
        //     //     $affiliateUser->update(['is_verified' => true]);
        //     // }

        return $this->sendSuccess(__('messages.flash.verified_email'));
    }

    public function updateStatus(User $user): JsonResponse
    {
        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return $this->sendSuccess(__('messages.flash.user_status'));
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userRepo->update($request->all(), $user);

        Flash::success(__('messages.flash.user_update'));

        return redirect(route('users.index'));
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->getRoleNames()[0] == 'admin') {
            $affiliateUsers = AffiliateUser::whereUserId($user->id)->orWhere('affiliated_by', $user->id)->get();
            $withdrawals = Withdrawal::whereUserId($user->id)->get();
            foreach ($withdrawals as $withdrawal) {
                $withdrawalTransactions = WithdrawalTransaction::where('withdrawal_id', $withdrawal->id)->get();
                foreach ($withdrawalTransactions as $transaction) {
                    $transaction->delete();
                }

                $withdrawal->delete();
            }
            foreach ($affiliateUsers as $affiliateUser) {
                $affiliateUser->delete();
            }
            NfcOrderTransaction::where('user_id', $user->id)->delete();
            NfcOrders::where('user_id', $user->id)->delete();
            Vcard::where('tenant_id', $user->tenant_id)->delete();
            MultiTenant::where('id', $user->tenant_id)->delete();
            $user->delete();

            return $this->sendSuccess('User deleted successfully.');
        }

        return $this->sendError('Seems, you are not allowed to access this record.');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function impersonate(User $user): RedirectResponse
    {
        getLogInUser()->impersonate($user);

        return redirect(route('admin.dashboard'));
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function impersonateLeave(): RedirectResponse
    {
        getLogInUser()->leaveImpersonation();

        return redirect(route('users.index'));
    }

    /**
     * @return Application|Factory|View
     */
    public function editProfile(): \Illuminate\View\View
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function updateProfile(UpdateUserProfileRequest $request): RedirectResponse
    {
        $this->userRepo->updateProfile($request->all());
        $user = Auth::user();
        $verifiedUser = EmailVerification::where('user_id', getLogInUserId())->first();
        if($user->hasrole('admin')){
            if ($verifiedUser) {
                Flash::success(__('messages.placeholder.email_verification'));
            } else {
                Flash::success(__('messages.flash.user_profile'));
            }
        } else {
            Flash::success(__('messages.flash.user_profile'));
        }

        return redirect(route('profile.setting'));
    }

    public function changePassword(UpdateChangePasswordRequest $request): JsonResponse
    {
        $input = $request->all();

        try {
            /** @var User $user */
            $user = Auth::user();
            if (! Hash::check($input['current_password'], $user->password)) {
                return $this->sendError(__('messages.flash.current_invalid'));
            }
            $input['password'] = Hash::make($input['new_password']);
            $user->update($input);

            return $this->sendSuccess(__('messages.flash.password_update'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function changeUserPassword(UpdateUserPasswordRequest $request, User $user): JsonResponse
    {
        $input = $request->all();

        try {
            $input['password'] = Hash::make($input['new_password']);
            $this->userRepo->update($input, $user);
            $data = [
                'name' => $user->full_name,
                'toName' => getLogInUser()->full_name,
            ];

            Mail::to($user->email)
                ->send(new ChangePasswordMail('emails.change_password_mail',
                    __('messages.flash.password_update'),
                    $data));

            return $this->sendSuccess(__('messages.flash.password_update'));
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function changeLanguage(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = Auth::user();
        if ($user !== null) {
            $user->update($input);
        }

        return $this->sendSuccess(__('messages.flash.language_update'));
    }

    public function changeMode(): RedirectResponse
    {
        $user = Auth::user();

        if ($user !== null) {
            $user->update([
                'theme_mode' => ! $user->theme_mode,
            ]);
        }

        return redirect()->back();
    }

    public function userDelete(User $user)
    {
        $result = $this->userRepo->userDataDelete($user);

        if ($result) {
            return Redirect::route('home');
        }

        return $this->sendError('Seems, you are not allowed to access this record.');
    }

    public function updateSteps($steps) {
        $user = getLogInUser();
        $user->steps = $steps;
        $user->update();

        return response()->json(['message' => 'Steps updated successfully']);
    }

}
