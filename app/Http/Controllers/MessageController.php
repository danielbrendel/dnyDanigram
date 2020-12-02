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
use App\IgnoreModel;
use App\MessageModel;
use App\TagsModel;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * View message list
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

            $data = MessageModel::fetch(auth()->id(), env('APP_MESSAGEPACKLIMIT'), $paginate)->unique('channel')->values()->all();
            foreach ($data as &$item) {
                if ($item->senderId === auth()->id()) {
                    $item->user = User::get($item->userId);
                } else {
                    $item->user = User::get($item->senderId);
                }

                $item->diffForHumans = $item->created_at->diffForHumans();
            }

            return response()->json(array('code' => 200, 'data' => $data, 'min' => MessageModel::where('userId', '=', auth()->id())->min('id'), 'max' => MessageModel::where('userId', '=', auth()->id())->max('id')));
        } catch (\Exception $e) {
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
            $thread = MessageModel::getMessageThread($id);
            if (!$thread) {
                return back()->with('error', __('app.message_not_found'));
            }

            $thread['msg']->user = User::get($thread['msg']->userId);
            $thread['msg']->sender = User::get($thread['msg']->senderId);

            foreach($thread['previous'] as &$item) {
                $item->user = User::get($item->userId);
                $item->sender = User::get($item->senderId);
            }

            if ($thread['msg']->senderId == auth()->id()) {
                $thread['message_partner'] = $thread['msg']->user->username;
            } else {
                $thread['message_partner'] = $thread['msg']->sender->username;
            }

            return view('message.show', [
                'user' => User::getByAuthId(),
                'thread' => $thread,
				'cookie_consent' => AppModel::getCookieConsentText(),
                'taglist' => TagsModel::getPopularTags(),
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View message creation form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     */
    public function send()
    {
        try {
            $attr = request()->validate([
               'username' => 'required',
               'subject' => 'nullable',
               'text' => 'required'
            ]);

            if (!isset($attr['subject'])) {
                $attr['subject'] = '';
            }

            $sender = User::getByAuthId();
            if (!$sender) {
                throw new \Exception('Not logged in');
            }

            $receiver = User::getByUsername($attr['username']);
            if (!$receiver) {
                throw new \Exception(__('app.user_not_found'));
            }

            if (IgnoreModel::hasIgnored($receiver->id, $sender->id)) {
                throw new \Exception(__('app.user_not_receiving_messages'));
            }

            $id = MessageModel::add($receiver->id, $sender->id, $attr['subject'], $attr['text']);

            return redirect('/messages/show/' . $id)->with('flash.success', __('app.message_sent'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
