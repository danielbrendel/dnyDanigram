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
use App\CaptchaModel;
use App\FavoritesModel;
use App\HeartModel;
use App\IgnoreModel;
use App\PostModel;
use App\ReportModel;
use App\StoryModel;
use App\TagsModel;
use App\ThreadModel;
use Exception;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\User;
use stdClass;

class PostsController extends Controller
{
    private $postPackLimit;

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
               return redirect('/')->with('error', __('app.not_logged_in'));
           }

            return $next($request);
        });

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
            'user' => User::getByAuthId(),
            'cookie_consent' => AppModel::getCookieConsentText(),
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
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
            if (!$post) {
                return redirect('/')->with('flash.error', __('app.post_not_found_or_locked'));
            }

            $cmdId = request('c', null);
            if (is_numeric($cmdId)) {
                $cmt = ThreadModel::where('id', '=', $cmdId)->where('postId', '=', $id)->first();
                $cmt->user = User::get($cmt->userId);
                $cmt->hearts = HeartModel::where('type', '=', 'ENT_COMMENT')->where('entityId', '=', $cmt->id)->count();
                $cmt->adminOrOwner = User::isAdmin(auth()->id()) || ($cmt->userId === auth()->id());
                $cmt->userHearted = HeartModel::hasUserHearted(auth()->id(), $cmt->id, 'ENT_COMMENT');
            } else {
                $cmt = null;
            }


            $favorites = FavoritesModel::getDetailedForUser(auth()->id());

            return view('feed.showpost', [
                'user' => User::getByAuthId(),
                'post' => $post,
                'taglist' => TagsModel::getPopularTags(),
                'favorites' => $favorites,
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => AppModel::getCookieConsentText(),
                'meta_description' => $post->description,
                'meta_tags' => $post->hashtags,
                'highlight_comment' => $cmt
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
                $user->stats = User::getStats($user->id);
            }

            return view('feed.index', [
                'user' => $user,
                'taglist' => TagsModel::getPopularTags(),
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => AppModel::getCookieConsentText()
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
            $hashtag = strtolower($hashtag);

            $tag = TagsModel::where('tag', '=', $hashtag)->orWhere('id', '=', $hashtag)->first();
            if (!$tag) {
                return back()->with('notice', __('app.hashtag_not_yet_used'));
            }

            if ($tag->locked) {
                return redirect('/')->with('flash.error', __('app.hashtag_locked'));
            }

            $tag->stats = new stdClass();
            $tag->stats->posts = Cache::remember('tag_stats_posts_' . $hashtag, 3600 * 24, function () use ($hashtag) {
                return PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->count();
            });
            $tag->stats->comments = Cache::remember('tag_stats_comments_' . $hashtag, 3600 * 24, function () use ($hashtag) {
                $comments = 0;
                $posts = PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->get();
                foreach ($posts as $post) {
                    $comments += ThreadModel::where('postId', '=', $post->id)->count();
                }
                return $comments;
            });
            $tag->stats->hearts = Cache::remember('tag_stats_hearts_' . $hashtag, 3600 * 24, function () use ($hashtag) {
                $hearts = 0;
                $posts = PostModel::where('hashtags', 'LIKE', '%' . $hashtag . ' %')->get();
                foreach ($posts as $post) {
                    $hearts += HeartModel::where('entityId', '=', $post->id)->where('type', '=', 'ENT_POST')->count();
                }
                return $hearts;
            });

            $tag->top_image = TagsModel::getTopImage($hashtag);

            $user = User::getByAuthId();
            if ($user) {
                $user->stats = new stdClass();
                $user->stats->posts = PostModel::where('userId', '=', $user->id)->count();
                $user->stats->comments = ThreadModel::where('userId', '=', $user->id)->count();
            }

            return view('feed.hashtag', [
                'user' => $user,
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'taglist' => TagsModel::getPopularTags(),
                'hashtag' => $hashtag,
                'tag' => $tag,
                'tagdata' => $tag,
                'favorited' => FavoritesModel::hasUserFavorited(auth()->id(), $tag->id, 'ENT_HASHTAG'),
                'hearted' => HeartModel::hasUserHearted(auth()->id(), $tag->id, 'ENT_HASHTAG'),
                'cookie_consent' => AppModel::getCookieConsentText(),
                'meta_description' => __('app.tag_is_about', ['subject' => $tag->tag])
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
            $user = request('user', null);

            $posts = PostModel::getPostPack($type, $this->postPackLimit, $hashtag, $user, $paginate);
            foreach ($posts as $key => &$post) {
                $post['_type'] = 'post';

                if (IgnoreModel::hasIgnored(auth()->id(), $post['userId'])) {
                    unset($posts[$key]);
                    continue;
                }

                $post['diffForHumans'] = Carbon::createFromDate($post['created_at'])->diffForHumans();
                $post['user'] = User::get($post['userId']);
                $post['comment_count'] = ThreadModel::where('postId', '=', $post['id'])->where('locked', '=', false)->count();
                $post['userHearted'] = HeartModel::hasUserHearted(auth()->id(), $post['id'], 'ENT_POST');
                $post['hearts'] = HeartModel::where('entityId', '=', $post['id'])->where('type', '=', 'ENT_POST')->count();
            }

            if ((Auth::guest()) || (!User::get(auth()->id())->pro)) {
                $adCode = AppModel::getAdCode();
                if ((strlen($adCode) > 0) && (count($posts) > 0)) {
                    $ad = array();
                    $ad['_type'] = 'ad';
                    $ad['code'] = $adCode;
                    $posts[] = $ad;
                }
            }

            return response()->json(array('code' => 200, 'data' => array_values($posts), 'last' => (count($posts) === 0)));
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
            foreach ($threads as $key => &$thread) {
                if (IgnoreModel::hasIgnored(auth()->id(), $thread['userId'])) {
                    unset($threads[$key]);
                    continue;
                }

                $thread['user'] = User::get($thread['userId']);
                $thread['hearts'] = HeartModel::where('type', '=', 'ENT_COMMENT')->where('entityId', '=', $thread['id'])->count();
                $thread['adminOrOwner'] = User::isAdmin(auth()->id()) || ($thread['userId'] === auth()->id());
                $thread['userHearted'] = HeartModel::hasUserHearted(auth()->id(), $thread['id'], 'ENT_COMMENT');
                $thread['diffForHumans'] = Carbon::createFromDate($thread['created_at'])->diffForHumans();
                $thread['subCount'] = ThreadModel::getSubCount($thread['id']);
            }

            return response()->json(array('code' => 200, 'data' => array_values($threads), 'last' => (count($threads) === 0)));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Fetch sub thread comment pack
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchSubThread()
    {
        try {
            $parentId = request('parent');
            $paginate = request('paginate', null);

            $threads = ThreadModel::getSubPosts($parentId, $paginate);
            foreach ($threads as $key => &$thread) {
                if (IgnoreModel::hasIgnored(auth()->id(), $thread['userId'])) {
                    unset($threads[$key]);
                    continue;
                }

                $thread['user'] = User::get($thread['userId']);
                $thread['hearts'] = HeartModel::where('type', '=', 'ENT_COMMENT')->where('entityId', '=', $thread['id'])->count();
                $thread['adminOrOwner'] = User::isAdmin(auth()->id()) || ($thread['userId'] === auth()->id());
                $thread['userHearted'] = HeartModel::hasUserHearted(auth()->id(), $thread['id'], 'ENT_COMMENT');
                $thread['diffForHumans'] = Carbon::createFromDate($thread['created_at'])->diffForHumans();
            }

            return response()->json(array('code' => 200, 'data' => $threads, 'last' => (count($threads) === 0)));
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
     * Reply to thread
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function replyThread()
    {
        try {
            $parentId = request('parent');

            $attr = request()->validate([
               'text' => 'required|max:4096'
            ]);

            $reply = ThreadModel::reply(auth()->id(), $parentId, $attr['text']);

            return response()->json(array('code' => 200, 'post' => $reply));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Set heart value to post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function heart()
    {
        try {
            $attr['entity'] = request('entity');
            $attr['value'] = request('value');
            $attr['type'] = request('type');

            if ($attr['value']) {
                HeartModel::addHeart(auth()->id(), $attr['entity'], $attr['type']);
            } else {
                HeartModel::removeHeart(auth()->id(), $attr['entity'], $attr['type']);
            }

            return response()->json(array('code' => 200, 'value' => $attr['value'], 'count' => HeartModel::where('entityId', '=', $attr['entity'])->where('type', '=', $attr['type'])->count()));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Fetch single post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchSinglePost()
    {
        try {
            $postId = request('post');

            $post = PostModel::getPost($postId);
            $post->user = User::where('id', '=', $post->userId)->first();

            $post->hearts = HeartModel::where('entityId', '=', $post->id)->where('type', '=', 'ENT_POST')->count();
            $post->userHearted = HeartModel::hasUserHearted(auth()->id(), $post->id, 'ENT_POST');
            $post->diffForHumans = $post->created_at->diffForHumans();
            $post->comment_count = ThreadModel::where('postId', '=', $post->id)->where('locked', '=', false)->count();

            return response()->json(array('code' => 200, 'elem' => $post));
        } catch (Exception $e) {
            return response()->json(array('codee' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Report a post
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportPost($id)
    {
        try {
            $post = PostModel::getPost($id);
            if (!$post) {
                return response()->json(array('code' => 404, 'msg' => __('app.post_not_found')));
            }

            ReportModel::addReport(auth()->id(), $id, 'ENT_POST');

            return response()->json(array('code' => 200));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Report a tag
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportTag($id)
    {
        try {
            $tag = TagsModel::where('id', '=', $id);
            if (!$tag) {
                return response()->json(array('code' => 404, 'msg' => __('app.tag_not_found')));
            }

            ReportModel::addReport(auth()->id(), $id, 'ENT_HASHTAG');

            return response()->json(array('code' => 200, 'msg' => __('app.tag_reported')));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Report a post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportComment()
    {
        try {
            $id = request('comment');

            $cmt = ThreadModel::where('id', '=', $id)->first();
            if (!$cmt) {
                return response()->json(array('code' => 404, 'msg' => __('app.comment_not_found')));
            }

            ReportModel::addReport(auth()->id(), $id, 'ENT_COMMENT');

            return response()->json(array('code' => 200, 'msg' => __('app.comment_reported')));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Edit comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function editComment()
    {
        try {
            $id = request('comment');
            $text = request('text');

            $comment = ThreadModel::where('id', '=', $id)->first();

            if (!$comment) {
                return response()->json(array('code' => 404, 'msg' => __('app.comment_not_found')));
            }

            $user = User::get(auth()->id());

            if (($comment->userId !== auth()->id()) && (!$user->admin)) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $comment->text = $text;
            $comment->save();

            return response()->json(array('code' => 200));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment()
    {
        try {
            $id = request('comment');

            $comment = ThreadModel::where('id', '=', $id)->first();

            if (!$comment) {
                return response()->json(array('code' => 404, 'msg' => __('app.comment_not_found')));
            }

            $user = User::get(auth()->id());

            if (($comment->userId !== auth()->id()) && (!$user->admin)) {
                return response()->json(array('code' => 403, 'msg' => __('app.insufficient_permissions')));
            }

            $hearts = HeartModel::getFromEntity($comment->id, 'ENT_COMMENT');
            foreach ($hearts as $heart) {
                $heart->delete();
            }

            $comment->delete();

            return response()->json(array('code' => 200));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Get selection of stories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function selection()
    {
        try {
            $data = StoryModel::randomSelection(auth()->id(), env('APP_STORYPACK'));

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View story list
     *
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function story($userId)
    {
        try {
            $data = StoryModel::view(auth()->id(), $userId);

            return response()->json(array('code' => 200, 'data' => $data, 'user' => User::get($userId)));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Add image to story
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStoryImage()
    {
        try {
            $attr = request()->validate([
                'image' => 'required',
                'message' => 'nullable',
                'color' => 'required'
            ]);

            if (!isset($attr['message'])) {
                $attr['message'] = '';
            }

            StoryModel::add(auth()->id(), $attr['message'], $attr['image'], $attr['color'], 1);

            return response()->json(array('code' => 200, 'msg' => __('app.story_added')));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Add message to story
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStoryText()
    {
        try {
            $attr = request()->validate([
               'message' => 'required',
               'color' => 'required',
               'bgcolor' => 'required'
            ]);

            StoryModel::add(auth()->id(), $attr['message'], $attr['bgcolor'], $attr['color'], 2);

            return response()->json(array('code' => 200, 'msg' => __('app.story_added')));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Set story image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStoryImage()
    {
        try {
            $att = request()->file('image');

            $fname = $att->getClientOriginalName() . '_' . uniqid('', true) . '_' . md5($att->getClientOriginalName()) . '_' . strval(auth()->id()) . '_';
            $fext = $att->getClientOriginalExtension();
            $att->move(public_path() . '/gfx/stories/', $fname . '.' . $fext);
            if (!PostModel::isValidImage(public_path() . '/gfx/stories/' . $fname . '.' . $fext)) {
                unlink(public_path() . '/gfx/stories/', $fname . '.' . $fext);
                throw new Exception(__('app.post_invalid_image'));
            }

            $baseFile = public_path() . '/gfx/stories/' . $fname;
            $fullFile = $baseFile . '.' . $fext;

            if (!PostModel::createThumbFile($fullFile, PostModel::getImageType($fext, $baseFile), $baseFile, $fext)) {
                throw new Exception('createThumbFile failed', 500);
            }

            $imageName = $fname . '_thumb.' . $fext;
            unlink($fullFile);

            return response()->json(array('code' => 200, 'name' => $imageName));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Expire the stories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function expireStories()
    {
        try {
            StoryModel::expireStory();

            return response()->json(array('code' => 200));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete story
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStory($id)
    {
        try {
            StoryModel::deleteStory($id);

            return response()->json(array('code' => 200, 'msg' => __('app.story_deleted')));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
