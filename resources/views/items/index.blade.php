@extends('layouts.app')

@section('title', 'おうちの在庫一覧 - くらすと')

@section('content')
<div class="space-y-6">

    <!-- ページヘッダー -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-soft border border-blue-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-brand-blue flex-shrink-0">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L2 12H5V20C5 20.55 5.45 21 6 21H18C18.55 21 19 20.55 19 20V12H22L12 3Z" fill="#5F9BE8" />
                    <path d="M10 14H14V21H10V14Z" fill="white" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl sm:text-3xl font-black text-brand-blue font-rounded tracking-tight">
                        おうちの在庫一覧
                    </h1>
                    <span class="text-brand-yellow text-xl">✨</span>
                </div>
                <p class="text-xs font-bold text-slate-400 font-rounded mt-0.5">
                    おうちにあるストックの数量や買い替え周期を管理できます
                </p>
            </div>
        </div>

        <a href="{{ route('items.create') }}" class="pressable px-6 py-3.5 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-black font-rounded text-sm shadow-btn-blue flex items-center gap-2 hover:opacity-95">
            <span>＋</span> 定期購入・アイテムを登録
        </a>
    </div>


    <!-- カテゴリーフィルター -->
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
        <a href="{{ route('items.index', ['category' => 'all']) }}" 
           class="px-5 py-2.5 rounded-full font-black text-sm font-rounded transition whitespace-nowrap {{ $selectedCategory === 'all' ? 'bg-brand-blue text-white shadow-btn-blue' : 'bg-white text-[#44566C] border border-blue-100 hover:bg-blue-50' }}">
            すべて
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('items.index', ['category' => $cat->name]) }}" 
               class="px-5 py-2.5 rounded-full font-black text-sm font-rounded transition whitespace-nowrap {{ $selectedCategory === $cat->name ? 'bg-brand-blue text-white shadow-btn-blue' : 'bg-white text-[#44566C] border border-blue-100 hover:bg-blue-50' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>


    <!-- 在庫アイテム一覧 (PC向けリッチカードグリッド) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-blue-100 hover:border-blue-300 transition flex flex-col justify-between space-y-4">
                
                <div>
                    <!-- 上部バッジ ＆ 削除 -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-extrabold px-3 py-1 rounded-full bg-blue-50 text-brand-blue border border-blue-100 font-rounded">
                            {{ $item->category ? $item->category->name : 'その他' }}
                        </span>

                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('「{{ $item->name }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-400 p-1 transition" title="削除">
                                <svg class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- 商品名 ＆ メモ -->
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-[#EAF2FC] flex items-center justify-center text-2xl flex-shrink-0 overflow-hidden">
                            @if($item->photo_original_ref)
                                <img src="{{ $item->photo_original_ref }}" alt="{{ $item->name }}" class="w-full h-full object-cover rounded-2xl">
                            @elseif(str_contains($item->name, '水') || str_contains($item->name, '天然水'))
                                🧴
                            @elseif(str_contains($item->name, '牛乳'))
                                🥛
                            @elseif(str_contains($item->name, 'たまご') || str_contains($item->name, '卵'))
                                🥚
                            @elseif(str_contains($item->name, 'ティッシュ') || str_contains($item->name, 'ペーパー'))
                                🧻
                            @else
                                📦
                            @endif
                        </div>
                        <div>
                            <h3 class="font-black text-lg text-[#2C3E50] font-rounded leading-snug">
                                {{ $item->name }}
                            </h3>
                            @if($item->memo)
                                <p class="text-xs text-slate-400 font-rounded mt-1">
                                    {{ $item->memo }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- 定期購入情報 -->
                    @if($item->is_recurring && $item->purchase_cycle_days)
                        <div class="mt-4 p-3 rounded-2xl bg-amber-50/60 border border-amber-200/60 flex items-center gap-2 text-xs font-bold text-amber-900 font-rounded">
                            <span>📅</span> 周期目安: <span class="font-black text-brand-blue">{{ $item->purchase_cycle_days }}</span> 日ごと
                        </div>
                    @endif
                </div>

                <!-- 下部：在庫数コントロール (＋ / ー で実際に運用可能！) -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block font-rounded">現在の在庫数</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-3xl font-black text-brand-blue font-rounded">{{ $item->stock_quantity }}</span>
                            <span class="text-xs font-bold text-slate-500 font-rounded">個</span>
                        </div>
                    </div>

                    <!-- ＋ / ー ボタン -->
                    <div class="flex items-center gap-2">
                        <!-- マイナス -->
                        <form action="{{ route('items.updateStock', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="change" value="-1">
                            <button type="submit" class="w-9 h-9 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-black text-lg flex items-center justify-center transition" title="1個減らす">
                                -
                            </button>
                        </form>

                        <!-- プラス -->
                        <form action="{{ route('items.updateStock', $item->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="change" value="1">
                            <button type="submit" class="w-9 h-9 rounded-2xl bg-brand-blue hover:bg-brand-blueDark text-white font-black text-lg flex items-center justify-center transition shadow-sm" title="1個増やす">
                                +
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-white rounded-[32px] border border-blue-100">
                <div class="text-4xl mb-3">📦</div>
                <p class="font-bold text-slate-500 font-rounded text-base">このカテゴリーにはアイテムがありません</p>
                <a href="{{ route('items.create') }}" class="inline-block mt-4 text-xs font-bold text-brand-blue hover:underline">
                    ＋ 新しくアイテムを登録する
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
