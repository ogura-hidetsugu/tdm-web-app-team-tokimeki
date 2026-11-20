<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム | くらすと</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #F2F8FC;
            font-family: "Hiragino Maru Gothic ProN", "Rounded Mplus 1c", sans-serif;
            color: #4A5568;
        }
    </style>
</head>
<body class="min-h-screen pb-24">

    <!-- トップバー -->
    <header class="bg-white/90 backdrop-blur-sm sticky top-0 z-10 border-b border-sky-100">
        <div class="max-w-md mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">🏠</span>
                <span class="text-lg font-bold text-sky-700 tracking-wide">くらすと</span>
            </div>
            <button type="button" class="relative w-9 h-9 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 hover:bg-sky-100 transition">
                <span>🔔</span>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-400"></span>
            </button>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-md mx-auto px-4 pt-4 space-y-4">

        <!-- キャラクター＆在庫1以下のアラート吹き出しエリア -->
        @php
            $lowItems = $lowStockProducts ?? collect();
        @endphp
        <div class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80 flex items-center gap-3">
            <div class="w-20 h-20 rounded-2xl bg-sky-50 border border-sky-100 flex flex-col items-center justify-center shrink-0">
                <span class="text-3xl leading-none">🐻‍❄️</span>
                <span class="text-[10px] font-bold text-sky-600 mt-1">お知らせ</span>
            </div>

            <div class="relative bg-sky-50/90 border border-sky-200/80 rounded-2xl px-3.5 py-2.5 text-xs leading-relaxed text-slate-600 flex-1">
                @if ($lowItems->isNotEmpty())
                    <p class="font-bold text-sky-700 mb-1">
                        在庫が1以下のアイテムがあるよ！
                    </p>
                    <ul class="space-y-0.5">
                        @foreach ($lowItems->take(3) as $item)
                            <li class="flex items-center justify-between gap-2">
                                <a href="{{ route('products.index', ['category' => $item->category]) }}"
                                    class="font-bold text-slate-700 hover:text-sky-600 hover:underline truncate">
                                    ・{{ $item->name }}
                                </a>
                                <span class="shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $item->stock === 0 ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-700' }}">
                                    残り{{ $item->stock }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    @if ($lowItems->count() > 3)
                        <p class="text-[10px] text-sky-500 font-bold mt-1">
                            ほか {{ $lowItems->count() - 3 }} 件
                        </p>
                    @endif
                @else
                    <p class="font-bold text-sky-700">いまは在庫1以下のものはないよ！</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">おうちの在庫は順調だね🫧</p>
                @endif
            </div>
        </div>

        <!-- 在庫数＆買い物リスト サマリーカード -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400">おうちの在庫</p>
                    <p class="text-xl font-bold text-sky-700 mt-0.5">
                        {{ $productCount ?? 5 }} <span class="text-xs font-normal text-slate-500">種類</span>
                    </p>
                </div>
                <span class="text-2xl">📦</span>
            </div>

            <div class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400">買い物リスト</p>
                    <p class="text-xl font-bold text-sky-700 mt-0.5">
                        {{ $shoppingCount ?? 3 }} <span class="text-xs font-normal text-slate-500">アイテム</span>
                    </p>
                </div>
                <span class="text-2xl">🛒</span>
            </div>
        </div>

        <!-- アクションボタン（アイテム登録 / 買い物リスト） -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('products.create') }}"
                class="bg-[#89B9E8] hover:bg-[#75AAE0] active:scale-[0.99] text-white font-bold py-3 px-4 rounded-full shadow-md shadow-sky-200/70 transition flex items-center justify-center gap-1.5 text-xs">
                <span>📷</span>
                <span>アイテムを登録</span>
            </a>

            <a href="{{ route('products.index') }}"
                class="bg-white hover:bg-sky-50 active:scale-[0.99] text-sky-600 border border-sky-200 font-bold py-3 px-4 rounded-full shadow-sm transition flex items-center justify-center gap-1.5 text-xs">
                <span>🛒</span>
                <span>在庫一覧を見る &gt;</span>
            </a>
        </div>

        <!-- カテゴリーカード -->
        <section class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-sky-700 flex items-center gap-1">
                    <span>🫧</span> カテゴリー
                </h2>
                <a href="{{ route('products.index') }}" class="text-[11px] text-sky-500 font-bold hover:underline">すべて見る &gt;</a>
            </div>

            @php
                $categories = [
                    ['name' => '食品', 'icon' => '🍞'],
                    ['name' => '飲み物', 'icon' => '🥛'],
                    ['name' => '日用品', 'icon' => '🧻'],
                    ['name' => '調味料', 'icon' => '🫙'],
                    ['name' => 'コスメ', 'icon' => '🧴'],
                    ['name' => '薬・サプリ', 'icon' => '💊'],
                    ['name' => 'その他', 'icon' => '🧸'],
                ];
            @endphp

            <div class="grid grid-cols-4 gap-2.5 text-center">
                @foreach ($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat['name']]) }}"
                        class="group rounded-2xl bg-sky-50/70 hover:bg-sky-100/80 border border-sky-100 py-2.5 px-1 transition flex flex-col items-center justify-center">
                        <span class="text-xl mb-1 group-hover:scale-110 transition">{{ $cat['icon'] }}</span>
                        <span class="text-[11px] font-bold text-slate-600">{{ $cat['name'] }}</span>
                    </a>
                @endforeach

                <a href="{{ route('products.create') }}"
                    class="rounded-2xl bg-sky-50/40 hover:bg-sky-100/60 border border-dashed border-sky-300 py-2.5 px-1 transition flex flex-col items-center justify-center text-sky-500">
                    <span class="text-lg font-bold leading-none mb-1">＋</span>
                    <span class="text-[10px] font-bold">アイテム追加</span>
                </a>
            </div>
        </section>

        <!-- 次回の買い替え予定カード -->
        <section class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-xl">
                    🗓️
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400">次回の買い替え予定</p>
                    <p class="text-sm font-bold text-sky-700 mt-0.5">
                        牛乳 <span class="text-xs font-normal text-slate-600">あと <strong class="text-sky-600">3</strong> 日</span>
                    </p>
                </div>
            </div>
            <span class="text-sky-400 text-sm font-bold">&gt;</span>
        </section>

    </main>

    <!-- 下部ナビゲーションバー -->
    <nav class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-sm border-t border-sky-100 z-10">
        <div class="max-w-md mx-auto grid grid-cols-5 text-center py-2">
            <a href="{{ url('/') }}" class="flex flex-col items-center text-sky-600 font-bold">
                <span class="text-lg leading-none">🏠</span>
                <span class="text-[10px] mt-1">ホーム</span>
            </a>
            <a href="{{ route('products.index') }}" class="flex flex-col items-center text-slate-400 hover:text-sky-500 transition">
                <span class="text-lg leading-none">🔍</span>
                <span class="text-[10px] mt-1">さがす</span>
            </a>
            <a href="#" class="flex flex-col items-center text-slate-400 hover:text-sky-500 transition">
                <span class="text-lg leading-none">📝</span>
                <span class="text-[10px] mt-1">メモ</span>
            </a>
            <a href="#" class="flex flex-col items-center text-slate-400 hover:text-sky-500 transition">
                <span class="text-lg leading-none">🛒</span>
                <span class="text-[10px] mt-1">リスト</span>
            </a>
            <a href="#" class="flex flex-col items-center text-slate-400 hover:text-sky-500 transition">
                <span class="text-lg leading-none">⚙️</span>
                <span class="text-[10px] mt-1">設定</span>
            </a>
        </div>
    </nav>

</body>
</html>