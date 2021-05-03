<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Throwable;

/**
 * Class MessageModel
 *
 * Interface to private messages
 */
class MessageModel extends Model
{
    /**
     * Add message
     *
     * @param $userId
     * @param $senderId
     * @param $subject
     * @param $message
     * @return mixed
     * @throws Exception|Throwable
     */
    public static function add($userId, $senderId, $subject, $message)
    {
        try {
            $user = User::get($userId);
            if (!$user) {
                throw new Exception('User not found: ' . $userId);
            }

            $sender = User::get($senderId);
            if (!$sender) {
                throw new Exception('Sender not found: ' . $senderId);
            }

            $channel = MessageModel::select('channel')->where('userId', '=', $userId)->where('senderId', '=', $senderId)->first();
            if (!$channel) {
                $channel = MessageModel::select('channel')->where('senderId', '=', $userId)->where('userId', '=', $senderId)->first();
                if (!$channel) {
                    $channel = md5(strval($userId) . strval($senderId) . random_bytes(55));
                } else {
                    $channel = $channel->channel;
                }
            } else {
                $channel = $channel->channel;
            }

            $msg = new MessageModel();
            $msg->userId = $userId;
            $msg->senderId = $senderId;
            $msg->channel = $channel;
            $msg->subject = $subject;
            $msg->message = \Purifier::clean($message);
            $msg->save();

            PushModel::addNotification(__('app.new_message_short', ['name' => $sender->username]), __('app.new_message', ['name' => $sender->username, 'subject' => $subject]), 'PUSH_MESSAGED', $userId);

            if ($user->email_on_message) {
                $html = view('mail.message', ['name' => $user->username, 'sender' => $sender->username, 'message' => $message, 'msgid' => $msg->id])->render();
                MailerModel::sendMail($user->email, __('app.message_received'), $html);
            }

            return $msg->id;
        } catch (Exception $e) {
            throw $e;
        }

        return 0;
    }

    /**
     * Fetch message pack
     *
     * @param $userId
     * @param $limit
     * @param null $paginate
     * @return mixed
     * @throws Exception
     */
    public static function fetch($userId, $limit, $paginate = null)
    {
        try {
            $rowset = MessageModel::where('userId', '=', $userId)->orWhere('senderId', '=', $userId);

            if ($paginate !== null) {
                $rowset->where('id', '<', $paginate);
            }

            return $rowset->orderBy('id', 'desc')->limit($limit)->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get thread pack
     * 
     * @param $ident
     * @param $limit
     * @param $paginate
     * @return array
     * @throws Exception
     */
    public static function queryThreadPack($ident, $limit, $paginate = null)
    {
        try {
            $query = static::where('channel', '=', $ident)->where(function($query){
                $query->where('userId', '=', auth()->id())->orWhere('senderId', '=', auth()->id());
            });

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            $items = $query->orderBy('id', 'desc')->limit($limit)->get();
            foreach ($items as &$item) {
                $item->seen = true;
                $item->save();
            }

            $items = $items->toArray();

            foreach ($items as &$item) {
                $item['sender'] = User::get($item['senderId'])->toArray();
                $item['receiver'] = User::get($item['userId'])->toArray();
                $item['diffForHumans'] = Carbon::parse($item['created_at'])->diffForHumans();
            }

            return $items;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get message thread
     *
     * @param $msgId
     * @param $userId
     * @return array
     * @throws Exception
     */
    public static function getMessageThread($msgId, $userId)
    {
        try {
            $msg = MessageModel::where('id', '=', $msgId)->where(function($query) use ($userId) {
                $query->where('userId', '=', $userId)->orWhere('senderId', '=', $userId);
            })->first();
            
            if (!$msg) {
                throw new Exception('Message not found: ' . $msgId);
            }

            $msg->seen = true;
            $msg->save();

            /*$previous = MessageModel::where(function($query) use ($msg) {
                $query->where('userId', '=', $msg->userId)
                    ->where('senderId', '=', $msg->senderId)
                    ->where('id', '<>', $msg->id);
            })->orWhere(function($query) use ($msg) {
                $query->where('userId', '=', $msg->senderId)
                    ->where('senderId', '=', $msg->userId);
            })->orderBy('created_at', 'desc')->get();
            foreach ($previous as $item) {
                if (!$item->seen) {
                    $item->seen = true;
                    $item->save();
                }
            }

            return array(
              'msg' => $msg,
              'previous' => $previous
            );*/

            return $msg;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get amount of unread messages
     *
     * @param $userId
     * @return int
     * @throws Exception
     */
    public static function unreadCount($userId)
    {
        try {
            return MessageModel::where('userId', '=', $userId)->where('seen', '=', false)->count();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
