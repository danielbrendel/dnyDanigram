<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use App\AgentModel;
use App\User;
use App\WorkSpaceModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    /**
     * PaymentController constructor.
     *
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
		
        $this->middleware(function ($request, $next) {
            if (Auth::guest()) {
                abort(403);
            }

            return $next($request);
        });

        \Stripe\Stripe::setApiKey(env('STRIPE_TOKEN_SECRET'));
    }

    /**
     * Perform the payment operation
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function charge()
    {
        try {
			if (!env('STRIPE_ENABLE')) {
				throw new Exception(__('app.payment_service_deactivated'));
			}
			
            $attr = request()->validate([
               'stripeToken' => 'required'
            ]);

            $user = User::get(auth()->id());
            if ((!$user) || ($user->locked) || (User::isUserPro($user))) {
                throw new Exception(__('app.user_not_found_or_locked_or_already_pro'));
            }

            $charge = \Stripe\Charge::create([
                'amount' => env('STRIPE_COSTS_VALUE'),
                'currency' => env('STRIPE_CURRENCY'),
                'description' => 'Purchasing of pro mode for ' . $user->username . '/' . $user->id . ' (' . $user->email . ')',
                'source' => $attr['stripeToken'],
                'receipt_email' => $user->email
            ]);

            if ((!$charge instanceof \Stripe\Charge) || (!isset($charge->status) || ($charge->status !== 'succeeded'))) {
                throw new Exception(__('app.payment_failed'));
            }

            $user->pro_date = Carbon::now()->format('y-m-d h:i:s');
            $user->save();

            return back()->with('success', __('app.payment_succeeded'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
