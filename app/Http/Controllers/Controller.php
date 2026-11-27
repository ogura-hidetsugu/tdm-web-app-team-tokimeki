<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * ログイン中のユーザー（未ログイン時はフォールバック）を取得する。
     */
    protected function currentUser(): ?User
    {
        return Auth::user() ?? User::first();
    }

    /**
     * ログイン中ユーザーの代表グループを取得する。
     */
    protected function currentGroup(): ?Group
    {
        $user = $this->currentUser();

        if ($user) {
            $group = $user->primaryGroup();
            if ($group) {
                return $group;
            }
        }

        return Group::first();
    }
}
