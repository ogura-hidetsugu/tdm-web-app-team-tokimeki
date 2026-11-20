@extends('layouts.app')

@section('title', '通知・購入周期設定 - くらすと')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <!-- ページヘッダー -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-soft border border-blue-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- ベルアイコン -->
            <div class="w-14 h-14 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue flex-shrink-0 relative">
                <svg class="w-8 h-8 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <span class="absolute top-2.5 right-2.5 w-3 h-3 bg-brand-coral rounded-full border-2 border-white animate-ping"></span>
                <span class="absolute top-2.5 right-2.5 w-3 h-3 bg-brand-coral rounded-full border-2 border-white"></span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-blue font-rounded tracking-tight">
                        お知らせ・購入周期設定
                    </h1>
                    <span class="text-brand-yellow text-xl">✨</span>
                </div>
                <p class="text-xs font-bold text-slate-400 font-rounded mt-0.5">
                    少なくなったストックと、購入周期が近づいた品を優先的にお知らせします
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('shopping.index') }}" class="pressable px-5 py-3 rounded-2xl bg-white border border-blue-200 text-brand-blue font-bold font-rounded text-sm hover:bg-blue-50 transition flex items-center gap-2">
                <span>🛒</span> 買い物リストを見る
            </a>
            <a href="{{ route('items.create') }}" class="pressable px-5 py-3 rounded-2xl bg-brand-blue text-white font-extrabold font-rounded text-sm shadow-btn-blue hover:bg-brand-blueDark transition flex items-center gap-2">
                <span>＋</span> 新しい定期購入を登録
            </a>
        </div>
    </div>


    <!-- 1. 少なくなった商品アラート（在庫僅少） -->
    <div class="bg-gradient-to-br from-red-50/60 via-white to-orange-50/40 rounded-[36px] p-6 sm:p-8 border border-red-100 shadow-soft space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-red-100/60">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">⚠️</span>
                <div>
                    <h2 class="text-lg font-black text-[#8B2E2E] font-rounded">
                        少なくなった商品 ({{ $lowStockItems->count() }}件)
                    </h2>
                    <span class="text-xs font-bold text-red-500 font-rounded">
                        そろそろ在庫がなくなる、または基準数を下回っている品です
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($lowStockItems as $item)
                <div class="bg-white rounded-2xl p-4 border border-red-100 shadow-sm flex items-center justify-between gap-3 hover:border-red-300 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-2xl flex-shrink-0">
                            @if(str_contains($item->name, 'たまご') || str_contains($item->name, '卵'))
                                🥚
                            @elseif(str_contains($item->name, '牛乳'))
                                🥛
                            @elseif(str_contains($item->name, 'ティッシュ') || str_contains($item->name, 'ペーパー'))
                                🧻
                            @else
                                ⚠️
                            @endif
                        </div>
                        <div>
                            <span class="text-sm font-extrabold text-[#2C3E50] font-rounded block">
                                {{ $item->name }}
                            </span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-[11px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">
                                    残り {{ $item->stock_quantity }} 個
                                </span>
                                @if($item->reorder_level)
                                    <span class="text-[10px] text-slate-400 font-bold">
                                        (基準: {{ $item->reorder_level }}個)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 買い物リストへ追加ボタン -->
                    <form action="{{ route('notifications.addToList', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="pressable px-3 py-2 rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-white font-extrabold text-xs font-rounded shadow-sm hover:opacity-95 transition whitespace-nowrap" title="買い物リストに追加">
                            🛒 買う
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full text-center py-6 text-slate-400 font-rounded text-sm">
                    現在、在庫が少なくなっている商品はありません✨
                </div>
            @endforelse
        </div>

    </div>


    <!-- 2. 購入周期が近づいたもの（残り日数の昇順ソートで表示！） -->
    <div class="bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">📅</span>
                <div>
                    <h2 class="text-lg font-black text-[#2D415A] font-rounded">
                        購入周期が近づいた商品（予定順）
                    </h2>
                    <span class="text-xs font-bold text-slate-400 font-rounded">
                        買い替え目安日が近い順に並んでいます。早めの準備が安心です
                    </span>
                </div>
            </div>
            <span class="text-xs font-bold text-brand-blue bg-blue-50 px-3 py-1.5 rounded-full self-start sm:self-auto">
                定期購入 {{ $recurringItems->count() }} 件
            </span>
        </div>

        <!-- 周期アイテム一覧（日数が少ない順） -->
        <div class="space-y-4">
            @forelse($recurringItems as $index => $item)
                @php
                    $days = $item->days_until_next_purchase ?? 30;
                    $isVeryClose = $days <= 3;
                    $isClose = $days <= 7;
                @endphp
                <div class="p-4 sm:p-5 rounded-3xl border transition flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $isVeryClose ? 'bg-amber-50/50 border-amber-200' : ($isClose ? 'bg-blue-50/30 border-blue-200' : 'bg-slate-50/40 border-slate-100') }}">
                    
                    <div class="flex items-center gap-4">
                        <!-- 順位バッジ -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs font-rounded {{ $index === 0 ? 'bg-brand-yellow text-amber-900 shadow-sm' : 'bg-slate-200 text-slate-600' }}">
                            {{ $index + 1 }}
                        </div>

                        <!-- アイコン -->
                        <div class="w-12 h-12 rounded-2xl bg-white border border-blue-100 flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                            @if(str_contains($item->name, '牛乳'))
                                🥛
                            @elseif(str_contains($item->name, 'たまご') || str_contains($item->name, '卵'))
                                🥚
                            @elseif(str_contains($item->name, 'トイレットペーパー'))
                                🧻
                            @elseif(str_contains($item->name, '水') || str_contains($item->name, '天然水'))
                                🧴
                            @else
                                📦
                            @endif
                        </div>

                        <!-- 商品名・周期情報 -->
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-extrabold text-[#2C3E50] font-rounded">
                                    {{ $item->name }}
                                </span>
                                @if($item->defaultStore)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white border border-blue-100 text-slate-500">
                                        {{ $item->defaultStore->name }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-400 font-rounded block mt-0.5">
                                周期: {{ $item->purchase_cycle_days }}日ごと
                                @if($item->memo) • {{ $item->memo }} @endif
                            </span>
                        </div>
                    </div>

                    <!-- 残り日数 ＆ 買い物リスト追加ボタン -->
                    <div class="flex items-center justify-between sm:justify-end gap-4 self-stretch sm:self-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200/60">
                        
                        <!-- 残り日数バッジ -->
                        <div class="text-right">
                            <span class="text-[11px] font-bold text-slate-400 block font-rounded">次回目安まで</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-bold text-slate-600">あと</span>
                                <span class="text-2xl font-black font-rounded {{ $isVeryClose ? 'text-amber-600' : 'text-brand-blue' }}">
                                    {{ $days }}
                                </span>
                                <span class="text-xs font-bold text-slate-600">日</span>
                            </div>
                        </div>

                        <!-- ワンクリックで買い物リストに追加 -->
                        <form action="{{ route('notifications.addToList', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="pressable px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-extrabold text-xs font-rounded shadow-btn-blue hover:opacity-95 transition flex items-center gap-1.5 whitespace-nowrap">
                                <span>🛒</span> 買い物リストに追加
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <div class="text-center py-8 text-slate-400 font-rounded text-sm">
                    定期購入アイテムがまだ登録されていません
                </div>
            @endforelse
        </div>

    </div>


    <!-- 3. 通知のカスタマイズ設定カード -->
    <div class="bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6">
        
        <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100">
            <span class="text-2xl">⚙️</span>
            <div>
                <h2 class="text-lg font-black text-[#2D415A] font-rounded">
                    通知タイミング設定
                </h2>
                <span class="text-xs font-bold text-slate-400 font-rounded">
                    購入周期が近づいた時にどのくらい前にお知らせするかを設定できます
                </span>
            </div>
        </div>

        <form action="{{ route('notifications.updateSettings') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1. 通知するタイミング -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 font-rounded">
                        通知する日数（目安日の何日前）
                    </label>
                    <select name="notify_days_before" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm font-bold text-[#44566C] bg-white focus:outline-none focus:border-brand-blue font-rounded">
                        <option value="1">1日前（前日）</option>
                        <option value="3" selected>3日前（おすすめ）</option>
                        <option value="5">5日前</option>
                        <option value="7">1週間前</option>
                    </select>
                </div>

                <!-- 2. 自動追加オプション -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 font-rounded">
                        目安日が来たら自動で買い物リストに追加
                    </label>
                    <select name="auto_add_shopping" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm font-bold text-[#44566C] bg-white focus:outline-none focus:border-brand-blue font-rounded">
                        <option value="0">手動で追加する（通知のみ）</option>
                        <option value="1">自動で買い物リストに追加する</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 font-rounded">
                    <span>💡 設定した内容は家族全員の通知に共有されます</span>
                </div>
                <button type="submit" class="pressable px-6 py-3 rounded-2xl bg-brand-blue text-white font-extrabold text-sm font-rounded shadow-btn-blue hover:bg-brand-blueDark transition">
                    設定を保存する
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
