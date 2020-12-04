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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\TagsModel;
use App\CaptchaModel;
use App\AppModel;
use App\ForumModel;
use App\ForumThreadModel;
use App\ForumPostModel;

class ForumController extends Controller
{
    /**
     * Validate permissions
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            if ((!env('APP_PUBLICFEED')) && (Auth::guest())) {
                return redirect('/');
            }

            return $next($request);
        });
    }

    /**
     * View forum index
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $user = User::getByAuthId();
        if ($user) {
            $user->stats = User::getStats($user->id);
        }

        return view('forum.index', [
            'user' => $user,
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => AppModel::getCookieConsentText()
        ]);
    }

    /**
     * Get forum list
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $paginate = request('paginate', null);
            $name = request('name', '');

            $data = ForumModel::queryList($paginate, $name);

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View specific forum
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $user = User::getByAuthId();
        if ($user) {
            $user->stats = User::getStats($user->id);
        }

        $forum = ForumModel::where('id', '=', $id);
        
        if ((!$user->maintainer) && (!$user->admin)) {
            $forum->where('locked', '=', false);
        }

        $forum = $forum->first();

        if (!$forum) {
            return redirect('/forum')->with('flash.error', __('app.forum_not_found_or_locked'));
        }

        return view('forum.show', [
            'user' => $user,
            'forum' => $forum,
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => AppModel::getCookieConsentText()
        ]);
    }

    /**
     * Get thread list
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function threadList($id)
    {
        try {
            $paginate = request('paginate', null);

            $data = ForumThreadModel::list($id, $paginate);

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View specific forum thread
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showThread($id)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $user = User::getByAuthId();
        if ($user) {
            $user->stats = User::getStats($user->id);
        }

        $thread = ForumThreadModel::where('id', '=', $id);
        
        if ((!$user->maintainer) && (!$user->admin)) {
            $thread->where('locked', '=', false);
        }

        $thread = $thread->first();

        if (!$thread) {
            return redirect('/forum')->with('flash.error', __('app.thread_not_found_or_locked'));
        }

        $thread->owner = User::get($thread->ownerId);

        return view('forum.thread', [
            'user' => $user,
            'thread' => $thread,
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => AppModel::getCookieConsentText()
        ]);
    }

    /**
     * Get thread postings
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function threadPostings($id)
    {
        try {
            $paginate = request('paginate', null);

            $data = ForumPostModel::getPosts($id, $paginate);

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Create new forum thread
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createThread()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'title' => 'required',
                'message' => 'required'
            ]);

            $id = ForumThreadModel::add(auth()->id(), $attr['id'], $attr['title'], $attr['message']);

            return redirect('/forum/thread/' . $id . '/show')->with('flash.success', __('app.thread_created'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reply to forum thread
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function replyThread()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'message' => 'required'
            ]);

            $id = ForumPostModel::add($attr['id'], auth()->id(), $attr['message']);

            return back()->with('flash.success', __('app.thread_replied'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
