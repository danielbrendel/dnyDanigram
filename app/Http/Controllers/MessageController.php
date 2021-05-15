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

use App\AppModel;
use App\CaptchaModel;
use App\IgnoreModel;
use App\MessageModel;
use App\TagsModel;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class MessageController extends Controller
{
	/**
     * Constructor
     *
     * @return void
     */
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * View message list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function list()
    {
        return view('message.list', [
            'user' => User::getByAuthId(),
			'cookie_consent' => AppModel::getCookieConsentText(),
            'taglist' => TagsModel::getPopularTags(),
        ]);
    }

    /**
     * Fetch list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchList()
    {
        try {
            $paginate = request('paginate', null);

            $data = MessageModel::fetch(auth()->id(), env('APP_MESSAGEPACKLIMIT'), $paginate);
            foreach ($data as &$item) {
                if ($item->lm->senderId === auth()->id()) {
                    $item->lm->user = User::get($item->lm->userId);
                } else {
                    $item->lm->user = User::get($item->lm->senderId);
                }

                $item->lm->sender = User::get($item->lm->senderId);

                $item->lm->diffForHumans = $item->lm->created_at->diffForHumans();
            }

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Show message thread
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $msg = MessageModel::getMessageThread($id, auth()->id());
            if (!$msg) {
                return back()->with('error', __('app.message_not_found'));
            }

            $msg->user = User::get($msg->userId);
            $msg->sender = User::get($msg->senderId);

            if ($msg->senderId == auth()->id()) {
                $msg->message_partner = $msg->user->username;
            } else {
                $msg->message_partner = $msg->sender->username;
            }

            return view('message.show', [
                'user' => User::getByAuthId(),
                'msg' => $msg,
				'cookie_consent' => AppModel::getCookieConsentText(),
                'taglist' => TagsModel::getPopularTags(),
            ]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Query message pack
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function query()
    {
        try {
            $ident = request('id');
            $paginate = request('paginate');

            $data = MessageModel::queryThreadPack($ident, env('APP_MESSAGETHREADPACK'), $paginate);

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View message creation form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws Exception
     */
    public function create()
    {
        return view('message.create', [
            'user' => User::getByAuthId(),
            'username' => request('u', ''),
			'cookie_consent' => AppModel::getCookieConsentText(),
            'taglist' => TagsModel::getPopularTags(),
        ]);
    }

    /**
     * Send message
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws Throwable
     */
    public function send()
    {
        try {
            $attr = request()->validate([
               'username' => 'required',
               'subject' => 'required',
               'text' => 'required'
            ]);

            if (!isset($attr['subject'])) {
                $attr['subject'] = '';
            }

            $sender = User::getByAuthId();
            if (!$sender) {
                throw new Exception('Not logged in');
            }

            $receiver = User::getByUsername($attr['username']);
            if (!$receiver) {
                throw new Exception(__('app.user_not_found'));
            }

            if (IgnoreModel::hasIgnored($receiver->id, $sender->id)) {
                throw new Exception(__('app.user_not_receiving_messages'));
            }

            $id = MessageModel::add($receiver->id, $sender->id, $attr['subject'], $attr['text']);

            return redirect('/messages/show/' . $id)->with('flash.success', __('app.message_sent'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get amount of unread messages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unreadCount()
    {
        try {
            $count = MessageModel::unreadCount(auth()->id());

            return response()->json(array('code' => 200, 'count' => $count));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
