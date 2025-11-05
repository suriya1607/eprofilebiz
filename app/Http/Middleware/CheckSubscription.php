<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\Vcard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
        public function handle(Request $request, Closure $next): Response
    {
        $restrictedActions = [
            'create', 'edit', 'update', 'delete', 'store',
            'sendInvite', 'withdrawAmount', 'destroy', 'updateStatus','updatePwaStatus','ChangeCompanyStatus','duplicateVcard'
        ];
        $currentAction = $request->route() ? $request->route()->getActionMethod() : null;
        if (in_array($currentAction, $restrictedActions)) {

            $subscription = Subscription::with('plan')
                ->where('status', Subscription::ACTIVE)
                ->where('tenant_id', getLogInUser()->tenant_id)
                ->first();

            if (! $subscription || $subscription->isExpired()) {
                Vcard::where('tenant_id', getLogInUser()->tenant_id)->update(['status' => 0]);

                // 🔹 Livewire request (header present)
                if ($request->header('X-Livewire')) {
                    return response()->json([
                        'effects' => [
                            'redirect' => route('subscription.upgrade')
                        ]
                    ]);
                }

                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'redirect_url' => route('subscription.upgrade'),
                        'message' => __('messages.subscription.subscription_expired')
                    ], 403);
                }

                return redirect(route('subscription.upgrade'))
                    ->withErrors(__('messages.subscription.subscription_expired'));
            }
        }

        return $next($request);
    }
}
