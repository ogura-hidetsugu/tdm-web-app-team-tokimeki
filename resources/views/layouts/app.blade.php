<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'くらすと - 家族で共有する在庫・買い物管理')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: M PLUS Rounded 1c & Zen Maru Gothic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;500;700;800;900&family=Zen+Maru+Gothic:wght@500;700;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        rounded: ['"M PLUS Rounded 1c"', '"Zen Maru Gothic"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#4A85D9',
                            blueDark: '#356BB8',
                            blueLight: '#E8F1FC',
                            blueIce: '#F2F7FD',
                            yellow: '#FFC83B',
                            pink: '#FFAAB8',
                            coral: '#F87171',
                            text: '#44566C',
                            textDark: '#2C3E50',
                            textMuted: '#8496AA',
                            border: '#D9E6F6',
                        }
                    },
                    boxShadow: {
                        'soft': '0 10px 28px -4px rgba(74, 133, 217, 0.12), 0 4px 10px -2px rgba(74, 133, 217, 0.05)',
                        'card': '0 6px 18px 0 rgba(74, 133, 217, 0.08)',
                        'btn-blue': '0 6px 18px -2px rgba(74, 133, 217, 0.38)',
                        'cat': '0 3px 10px 0 rgba(74, 133, 217, 0.07)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'M PLUS Rounded 1c', 'Zen Maru Gothic', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F4F8FE;
            background-image: 
                radial-gradient(circle, #D8E7FA 14%, transparent 15%),
                radial-gradient(circle, #E3EFFD 20%, transparent 21%),
                radial-gradient(circle, #CEE2FA 10%, transparent 11%);
            background-size: 90px 90px, 140px 140px, 110px 110px;
            background-position: 10px 20px, 50px 60px, 25px 90px;
            -webkit-tap-highlight-color: transparent;
        }

        .pressable {
            transition: transform 0.16s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.16s ease;
        }
        .pressable:active {
            transform: scale(0.97);
        }

        @keyframes gentle-breathing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-breathe {
            animation: gentle-breathing 4s ease-in-out infinite;
        }

        @keyframes heart-float {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            50% { transform: translateY(-6px) scale(1.1) rotate(6deg); }
        }
        .animate-float-heart {
            animation: heart-float 2.8s ease-in-out infinite;
        }

        @keyframes speech-bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2.5px); }
        }
        .animate-bob {
            animation: speech-bob 3.2s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen text-brand-text flex flex-col antialiased selection:bg-brand-blueLight selection:text-brand-blue">

    <!-- ナビゲーションヘッダー -->
    <header class="bg-white/95 backdrop-blur-md border-b border-blue-100/80 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- ロゴ「くらすと」 -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                    <div class="w-10 h-10 flex items-center justify-center text-brand-blue group-hover:scale-105 transition-transform flex-shrink-0">
                        <svg class="w-10 h-10 stroke-brand-blue" viewBox="0 0 32 32" fill="none" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 14.5L16 4.5L28 14.5" />
                            <path d="M22 9.5V5.5H25V12" />
                            <path d="M7 13.5V25.5C7 26.6 7.9 27.5 9 27.5H23C24.1 27.5 25 26.6 25 25.5V13.5" />
                            <path d="M12.5 19H19.5V27.5" />
                        </svg>
                    </div>
                    <div class="relative flex items-center">
                        <span class="text-3xl font-black tracking-tight text-brand-blue font-rounded drop-shadow-sm whitespace-nowrap">
                            くらすと
                        </span>
                        <div class="absolute -top-1.5 -right-5 flex items-center justify-center pointer-events-none">
                            <svg class="w-5 h-5 text-brand-yellow stroke-brand-yellow" viewBox="0 0 20 20" fill="none" stroke-width="2.8" stroke-linecap="round">
                                <line x1="3" y1="17" x2="16" y2="4" />
                                <line x1="10" y1="19" x2="19" y2="10" />
                                <line x1="1" y1="10" x2="10" y2="1" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- PC用メインナビゲーションリンク（改行なし whitespace-nowrap） -->
                <nav class="hidden md:flex items-center gap-1.5 lg:gap-2.5 flex-nowrap">
                    <a href="{{ route('home') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('home') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                        </svg>
                        <span>ホーム</span>
                    </a>

                    <a href="{{ route('shopping.index') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('shopping.index') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 stroke-current fill-none stroke-[2.3] flex-shrink-0" viewBox="0 0 24 24">
                            <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                            <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                            <path d="M2 3H5L7 15H19L21 7H6" />
                        </svg>
                        <span>買い物リスト</span>
                    </a>

                    <a href="{{ route('shopping.create') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('shopping.create') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 stroke-current fill-none stroke-[2.5] flex-shrink-0" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        <span>入力して追加</span>
                    </a>

                    <a href="{{ route('items.create') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('items.create') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 stroke-current fill-none stroke-[2.3] flex-shrink-0" viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2.2" />
                        </svg>
                        <span>定期購入・登録</span>
                    </a>

                    <a href="{{ route('family.index') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('family.*') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 stroke-current fill-none stroke-[2.3] flex-shrink-0" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span>家族で共有</span>
                    </a>

                    <a href="{{ route('items.index') }}" class="px-3.5 lg:px-4 py-2.5 rounded-2xl font-bold font-rounded text-sm flex items-center gap-2 transition whitespace-nowrap flex-shrink-0 {{ request()->routeIs('items.index') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-600 hover:text-brand-blue hover:bg-blue-50' }}">
                        <svg class="w-4 h-4 stroke-current fill-none stroke-[2.3] flex-shrink-0" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                        <span>おうちの在庫</span>
                    </a>
                </nav>

                <!-- 右側アイテム（ユーザー情報・通知・スマホ用ハンバーガーボタン） -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- 家族グループ表示 (PCのみ) -->
                    <div class="hidden lg:flex items-center gap-2 bg-[#EAF2FC] px-3.5 py-1.5 rounded-full border border-blue-200/80 whitespace-nowrap">
                        <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold text-brand-blue font-rounded">{{ $headerGroupName ?? 'ときめきファミリー' }}</span>
                        <span class="text-slate-300">|</span>
                        <span class="text-xs font-bold text-[#44566C]">{{ $headerUserName ?? 'ゲスト' }}</span>
                    </div>

                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-2xl border border-blue-100 bg-white hover:bg-red-50 hover:border-red-200 hover:text-red-500 text-xs font-bold text-slate-500 transition shadow-sm whitespace-nowrap cursor-pointer font-rounded" title="ログアウト">
                                ログアウト
                            </button>
                        </form>
                    @endauth

                    <!-- ベルマーク（通知・少なくなった商品お知らせ ＆ ドロップダウン） -->
                    <div class="relative" id="notification-wrapper">
                        <button type="button" id="notification-bell-btn" class="relative w-11 h-11 rounded-2xl bg-white border border-blue-100 flex items-center justify-center text-brand-blue hover:bg-blue-50 transition shadow-sm flex-shrink-0" title="少なくなった商品・周期通知" aria-label="通知">
                            <svg class="w-6 h-6 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                            <!-- 件数バッジ -->
                            @if(($headerNotificationCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 px-1.5 py-0.5 min-w-[20px] h-5 bg-brand-coral text-white text-[10px] font-black font-rounded rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                    {{ $headerNotificationCount ?? 3 }}
                                </span>
                            @endif
                        </button>

                        <!-- ベルマークをクリックしたときのドロップダウンパネル -->
                        <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl border border-blue-100 p-4 space-y-3 z-50 animate-fade-in">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">🔔</span>
                                    <span class="font-extrabold text-sm text-[#2C3E50] font-rounded">お知らせ・通知</span>
                                </div>
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-brand-blue hover:underline font-rounded">
                                    通知設定を開く ＞
                                </a>
                            </div>

                            <!-- 1. 少なくなった商品 -->
                            <div>
                                <span class="text-[11px] font-black text-red-500 uppercase tracking-wide block mb-1.5 font-rounded flex items-center gap-1">
                                    <span>⚠️</span> 少なくなった商品
                                </span>
                                <div class="space-y-1.5">
                                    @forelse($headerLowStockItems ?? [] as $lowItem)
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-red-50/50 border border-red-100 text-xs">
                                            <span class="font-bold text-[#2C3E50]">{{ $lowItem->name }}</span>
                                            <span class="font-black text-red-500">残り {{ $lowItem->stock_quantity }} 個</span>
                                        </div>
                                    @empty
                                        <div class="text-[11px] text-slate-400 py-1">少なくなった商品はありません</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- 2. 購入周期が近づいたもの（予定順） -->
                            <div class="pt-2 border-t border-slate-100">
                                <span class="text-[11px] font-black text-brand-blue uppercase tracking-wide block mb-1.5 font-rounded flex items-center gap-1">
                                    <span>📅</span> もうすぐ買い替え（周期順）
                                </span>
                                <div class="space-y-1.5">
                                    @forelse(($headerCycleItems ?? [])->take(3) as $cItem)
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-blue-50/40 border border-blue-100 text-xs">
                                            <span class="font-bold text-[#2C3E50]">{{ $cItem->name }}</span>
                                            <span class="font-black text-brand-blue">あと {{ $cItem->days_until_next_purchase }} 日</span>
                                        </div>
                                    @empty
                                        <div class="text-[11px] text-slate-400 py-1">定期購入品はありません</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- 下部リンク -->
                            <div class="pt-2 border-t border-slate-100 text-center">
                                <a href="{{ route('notifications.index') }}" class="block w-full py-2.5 rounded-xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-extrabold text-xs font-rounded shadow-sm hover:opacity-95 transition">
                                    すべての通知・購入周期設定を見る
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- スマホ用ハンバーガーメニューボタン (md:hidden) -->
                    <button type="button" id="hamburger-btn" aria-label="メニューを開く" class="md:hidden w-11 h-11 rounded-2xl bg-[#EAF2FC] hover:bg-blue-100 text-brand-blue flex items-center justify-center transition border border-blue-200/80 focus:outline-none">
                        <!-- ハンバーガーアイコン (三本線) -->
                        <svg id="hamburger-icon-open" class="w-6 h-6 stroke-brand-blue stroke-[2.4]" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                        <!-- 閉じるアイコン (✕) -->
                        <svg id="hamburger-icon-close" class="w-6 h-6 stroke-brand-blue stroke-[2.4] hidden" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>

                </div>

            </div>
        </div>

        <!-- スマホ用ドロップダウンメニュー (ハンバーガーメニュー展開部) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-md border-t border-blue-100 px-4 pt-3 pb-6 space-y-2 shadow-xl animate-fade-in">
            
            <!-- ユーザー情報バナー (スマホ用) -->
            <div class="flex items-center justify-between p-3 rounded-2xl bg-[#F2F7FD] border border-blue-100 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-brand-blue font-rounded">{{ $headerGroupName ?? 'ときめきファミリー' }}</span>
                </div>
                <span class="text-xs font-bold text-[#44566C]">{{ $headerUserName ?? 'ゲスト' }}</span>
            </div>

            <!-- スマホ用メニューリンク一覧 -->
            <div class="grid grid-cols-1 gap-1.5 font-rounded text-sm font-bold">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('home') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                    </svg>
                    <span>ホーム</span>
                </a>

                <a href="{{ route('shopping.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('shopping.index') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 stroke-current fill-none stroke-[2.3]" viewBox="0 0 24 24">
                        <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                        <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                        <path d="M2 3H5L7 15H19L21 7H6" />
                    </svg>
                    <span>毎日の買い物リスト</span>
                </a>

                <a href="{{ route('shopping.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('shopping.create') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 stroke-current fill-none stroke-[2.5]" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>入力して追加</span>
                </a>

                <a href="{{ route('items.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('items.create') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 stroke-current fill-none stroke-[2.3]" viewBox="0 0 24 24">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                        <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2.2" />
                    </svg>
                    <span>定期購入・商品登録</span>
                </a>

                <a href="{{ route('family.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('family.*') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 stroke-current fill-none stroke-[2.3]" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span>家族で共有</span>
                </a>

                <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition {{ request()->routeIs('items.index') ? 'bg-brand-blue text-white shadow-btn-blue' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-5 h-5 stroke-current fill-none stroke-[2.3]" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                    </svg>
                    <span>おうちの在庫一覧</span>
                </a>
            </div>

            <!-- くまのワンポイントイラスト ＆ ログアウト -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs font-bold text-slate-400">
                <span>🐻 くらすとでスマートにお買い物♪</span>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 text-red-500 border border-red-100 font-bold text-xs cursor-pointer">
                            ログアウト
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </header>

    <!-- フラッシュメッセージ通知・エラー通知 -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 z-40">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-5 py-3.5 rounded-2xl shadow-lg flex items-center justify-between gap-3 animate-bounce-once">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✨</span>
                    <span class="font-bold font-rounded text-sm">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white text-lg font-bold">×</button>
            </div>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 z-40">
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl shadow-sm flex items-start justify-between gap-3 font-rounded text-sm font-bold">
                <div class="space-y-1">
                    @if(session('error'))
                        <p>⚠️ {{ session('error') }}</p>
                    @endif
                    @foreach($errors->all() as $error)
                        <p>⚠️ {{ $error }}</p>
                    @endforeach
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-lg font-bold">×</button>
            </div>
        </div>
    @endif

    <!-- メインコンテンツ -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- フッター -->
    <footer class="bg-white/70 border-t border-blue-100/60 py-6 mt-12 text-center text-xs text-slate-400 font-rounded">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-brand-blue font-bold">
                <span>くらすと</span>
                <span class="text-slate-300">|</span>
                <span class="text-slate-500">家族で買い物・在庫情報を共有するアプリ</span>
            </div>
            <div>
                © 2026 ときめきパワーアップガールズ ～今日もかわいくレベルアップ～
            </div>
        </div>
    </footer>

    <!-- ハンバーガーメニュー ＆ 通知ベルドロップダウン開閉制御スクリプト -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ハンバーガーメニュー
            const btn = document.getElementById('hamburger-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('hamburger-icon-open');
            const iconClose = document.getElementById('hamburger-icon-close');

            if (btn && menu) {
                btn.addEventListener('click', function () {
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        iconOpen.classList.add('hidden');
                        iconClose.classList.remove('hidden');
                    } else {
                        menu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    }
                });

                menu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        menu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    });
                });
            }

            // 通知ベルドロップダウン
            const bellBtn = document.getElementById('notification-bell-btn');
            const bellDropdown = document.getElementById('notification-dropdown');
            const bellWrapper = document.getElementById('notification-wrapper');

            if (bellBtn && bellDropdown) {
                bellBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    bellDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function (e) {
                    if (bellWrapper && !bellWrapper.contains(e.target)) {
                        bellDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

</body>
</html>
