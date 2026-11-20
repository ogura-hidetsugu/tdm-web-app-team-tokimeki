@extends('layouts.app')

@section('title', '毎日の買い物リスト - くらすと')

@section('content')
<div class="space-y-6">

    <!-- ページヘッダー (画像1デザイン) -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-soft border border-blue-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- カートアイコン -->
            <div class="w-14 h-14 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue flex-shrink-0">
                <svg class="w-8 h-8 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                    <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                    <path d="M2 3H5L7 15H19L21 7H6" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-blue font-rounded tracking-tight">
                        毎日の買い物リスト
                    </h1>
                    <!-- 黄色いキラキラ線 -->
                    <div class="flex items-center text-brand-yellow">
                        <svg class="w-6 h-6 stroke-brand-yellow stroke-[2.8]" viewBox="0 0 20 20" fill="none">
                            <line x1="3" y1="17" x2="16" y2="4" />
                            <line x1="10" y1="19" x2="19" y2="10" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-bold text-slate-400 font-rounded mt-0.5">
                    家族みんなで買いたいものをリアルタイムでチェック＆共有できます
                </p>
            </div>
        </div>

        <!-- アクションボタン群 -->
        <div class="flex items-center gap-3">
            <a href="{{ route('shopping.create') }}" class="pressable px-5 py-3 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-extrabold font-rounded text-sm shadow-btn-blue flex items-center gap-2 hover:opacity-95">
                <svg class="w-5 h-5 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                入力して追加
            </a>
            <a href="{{ route('items.create') }}" class="pressable px-4 py-3 rounded-2xl bg-white border border-blue-200 text-brand-blue font-bold font-rounded text-sm hover:bg-blue-50 transition">
                定期購入を登録
            </a>
        </div>
    </div>


    <!-- メインコンテンツ 2カラム (PCレイアウト) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- 左側メイン：買い物リストカード (8カラム) -->
        <div class="lg:col-span-8 bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6">
            
            <!-- 1. 店舗フィルタータブ ＆ 売り場順トグル (画像1そのまま) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                
                <!-- フィルタータブ：「すべて」「スーパー」「薬局」... -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                    <a href="{{ route('shopping.index', ['store' => 'all', 'sort_by_store' => $sortByStore ? 1 : 0]) }}" 
                       class="px-5 py-2 rounded-full font-black text-sm font-rounded transition whitespace-nowrap {{ $selectedStore === 'all' ? 'bg-brand-blue text-white shadow-btn-blue' : 'bg-slate-100 text-[#44566C] hover:bg-blue-50' }}">
                        すべて <span class="text-xs opacity-90">({{ $counts['all'] ?? 0 }})</span>
                    </a>
                    @foreach($stores as $st)
                        @if($st->name !== '未設定')
                            <a href="{{ route('shopping.index', ['store' => $st->name, 'sort_by_store' => $sortByStore ? 1 : 0]) }}" 
                               class="px-5 py-2 rounded-full font-black text-sm font-rounded transition whitespace-nowrap {{ $selectedStore === $st->name ? 'bg-brand-blue text-white shadow-btn-blue' : 'bg-slate-100 text-[#44566C] hover:bg-blue-50' }}">
                                {{ $st->name }} <span class="text-xs opacity-90">({{ $counts[$st->name] ?? 0 }})</span>
                            </a>
                        @endif
                    @endforeach
                </div>

                <!-- 売り場順トグルスイッチ -->
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <span class="text-xs font-bold text-slate-500 font-rounded">売り場順</span>
                    <a href="{{ route('shopping.index', ['store' => $selectedStore, 'sort_by_store' => $sortByStore ? 0 : 1]) }}" 
                       class="w-12 h-6 flex items-center rounded-full p-1 cursor-pointer transition {{ $sortByStore ? 'bg-brand-blue justify-end' : 'bg-slate-200 justify-start' }}" title="売り場順ソート">
                        <div class="bg-white w-4 h-4 rounded-full shadow-md transform transition"></div>
                    </a>
                </div>

            </div>


            <!-- 2. 「ネットで探す」バナー (画像1) -->
            <div class="bg-[#F2F7FD] rounded-2xl p-3.5 px-5 flex items-center justify-between border border-blue-100/80">
                <div class="flex items-center gap-2.5">
                    <span class="text-brand-blue text-lg">🏷️</span>
                    <span class="font-bold text-sm text-[#33465E] font-rounded">ネットで探す</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-400 font-rounded">{{ $counts['all'] }}件</span>
                    <a href="https://www.amazon.co.jp" target="_blank" class="text-xs font-extrabold text-brand-blue hover:underline font-rounded flex items-center gap-0.5">
                        すべて見る
                        <svg class="w-3.5 h-3.5 stroke-[2.6]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </a>
                </div>
            </div>


            <!-- 3. 未購入アイテムリスト -->
            <div>
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-blue"></span>
                        <h2 class="text-base font-black text-[#2C3E50] font-rounded">
                            {{ $selectedStore === 'all' ? '買うもの一覧' : $selectedStore . ' で買うもの' }}
                        </h2>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-rounded">
                        残り {{ $items->count() }} 品
                    </span>
                </div>

                @if($items->isEmpty())
                    <div class="text-center py-12 bg-blue-50/40 rounded-3xl border border-dashed border-blue-200">
                        <div class="text-4xl mb-3">🎉</div>
                        <p class="font-bold text-[#44566C] font-rounded text-base">買うものはすべて完了しています！</p>
                        <p class="text-xs text-slate-400 mt-1 font-rounded">新しく買うものを右のフォームから追加してみましょう</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($items as $item)
                            <div class="py-4 flex items-center justify-between gap-4 group hover:bg-blue-50/30 px-3 rounded-2xl transition">
                                
                                <div class="flex items-center gap-4 flex-1">
                                    <!-- アイコン／イラスト (品目に応じたかわいいアイコン) -->
                                    <div class="w-12 h-12 rounded-2xl bg-[#EAF2FC] flex items-center justify-center flex-shrink-0 text-2xl group-hover:scale-105 transition-transform">
                                        @if(str_contains($item->name, 'ティッシュ') || str_contains($item->name, 'ペーパー'))
                                            🧻
                                        @elseif(str_contains($item->name, '牛乳') || str_contains($item->name, 'ミルク'))
                                            🥛
                                        @elseif(str_contains($item->name, '卵') || str_contains($item->name, 'たまご'))
                                            🥚
                                        @elseif(str_contains($item->name, '食パン') || str_contains($item->name, 'パン'))
                                            🍞
                                        @elseif(str_contains($item->name, 'バナナ'))
                                            🍌
                                        @elseif(str_contains($item->name, 'りんご'))
                                            🍎
                                        @elseif(str_contains($item->name, 'キャベツ'))
                                            🥬
                                        @elseif(str_contains($item->name, 'じゃがいも'))
                                            🥔
                                        @else
                                            🛍️
                                        @endif
                                    </div>

                                    <!-- アイテム情報 -->
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-base font-extrabold text-[#2C3E50] font-rounded">
                                                {{ $item->name }}
                                            </span>
                                            @if($item->store)
                                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-100/70 text-brand-blue">
                                                    {{ $item->store->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($item->memo)
                                            <span class="text-xs font-bold text-slate-400 block mt-0.5 font-rounded">
                                                {{ $item->memo }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- 右側：丸いチェックボタン（画像1の○ボタン） ＆ 削除 -->
                                <div class="flex items-center gap-3">
                                    <!-- 削除ボタン（ホバーで表示） -->
                                    <form action="{{ route('shopping.destroy', $item->id) }}" method="POST" onsubmit="return confirm('「{{ $item->name }}」を取り消しますか？')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full text-slate-300 hover:text-red-400 hover:bg-red-50 flex items-center justify-center transition" title="取り消す">
                                            <svg class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- 丸いチェックトグルボタン (画像1の青い丸) -->
                                    <form action="{{ route('shopping.toggle', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 rounded-full border-2 border-brand-blue hover:bg-blue-100 flex items-center justify-center transition group/btn" title="購入済みにする">
                                            <svg class="w-5 h-5 text-transparent group-hover/btn:text-brand-blue/50 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


            <!-- 4. 購入済みセクション (折りたたみ可) -->
            @if($purchasedItems->isNotEmpty())
                <div class="pt-6 border-t border-slate-100">
                    <details class="group/details">
                        <summary class="flex items-center justify-between cursor-pointer list-none select-none text-slate-400 hover:text-slate-600 font-bold font-rounded text-sm">
                            <span class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                購入済みのアイテム ({{ $purchasedItems->count() }}件)
                            </span>
                            <span class="text-xs transition group-open/details:rotate-180">▼</span>
                        </summary>

                        <div class="mt-4 divide-y divide-slate-100 opacity-70">
                            @foreach($purchasedItems as $pItem)
                                <div class="py-3 flex items-center justify-between px-2">
                                    <div class="flex items-center gap-3">
                                        <form action="{{ route('shopping.toggle', $pItem->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center" title="未購入に戻す">
                                                <svg class="w-4 h-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </button>
                                        </form>
                                        <div>
                                            <span class="text-sm font-bold text-slate-500 line-through font-rounded">{{ $pItem->name }}</span>
                                            @if($pItem->purchasedBy)
                                                <span class="text-[10px] text-emerald-600 font-bold ml-2">({{ $pItem->purchasedBy->display_name }} が購入)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('shopping.destroy', $pItem->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-slate-300 hover:text-red-400 font-bold">削除</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </details>
                </div>
            @endif


            <!-- 5. 下部ダンボールに入ったくま＆買い物カートのイラスト (画像1) -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between select-none">
                <div class="flex items-center gap-3">
                    <!-- ダンボールに入った白くま -->
                    <div class="w-20 h-16 flex items-center justify-center">
                        <svg viewBox="0 0 100 80" fill="none" class="w-full h-full">
                            <!-- くまの耳・頭 -->
                            <circle cx="36" cy="24" r="9" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="36" cy="24" r="5" fill="#FFCCD5" />
                            <circle cx="64" cy="24" r="9" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="64" cy="24" r="5" fill="#FFCCD5" />
                            <ellipse cx="50" cy="40" rx="26" ry="22" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="43" cy="38" r="2" fill="#4A3831" />
                            <circle cx="57" cy="38" r="2" fill="#4A3831" />
                            <ellipse cx="50" cy="42" rx="2.5" ry="1.8" fill="#4A3831" />
                            <!-- ダンボール箱 -->
                            <path d="M20 48L50 42L80 48V75L50 78L20 75V48Z" fill="#FDE68A" stroke="#D97706" stroke-width="2" />
                            <path d="M50 42V78" stroke="#D97706" stroke-width="2" />
                            <!-- ちょこんと出た手 -->
                            <ellipse cx="32" cy="50" rx="6" ry="4" fill="#FFFFFF" stroke="#8C7971" stroke-width="1.8" />
                            <ellipse cx="68" cy="50" rx="6" ry="4" fill="#FFFFFF" stroke="#8C7971" stroke-width="1.8" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-rounded">
                        買ったものはタップで簡単に完了できます♪
                    </span>
                </div>

                <!-- 右下の買い物カート -->
                <div class="text-blue-200">
                    <svg class="w-10 h-10 stroke-current fill-none stroke-[2]" viewBox="0 0 24 24">
                        <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                        <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                        <path d="M2 3H5L7 15H19L21 7H6" />
                    </svg>
                </div>
            </div>

        </div>


        <!-- 右側：PC向けクイック追加フォーム ＆ 家族共有情報 (4カラム) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- 追加フォームカード -->
            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-blue-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-black text-[#2D415A] font-rounded text-base flex items-center gap-2">
                        <span>✏️</span> 新しい品を追加
                    </h3>
                    <a href="{{ route('shopping.create') }}" class="text-xs font-bold text-brand-blue hover:underline">
                        専用画面 ＞
                    </a>
                </div>

                <form action="{{ route('shopping.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="redirect_to" value="index">

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 font-rounded">品名 <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required placeholder="例: 牛乳、キャベツ、卵" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 font-rounded">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 font-rounded">購入先</label>
                        <select name="store_id" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm text-[#44566C] bg-white focus:outline-none focus:border-brand-blue font-rounded">
                            @foreach($stores as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5 font-rounded">メモ (任意)</label>
                        <input type="text" name="memo" placeholder="例: 10個入り、特売なら2つ" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue font-rounded">
                    </div>

                    <button type="submit" class="w-full pressable py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-extrabold text-sm font-rounded shadow-btn-blue hover:opacity-95 transition flex items-center justify-center gap-2">
                        <span>＋</span> 買い物リストに追加する
                    </button>
                </form>
            </div>

            <!-- 共有連携バナー -->
            <div class="bg-gradient-to-br from-[#EAF2FC] to-[#F3F7FD] rounded-[32px] p-6 border border-blue-200 shadow-card">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-2xl">👨‍👩‍👧</span>
                    <h3 class="font-black text-[#2D415A] font-rounded text-sm">家族みんなに通知されます</h3>
                </div>
                <p class="text-xs text-slate-500 font-rounded leading-relaxed mb-4">
                    あなたがリストに追加したアイテムや購入完了は、リアルタイムで家族全員に共有されます。
                </p>
                <a href="{{ route('family.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-blue hover:underline">
                    家族の共有状況を見る ＞
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
