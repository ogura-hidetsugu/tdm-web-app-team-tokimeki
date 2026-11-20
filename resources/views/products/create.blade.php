<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アイテムを登録 | くらすと</title>
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
    <header class="bg-white/80 backdrop-blur-sm sticky top-0 z-10 border-b border-sky-100">
        <div class="max-w-md mx-auto px-4 h-14 flex items-center justify-between">
            <a href="javascript:history.back()" class="text-sky-500 text-sm font-bold flex items-center gap-1">
                <span>‹</span> もどる
            </a>
            <h1 class="text-base font-bold text-sky-700 tracking-wide">アイテムを登録</h1>
            <div class="w-10"></div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-md mx-auto px-4 pt-5">

        <!-- 登録完了メッセージ -->
        @if (session('success'))
            <div class="bg-sky-100 border border-sky-200 text-sky-800 text-xs font-bold rounded-2xl p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- エラー表示 -->
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-600 text-xs rounded-2xl p-3 mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- 商品画像アップロードカード -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-sky-100/80 text-center">
                <label for="image" class="cursor-pointer block">
                    <div id="preview-container" class="w-32 h-32 mx-auto rounded-2xl bg-sky-50 border-2 border-dashed border-sky-200 flex flex-col items-center justify-center overflow-hidden hover:bg-sky-100/60 transition">
                        <img id="image-preview" src="" alt="プレビュー" class="hidden w-full h-full object-cover">
                        <div id="upload-placeholder" class="text-sky-400">
                            <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs font-bold">写真をえらぶ</span>
                        </div>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                </label>
                <p class="text-[11px] text-slate-400 mt-2">タップして画像を選択</p>
            </div>

            <!-- 入力フォームカード -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-sky-100/80 space-y-4">

                <!-- 商品名 -->
                <div>
                    <label class="block text-xs font-bold text-sky-700 mb-1.5">
                        商品名 <span class="bg-sky-100 text-sky-600 text-[10px] px-2 py-0.5 rounded-full ml-1">必須</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="例：おいしい牛乳 1000ml" required
                        class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">
                </div>

                <!-- カテゴリー & 在庫数 -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-sky-700 mb-1.5">カテゴリー</label>
                        <select name="category"
                            class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">
                            <option value="食品" {{ old('category') === '食品' ? 'selected' : '' }}>食品</option>
                            <option value="飲み物" {{ old('category') === '飲み物' ? 'selected' : '' }}>飲み物</option>
                            <option value="日用品" {{ old('category') === '日用品' ? 'selected' : '' }}>日用品</option>
                            <option value="調味料" {{ old('category') === '調味料' ? 'selected' : '' }}>調味料</option>
                            <option value="コスメ" {{ old('category') === 'コスメ' ? 'selected' : '' }}>コスメ</option>
                            <option value="薬・サプリ" {{ old('category') === '薬・サプリ' ? 'selected' : '' }}>薬・サプリ</option>
                            <option value="その他" {{ old('category') === 'その他' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-sky-700 mb-1.5">いまの在庫数</label>
                        <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0"
                            class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">
                    </div>
                </div>

                <!-- 購入先 -->
                <div>
                    <label class="block text-xs font-bold text-sky-700 mb-1.5">購入先</label>
                    @php
                        $stores = ['スーパー', '薬局', 'コンビニ', 'ホームセンター', '100円ショップ', 'Amazon', '楽天', 'その他'];
                    @endphp
                    <select name="store"
                        class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">
                        <option value="">選択してください</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store }}" {{ old('store') === $store ? 'selected' : '' }}>{{ $store }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 商品URL -->
                <div>
                    <label class="block text-xs font-bold text-sky-700 mb-1.5">
                        商品URL <span class="text-[11px] font-normal text-slate-400">（ネット通販のリンクなど）</span>
                    </label>
                    <input type="url" name="url" value="{{ old('url') }}" placeholder="https://..."
                        class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">
                </div>

                <!-- メモ -->
                <div>
                    <label class="block text-xs font-bold text-sky-700 mb-1.5">メモ</label>
                    <textarea name="memo" rows="2" placeholder="特売日やメーカー名など"
                        class="w-full rounded-2xl bg-sky-50/60 border border-sky-100 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 focus:bg-white transition">{{ old('memo') }}</textarea>
                </div>

            </div>

            <!-- 登録ボタン -->
            <button type="submit"
                class="w-full bg-[#89B9E8] hover:bg-[#75AAE0] active:scale-[0.99] text-white font-bold py-3.5 rounded-full shadow-md shadow-sky-200/70 transition flex items-center justify-center gap-2">
                <span>＋</span> この内容で登録する
            </button>
        </form>
    </main>

    <!-- 画像プレビュー用スクリプト -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                const placeholder = document.getElementById('upload-placeholder');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>