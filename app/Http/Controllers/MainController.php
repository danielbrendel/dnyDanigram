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

use App\AppModel;
use App\TagsModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\User;
use App\CaptchaModel;
use App\FaqModel;
use App\PushModel;
use App\MailerModel;
use Throwable;

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
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->cookie_consent = AppModel::getCookieConsentText();
    }

    /**
     * Default index page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws Exception
     */
    public function index()
    {
        if ((Auth::guest()) && (!env('APP_PUBLICFEED'))) {
            return view('home.index', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => $this->cookie_consent,
                'index_content' => AppModel::getIndexContent(),
                'taglist' => TagsModel::getPopularTags()
            ]);
        } else {
            session()->reflash();

            return redirect('/feed');
        }
    }

    /**
     * Show client endpoint index
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function clep_index()
    {
        if (((Auth::guest()) && (!env('APP_PUBLICFEED'))) || (!isset($_COOKIE['clep']))) {
            return view('clep.index', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => $this->cookie_consent
            ]);
        } else {
            session()->reflash();

            return redirect('/feed' . ((isset($_GET['clep_push_handler'])) ? '?clep_push_handler=' . $_GET['clep_push_handler'] : ''));
        }
    }

    /**
     * View about page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('home.about', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent, 'about_content' => AppModel::getAboutContent()]);
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
     * View news page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news()
    {
        return view('home.news', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent]);
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
     * View contact page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewContact()
    {
        return view('home.contact', ['captcha' => CaptchaModel::createSum(session()->getId()), 'cookie_consent' => $this->cookie_consent]);
    }

    /**
     * Process contact request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact()
    {
        try {
            $attr = request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'body' => 'required',
                'captcha' => 'required'
            ]);

            if ($attr['captcha'] !== CaptchaModel::querySum(session()->getId())) {
                return back()->with('error', __('app.captcha_invalid'))->withInput();
            }

            AppModel::createTicket($attr['name'], $attr['email'], $attr['subject'], $attr['body']);

            return back()->with('success', __('app.contact_success'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
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
                return redirect('/feed')->with('flash.success', __('app.login_successful'));
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

            return  redirect('/')->with('flash.success', __('app.logout_success'));
        } else {
            return  redirect('/')->with('error', __('app.not_logged_in'));
        }
    }

    /**
     * Send email with password recovery link to user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     */
    public function recover()
    {
        $attr = request()->validate([
            'email' => 'required|email'
        ]);

        try {
            User::recover($attr['email']);

            return back()->with('success', __('app.pw_recovery_ok'));
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    /**
     * View password reset form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewReset()
    {
        return view('home.pwreset', [
            'hash' => request('hash', ''),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => $this->cookie_consent
        ]);
    }

    /**
     * Process registration
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
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
            $id = User::register($attr);

            return back()->with('success', __('app.register_confirm_email', ['id' => $id]));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Resend confirmation link
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     */
    public function resend($id)
    {
        try {
            User::resend($id);

            return back()->with('success', __('app.resend_ok', ['id' => $id]));
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    /**
     * Perform newsletter cronjob
     *
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    public function cronjob_newsletter($password)
    {
        try {
            if ($password !== env('APP_CRONPW')) {
                return response()->json(array('code' => 403));
            }

            $data = AppModel::newsletterJob();

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
