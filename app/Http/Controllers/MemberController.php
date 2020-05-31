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

use App\TagsModel;
use App\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Show profile
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $user = User::get($id);
            if ((!$user) || ($user->deactivated)) {
                return back()->with('error', __('app.user_not_found_or_deactivated'));
            }

            $user->stats = User::getStats($user->id);

            return view('member.profile', [
                'user' => User::getByAuthId(),
                'profile' => $user,
                'taglist' => TagsModel::getPopularTags()
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Redirect to own profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function profile()
    {
        return redirect('/u/' . auth()->id());
    }
}
