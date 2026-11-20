@extends('layouts.app')

@section('title', '定期購入品を登録 - くらすと')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- ヘッダーエリア (画像3デザイン) -->
    <div class="bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-soft border border-blue-100 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <!-- ✕ 閉じるボタン (画像3) -->
            <a href="{{ route('home') }}" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition" title="閉じる">
                <svg class="w-5 h-5 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </a>

            <!-- タイトル「定期購入品を登録」 + 黄色い光線 -->
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-[#2C3E50] font-rounded tracking-tight">
                    定期購入品を登録
                </h1>
                <div class="flex items-center text-brand-yellow">
                    <svg class="w-5 h-5 stroke-brand-yellow stroke-[2.8]" viewBox="0 0 20 20" fill="none">
                        <line x1="3" y1="17" x2="16" y2="4" />
                        <line x1="10" y1="19" x2="19" y2="10" />
                    </svg>
                </div>
            </div>
        </div>

        <a href="{{ route('items.index') }}" class="text-xs font-bold text-brand-blue hover:underline font-rounded">
            在庫一覧を見る ＞
        </a>
    </div>


    <!-- 登録フォームカード (画像3の項目を忠実かつPC向けに美しくレイアウト) -->
    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[36px] p-6 sm:p-10 shadow-soft border border-blue-100 space-y-8">
        @csrf

        <!-- 1. 商品名 (必須) -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded flex items-center gap-2">
                    商品名
                    <span class="text-[11px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">必須</span>
                </label>
            </div>
            <input type="text" name="name" required placeholder="富士清水 バナジウム＆シリカ天然水" class="w-full px-5 py-3.5 rounded-2xl border border-blue-200 text-base focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-blue-100 font-rounded text-[#2C3E50]">
        </div>


        <!-- 2. カメラ撮影機能 ＆ 棚での見え方 (画像3: 切り抜き / 元の写真 選択 ＋ カメラ撮影) -->
        <div class="space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded">
                    商品の写真 ＆ 棚での見え方
                </label>
                
                <!-- カメラ起動・写真選択ボタン -->
                <div class="flex items-center gap-2">
                    <!-- カメラで撮影ボタン -->
                    <button type="button" id="start-camera-btn" class="pressable px-4 py-2 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white text-xs font-extrabold font-rounded shadow-sm flex items-center gap-1.5 hover:opacity-95 transition">
                        <svg class="w-4 h-4 stroke-[2.2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                            <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2" />
                        </svg>
                        カメラで撮影する
                    </button>

                    <!-- ファイルから選ぶボタン -->
                    <button type="button" onclick="document.getElementById('photo-file-input').click()" class="pressable px-4 py-2 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold font-rounded flex items-center gap-1.5 transition">
                        <span>📁</span> 写真を選ぶ
                    </button>

                    <input type="file" id="photo-file-input" accept="image/*" class="hidden">
                    <!-- 撮影した画像データの保持用 -->
                    <input type="hidden" name="photo_captured" id="photo-captured-input">
                </div>
            </div>

            <!-- 見え方カード選択（切り抜き / 元の写真） -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <!-- 選択肢1: 切り抜き -->
                <label class="cursor-pointer">
                    <input type="radio" name="photo_display" value="cutout" checked class="peer sr-only">
                    <div class="p-4 rounded-3xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-blue-50/50 flex flex-col items-center justify-center gap-3 transition">
                        <div class="relative w-32 h-32 rounded-2xl bg-white border border-blue-100 flex items-center justify-center p-2 shadow-sm overflow-hidden">
                            <!-- プレビュー画像（デフォルトまたは撮影画像） -->
                            <div id="cutout-preview-container" class="w-full h-full flex items-center justify-center">
                                <div class="text-5xl" id="default-cutout-icon">🧴</div>
                                <img id="cutout-photo-img" src="" alt="切り抜き写真" class="hidden w-full h-full object-contain filter drop-shadow-md">
                            </div>
                            <!-- 黄色いキラキラ -->
                            <span class="absolute top-2 left-2 text-brand-yellow font-bold text-sm drop-shadow-sm pointer-events-none">✨</span>
                        </div>
                        <span class="font-extrabold text-sm text-[#33465E] font-rounded flex items-center gap-1">
                            <span>✨</span> 切り抜き
                        </span>
                    </div>
                </label>

                <!-- 選択肢2: 元の写真 -->
                <label class="cursor-pointer">
                    <input type="radio" name="photo_display" value="original" class="peer sr-only">
                    <div class="p-4 rounded-3xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-blue-50/50 flex flex-col items-center justify-center gap-3 transition">
                        <div class="relative w-32 h-32 rounded-2xl bg-amber-50/60 border border-amber-200 flex items-center justify-center p-2 shadow-sm overflow-hidden">
                            <!-- プレビュー画像 -->
                            <div id="original-preview-container" class="w-full h-full flex items-center justify-center">
                                <div class="text-5xl" id="default-original-icon">📸</div>
                                <img id="original-photo-img" src="" alt="元の写真" class="hidden w-full h-full object-cover rounded-xl">
                            </div>
                        </div>
                        <span class="font-extrabold text-sm text-[#33465E] font-rounded flex items-center gap-1">
                            <span>📷</span> 元の写真
                        </span>
                    </div>
                </label>

            </div>
            
            <p class="text-xs text-slate-400 font-rounded">
                「棚での見え方」で、切り抜きと元の写真から棚に置く見た目を選べます。カメラで商品を撮影するとプレビューが切り替わります。
            </p>
        </div>


        <!-- 3. カテゴリー ＆ 購入先 (画像3) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            
            <!-- カテゴリー -->
            <div>
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded block mb-2">
                    カテゴリー
                </label>
                <select name="category_id" class="w-full px-4 py-3.5 rounded-2xl border border-blue-200 text-sm text-[#44566C] bg-white focus:outline-none focus:border-brand-blue font-rounded">
                    <option value="">カテゴリーを選択（任意）</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 購入先タグ選択 -->
            <div>
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded block mb-2">
                    購入先
                </label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($stores as $st)
                        <label class="cursor-pointer">
                            <input type="radio" name="default_store_id" value="{{ $st->id }}" class="peer sr-only" {{ $st->name === '未設定' ? 'checked' : '' }}>
                            <div class="py-2.5 px-2 text-center rounded-2xl border border-blue-100 text-xs font-bold font-rounded text-slate-600 peer-checked:bg-brand-blue peer-checked:text-white peer-checked:border-brand-blue transition hover:bg-blue-50/50">
                                {{ $st->name }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>


        <!-- 4. メモ (任意) (画像3) -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded flex items-center gap-2">
                    メモ
                    <span class="text-xs text-slate-400 font-normal">任意</span>
                </label>
                <span class="text-slate-400 text-sm">📝</span>
            </div>
            <input type="text" name="memo" placeholder="例：特売なら2つ、無香料" class="w-full px-5 py-3.5 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue font-rounded text-[#2C3E50]">
        </div>


        <!-- 5. 購入周期 (目安) (画像3: カレンダーアイコン [30] 日ごと ▼) -->
        <div class="bg-blue-50/50 rounded-3xl p-6 border border-blue-100 space-y-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded">
                    購入周期
                </label>
                <span class="text-xs text-slate-400 font-bold font-rounded">目安</span>
                <span class="text-brand-yellow font-bold">✨</span>
            </div>

            <div class="flex items-center gap-3">
                <!-- カレンダーアイコン -->
                <div class="w-12 h-12 rounded-2xl bg-white border border-blue-200 flex items-center justify-center text-brand-blue flex-shrink-0">
                    <svg class="w-6 h-6 stroke-brand-blue" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="3" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <circle cx="8" cy="14" r="1" fill="currentColor" />
                        <circle cx="12" cy="14" r="1" fill="currentColor" />
                    </svg>
                </div>

                <!-- 日数入力 -->
                <div class="flex items-center gap-2">
                    <input type="number" name="purchase_cycle_days" value="30" min="1" max="365" class="w-24 px-4 py-3 rounded-2xl bg-white border border-blue-200 text-center text-xl font-black text-brand-blue font-rounded focus:outline-none focus:border-brand-blue">
                    <span class="font-extrabold text-sm text-[#33465E] font-rounded">日ごと</span>
                </div>
            </div>

            <!-- 通知設定を開くリンク（メニューバーのベルマークと直結！） -->
            <div class="flex items-center justify-between text-xs text-slate-500 font-rounded pt-1">
                <span>お好きな日数の前に通知／自動追加できます。</span>
                <a href="{{ route('notifications.index') }}" class="text-brand-blue font-extrabold flex items-center gap-1 hover:underline">
                    🔔 通知設定を開く
                </a>
            </div>
        </div>


        <!-- 6. 商品リンク (任意) ＆ 外部ストア検索 (画像3) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <label class="text-sm font-extrabold text-[#2C3E50] font-rounded flex items-center gap-2">
                    商品リンク
                    <span class="text-xs text-slate-400 font-normal">任意</span>
                </label>
            </div>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔗</span>
                <input type="url" name="product_url" placeholder="https://" class="w-full pl-11 pr-5 py-3.5 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue font-rounded text-[#2C3E50]">
            </div>

            <!-- 外部ストア検索ボタン -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <a href="https://www.amazon.co.jp" target="_blank" class="p-3 rounded-2xl border border-amber-200 bg-amber-50/40 hover:bg-amber-100/50 flex items-center justify-center gap-2 text-xs font-bold text-amber-900 transition font-rounded">
                    <span class="font-black text-amber-600 text-sm">a</span> Amazonで探す ↗
                </a>
                <a href="https://www.rakuten.co.jp" target="_blank" class="p-3 rounded-2xl border border-red-200 bg-red-50/40 hover:bg-red-100/50 flex items-center justify-center gap-2 text-xs font-bold text-red-900 transition font-rounded">
                    <span class="font-black text-red-600 text-sm">R</span> 楽天で探す ↗
                </a>
            </div>
            <div class="text-center pt-1">
                <a href="#other" class="text-xs font-bold text-slate-400 hover:text-slate-600 font-rounded">
                    ほかの店で探す ＞
                </a>
            </div>
        </div>


        <!-- 7. 同時に買い物リストにも追加するオプション -->
        <div class="bg-blue-50/30 p-4 rounded-2xl border border-blue-100 flex items-center gap-3">
            <input type="checkbox" name="add_to_shopping_list" id="add-to-shopping" value="1" class="w-5 h-5 rounded-lg text-brand-blue border-blue-300 focus:ring-brand-blue">
            <label for="add-to-shopping" class="text-xs font-bold text-[#33465E] font-rounded cursor-pointer select-none">
                同時に「買い物リスト」にも追加する
            </label>
        </div>


        <!-- 8. 登録するボタン (画像3の青い大型ボタン) -->
        <div class="pt-2">
            <button type="submit" class="w-full pressable py-4 px-6 rounded-2xl bg-gradient-to-r from-[#6FA5FA] to-[#4887EA] text-white font-black text-lg font-rounded shadow-btn-blue hover:opacity-95 transition flex items-center justify-center gap-2">
                登録する
            </button>
            <div class="text-center mt-3">
                <a href="#affiliate" class="text-[11px] text-slate-400 underline font-rounded">
                    アフィリエイトについて
                </a>
            </div>
        </div>

    </form>


    <!-- 下部ダンボールくま＆カート -->
    <div class="flex items-center justify-between px-4 py-2 select-none">
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
                定期購入に設定したアイテムは、買い替え目安日にお知らせします♪
            </span>
        </div>
        <div class="text-blue-200">
            <svg class="w-8 h-8 stroke-current fill-none stroke-[2]" viewBox="0 0 24 24">
                <circle cx="9" cy="20" r="1.5" fill="currentColor" />
                <circle cx="17" cy="20" r="1.5" fill="currentColor" />
                <path d="M2 3H5L7 15H19L21 7H6" />
            </svg>
        </div>
    </div>

</div>


<!-- カメラ撮影モーダル -->
<div id="camera-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[36px] max-w-lg w-full p-6 shadow-2xl border border-blue-100 flex flex-col items-center space-y-5 animate-scale-up">
        
        <div class="w-full flex items-center justify-between pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="text-xl">📸</span>
                <h3 class="font-black text-base text-[#2C3E50] font-rounded">商品の写真を撮影</h3>
            </div>
            <button type="button" id="close-camera-modal-btn" class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold flex items-center justify-center text-lg">
                ✕
            </button>
        </div>

        <!-- カメラビデオ画面 -->
        <div class="relative w-full aspect-square max-h-[360px] bg-black rounded-3xl overflow-hidden shadow-inner flex items-center justify-center">
            <video id="camera-video" autoplay playsinline class="w-full h-full object-cover"></video>
            
            <!-- ガイド枠線 -->
            <div class="absolute inset-8 border-2 border-dashed border-white/60 rounded-3xl pointer-events-none flex items-center justify-center">
                <span class="text-white/70 text-xs font-bold font-rounded bg-black/40 px-3 py-1 rounded-full">
                    枠内に商品を合わせてください
                </span>
            </div>

            <!-- カメラ未許可またはローディング時のメッセージ -->
            <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 text-white p-4 text-center">
                <div class="w-8 h-8 border-4 border-blue-400 border-t-transparent rounded-full animate-spin mb-3"></div>
                <p class="text-xs font-bold font-rounded">カメラを起動中...</p>
                <p class="text-[11px] text-slate-400 mt-1">ブラウザのカメラ許可を承認してください</p>
            </div>
        </div>

        <!-- 隠しCanvas (撮影キャプチャ用) -->
        <canvas id="camera-canvas" class="hidden"></canvas>

        <!-- シャッターボタン & 操作部 -->
        <div class="flex items-center justify-center gap-6 w-full pt-2">
            <button type="button" id="switch-camera-btn" class="p-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="カメラ切り替え">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67" />
                </svg>
            </button>

            <!-- 大きなシャッターボタン -->
            <button type="button" id="take-photo-btn" class="w-16 h-16 rounded-full bg-brand-blue border-4 border-white shadow-xl flex items-center justify-center active:scale-95 transition hover:bg-brand-blueDark" title="撮影する">
                <div class="w-12 h-12 rounded-full border-2 border-white/80"></div>
            </button>

            <button type="button" onclick="document.getElementById('photo-file-input').click(); document.getElementById('close-camera-modal-btn').click();" class="p-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="アルバムから選ぶ">
                <span>📁</span>
            </button>
        </div>

    </div>
</div>


<!-- カメラ＆写真プレビュー制御スクリプト -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startCameraBtn = document.getElementById('start-camera-btn');
    const cameraModal = document.getElementById('camera-modal');
    const closeCameraBtn = document.getElementById('close-camera-modal-btn');
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');
    const takePhotoBtn = document.getElementById('take-photo-btn');
    const switchCameraBtn = document.getElementById('switch-camera-btn');
    const loadingMessage = document.getElementById('camera-loading');
    const fileInput = document.getElementById('photo-file-input');
    const capturedInput = document.getElementById('photo-captured-input');

    // プレビュー表示用要素
    const cutoutImg = document.getElementById('cutout-photo-img');
    const originalImg = document.getElementById('original-photo-img');
    const defaultCutoutIcon = document.getElementById('default-cutout-icon');
    const defaultOriginalIcon = document.getElementById('default-original-icon');

    let stream = null;
    let facingMode = 'environment'; // 背面カメラ優先

    // カメラ起動
    async function startCamera() {
        if (loadingMessage) loadingMessage.classList.remove('hidden');
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: facingMode,
                    width: { ideal: 1080 },
                    height: { ideal: 1080 }
                },
                audio: false
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
                if (loadingMessage) loadingMessage.classList.add('hidden');
            };
        } catch (err) {
            console.warn('カメラの直接起動に失敗しました:', err);
            if (loadingMessage) {
                loadingMessage.innerHTML = `
                    <p class="text-xs font-bold text-red-300">カメラにアクセスできませんでした</p>
                    <p class="text-[11px] text-slate-400 mt-1">端末のファイル選択をご利用ください</p>
                    <button type="button" onclick="document.getElementById('photo-file-input').click(); document.getElementById('close-camera-modal-btn').click();" class="mt-3 px-4 py-2 bg-brand-blue text-white rounded-xl text-xs font-bold">
                        📁 ファイルから選ぶ
                    </button>
                `;
            }
        }
    }

    // カメラ停止
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        if (cameraModal) cameraModal.classList.add('hidden');
    }

    // モーダルを開く
    if (startCameraBtn) {
        startCameraBtn.addEventListener('click', function () {
            cameraModal.classList.remove('hidden');
            startCamera();
        });
    }

    // モーダルを閉じる
    if (closeCameraBtn) {
        closeCameraBtn.addEventListener('click', stopCamera);
    }

    // カメラ切り替え（イン/アウト）
    if (switchCameraBtn) {
        switchCameraBtn.addEventListener('click', function () {
            facingMode = facingMode === 'environment' ? 'user' : 'environment';
            startCamera();
        });
    }

    // 写真撮影
    if (takePhotoBtn) {
        takePhotoBtn.addEventListener('click', function () {
            if (!video.videoWidth) return;

            const size = Math.min(video.videoWidth, video.videoHeight);
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');

            // 正方形にトリミング描画
            const startX = (video.videoWidth - size) / 2;
            const startY = (video.videoHeight - size) / 2;
            ctx.drawImage(video, startX, startY, size, size, 0, 0, size, size);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            applyPhoto(dataUrl);
            stopCamera();
        });
    }

    // ファイル選択時
    if (fileInput) {
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    applyPhoto(event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // プレビューとhiddenフィールドに反映
    function applyPhoto(dataUrl) {
        if (capturedInput) capturedInput.value = dataUrl;

        // 切り抜き側
        if (cutoutImg && defaultCutoutIcon) {
            cutoutImg.src = dataUrl;
            cutoutImg.classList.remove('hidden');
            defaultCutoutIcon.classList.add('hidden');
        }

        // 元の写真側
        if (originalImg && defaultOriginalIcon) {
            originalImg.src = dataUrl;
            originalImg.classList.remove('hidden');
            defaultOriginalIcon.classList.add('hidden');
        }
    }
});
</script>
@endsection
