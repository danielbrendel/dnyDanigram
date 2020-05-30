<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use App\AppModel;
use App\TagsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\User;
use App\CaptchaModel;
use App\FaqModel;
use App\PushModel;
use App\MailerModel;

/**
 * Class MainController
 *
 * Perform general computations
 */
class MainController extends Controller
{
    private $cookie_consent;

    /**
     * MainController constructor.
     */
    public function __construct()
    {
        $this->cookie_consent = AppModel::getCookieConsentText();
    }

    /**
     * Default index page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        if (Auth::guest()) {
            return view('home.index', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => $this->cookie_consent,
                'index_content' => AppModel::getIndexContent(),
                'taglist' => TagsModel::getPopularTags()
            ]);
        } else {
            return redirect('/feed');
        }
    }

    /**
     * View about page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        $donationCode = null;

        if (file_exists(public_path() . '/data/donation.txt')) {
            $donationCode = Cache::remember('donation_code', 3600, function() {
                return file_get_contents(public_path() . '/data/donation.txt');
            });
        }

        return view('home.about', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent, 'donationCode' => $donationCode, 'about_content' => AppModel::getAboutContent()]);
    }

    /**
     * View faq page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq()
    {
        return view('home.faq', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent, 'faqs' => FaqModel::getAll()]);
    }

    /**
     * View imprint page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function imprint()
    {
        return view('home.imprint', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent, 'imprint_content' => AppModel::getImprint()]);
    }

    /**
     * View tos page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tos()
    {
        return view('home.tos', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent, 'tos_content' => AppModel::getTermsOfService()]);
    }

    /**
     * Perform login
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $attr = request()->validate([
           'email' => 'required|email',
           'password' => 'required'
        ]);

        if (Auth::guest()) {
            $user = User::where('email', '=', $attr['email'])->first();
            if ($user !== null) {
                if ($user->account_confirm !== '_confirmed') {
                    return back()->with('error', __('app.account_not_yet_confirmed'));
                }

                if ($user->deactivated) {
                    return back()->with('error', __('app.account_deactivated'));
                }
            }

            if (Auth::attempt([
                'email' => $attr['email'],
                'password' => $attr['password']
            ])) {
                return redirect('/')->with('success', __('app.login_welcome_back'));
            } else {
                return back()->with('error', __('app.login_failed'));
            }
        } else {
            return back()->with('error', __('app.login_already_logged_in'));
        }
    }

    /**
     * Perform logout
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if(Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();

            return  redirect('/')->with('success', __('app.logout_success'));
        } else {
            return  redirect('/')->with('error', __('app.not_logged_in'));
        }
    }

    /**
     * Send email with password recovery link to user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recover()
    {
        $attr = request()->validate([
            'email' => 'required|email'
        ]);

        try {
            User::recover($attr['email']);

            return back()->with('success', __('app.pw_recovery_ok'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reset password
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset()
    {
        $attr = request()->validate([
            'password' => 'required',
            'password_confirm' => 'required'
        ]);

        $hash = request('hash');

        try {
            User::reset($attr['password'], $attr['password_confirm'], $hash);

            return redirect('/')->with('success', __('app.password_reset_ok'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process registration
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        $attr = request()->validate([
           'username' => 'required|max:35',
           'email' => 'required|email',
           'password' => 'required',
           'password_confirmation' => 'required',
           'captcha' => 'required|numeric'
        ]);

        try {
            User::register($attr);

            return back()->with('success', __('app_register_confirm_email'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm account
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirm()
    {
        $hash = request('hash');

        try {
            User::confirm($hash);

            return redirect('/')->with('success', __('app.register_confirmed_ok'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
