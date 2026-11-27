@extends('layouts.app')

@section('title', 'ホーム - くらすと')

@section('content')
<div class="space-y-8">

    <!-- PC向け 2カラムメインダッシュボード -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- 左側メインエリア (8カラム) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- 1. 白くまキャラクター ＆ 吹き出し ＆ 在庫・買い物リストカウンター -->
            <div class="relative pt-6">
                
                <!-- 上部キャラクターと吹き出しの配置エリア -->
                <div class="relative flex items-end justify-between px-4 -mb-3 z-10">
                    
                    <!-- 白くまのイラスト（カードにちょこんと両手を乗せる） -->
                    <div class="relative w-48 h-32 select-none pointer-events-none animate-breathe">
                        <svg viewBox="0 0 160 110" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full overflow-visible">
                            <!-- 黄色いキラキラ線 (3本) -->
                            <g transform="translate(136, 32)">
                                <path d="M0 6L9 0" stroke="#FFC83B" stroke-width="2.6" stroke-linecap="round" />
                                <path d="M6 14L17 10" stroke="#FFC83B" stroke-width="2.6" stroke-linecap="round" />
                                <path d="M4 22L13 27" stroke="#FFC83B" stroke-width="2.6" stroke-linecap="round" />
                            </g>

                            <!-- 左耳 -->
                            <circle cx="38" cy="30" r="14" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.6" />
                            <circle cx="38" cy="30" r="8" fill="#FFCCD5" />

                            <!-- 右耳 -->
                            <circle cx="98" cy="30" r="14" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.6" />
                            <circle cx="98" cy="30" r="8" fill="#FFCCD5" />

                            <!-- 頭部 -->
                            <ellipse cx="68" cy="62" rx="46" ry="38" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.6" />

                            <!-- つぶらな目 -->
                            <ellipse cx="53" cy="60" rx="3.2" ry="3.5" fill="#4A3831" />
                            <circle cx="54.2" cy="58.8" r="1.1" fill="#FFFFFF" />
                            <ellipse cx="83" cy="60" rx="3.2" ry="3.5" fill="#4A3831" />
                            <circle cx="84.2" cy="58.8" r="1.1" fill="#FFFFFF" />

                            <!-- 鼻と口 (ω) -->
                            <ellipse cx="68" cy="66" rx="3.5" ry="2.5" fill="#4A3831" />
                            <path d="M63.5 70C65.5 72 67.5 71.5 68 69.5C68.5 71.5 70.5 72 72.5 70" stroke="#4A3831" stroke-width="2" stroke-linecap="round" />

                            <!-- ほっぺチーク -->
                            <ellipse cx="43" cy="68" rx="6.5" ry="4" fill="#FFAAB8" opacity="0.65" />
                            <ellipse cx="93" cy="68" rx="6.5" ry="4" fill="#FFAAB8" opacity="0.65" />

                            <!-- ちょこんと乗せた前足（左手） -->
                            <ellipse cx="36" cy="94" rx="11" ry="8" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
                            <path d="M32 96V91" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M37 96V90" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M42 96V92" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />

                            <!-- ちょこんと乗せた前足（右手） -->
                            <ellipse cx="98" cy="94" rx="11" ry="8" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.5" />
                            <path d="M93 96V92" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M98 96V90" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M103 96V91" stroke="#8C7971" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </div>

                    <!-- 吹き出し：「そろそろ たまごがなくなるよ！」 -->
                    <div class="animate-bob mb-3">
                        <a href="{{ route('notifications.index') }}" class="relative bg-white/95 backdrop-blur-sm px-4 py-2.5 rounded-2xl shadow-card border border-blue-100 flex items-center gap-3 max-w-[280px] hover:border-brand-blue transition block">
                            <!-- たまごアイコン -->
                            <div class="w-7 h-8 flex-shrink-0 flex items-center justify-center">
                                <svg viewBox="0 0 28 34" fill="none" class="w-full h-full transform -rotate-12">
                                    <path d="M14 2C7 2 2 11 2 21C2 27.5 7.4 32 14 32C20.6 32 26 27.5 26 21C26 11 21 2 14 2Z" fill="#FFFDF5" stroke="#D4A373" stroke-width="1.8" />
                                    <ellipse cx="11" cy="18" rx="6.5" ry="9.5" fill="#FEF08A" opacity="0.6" />
                                    <ellipse cx="10" cy="13" rx="2" ry="4" fill="#FFFFFF" opacity="0.8" />
                                </svg>
                            </div>
                            <div>
                                @if($alertItem)
                                    <span class="text-xs text-red-500 font-extrabold flex items-center gap-1">
                                        <span>⚠️</span> 在庫お知らせ
                                    </span>
                                    <span class="text-sm font-extrabold text-[#475E7B] font-rounded">
                                        そろそろ <span class="text-brand-blue">{{ $alertItem->name }}</span> がなくなるよ！
                                    </span>
                                @else
                                    <span class="text-xs text-brand-blue font-extrabold flex items-center gap-1">
                                        <span>✨</span> 在庫チェック
                                    </span>
                                    <span class="text-sm font-extrabold text-[#475E7B] font-rounded">
                                        おうちの在庫は安心だよ♪
                                    </span>
                                @endif
                            </div>
                            <!-- 吹き出しのしっぽ -->
                            <div class="absolute top-1/2 -left-2 -translate-y-1/2 w-0 h-0 border-t-[6px] border-t-transparent border-b-[6px] border-b-transparent border-r-[8px] border-r-white"></div>
                        </a>
                    </div>

                </div>

                <!-- メインスタッツカード（おうちの在庫 & 買い物リスト） -->
                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-blue-100/90 relative z-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                        
                        <!-- 左側：おうちの在庫 -->
                        <a href="{{ route('items.index') }}" class="py-3 md:py-1 px-4 flex items-center justify-between group hover:bg-blue-50/40 rounded-2xl transition">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue group-hover:scale-105 transition-transform">
                                    <svg class="w-9 h-9" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3L2 12H5V20C5 20.55 5.45 21 6 21H18C18.55 21 19 20.55 19 20V12H22L12 3Z" fill="#5F9BE8" />
                                        <path d="M10 14H14V21H10V14Z" fill="white" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-500 block font-rounded">おうちの在庫</span>
                                    <div class="flex items-baseline gap-1.5 mt-0.5">
                                        <span class="text-4xl font-black text-brand-blue tracking-tight leading-none font-rounded">{{ $stockCount }}</span>
                                        <span class="text-base font-bold text-[#44566C] font-rounded">種類</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-brand-blue/50 group-hover:translate-x-1 transition-transform">
                                <svg class="w-6 h-6 stroke-[2.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </div>
                        </a>

                        <!-- 右側：買い物リスト -->
                        <a href="{{ route('shopping.index') }}" class="py-3 md:py-1 px-4 flex items-center justify-between group hover:bg-blue-50/40 rounded-2xl transition">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue group-hover:scale-105 transition-transform">
                                    <svg class="w-9 h-9 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                                        <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                                        <path d="M2 3H5L7 15H19L21 7H6" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-500 block font-rounded">買い物リスト</span>
                                    <div class="flex items-baseline gap-1.5 mt-0.5">
                                        <span class="text-4xl font-black text-brand-blue tracking-tight leading-none font-rounded">{{ $shoppingCount }}</span>
                                        <span class="text-base font-bold text-[#44566C] font-rounded">アイテム</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-brand-blue/50 group-hover:translate-x-1 transition-transform">
                                <svg class="w-6 h-6 stroke-[2.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </div>
                        </a>

                    </div>
                </div>

            </div>


            <!-- 2. メインアクションボタン（2つ並び） -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- 左ボタン：「アイテムを登録」 -->
                <a href="{{ route('items.create') }}" class="relative pressable rounded-3xl p-5 bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white shadow-btn-blue flex items-center justify-center gap-3 group hover:opacity-95">
                    <div class="absolute -top-2 -left-2 pointer-events-none">
                        <svg class="w-6 h-6 text-brand-yellow stroke-brand-yellow drop-shadow-sm" viewBox="0 0 20 20" fill="none" stroke-width="3" stroke-linecap="round">
                            <line x1="6" y1="14" x2="2" y2="18" />
                            <line x1="10" y1="10" x2="14" y2="3" />
                            <line x1="14" y1="14" x2="19" y2="18" />
                        </svg>
                    </div>

                    <div class="w-8 h-8 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="2.2" />
                        </svg>
                    </div>

                    <span class="font-black text-lg tracking-wide font-rounded">アイテムを登録</span>
                </a>

                <!-- 右ボタン：「買い物リストを見る ＞」 -->
                <a href="{{ route('shopping.index') }}" class="pressable rounded-3xl p-5 bg-white border-2 border-blue-200/90 text-brand-blue shadow-card flex items-center justify-center gap-3 group hover:bg-blue-50/50">
                    <div class="w-8 h-8 flex items-center justify-center text-brand-blue">
                        <svg class="w-8 h-8 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                            <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                            <path d="M2 3H5L7 15H19L21 7H6" />
                        </svg>
                    </div>

                    <span class="font-black text-lg text-brand-blue font-rounded flex items-center gap-1">
                        買い物リストを見る
                        <svg class="w-5 h-5 text-brand-blue inline stroke-[2.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </span>
                </a>

            </div>


            <!-- 3. カテゴリーセクション (8個のカード) -->
            <div class="bg-white/80 backdrop-blur-sm rounded-[32px] p-6 shadow-soft border border-blue-100">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🌱</span>
                        <h2 class="text-xl font-black text-[#2D415A] font-rounded tracking-tight">カテゴリー</h2>
                    </div>
                    <a href="{{ route('items.index') }}" class="text-sm font-bold text-brand-blue flex items-center gap-1 hover:underline">
                        すべて見る
                        <svg class="w-4 h-4 inline stroke-[2.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </a>
                </div>

                <!-- 4列×2行のグリッド (PCでは広々配置) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    
                    <!-- 1. 食品 -->
                    <a href="{{ route('items.index', ['category' => '食品']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <rect x="7" y="12" width="22" height="15" rx="3" fill="#FDBA74" stroke="#D97706" stroke-width="1.6" />
                                <rect x="7" y="16" width="22" height="8" fill="#FEE2E2" />
                                <ellipse cx="18" cy="20" rx="3" ry="2" fill="#EF4444" opacity="0.75" />
                                <ellipse cx="18" cy="12" rx="11" ry="4.5" fill="#E2E8F0" stroke="#94A3B8" stroke-width="1.6" />
                                <ellipse cx="18" cy="12" rx="7" ry="2.5" fill="#CBD5E1" />
                                <circle cx="15" cy="11.5" r="1.5" fill="#64748B" />
                                <path d="M15 11.5L20 11" stroke="#64748B" stroke-width="1.2" stroke-linecap="round" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">食品</span>
                    </a>

                    <!-- 2. 飲み物 -->
                    <a href="{{ route('items.index', ['category' => '飲み物']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <rect x="15" y="4" width="6" height="4" rx="1" fill="#60A5FA" stroke="#2563EB" stroke-width="1.2" />
                                <path d="M16 8H20L22 13H14L16 8Z" fill="#E0F2FE" stroke="#60A5FA" stroke-width="1.2" />
                                <rect x="12" y="13" width="12" height="18" rx="2" fill="#E0F2FE" stroke="#60A5FA" stroke-width="1.4" />
                                <circle cx="18" cy="21" r="4.5" fill="#BAE6FD" />
                                <path d="M18 18C17 19.5 16 20.8 16 22C16 23.1 16.9 24 18 24C19.1 24 20 23.1 20 22C20 20.8 19 19.5 18 18Z" fill="#38BDF8" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">飲み物</span>
                    </a>

                    <!-- 3. 日用品 -->
                    <a href="{{ route('items.index', ['category' => '日用品']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <path d="M18 7H13C12 7 11 8 11 9" stroke="#94A3B8" stroke-width="1.6" stroke-linecap="round" />
                                <rect x="16" y="9" width="4" height="4" fill="#CBD5E1" />
                                <rect x="11" y="13" width="14" height="18" rx="4" fill="#BAE6FD" stroke="#38BDF8" stroke-width="1.4" />
                                <ellipse cx="18" cy="22" rx="4" ry="5" fill="#E0F2FE" />
                                <circle cx="18" cy="22" r="1.5" fill="#0284C7" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">日用品</span>
                    </a>

                    <!-- 4. 消耗品 -->
                    <a href="{{ route('items.index', ['category' => '消耗品']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <rect x="6" y="18" width="24" height="12" rx="2" fill="#93C5FD" stroke="#3B82F6" stroke-width="1.4" />
                                <ellipse cx="18" cy="18" rx="7" ry="2.5" fill="#BFDBFE" />
                                <path d="M14 18C14 14 15 10 17 8C19 12 21 11 23 9C22 13 22 16 22 18H14Z" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">消耗品</span>
                    </a>

                    <!-- 5. コスメ -->
                    <a href="{{ route('items.index', ['category' => 'コスメ']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <ellipse cx="18" cy="24" rx="10" ry="5.5" fill="#FEE2E2" stroke="#F472B6" stroke-width="1.4" />
                                <ellipse cx="18" cy="23.5" rx="7.5" ry="3.5" fill="#FDE047" opacity="0.4" />
                                <ellipse cx="18" cy="14" rx="9" ry="5" fill="#FBCFE8" stroke="#F472B6" stroke-width="1.4" />
                                <ellipse cx="18" cy="14" rx="7" ry="3.5" fill="#FFFFFF" opacity="0.8" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">コスメ</span>
                    </a>

                    <!-- 6. 薬・サプリ -->
                    <a href="{{ route('items.index', ['category' => '薬・サプリ']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <circle cx="25" cy="24" r="4.5" fill="#E0F2FE" stroke="#60A5FA" stroke-width="1.4" />
                                <line x1="22" y1="24" x2="28" y2="24" stroke="#60A5FA" stroke-width="1.2" />
                                <g transform="rotate(-35 15 17)">
                                    <rect x="9" y="13" width="6" height="12" rx="3" fill="#60A5FA" stroke="#2563EB" stroke-width="1.4" />
                                    <path d="M9 19H15V22C15 23.6 13.6 25 12 25C10.4 25 9 23.6 9 22V19Z" fill="#FFFFFF" />
                                </g>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">薬・サプリ</span>
                    </a>

                    <!-- 7. その他 -->
                    <a href="{{ route('items.index', ['category' => 'その他']) }}" class="pressable bg-white rounded-2xl p-4 border border-blue-100 shadow-cat flex flex-col items-center justify-center hover:border-brand-blue transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg viewBox="0 0 36 36" fill="none" class="w-full h-full">
                                <path d="M8 14L18 9L28 14V26L18 31L8 26V14Z" fill="#FDE68A" stroke="#D97706" stroke-width="1.4" stroke-linejoin="round" />
                                <path d="M18 9V31" stroke="#D97706" stroke-width="1.4" />
                                <path d="M8 14L18 19L28 14" stroke="#D97706" stroke-width="1.4" />
                                <path d="M16 11L20 13V21L16 19V11Z" fill="#F59E0B" opacity="0.6" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-[#4B6282] font-rounded">その他</span>
                    </a>

                    <!-- 8. カテゴリー追加 -->
                    <a href="{{ route('items.create') }}" class="pressable bg-brand-blueLight/85 rounded-2xl p-4 border border-blue-100/50 shadow-cat flex flex-col items-center justify-center hover:bg-blue-100 transition group">
                        <div class="w-12 h-12 mb-2 flex items-center justify-center text-brand-blue group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </div>
                        <span class="text-xs font-black text-brand-blue font-rounded whitespace-nowrap">カテゴリー追加</span>
                    </a>

                </div>
            </div>


            <!-- 4. 「次回の買い替え予定」バナー ＆ 笑顔の白くま＆ハート -->
            <div class="relative bg-white rounded-3xl p-6 border border-blue-100 shadow-soft overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                    
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 group cursor-pointer" title="通知・周期設定を見る">
                        <div class="w-14 h-14 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue flex-shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-8 h-8 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="3" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                                <circle cx="8" cy="14" r="1" fill="currentColor" />
                                <circle cx="12" cy="14" r="1" fill="currentColor" />
                                <circle cx="16" cy="14" r="1" fill="currentColor" />
                                <circle cx="8" cy="18" r="1" fill="currentColor" />
                                <circle cx="12" cy="18" r="1" fill="currentColor" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 block font-rounded">次回の買い替え予定</span>
                            @if($upcomingItem)
                                <div class="text-lg font-bold text-[#33465E] font-rounded flex items-baseline gap-2 mt-0.5">
                                    <span class="text-brand-blue font-black text-xl">{{ $upcomingItem->name }}</span>
                                    <span>あと</span>
                                    <span class="text-3xl font-black text-brand-blue leading-none">{{ $upcomingItem->days_until_next_purchase ?? 3 }}</span>
                                    <span>日</span>
                                </div>
                            @else
                                <div class="text-sm font-bold text-[#33465E] font-rounded mt-1">
                                    定期購入を登録すると予定をお知らせします♪
                                </div>
                            @endif
                        </div>
                        <div class="text-brand-blue/60 ml-2 group-hover:translate-x-1 transition-transform">
                            <svg class="w-5 h-5 stroke-[2.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </div>
                    </a>

                    <!-- 笑顔の白くまイラスト（ハート付き） -->
                    <div class="relative w-40 h-24 self-end sm:self-auto select-none pointer-events-none">
                        <!-- 浮かぶピンクハート -->
                        <div class="absolute top-0 left-2 text-[#F472B6] animate-float-heart">
                            <svg class="w-6 h-6 fill-current drop-shadow-sm" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </div>

                        <!-- 笑顔のくま SVG -->
                        <svg viewBox="0 0 130 95" fill="none" class="w-full h-full overflow-visible">
                            <circle cx="34" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="34" cy="26" r="6" fill="#FFCCD5" />
                            <circle cx="82" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="82" cy="26" r="6" fill="#FFCCD5" />
                            <ellipse cx="58" cy="52" rx="36" ry="30" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <path d="M44 48C46 45 49 45 51 48" stroke="#4A3831" stroke-width="2.2" stroke-linecap="round" />
                            <path d="M66 48C68 45 71 45 73 48" stroke="#4A3831" stroke-width="2.2" stroke-linecap="round" />
                            <ellipse cx="58" cy="53" rx="2.8" ry="2" fill="#4A3831" />
                            <path d="M54.5 57C56 58.5 57.5 58 58 56.5C58.5 58 60 58.5 61.5 57" stroke="#4A3831" stroke-width="1.8" stroke-linecap="round" />
                            <ellipse cx="38" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <ellipse cx="78" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <ellipse cx="26" cy="74" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <path d="M23 75V72" stroke="#8C7971" stroke-width="1.6" stroke-linecap="round" />
                            <path d="M27 75V71" stroke="#8C7971" stroke-width="1.6" stroke-linecap="round" />
                            <ellipse cx="88" cy="76" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <path d="M85 77V73" stroke="#8C7971" stroke-width="1.6" stroke-linecap="round" />
                            <path d="M89 77V72" stroke="#8C7971" stroke-width="1.6" stroke-linecap="round" />
                        </svg>
                    </div>

                </div>

                <!-- 背景の下部水色ウェーブ -->
                <div class="absolute -bottom-4 -left-4 right-0 h-16 pointer-events-none opacity-40 z-0">
                    <svg viewBox="0 0 380 60" fill="none" class="w-full h-full">
                        <path d="M0 30C80 10 180 45 280 20C330 8 360 25 380 30V60H0V30Z" fill="#D7E8FC" />
                    </svg>
                </div>
            </div>

        </div>


        <!-- 右側サイドエリア (4カラム: PC向けクイック操作・家族共有ウィジェット) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- 1. ホームからすぐ追加！クイック買い物追加カード -->
            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-blue-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-[#2D415A] font-rounded text-base flex items-center gap-2">
                        <span class="text-xl">🛒</span> クイック追加
                    </h3>
                    <a href="{{ route('shopping.create') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        詳しく入力 ＞
                    </a>
                </div>

                <form action="{{ route('shopping.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="redirect_to" value="home">
                    
                    <div>
                        <input type="text" name="name" required placeholder="買うものを入力（例: たまご、牛乳）" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 font-rounded">
                    </div>

                    <div class="flex gap-2">
                        <select name="store_id" class="px-3 py-2.5 rounded-2xl border border-blue-200 text-xs text-slate-600 bg-white focus:outline-none focus:border-brand-blue font-rounded flex-1">
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name === '未設定' ? '購入先：未設定' : $st->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="pressable bg-brand-blue text-white px-5 py-2.5 rounded-2xl text-xs font-extrabold font-rounded shadow-btn-blue hover:bg-brand-blueDark transition flex items-center gap-1">
                            <span>＋</span> 追加
                        </button>
                    </div>
                </form>

                <!-- 直近の買い物リスト（プレビュー） -->
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-400 block mb-3 font-rounded">最近の買い物リスト</span>
                    <div class="space-y-2">
                        @forelse($recentShoppingEntries as $entry)
                            <div class="flex items-center justify-between p-2.5 rounded-xl {{ $entry->status === '購入済' ? 'bg-slate-50 opacity-60' : 'bg-blue-50/50' }}">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('shopping.toggle', $entry->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-5 h-5 rounded-full border-2 {{ $entry->status === '購入済' ? 'bg-emerald-500 border-emerald-500 text-white flex items-center justify-center' : 'border-blue-300 hover:border-brand-blue' }}">
                                            @if($entry->status === '購入済')
                                                <svg class="w-3 h-3 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <span class="text-xs font-bold text-[#33465E] {{ $entry->status === '購入済' ? 'line-through text-slate-400' : '' }}">
                                        {{ $entry->name }}
                                    </span>
                                </div>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white border border-blue-100 font-bold text-slate-500">
                                    {{ $entry->store ? $entry->store->name : '未設定' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-2 text-center">リストは空っぽです</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 2. 家族で共有ウィジェット -->
            <div class="bg-gradient-to-br from-[#EAF2FC] to-[#F3F7FD] rounded-[32px] p-6 border border-blue-200/80 shadow-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">👨‍👩‍👧</span>
                        <div>
                            <h3 class="font-black text-[#2D415A] font-rounded text-base">家族と共有中</h3>
                            <span class="text-xs text-slate-500 font-bold">{{ $group->name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('family.index') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        管理 ＞
                    </a>
                </div>

                <!-- メンバーアバター一覧 -->
                <div class="flex items-center gap-2 mb-4">
                    @foreach($familyMembers as $m)
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-full border border-blue-100 text-xs font-bold text-[#44566C]">
                            <span class="w-2 h-2 rounded-full {{ $m->is_active ? 'bg-green-400' : 'bg-slate-300' }}"></span>
                            {{ $m->user ? $m->user->display_name : 'メンバー' }}
                        </div>
                    @endforeach
                </div>

                <!-- ワンタップリアクション -->
                <div class="bg-white/80 backdrop-blur-sm p-3.5 rounded-2xl border border-blue-100">
                    <span class="text-xs font-bold text-slate-500 block mb-2 font-rounded">家族にワンタップで知らせる</span>
                    <div class="grid grid-cols-2 gap-2">
                        <form action="{{ route('family.postReaction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reaction" value="bought">
                            <button type="submit" class="w-full pressable py-2 px-3 rounded-xl bg-blue-500 text-white font-bold text-xs font-rounded hover:bg-blue-600 transition shadow-sm">
                                🛒 買ったよ！
                            </button>
                        </form>
                        <form action="{{ route('family.postReaction') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reaction" value="thanks">
                            <button type="submit" class="w-full pressable py-2 px-3 rounded-xl bg-pink-100 text-pink-600 font-bold text-xs font-rounded hover:bg-pink-200 transition">
                                💖 ありがとう！
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
