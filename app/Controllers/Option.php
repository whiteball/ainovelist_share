<?php

namespace App\Controllers;

use App\Models\User;

class Option extends BaseController
{
    public function ng()
    {
        helper('ng');
        $userIdList = clean_up_ng_users();
        $tagList    = clean_up_ng_tags();
        $userList   = [];

        if (! empty($userIdList)) {
            /** @var User */
            $user     = model(User::class);
            $userData = $user->whereIn('id', $userIdList)->findAll();

            foreach ($userData as $user) {
                $userList[$user->id] = $user->screen_name;
            }
        }

        return view('option/ng', ['userList' => $userList, 'tagList' => $tagList]);
    }
}
