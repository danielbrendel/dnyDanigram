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
use App\PostModel;
use App\TagsModel;
use App\ThreadModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class PostsController extends Controller
{
    private $postPackLimit;

    /**
     * PostsController constructor.
     */
    public function __construct()
    {
        if (Auth::guest()) {
            //throw new Exception('Reserved for registered users only', 403);
        }

        $this->postPackLimit = env('APP_POSTPACKLIMIT');
    }

    /**
     * Show upload form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewUpload()
    {
        return view('member.upload', [
            'user' => User::getByAuthId()
        ]);
    }

    /**
     * Perform upload
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function upload()
    {
        try {
            $id = PostModel::upload();

            return redirect('/p/' . $id);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show specific post
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showPost($id)
    {
        try {
            $post = PostModel::getPost($id);
            $user = User::where('id', '=', $post->userId)->first();

            $threads = ThreadModel::getFromPost($post->id);
            foreach ($threads as &$thread) {
                $thread->user = User::get($thread->userId);
            }

            return view('member.showpost', [
                'user' => User::getByAuthId(),
                'post' => $post,
                'thread_count' => ThreadModel::where('postId', '=', $post->id)->where('locked', '=', false)->count(),
                'poster' => $user,
                'threads' => $threads
            ]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show feed
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function feed()
    {
        try {
            return view('member.index', [
                'user' => User::getByAuthId(),
                'taglist' => TagsModel::getPopularTags()
            ]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    /**
     * Show hashtag feed
     *
     * @param $hashtag
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function hashtag($hashtag)
    {
        try {
            $tag = TagsModel::where('tag', '=', $hashtag)->first();

            return view('member.hashtag', [
                'user' => User::getByAuthId(),
                'taglist' => TagsModel::getPopularTags(),
                'hashtag' => $hashtag,
                'tagdata' => $tag
            ]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    /**
     * Fetch posts pack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        try {
            $type = request('type', PostModel::FETCH_TOP);
            $paginate = request('paginate', null);
            $hashtag = request('hashtag', null);

            $posts = PostModel::getPostPack($type, $this->postPackLimit, $hashtag, $paginate);
            foreach ($posts as &$post) {
                $post->diffForHumans = $post->created_at->diffForHumans();
                $post->user = User::get($post->userId);
            }

            return response()->json(array('code' => 200, 'data' => $posts));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Add new thread post
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addThread($id)
    {
        try {
            $attr = request()->validate([
                'text' => 'required|max:4096'
            ]);

            $threadId = ThreadModel::add(auth()->id(), $id, $attr['text']);

            return redirect('/p/' . $id . '#' . $threadId);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
