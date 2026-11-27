<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    /**
     * 代表グループが存在しない場合は初期グループを作成して返す。
     */
    protected function ensureCurrentGroup(): Group
    {
        $group = $this->currentGroup();
        if ($group) {
            return $group;
        }

        $user = $this->currentUser();
        $baseName = $user
            ? ($user->display_name ?: Str::before($user->email, '@'))
            : 'マイホーム';

        $group = Group::create([
            'name' => Str::limit($baseName . 'のおうち', 50, ''),
        ]);

        if ($user) {
            Membership::firstOrCreate(
                ['user_id' => $user->id, 'group_id' => $group->id],
                ['role' => 'admin', 'is_active' => true]
            );
        }

        $group->seedDefaults();

        return $group;
    }
}
