<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $selectedCategory ? $selectedCategory . 'の一覧' : '登録アイテム一覧' }} | くらすと</title>
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

    <!-- ヘッダー -->
    <header class="bg-white/90 backdrop-blur-sm sticky top-0 z-10 border-b border-sky-100">
        <div class="max-w-md mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-sky-500 text-sm font-bold flex items-center gap-1">
                <span>‹</span> ホーム
            </a>
            <h1 class="text-base font-bold text-sky-700 tracking-wide">
                {{ $selectedCategory ? $selectedCategory : 'すべてのアイテム' }}
            </h1>
            <a href="{{ route('products.create') }}" class="text-xs font-bold bg-[#89B9E8] hover:bg-[#75AAE0] text-white px-3 py-1.5 rounded-full transition">
                ＋ 追加
            </a>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-md mx-auto px-4 pt-4 space-y-4">

        <!-- 完了メッセージ -->
        @if (session('success'))
            <div class="bg-sky-100 border border-sky-200 text-sky-800 text-xs font-bold rounded-2xl p-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- カテゴリー絞り込みバー -->
        @php
            $filterCategories = ['すべて', '食品', '飲み物', '日用品', '調味料', 'コスメ', '薬・サプリ', 'その他'];
        @endphp
        <div class="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
            @foreach ($filterCategories as $cat)
                @php
                    $isAll = ($cat === 'すべて');
                    $isActive = ($isAll && empty($selectedCategory)) || (!$isAll && $selectedCategory === $cat);
                    $href = $isAll ? route('products.index') : route('products.index', ['category' => $cat]);
                @endphp
                <a href="{{ $href }}"
                    class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ $isActive ? 'bg-[#89B9E8] text-white shadow-sm' : 'bg-white text-sky-700 border border-sky-100 hover:bg-sky-50' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        <!-- 商品一覧 -->
        @if ($products->isEmpty())
            <div class="bg-white rounded-3xl p-8 text-center shadow-sm border border-sky-100/80 space-y-3">
                <p class="text-3xl">🫧</p>
                <p class="text-sm font-bold text-sky-700">
                    {{ $selectedCategory ? $selectedCategory . 'のアイテムはまだありません' : 'まだアイテムが登録されていません' }}
                </p>
                <p class="text-xs text-slate-400">右上の「＋ 追加」から最初のアイテムを登録してみましょう！</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($products as $product)
                    <div class="bg-white rounded-3xl p-4 shadow-sm border border-sky-100/80 flex items-center gap-3.5">
                        <!-- 商品画像 -->
                        <div class="w-16 h-16 rounded-2xl bg-sky-50 border border-sky-100 overflow-hidden flex items-center justify-center shrink-0">
                            @if ($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl">📦</span>
                            @endif
                        </div>

                        <!-- 商品情報 -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                @if ($product->category)
                                    <span class="text-[10px] font-bold bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full">
                                        {{ $product->category }}
                                    </span>
                                @endif
                                <span class="text-xs font-bold text-slate-500">
                                    在庫: <strong class="text-sky-700">{{ $product->stock }}</strong>
                                </span>
                            </div>

                            <h2 class="text-sm font-bold text-slate-700 truncate mt-1">{{ $product->name }}</h2>

                            @if ($product->memo)
                                <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $product->memo }}</p>
                            @endif

                            @if ($product->url)
                                <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-sky-500 hover:underline mt-1">
                                    <span>🔗 商品ページを開く</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </main>

</body>
</html>