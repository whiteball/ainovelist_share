<?php

/**
 * NGユーザーのクッキーの重複を削除して、再設定し、その後NGユーザーのリストを返す。
 * 
 * @return string[] NGユーザーIDのリスト
 */
function clean_up_ng_users()
{
    helper('cookie');
    $users = get_cookie('ng_users');
    if (empty($users)) {
        return [];
    }

    $user_list = array_filter(explode(' ', $users), static fn ($val) => (int) $val > 0);

    if (empty($user_list)) {
        return [];
    }

    $user_list = array_unique($user_list);
    set_cookie('ng_users', implode(' ', $user_list), 31536000, '', '/', '', null, false, 'lax');

    return $user_list;
}

/**
 * NGタグのクッキーの重複を削除して、再設定し、その後NGタグのリストを返す。
 * 
 * @return string[] NGタグのリスト
 */
function clean_up_ng_tags()
{
    helper('cookie');
    $tags = get_cookie('ng_tags');
    if (empty($tags)) {
        return;
    }

    $tag_list = array_filter(explode(' ', $tags), static fn ($val) => ! empty($val));

    if (empty($tag_list)) {
        return;
    }

    $tag_list = array_unique($tag_list);
    set_cookie('ng_tags', implode(' ', $tag_list), 31536000, '', '/', '', null, false, 'lax');

    return $tag_list;
}
