@extends('layouts.app')

@section('title', '入力して追加 - くらすと')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- ヘッダーエリア (画像2デザイン) -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-soft border border-blue-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <!-- 閉じる／戻るボタン -->
            <a href="{{ route('shopping.index') }}" class="pressable px-4 py-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs font-rounded flex items-center gap-1.5 transition">
                <svg class="w-4 h-4 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
                閉じる
            </a>

            <!-- タイトル「入力して追加」 -->
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-[#2C3E50] font-rounded tracking-tight">
                    入力して追加
                </h1>
                <!-- 黄色いキラキラ -->
                <div class="flex items-center text-brand-yellow">
                    <svg class="w-5 h-5 stroke-brand-yellow stroke-[2.8]" viewBox="0 0 20 20" fill="none">
                        <line x1="3" y1="17" x2="16" y2="4" />
                        <line x1="10" y1="19" x2="19" y2="10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 「○品 入れました」バッジ (画像2) -->
        <div class="bg-[#EAF2FC] text-brand-blue px-4 py-2 rounded-2xl font-extrabold text-sm font-rounded border border-blue-200/80">
            <span class="text-lg text-brand-blueDark">{{ $addedCount }}</span> 品 入れました
        </div>
    </div>


    <!-- メイングリッド (PC向け 2カラムレイアウト) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- 左側：追加した品々のリスト ＆ 取り消し (7カラム) -->
        <div class="lg:col-span-7 bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-black text-[#2D415A] font-rounded flex items-center gap-2">
                    <span>📋</span> 追加された品目
                </h2>
                <span class="text-xs text-slate-400 font-bold font-rounded">
                    不要なものは「取り消す」を押せます
                </span>
            </div>

            <!-- 品目リスト (画像2そっくりに再現) -->
            <div class="divide-y divide-slate-100 max-h-[460px] overflow-y-auto pr-1">
                @forelse($entries as $entry)
                    <div class="py-3.5 flex items-center justify-between gap-3 group hover:bg-blue-50/30 px-3 rounded-2xl transition">
                        
                        <div class="flex items-center gap-3.5">
                            <!-- かわいいフルーツ・野菜・食材アイコン -->
                            <div class="w-11 h-11 rounded-2xl bg-[#EAF2FC] flex items-center justify-center flex-shrink-0 text-2xl group-hover:scale-105 transition-transform">
                                @if(str_contains($entry->name, 'りんご') || str_contains($entry->name, 'リンゴ'))
                                    🍎
                                @elseif(str_contains($entry->name, 'キャベツ') || str_contains($entry->name, 'レタス'))
                                    🥬
                                @elseif(str_contains($entry->name, 'じゃがいも') || str_contains($entry->name, 'ポテト'))
                                    🥔
                                @elseif(str_contains($entry->name, '牛乳') || str_contains($entry->name, 'ミルク'))
                                    🥛
                                @elseif(str_contains($entry->name, 'たまご') || str_contains($entry->name, '卵'))
                                    🥚
                                @elseif(str_contains($entry->name, 'バナナ'))
                                    🍌
                                @elseif(str_contains($entry->name, 'パン'))
                                    🍞
                                @else
                                    🛍️
                                @endif
                            </div>

                            <div>
                                <span class="text-sm font-extrabold text-[#2C3E50] font-rounded block">
                                    {{ $entry->name }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-400 font-rounded">
                                    {{ $entry->store ? $entry->store->name : '未設定' }}
                                    @if($entry->memo) • {{ $entry->memo }} @endif
                                </span>
                            </div>
                        </div>

                        <!-- 「取り消す」ボタン (画像2デザイン) -->
                        <form action="{{ route('shopping.destroy', $entry->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pressable px-3.5 py-1.5 rounded-full border border-blue-200 text-xs font-bold text-brand-blue hover:bg-blue-50 hover:border-blue-300 font-rounded transition">
                                取り消す
                            </button>
                        </form>

                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <p class="text-sm font-bold font-rounded">まだ品目が追加されていません</p>
                        <p class="text-xs mt-1">右側のフォームから追加してみましょう！</p>
                    </div>
                @endforelse
            </div>

            <!-- 下部ダンボールくま＆カート -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between select-none">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-14 flex items-center justify-center">
                        <svg viewBox="0 0 100 80" fill="none" class="w-full h-full">
                            <circle cx="36" cy="24" r="9" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="36" cy="24" r="5" fill="#FFCCD5" />
                            <circle cx="64" cy="24" r="9" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="64" cy="24" r="5" fill="#FFCCD5" />
                            <ellipse cx="50" cy="40" rx="26" ry="22" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <circle cx="43" cy="38" r="2" fill="#4A3831" />
                            <circle cx="57" cy="38" r="2" fill="#4A3831" />
                            <ellipse cx="50" cy="42" rx="2.5" ry="1.8" fill="#4A3831" />
                            <path d="M20 48L50 42L80 48V75L50 78L20 75V48Z" fill="#FDE68A" stroke="#D97706" stroke-width="2" />
                            <path d="M50 42V78" stroke="#D97706" stroke-width="2" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-rounded">
                        品目を追加したら「買い物リスト」に自動反映されます
                    </span>
                </div>
                <a href="{{ route('shopping.index') }}" class="text-xs font-extrabold text-brand-blue hover:underline font-rounded">
                    買い物リストを見る ＞
                </a>
            </div>

        </div>


        <!-- 右側：連続追加フォーム (5カラム: 画像2の下部フォームをPC向けに最適化) -->
        <div class="lg:col-span-5 bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6 sticky top-24">
            
            <div class="flex items-center gap-2">
                <span class="text-xl">✨</span>
                <h2 class="text-base font-black text-[#2D415A] font-rounded">
                    品目を入力して追加
                </h2>
            </div>

            <form action="{{ route('shopping.store') }}" method="POST" class="space-y-5" id="add-form">
                @csrf
                <input type="hidden" name="redirect_to" value="create">

                <!-- 1. 購入先選択ボタングループ (画像2デザイン: Amazon, スーパー, 薬局, 未設定) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-2 font-rounded">購入先を選択</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($stores as $st)
                            <label class="cursor-pointer">
                                <input type="radio" name="store_id" value="{{ $st->id }}" class="peer sr-only" {{ $st->name === '薬局' ? 'checked' : '' }}>
                                <div class="py-2.5 px-2 text-center rounded-2xl border border-blue-100 text-xs font-bold font-rounded text-slate-600 peer-checked:bg-brand-blue peer-checked:text-white peer-checked:border-brand-blue transition hover:bg-blue-50/50">
                                    {{ $st->name }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 2. 品名入力フォーム（画像2: 「薬局に入れる品」＋マイクアイコン） -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1.5 font-rounded">品名 <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input type="text" name="name" id="item-name-input" required placeholder="追加する品名（例: 牛乳、たまご、洗剤）" autofocus class="w-full pl-4 pr-12 py-3.5 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 font-rounded text-[#2C3E50]">
                        
                        <!-- マイクアイコン (画像2) -->
                        <button type="button" onclick="alert('音声入力が利用可能です（マイクをオンにしてください）')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-blue transition" title="音声入力">
                            <svg class="w-5 h-5 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" y1="19" x2="12" y2="23" />
                                <line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- 3. メモ（画像2: [＋メモ] ボタンで展開可能） -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-400 font-rounded">メモ (任意)</label>
                        <button type="button" onclick="document.getElementById('memo-box').classList.toggle('hidden')" class="text-xs font-bold text-brand-blue hover:underline font-rounded">
                            ＋メモを追加
                        </button>
                    </div>
                    <div id="memo-box" class="hidden">
                        <input type="text" name="memo" placeholder="例: 特売なら2つ、無調整" class="w-full px-4 py-3 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue font-rounded">
                    </div>
                </div>

                <!-- 4. 追加ボタン (画像2の青い[追加]ボタン) -->
                <button type="submit" class="w-full pressable py-4 px-6 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-extrabold text-base font-rounded shadow-btn-blue hover:opacity-95 transition flex items-center justify-center gap-2">
                    <span>＋</span> 追加する
                </button>
            </form>

            <!-- クイック候補タグ（よく買うもの） -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-400 block mb-2 font-rounded">よく買うもの（タップで入力）</span>
                <div class="flex flex-wrap gap-2">
                    @foreach(['牛乳', 'たまご', '食パン', '納豆', 'ヨーグルト', 'トイレットペーパー'] as $quickItem)
                        <button type="button" onclick="document.getElementById('item-name-input').value = '{{ $quickItem }}'" class="px-3 py-1.5 rounded-full bg-blue-50 hover:bg-blue-100 text-xs font-bold text-brand-blue font-rounded transition">
                            ＋ {{ $quickItem }}
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
