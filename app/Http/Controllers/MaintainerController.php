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
use App\FaqModel;
use App\User;
use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MaintainerController extends Controller
{
    /**
     * Validate permissions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = User::get(auth()->id());
            if ((!$user) || (!$user->maintainer)) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('maintainer.index', [
            'user' => User::get(auth()->id()),
            'settings' => AppModel::getSettings(),
            'faqs' => FaqModel::getAll()
        ]);
    }

    /**
     * Save app database settings
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        try {
            $attr = request()->validate([
               'attribute' => 'required',
               'content' => 'required'
            ]);

            AppModel::saveSetting($attr['attribute'], $attr['content']);

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.settings_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Add FAQ item
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFaq()
    {
        try {
            $attr = request()->validate([
                'question' => 'required',
                'answer' => 'required'
            ]);

            $faq = new FaqModel();
            $faq->question = $attr['question'];
            $faq->answer = $attr['answer'];
            $faq->save();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Edit FAQ item
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editFaq()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'question' => 'required',
                'answer' => 'required'
            ]);

            $faq = FaqModel::where('id', '=', $attr['id'])->first();
            $faq->question = $attr['question'];
            $faq->answer = $attr['answer'];
            $faq->save();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Remove FAQ item
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFaq($id)
    {
        try {
            $faq = FaqModel::where('id', '=', $id)->first();
            $faq->delete();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_removed'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Store env configuration
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function envSave()
    {
        try {
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 4) === 'ENV_') {
                    $_ENV[substr($key, 4)] = $value;
                }
            }

            AppModel::saveEnvironmentConfig();

            return back()->with('flash.success', __('app.env_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }
}
