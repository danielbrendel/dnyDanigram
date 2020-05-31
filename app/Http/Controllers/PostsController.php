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
use App\HeartModel;
use App\PostModel;
use App\TagsModel;
use App\ThreadModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\User;
use stdClass;

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

            $post->hearts = HeartModel::where('entityId', '=', $post->id)->where('type', '=', 'ENT_POST')->count();
            $post->hashtags = explode(' ', trim($post->hashtags));

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
            $user = User::getByAuthId();
            if ($user) {
                $user->stats = new stdClass();
                $user->stats->posts = PostModel::where('userId', '=', $user->id)->count();
                $user->stats->comments = ThreadModel::where('userId', '=', $user->id)->count();
            }

            return view('member.index', [
                'user' => $user,
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
     * @return mixed
     */
    public function hashtag($hashtag)
    {
        try {
            $tag = TagsModel::where('tag', '=', $hashtag)->first();
            if (!$tag) {
                return back()->with('notice', __('app.hashtag_not_yet_used'));
            }

            $tag->stats = new stdClass();
            $tag->stats->posts = Cache::remember('tag_stats_posts', 3600 * 24, function () use ($hashtag) {
                return PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->count();
            });
            $tag->stats->comments = Cache::remember('tag_stats_comments', 3600 * 24, function () use ($hashtag) {
                $comments = 0;
                $posts = PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->get();
                foreach ($posts as $post) {
                    $comments += ThreadModel::where('postId', '=', $post->id)->count();
                }
                return $comments;
            });
            $tag->stats->hearts = Cache::remember('tag_stats_hearts', 3600 * 24, function () use ($hashtag) {
                $hearts = 0;
                $posts = PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->get();
                foreach ($posts as $post) {
                    $hearts += HeartModel::where('entityId', '=', $post->id)->where('type', '=', 'ENT_POST')->count();
                }
                return $hearts;
            });

            $tag->top_image = Cache::remember('tag_top_image', 24, function() use ($hashtag) {
               $post = PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->orderBy('hearts', 'desc')->first();
               if ($post) {
                   return $post->image_thumb;
               }

               return null;
            });

            $user = User::getByAuthId();
            if ($user) {
                $user->stats = new stdClass();
                $user->stats->posts = PostModel::where('userId', '=', $user->id)->count();
                $user->stats->comments = ThreadModel::where('userId', '=', $user->id)->count();
            }

            return view('member.hashtag', [
                'user' => $user,
                'taglist' => TagsModel::getPopularTags(),
                'hashtag' => $hashtag,
                'tag' => $tag,
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
    public function fetchPosts()
    {
        try {
            $type = request('type', PostModel::FETCH_TOP);
            $paginate = request('paginate', null);
            $hashtag = request('hashtag', null);

            $posts = PostModel::getPostPack($type, $this->postPackLimit, $hashtag, $paginate);
            foreach ($posts as &$post) {
                $post->diffForHumans = $post->created_at->diffForHumans();
                $post->user = User::get($post->userId);
                $post->comment_count = ThreadModel::where('postId', '=', $post->id)->count();
                $post->userHearted = HeartModel::hasUserHearted(auth()->id(), $post->id, 'ENT_POST');
                $post->hearts = HeartModel::where('entityId', '=', $post->id)->where('type', '=', 'ENT_POST')->count();
            }

            return response()->json(array('code' => 200, 'data' => $posts));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Fetch thread comment pack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchThread()
    {
        try {
            $post = request('post');
            $paginate = request('paginate', null);

            $threads = ThreadModel::getFromPost($post, $paginate);
            foreach ($threads as &$thread) {
                $thread->user = User::get($thread->userId);
                $thread->hearts = HeartModel::where('type', '=', 'ENT_COMMENT')->where('entityId', '=', $thread->id)->count();
                $thread->adminOrOwner = User::isAdmin(auth()->id()) || ($thread->userId === auth()->id());
            }

            return response()->json(array('code' => 200, 'data' => $threads, 'last' => $threads[count($threads)-1]->id === 1));
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

    /**
     * Set heart value to post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function heartPost()
    {
        try {
            $attr['post'] = request('post');
            $attr['value'] = request('value');

            if ($attr['value']) {
                HeartModel::addHeart(auth()->id(), $attr['post'], 'ENT_POST');
            } else {
                HeartModel::removeHeart(auth()->id(), $attr['post'], 'ENT_POST');
            }

            return response()->json(array('code' => 200, 'value' => $attr['value'], 'count' => HeartModel::where('entityId', '=', $attr['post'])->where('type', '=', 'ENT_POST')->count()));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
