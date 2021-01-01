<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use App\PostModel;
use App\TagsModel;
use App\ThreadModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
	/**
     * Constructor
     */
	public function __construct()
	{
		parent::__construct();
	}
	
    /**
     * Lock post
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockPost($id)
    {
        try {
            $post = PostModel::getPost($id);
            if (!$post) {
                return response()->json(array('code' => 404, 'msg' => __('app.post_not_found')));
            }

            $user = User::get(auth()->id());
            if ((!$user) || ((!$user->admin) && ($user->id !== $post->userId))) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $post->locked = true;
            $post->save();

            return response()->json(array('code' => 200, 'msg' => __('app.post_locked')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Toggle nsfw flag
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleNsfw($id)
    {
        try {
            $post = PostModel::getPost($id);
            if (!$post) {
                return response()->json(array('code' => 404, 'msg' => __('app.post_not_found')));
            }

            $user = User::get(auth()->id());
            if ((!$user) || ((!$user->admin) && ($user->id !== $post->userId))) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $post->nsfw = !$post->nsfw;
            $post->save();

            return response()->json(array('code' => 200, 'msg' => __('app.post_nsfw_toggled')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Lock hashtag
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockHashtag($id)
    {
        try {
            $tag = TagsModel::where('id', '=', $id)->first();
            if (!$tag) {
                return response()->json(array('code' => 404, 'msg' => __('app.hashtag_not_found')));
            }

            $user = User::get(auth()->id());
            if ((!$user) || (!$user->admin)) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $tag->locked = true;
            $tag->save();

            return response()->json(array('code' => 200, 'msg' => __('app.hashtag_locked')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Deactivate user account
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivateUser($id)
    {
        try {
            $user = User::get($id);
            if (!$user) {
                return response()->json(array('code' => 404, 'msg' => __('app.user_not_found')));
            }

            $requester = User::get(auth()->id());
            if ((!$requester) || ((!$requester->admin) && ($user->id !== $requester->userId))) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $user->deactivated = true;
            $user->save();

            $logout = false;
            if ($user->id === auth()->id()) {
                Auth::logout();
                request()->session()->invalidate();

                $logout = true;
            }

            return response()->json(array('code' => 200, 'msg' => __('app.user_deactivated'), 'logout' => $logout));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Lock comment
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockComment($id)
    {
        try {
            $comment = ThreadModel::where('id', '=', $id)->first();
            if (!$comment) {
                return response()->json(array('code' => 404, 'msg' => __('app.comment_not_found')));
            }

            $user = User::get(auth()->id());
            if ((!$user) || (!$user->admin)) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $comment->locked = true;
            $comment->save();

            return response()->json(array('code' => 200, 'msg' => __('app.comment_locked')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
