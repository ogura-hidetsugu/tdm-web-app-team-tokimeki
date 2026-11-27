<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Membership;
use App\Models\ShoppingEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FamilyController extends Controller
{
    public function index()
    {
        $group = $this->currentGroup();

        if (!$group) {
            $members = collect();
            $entries = collect();
            $activities = [];
            $inviteCode = 'TOKI-DEFAULT';
            return view('family.index', compact('group', 'members', 'entries', 'activities', 'inviteCode'));
        }

        $members = Membership::where('group_id', $group->id)->with('user')->get();

        // 共有買い物リスト（最新の動き）
        $entries = ShoppingEntry::where('group_id', $group->id)
            ->with(['store', 'addedBy', 'purchasedBy'])
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        // 共有アクティビティ履歴（タイムライン）
        $activities = [];
        foreach ($entries as $e) {
            if ($e->status === '購入済') {
                $activities[] = [
                    'user' => $e->purchasedBy ? $e->purchasedBy->display_name : '家族',
                    'action' => '購入しました',
                    'item' => $e->name,
                    'time' => $e->purchased_at ? $e->purchased_at->diffForHumans() : 'たった今',
                    'type' => 'purchased',
                ];
            } else {
                $activities[] = [
                    'user' => $e->addedBy ? $e->addedBy->display_name : '家族',
                    'action' => '買い物リストに追加しました',
                    'item' => $e->name,
                    'time' => $e->created_at ? $e->created_at->diffForHumans() : '最近',
                    'type' => 'added',
                ];
            }
        }

        // 招待コード（グループごとに固定または生成）
        $inviteCode = 'TOKI-' . strtoupper(substr(md5($group->id . 'tokimeki'), 0, 6));

        return view('family.index', compact('group', 'members', 'entries', 'activities', 'inviteCode'));
    }

    public function addMember(Request $request)
    {
        Validator::make($request->all(), [
            'display_name' => 'required|string|max:50',
            'role' => 'nullable|string|in:admin,member',
        ])->validate();

        $group = $this->currentGroup();
        $name = $request->input('display_name');

        // ダミーメールアドレスでユーザーを作成
        $email = 'family_' . Str::random(6) . '@tokimeki.local';
        $user = User::create([
            'display_name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        Membership::create([
            'user_id' => $user->id,
            'group_id' => $group->id,
            'role' => $request->input('role', 'member'),
            'is_active' => true,
        ]);

        return back()->with('success', "「{$name}」さんを家族グループに追加しました！");
    }

    public function postReaction(Request $request)
    {
        $reaction = $request->input('reaction', 'bought'); // 'bought' = 買ったよ！, 'used' = 使ったよ！, 'thanks' = ありがとう！
        $group = $this->currentGroup();

        $messages = [
            'bought' => '「買ったよ！」と家族に通知を送りました！🛒✨',
            'used' => '「使ったよ！」と家族に通知を送りました！🧻✨',
            'thanks' => '「ありがとう！」と家族にハートを送りました！💖',
        ];

        return back()->with('success', $messages[$reaction] ?? '家族にメッセージを送りました！');
    }
}
