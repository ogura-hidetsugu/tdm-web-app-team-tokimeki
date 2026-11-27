<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\Membership;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const DEFAULT_STORES = [
        ['name' => '未設定', 'kind' => 'other', 'sort_order' => 0],
        ['name' => 'スーパー', 'kind' => 'supermarket', 'sort_order' => 1],
        ['name' => '薬局', 'kind' => 'drugstore', 'sort_order' => 2],
        ['name' => 'Amazon', 'kind' => 'online', 'sort_order' => 3],
    ];

    private const DEFAULT_CATEGORIES = [
        ['name' => '食品', 'icon' => 'canned', 'sort_order' => 1],
        ['name' => '飲み物', 'icon' => 'bottle', 'sort_order' => 2],
        ['name' => '日用品', 'icon' => 'pump', 'sort_order' => 3],
        ['name' => '消耗品', 'icon' => 'tissue', 'sort_order' => 4],
        ['name' => 'コスメ', 'icon' => 'cosme', 'sort_order' => 5],
        ['name' => '薬・サプリ', 'icon' => 'pill', 'sort_order' => 6],
        ['name' => 'その他', 'icon' => 'box', 'sort_order' => 7],
    ];

    /**
     * ログイン画面を表示する。
     */
    public function showLogin(): View
    {
        return view('login');
    }

    /**
     * ログイン処理を実行する。
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'password.required' => 'パスワードを入力してください。',
        ]);

        $attempted = Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'));

        if (! $attempted) {
            return back()
                ->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'おかえりなさい！ログインしました。');
    }

    /**
     * アカウント登録画面を表示する。
     */
    public function showRegister(): View
    {
        return view('register');
    }

    /**
     * アカウント登録処理を実行する。
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスの形式で入力してください。',
            'email.max' => 'メールアドレスは255文字以内で入力してください。',
            'email.unique' => 'このメールアドレスはすでに登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワード（確認）と一致しません。',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $displayName = Str::limit(Str::before($validated['email'], '@'), 50, '');

            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'display_name' => $displayName !== '' ? $displayName : 'ユーザー',
                'is_active' => true,
            ]);

            $group = Group::create([
                'name' => Str::limit($user->display_name . 'のおうち', 80, ''),
            ]);

            Membership::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'role' => 'admin',
                'is_active' => true,
            ]);

            $this->seedInitialGroupResources($group);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'アカウント登録が完了しました！ようこそ「くらすと」へ♪');
    }

    /**
     * ログアウト処理を実行する。
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'ログアウトしました。');
    }

    /**
     * 新規グループ用の初期店舗・初期カテゴリーを作成する。
     */
    private function seedInitialGroupResources(Group $group): void
    {
        foreach (self::DEFAULT_STORES as $store) {
            Store::create([
                'group_id' => $group->id,
                'name' => $store['name'],
                'kind' => $store['kind'],
                'sort_order' => $store['sort_order'],
            ]);
        }

        foreach (self::DEFAULT_CATEGORIES as $category) {
            Category::create([
                'group_id' => $group->id,
                'name' => $category['name'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
            ]);
        }
    }
}
