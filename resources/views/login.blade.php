@extends('layouts.auth')

@section('title', 'ログイン - くらすと')

@section('illustrations')
    <!-- 左上：木の棚とガラス瓶 -->
    <div class="hidden md:block absolute -top-2 left-0 lg:left-2 w-44 pointer-events-none select-none z-10" aria-hidden="true">
        <svg viewBox="0 0 180 130" fill="none" class="w-full h-auto">
            <!-- 黄色いキラキラ -->
            <path d="M118 22L122 10" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M132 30L143 21" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M138 44L151 42" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />

            <!-- 左の大きな瓶 -->
            <rect x="26" y="28" width="54" height="62" rx="12" fill="#E3EEFC" stroke="#B4C6DE" stroke-width="2.6" />
            <rect x="33" y="18" width="40" height="12" rx="5" fill="#9DC0EB" stroke="#85A9D8" stroke-width="2.2" />
            <path d="M36 44V76" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" opacity="0.7" />

            <!-- 右の小さな瓶 -->
            <rect x="92" y="48" width="38" height="42" rx="8" fill="#FAF0E4" stroke="#C7B29B" stroke-width="2.5" />
            <rect x="96" y="39" width="30" height="10" rx="4" fill="#D8BEA2" stroke="#B69A7E" stroke-width="2.2" />
            <rect x="98" y="61" width="26" height="14" rx="3" fill="#FFFDF9" opacity="0.85" />

            <!-- 木の棚 -->
            <rect x="8" y="88" width="136" height="12" rx="4" fill="#EBD7BE" stroke="#C8AF92" stroke-width="2.4" />
            <path d="M22 100V116M130 100V116" stroke="#D7C0A4" stroke-width="7" stroke-linecap="round" />
        </svg>
    </div>

    <!-- 左下：段ボール箱に入った白くま（チェックエプロン付き） -->
    <div class="hidden md:block absolute -bottom-8 left-0 lg:left-2 w-48 pointer-events-none select-none z-30 animate-float-slow" aria-hidden="true">
        <svg viewBox="0 0 190 155" fill="none" class="w-full h-auto">
            <!-- 黄色いキラキラ -->
            <path d="M136 22L141 10" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M148 31L160 22" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />
            <path d="M153 45L166 44" stroke="#FAD074" stroke-width="3.6" stroke-linecap="round" />

            <!-- 箱の後ろ側 -->
            <path d="M26 74L162 66L158 134L22 142Z" fill="#DFBE9B" stroke="#AE8D6D" stroke-width="2.6" stroke-linejoin="round" />

            <!-- 白くま -->
            <circle cx="62" cy="40" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <circle cx="62" cy="40" r="6" fill="#FFCCD5" />
            <circle cx="112" cy="36" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <circle cx="112" cy="36" r="6" fill="#FFCCD5" />
            <ellipse cx="87" cy="64" rx="42" ry="34" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
            <!-- 目・鼻・口・ほっぺ -->
            <circle cx="72" cy="60" r="3" fill="#4A3831" />
            <circle cx="101" cy="58" r="3" fill="#4A3831" />
            <ellipse cx="87" cy="63" rx="3" ry="2.2" fill="#4A3831" />
            <path d="M82.5 67C84.5 68.8 86.5 68.3 87 66.5C87.5 68.3 89.5 68.8 91.5 67" stroke="#4A3831" stroke-width="2" stroke-linecap="round" />
            <ellipse cx="62" cy="67" rx="5.5" ry="3.5" fill="#FFAAB8" opacity="0.65" />
            <ellipse cx="112" cy="65" rx="5.5" ry="3.5" fill="#FFAAB8" opacity="0.65" />

            <!-- 箱の前面 -->
            <path d="M20 74L154 78L152 140L26 144Z" fill="#E8CCA9" stroke="#AE8D6D" stroke-width="2.6" stroke-linejoin="round" />
            <!-- 前掛け風チェッククロス -->
            <path d="M62 76C64 98 76 112 91 112C106 112 116 98 118 77Z" fill="#F8FBFF" stroke="#8BB3E8" stroke-width="2.4" />
            <path d="M74 77V107M90 77V112M106 77V106M65 89H115M70 100H110" stroke="#B7D2F5" stroke-width="1.8" />
            <!-- ちょこんと出た手 -->
            <ellipse cx="56" cy="76" rx="9" ry="6.5" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.2" />
            <ellipse cx="118" cy="77" rx="9" ry="6.5" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.2" />
        </svg>
    </div>

    <!-- 右上：チェックリストのクリップボード -->
    <div class="hidden md:block absolute top-4 right-2 lg:right-4 w-36 pointer-events-none select-none z-10" aria-hidden="true">
        <svg viewBox="0 0 150 170" fill="none" class="w-full h-auto">
            <g transform="rotate(8 75 85)">
                <rect x="26" y="22" width="96" height="124" rx="12" fill="#E7D0B3" stroke="#C1A584" stroke-width="2.6" />
                <rect x="35" y="34" width="78" height="102" rx="6" fill="#FFFFFF" />
                <!-- クリップ -->
                <rect x="52" y="13" width="44" height="18" rx="6" fill="#C4CFE0" stroke="#9AA9C0" stroke-width="2.2" />
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

    <!-- 右下：双葉の瓶と観葉植物 -->
    <div class="hidden md:block absolute -bottom-8 right-0 lg:right-2 w-48 pointer-events-none select-none z-20" aria-hidden="true">
        <svg viewBox="0 0 190 140" fill="none" class="w-full h-auto">
            <!-- 双葉のガラス瓶 -->
            <rect x="18" y="34" width="66" height="84" rx="16" fill="#F3F6FA" stroke="#BAC7D8" stroke-width="2.6" />
            <rect x="27" y="22" width="48" height="13" rx="5" fill="#CBD5E1" stroke="#94A3B8" stroke-width="2.2" />
            <!-- 中身の白いラベルと双葉 -->
            <rect x="26" y="54" width="50" height="48" rx="10" fill="#FFFFFF" opacity="0.9" />
            <path d="M51 84V70" stroke="#76A586" stroke-width="2.4" stroke-linecap="round" />
            <path d="M51 74C42 74 37 68 38 61C45 61 50 66 51 74Z" fill="#9BC9A9" />
            <path d="M51 73C60 73 65 67 64 60C57 60 52 65 51 73Z" fill="#7FB48F" />

            <!-- 観葉植物の葉 -->
            <path d="M134 78V32" stroke="#6E9E7D" stroke-width="3" stroke-linecap="round" />
            <ellipse cx="134" cy="24" rx="9" ry="16" fill="#7DB58E" />
            <ellipse cx="115" cy="42" rx="14" ry="8" transform="rotate(-24 115 42)" fill="#94C5A3" />
            <ellipse cx="153" cy="40" rx="14" ry="8" transform="rotate(24 153 40)" fill="#70A781" />
            <ellipse cx="114" cy="64" rx="13" ry="8" transform="rotate(-12 114 64)" fill="#88BC98" />
            <ellipse cx="154" cy="62" rx="13" ry="8" transform="rotate(14 154 62)" fill="#88BC98" />
            <!-- 白い植木鉢 -->
            <path d="M110 78H158L152 118C151.4 121.4 148.5 124 145 124H123C119.5 124 116.6 121.4 116 118L110 78Z" fill="#FFFFFF" stroke="#B6C3D4" stroke-width="2.5" />
            <rect x="106" y="72" width="56" height="9" rx="4" fill="#F1F5F9" stroke="#B6C3D4" stroke-width="2.3" />
        </svg>
    </div>
@endsection

@section('card-content')
    <!-- システムタイトル -->
    <p class="text-center text-base sm:text-[17px] font-extrabold text-brand-textDark tracking-wide mb-7 font-rounded">
        ＼ おうちのストックを、かんたん管理 ／
    </p>

    <!-- ログアウトなどのステータスメッセージ -->
    @if (session('status'))
        <div class="mb-5 rounded-2xl bg-blue-50 border border-blue-200 px-4 py-3 text-left text-xs font-bold text-brand-blueDark font-rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- 認証エラー・入力エラー表示 -->
    @if ($errors->any())
        <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-left text-xs font-bold text-red-600 font-rounded space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf

        <!-- メールアドレス入力欄 -->
        <div class="relative">
            <label for="email" class="sr-only">メールアドレス</label>
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#8BB3E8]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="14" rx="2.5" />
                    <path d="M3.5 7l8.5 6 8.5-6" />
                </svg>
            </div>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', '') }}"
                placeholder="メールアドレス"
                required
                autofocus
                autocomplete="email"
                class="w-full h-[52px] py-3 pl-12 pr-4 rounded-2xl border border-brand-border bg-white text-sm text-brand-textDark placeholder:text-slate-400 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 transition"
            >
        </div>

        <!-- パスワード入力欄 -->
        <div class="relative">
            <label for="password" class="sr-only">パスワード</label>
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#8BB3E8]">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 1 1 4 0v2h-4V7Zm2.8 8.7V17a.8.8 0 1 1-1.6 0v-1.3a1.6 1.6 0 1 1 1.6 0Z" />
                </svg>
            </div>
            <input
                id="password"
                name="password"
                type="password"
                placeholder="パスワード"
                required
                autocomplete="current-password"
                class="w-full h-[52px] py-3 pl-12 pr-4 rounded-2xl border border-brand-border bg-white text-sm text-brand-textDark placeholder:text-slate-400 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 transition"
            >
        </div>

        <!-- ログインボタン -->
        <div class="pt-3 flex justify-center">
            <button
                type="submit"
                class="pressable w-full max-w-[290px] h-[52px] py-3 rounded-full bg-gradient-to-r from-[#8EB6EC] to-[#7AA6E3] text-white font-black text-base tracking-wider shadow-auth-btn hover:opacity-95 flex items-center justify-center gap-1.5 font-rounded cursor-pointer"
            >
                <span>ログイン</span>
                <svg class="w-4 h-4 stroke-[2.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>
    </form>

    <!-- アカウント登録画面へのリンク -->
    <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs font-bold text-brand-textMuted font-rounded">
        <span>はじめてご利用ですか？</span>
        <a href="{{ route('register') }}" class="ml-1 text-brand-blue hover:text-brand-blueDark hover:underline">
            アカウント登録はこちら
        </a>
    </div>
@endsection
