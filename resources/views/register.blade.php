@extends('layouts.auth')

@section('title', 'アカウント登録 - くらすと')

@section('illustrations')
    <!-- 左上：段ボール箱 -->
    <div class="hidden md:block absolute top-2 left-0 lg:left-2 w-40 pointer-events-none select-none z-10" aria-hidden="true">
        <svg viewBox="0 0 165 130" fill="none" class="w-full h-auto">
            <!-- 黄色いキラキラ -->
            <path d="M108 26L113 13" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M122 36L134 26" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M127 50L141 49" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />

            <!-- 閉じた段ボール箱 -->
            <path d="M22 46L66 34L114 52L70 66Z" fill="#F0D9BE" stroke="#B69677" stroke-width="2.5" stroke-linejoin="round" />
            <path d="M22 46L70 66V110L22 88Z" fill="#E3C6A4" stroke="#B69677" stroke-width="2.5" stroke-linejoin="round" />
            <path d="M70 66L114 52V94L70 110Z" fill="#EBD2B4" stroke="#B69677" stroke-width="2.5" stroke-linejoin="round" />
            <!-- テープ -->
            <path d="M44 40L92 59L86 61L38 42Z" fill="#FAF0E2" opacity="0.85" />
            <path d="M86 61L92 59V76L86 78Z" fill="#FAF0E2" opacity="0.85" />
        </svg>
    </div>

    <!-- 左下：チェックリストを持った箱入り白くま -->
    <div class="hidden md:block absolute -bottom-8 left-0 lg:left-2 w-48 pointer-events-none select-none z-30 animate-float-slow" aria-hidden="true">
        <svg viewBox="0 0 190 158" fill="none" class="w-full h-auto">
            <!-- 黄色いキラキラ -->
            <path d="M138 22L143 10" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M150 31L162 22" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M155 45L168 44" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />

            <!-- 箱の後ろ側 -->
            <path d="M26 74L162 66L158 134L22 142Z" fill="#DFBE9B" stroke="#AE8D6D" stroke-width="2.6" stroke-linejoin="round" />

            <!-- 白くま -->
            <circle cx="62" cy="40" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <circle cx="62" cy="40" r="6" fill="#FFCCD5" />
            <circle cx="112" cy="36" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <circle cx="112" cy="36" r="6" fill="#FFCCD5" />
            <ellipse cx="87" cy="64" rx="42" ry="34" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <circle cx="72" cy="60" r="3" fill="#4A3831" />
            <circle cx="101" cy="58" r="3" fill="#4A3831" />
            <ellipse cx="87" cy="63" rx="3" ry="2.2" fill="#4A3831" />
            <path d="M82.5 67C84.5 68.8 86.5 68.3 87 66.5C87.5 68.3 89.5 68.8 91.5 67" stroke="#4A3831" stroke-width="2" stroke-linecap="round" />
            <ellipse cx="62" cy="67" rx="5.5" ry="3.5" fill="#FFAAB8" opacity="0.65" />
            <ellipse cx="112" cy="65" rx="5.5" ry="3.5" fill="#FFAAB8" opacity="0.65" />

            <!-- 箱の前面 -->
            <path d="M20 74L154 78L152 140L26 144Z" fill="#E8CCA9" stroke="#AE8D6D" stroke-width="2.6" stroke-linejoin="round" />

            <!-- 持っているチェックリスト -->
            <g transform="rotate(-8 96 105)">
                <rect x="68" y="76" width="54" height="62" rx="7" fill="#E8F1FC" stroke="#9DC0EB" stroke-width="2.2" />
                <path d="M77 92L81 96L87 88" stroke="#6D9EE3" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="93" y1="93" x2="112" y2="93" stroke="#B6CBE6" stroke-width="3" stroke-linecap="round" />
                <path d="M77 107L81 111L87 103" stroke="#6D9EE3" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="93" y1="108" x2="112" y2="108" stroke="#B6CBE6" stroke-width="3" stroke-linecap="round" />
                <path d="M77 122L81 126L87 118" stroke="#6D9EE3" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="93" y1="123" x2="110" y2="123" stroke="#B6CBE6" stroke-width="3" stroke-linecap="round" />
            </g>

            <!-- 手 -->
            <ellipse cx="66" cy="84" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.2" />
            <ellipse cx="122" cy="88" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.2" />
        </svg>
    </div>

    <!-- 右上：水色クリップのクリップボード -->
    <div class="hidden md:block absolute top-4 right-2 lg:right-4 w-36 pointer-events-none select-none z-10" aria-hidden="true">
        <svg viewBox="0 0 150 170" fill="none" class="w-full h-auto">
            <g transform="rotate(8 75 85)">
                <rect x="26" y="22" width="96" height="124" rx="12" fill="#E7D0B3" stroke="#C1A584" stroke-width="2.6" />
                <rect x="35" y="34" width="78" height="102" rx="6" fill="#FFFFFF" />
                <!-- 水色クリップ -->
                <rect x="52" y="13" width="44" height="18" rx="6" fill="#9BC0ED" stroke="#7BA4DA" stroke-width="2.2" />
                <circle cx="74" cy="18" r="3" fill="#FFFFFF" />
                <!-- チェック3つと線 -->
                <path d="M46 56L51 61L60 51" stroke="#86B1EA" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="69" y1="57" x2="100" y2="57" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round" />

                <path d="M46 80L51 85L60 75" stroke="#86B1EA" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="69" y1="81" x2="100" y2="81" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round" />

                <path d="M46 104L51 109L60 99" stroke="#86B1EA" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />
                <line x1="69" y1="105" x2="96" y2="105" stroke="#CBD5E1" stroke-width="4" stroke-linecap="round" />
            </g>
        </svg>
    </div>

    <!-- 右下：カート表示ノートPCと段ボール箱 -->
    <div class="hidden md:block absolute -bottom-8 right-0 lg:right-2 w-52 pointer-events-none select-none z-20" aria-hidden="true">
        <svg viewBox="0 0 210 150" fill="none" class="w-full h-auto">
            <!-- 黄色いキラキラ -->
            <path d="M148 26L153 14" stroke="#FAD074" stroke-width="3.5" stroke-linecap="round" />
            <path d="M161 33L172 24" stroke="#FAD074" stroke-width="3.5" stroke-linecap="round" />
            <path d="M168 45L181 44" stroke="#FAD074" stroke-width="3.5" stroke-linecap="round" />

            <!-- 右後ろの段ボール2段 -->
            <rect x="130" y="58" width="52" height="36" rx="3" fill="#E9CFAF" stroke="#B69677" stroke-width="2.2" />
            <rect x="122" y="92" width="66" height="38" rx="3" fill="#E3C4A0" stroke="#B69677" stroke-width="2.2" />
            <line x1="150" y1="58" x2="150" y2="70" stroke="#FAF0E2" stroke-width="5" />
            <line x1="150" y1="92" x2="150" y2="104" stroke="#FAF0E2" stroke-width="5" />

            <!-- ノートPC -->
            <g transform="rotate(6 80 95)">
                <rect x="26" y="42" width="108" height="68" rx="8" fill="#DCE5F2" stroke="#9AA9C0" stroke-width="2.4" />
                <rect x="33" y="49" width="94" height="54" rx="4" fill="#FFFFFF" />
                <!-- 画面内のショッピングカート -->
                <path d="M56 62H64L70 84H96L102 68H66" stroke="#85B1EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="74" cy="91" r="3" fill="#85B1EB" />
                <circle cx="92" cy="91" r="3" fill="#85B1EB" />
                <!-- キーボード部分 -->
                <path d="M18 110H140L132 126C130.8 128.5 128.3 130 125.5 130H24.5C21.7 130 19.2 128.5 18 126L14 114C13.3 112 14.8 110 18 110Z" fill="#CBD6E8" stroke="#9AA9C0" stroke-width="2.4" />
                <line x1="34" y1="118" x2="120" y2="118" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" opacity="0.7" />
            </g>
        </svg>
    </div>
@endsection

@section('card-content')
    <!-- システムタイトル -->
    <p class="text-center text-lg sm:text-xl font-black text-[#73A2E3] tracking-wider mb-6 font-rounded">
        ＼ 商品管理システム ／
    </p>

    <!-- バリデーションエラー表示 -->
    @if ($errors->any())
        <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-left text-xs font-bold text-red-600 font-rounded space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <!-- メールアドレス -->
        <div>
            <label for="email" class="flex items-center gap-2 text-xs sm:text-sm font-bold text-brand-textDark mb-1.5 pl-0.5 font-rounded">
                <span class="inline-flex items-center justify-center w-5 h-4 rounded bg-[#78A6E4] text-white flex-shrink-0">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6l8 6 8-6" />
                    </svg>
                </span>
                <span>メールアドレス</span>
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', '') }}"
                required
                autofocus
                autocomplete="email"
                class="w-full h-11 px-4 rounded-xl border-2 border-[#C7D3E3] bg-white text-sm text-brand-textDark placeholder:text-slate-400 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 transition"
            >
        </div>

        <!-- パスワード -->
        <div>
            <label for="password" class="flex items-center gap-2 text-xs sm:text-sm font-bold text-brand-textDark mb-1.5 pl-0.5 font-rounded">
                <span class="inline-flex items-center justify-center w-5 h-5 text-[#78A6E4] flex-shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm2.8 8.7V17a.8.8 0 1 1-1.6 0v-1.3a1.6 1.6 0 1 1 1.6 0Z" />
                    </svg>
                </span>
                <span>パスワード</span>
            </label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
                class="w-full h-11 px-4 rounded-xl border-2 border-[#C7D3E3] bg-white text-sm text-brand-textDark placeholder:text-slate-400 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 transition"
            >
        </div>

        <!-- パスワード（確認） -->
        <div>
            <label for="password_confirmation" class="flex items-center gap-2 text-xs sm:text-sm font-bold text-brand-textDark mb-1.5 pl-0.5 font-rounded">
                <span class="inline-flex items-center justify-center w-5 h-5 text-[#78A6E4] flex-shrink-0">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm2.8 8.7V17a.8.8 0 1 1-1.6 0v-1.3a1.6 1.6 0 1 1 1.6 0Z" />
                    </svg>
                </span>
                <span>パスワード（確認）</span>
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                class="w-full h-11 px-4 rounded-xl border-2 border-[#C7D3E3] bg-white text-sm text-brand-textDark placeholder:text-slate-400 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 transition"
            >
        </div>

        <!-- アカウント登録ボタン -->
        <div class="pt-3 flex justify-center">
            <button
                type="submit"
                class="pressable w-full max-w-[290px] h-[52px] py-3 rounded-full bg-gradient-to-r from-[#82AFEB] to-[#6D9EE3] text-white font-black text-base tracking-wider shadow-auth-btn hover:opacity-95 flex items-center justify-center gap-1.5 font-rounded cursor-pointer"
            >
                <span>アカウント登録</span>
                <svg class="w-4 h-4 stroke-[2.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>
    </form>

    <!-- ログイン画面へのリンク -->
    <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs font-bold text-brand-textMuted font-rounded">
        <span>すでにアカウントをお持ちですか？</span>
        <a href="{{ route('login') }}" class="ml-1 text-brand-blue hover:text-brand-blueDark hover:underline">
            ログインはこちら
        </a>
    </div>
@endsection
