@extends('layouts.app')

@section('title', '家族で共有 - くらすと')

@section('content')
<div class="space-y-8">

    <!-- 1. トップバナー (画像4の2匹のくま＆吹き出しをPCサイズで美麗に再現！) -->
    <div class="bg-gradient-to-r from-[#EBF3FE] via-[#F3F8FE] to-[#EAF2FD] rounded-[36px] p-6 sm:p-10 border border-blue-200/80 shadow-soft relative overflow-hidden">
        
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            
            <!-- 左側タイトル＆説明 -->
            <div class="space-y-3 text-center md:text-left">
                <!-- おうちアイコン ＋ タイトル -->
                <div class="inline-flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-blue-200 flex items-center justify-center text-brand-blue shadow-sm">
                        <svg class="w-8 h-8 stroke-brand-blue" viewBox="0 0 32 32" fill="none" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 14.5L16 4.5L28 14.5" />
                            <path d="M7 13.5V25.5C7 26.6 7.9 27.5 9 27.5H23C24.1 27.5 25 26.6 25 25.5V13.5" />
                            <path d="M12.5 19H19.5V27.5" />
                        </svg>
                    </div>
                    <div class="relative">
                        <h1 class="text-3xl sm:text-4xl font-black text-brand-blue font-rounded tracking-tight">
                            家族で共有できる
                        </h1>
                        <!-- 黄色いキラキラ -->
                        <div class="absolute -top-3 -right-6 text-brand-yellow">
                            <svg class="w-6 h-6 stroke-brand-yellow stroke-[2.8]" viewBox="0 0 20 20" fill="none">
                                <line x1="3" y1="17" x2="16" y2="4" />
                                <line x1="10" y1="19" x2="19" y2="10" />
                            </svg>
                        </div>
                    </div>
                </div>

                <p class="text-sm font-bold text-slate-500 font-rounded max-w-md leading-relaxed">
                    おうちの在庫や買い物リストが家族間でリアルタイムに同期！<br>
                    「買い忘れ」や「二重買い」がなくなります。
                </p>

                <!-- 招待コードバッジ -->
                <div class="pt-2 flex flex-wrap items-center gap-2 justify-center md:justify-start">
                    <span class="text-xs font-bold text-slate-400 font-rounded">家族招待コード:</span>
                    <span class="bg-white px-3.5 py-1.5 rounded-full border border-blue-200 text-xs font-black text-brand-blue tracking-wider font-mono shadow-sm">
                        {{ $inviteCode }}
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $inviteCode }}'); alert('招待コードをコピーしました！')" class="text-xs text-brand-blue hover:underline font-bold font-rounded">
                        📋 コピー
                    </button>
                </div>
            </div>

            <!-- 右側：2匹の白くまイラスト（画像4のデザインそのまま！） -->
            <div class="flex items-end gap-6 select-none">
                
                <!-- くま1 (左): 「ありがとう！」吹き出し -->
                <div class="flex flex-col items-center">
                    <!-- 吹き出し1 -->
                    <div class="bg-white px-4 py-2 rounded-2xl shadow-card border border-blue-100 text-xs font-extrabold text-brand-blue font-rounded mb-1 animate-bob relative">
                        ありがとう！
                        <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-t-[6px] border-t-white"></div>
                    </div>

                    <!-- 白くま1 -->
                    <div class="w-28 h-20">
                        <svg viewBox="0 0 130 95" fill="none" class="w-full h-full">
                            <circle cx="34" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="34" cy="26" r="6" fill="#FFCCD5" />
                            <circle cx="82" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="82" cy="26" r="6" fill="#FFCCD5" />
                            <ellipse cx="58" cy="52" rx="36" ry="30" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="46" cy="48" r="2.8" fill="#4A3831" />
                            <circle cx="70" cy="48" r="2.8" fill="#4A3831" />
                            <ellipse cx="58" cy="53" rx="2.8" ry="2" fill="#4A3831" />
                            <path d="M54.5 57C56 58.5 57.5 58 58 56.5C58.5 58 60 58.5 61.5 57" stroke="#4A3831" stroke-width="1.8" stroke-linecap="round" />
                            <ellipse cx="38" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <ellipse cx="78" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <!-- 手 -->
                            <ellipse cx="30" cy="74" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <ellipse cx="86" cy="74" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                        </svg>
                    </div>
                </div>

                <!-- くま2 (右): 「買ったよ！」青い吹き出し ＆ にっこり笑顔 -->
                <div class="flex flex-col items-center">
                    <!-- 吹き出し2 (青) -->
                    <div class="bg-brand-blue text-white px-4 py-2 rounded-2xl shadow-btn-blue text-xs font-black font-rounded mb-1 animate-bob relative">
                        買ったよ！
                        <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-t-[6px] border-t-brand-blue"></div>
                    </div>

                    <!-- 白くま2 (笑顔) -->
                    <div class="w-28 h-20">
                        <svg viewBox="0 0 130 95" fill="none" class="w-full h-full">
                            <circle cx="34" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="34" cy="26" r="6" fill="#FFCCD5" />
                            <circle cx="82" cy="26" r="11" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <circle cx="82" cy="26" r="6" fill="#FFCCD5" />
                            <ellipse cx="58" cy="52" rx="36" ry="30" fill="#FFFFFF" stroke="#8C7971" stroke-width="2.3" />
                            <!-- にっこり目 -->
                            <path d="M44 48C46 45 49 45 51 48" stroke="#4A3831" stroke-width="2.2" stroke-linecap="round" />
                            <path d="M66 48C68 45 71 45 73 48" stroke="#4A3831" stroke-width="2.2" stroke-linecap="round" />
                            <ellipse cx="58" cy="53" rx="2.8" ry="2" fill="#4A3831" />
                            <path d="M54.5 57C56 58.5 57.5 58 58 56.5C58.5 58 60 58.5 61.5 57" stroke="#4A3831" stroke-width="1.8" stroke-linecap="round" />
                            <ellipse cx="38" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <ellipse cx="78" cy="54" rx="5" ry="3" fill="#FFAAB8" opacity="0.65" />
                            <!-- 手 -->
                            <ellipse cx="30" cy="74" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                            <ellipse cx="86" cy="74" rx="8" ry="6" fill="#FFFFFF" stroke="#8C7971" stroke-width="2" />
                        </svg>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- 2. 画像4の画面同期デモ（左右の連動プレビュー） -->
    <div class="bg-white rounded-[36px] p-6 sm:p-10 shadow-soft border border-blue-100 space-y-6">
        <div class="text-center max-w-xl mx-auto space-y-1">
            <span class="text-xs font-bold text-brand-blue uppercase tracking-wider font-rounded">SYNC PREVIEW</span>
            <h2 class="text-xl sm:text-2xl font-black text-[#2C3E50] font-rounded">
                家族間での同期イメージ
            </h2>
            <p class="text-xs text-slate-400 font-rounded">
                一人が「使ったよ」とチェックを入れたり購入すると、別の家族のスマホやPCにも即座に反映されます
            </p>
        </div>

        <!-- 2画面並列デモ (画像4のスマホ2台並びをPC上で再現！) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center max-w-3xl mx-auto">
            
            <!-- 左画面：使った人目線（たった今使った） -->
            <div class="bg-[#F8FAFD] rounded-[32px] p-5 border-2 border-blue-100 shadow-card">
                <div class="flex items-center justify-between pb-3 border-b border-blue-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-pink-400"></span>
                        <span class="text-xs font-black text-[#33465E] font-rounded">きょうかの画面（おうち）</span>
                    </div>
                    <span class="text-[11px] font-bold text-brand-blue font-rounded">買い物リスト</span>
                </div>

                <div class="space-y-2.5">
                    <!-- トイレットペーパー たった今使った (緑チェック) -->
                    <div class="bg-white p-3 rounded-2xl border border-blue-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🧻</span>
                            <div>
                                <span class="text-xs font-extrabold text-[#2C3E50] font-rounded block">トイレットペーパー</span>
                                <span class="text-[10px] font-bold text-red-400 font-rounded">たった今使った</span>
                            </div>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                    </div>

                    <!-- 他のアイテム -->
                    <div class="bg-white/70 p-2.5 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🥛</span>
                            <span class="text-xs font-bold text-[#44566C] font-rounded">牛乳</span>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-blue-200"></div>
                    </div>

                    <div class="bg-white/70 p-2.5 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🍎</span>
                            <span class="text-xs font-bold text-[#44566C] font-rounded">りんご</span>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-blue-200"></div>
                    </div>
                </div>
            </div>

            <!-- 右画面：買った人目線（たった今購入） -->
            <div class="bg-[#F8FAFD] rounded-[32px] p-5 border-2 border-blue-100 shadow-card">
                <div class="flex items-center justify-between pb-3 border-b border-blue-100 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                        <span class="text-xs font-black text-[#33465E] font-rounded">パパの画面（お買い物中）</span>
                    </div>
                    <span class="text-[11px] font-bold text-brand-blue font-rounded">買い物リスト</span>
                </div>

                <div class="space-y-2.5">
                    <!-- トイレットペーパー たった今購入 (緑チェック) -->
                    <div class="bg-white p-3 rounded-2xl border border-blue-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🧻</span>
                            <div>
                                <span class="text-xs font-extrabold text-[#2C3E50] font-rounded block">トイレットペーパー</span>
                                <span class="text-[10px] font-bold text-emerald-600 font-rounded">たった今購入</span>
                            </div>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <svg class="w-4 h-4 stroke-[3]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                    </div>

                    <!-- 他のアイテム -->
                    <div class="bg-white/70 p-2.5 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🥛</span>
                            <span class="text-xs font-bold text-[#44566C] font-rounded">牛乳</span>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-blue-200"></div>
                    </div>

                    <div class="bg-white/70 p-2.5 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🍎</span>
                            <span class="text-xs font-bold text-[#44566C] font-rounded">りんご</span>
                        </div>
                        <div class="w-6 h-6 rounded-full border-2 border-blue-200"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- 3. 家族メンバー管理 ＆ 招待 ＆ アクティビティ履歴 (PC 2カラム) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- 左カラム：家族メンバー一覧 ＆ 新規メンバー招待 (6カラム) -->
        <div class="lg:col-span-6 bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-black text-[#2D415A] font-rounded text-lg flex items-center gap-2">
                    <span>👥</span> 参加中の家族メンバー ({{ $members->count() }}人)
                </h3>
            </div>

            <div class="space-y-3">
                @foreach($members as $m)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-blue-50/40 border border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white border border-blue-200 flex items-center justify-center font-black text-brand-blue font-rounded text-sm">
                                {{ mb_substr($m->user ? $m->user->display_name : '友', 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-sm text-[#2C3E50] font-rounded block">
                                    {{ $m->user ? $m->user->display_name : '名無しメンバー' }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-bold">
                                    {{ $m->role === 'admin' ? '管理者（ホスト）' : 'ファミリーメンバー' }}
                                </span>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold font-rounded">
                            オンライン
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- 新しい家族メンバーを招待・追加フォーム -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-sm font-black text-[#2D415A] font-rounded mb-3">新しい家族を追加する</h4>
                <form action="{{ route('family.addMember') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="display_name" required placeholder="呼び名（例: おばあちゃん、長男）" class="flex-1 px-4 py-3 rounded-2xl border border-blue-200 text-sm focus:outline-none focus:border-brand-blue font-rounded">
                    <button type="submit" class="pressable px-5 py-3 rounded-2xl bg-brand-blue text-white font-extrabold text-xs font-rounded shadow-btn-blue hover:bg-brand-blueDark transition whitespace-nowrap">
                        招待・追加
                    </button>
                </form>
            </div>
        </div>


        <!-- 右カラム：家族のアクティビティ履歴 ＆ リアクション (6カラム) -->
        <div class="lg:col-span-6 bg-white rounded-[36px] p-6 sm:p-8 shadow-soft border border-blue-100 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-black text-[#2D415A] font-rounded text-lg flex items-center gap-2">
                    <span>🔔</span> 家族の最新アクティビティ
                </h3>
            </div>

            <!-- タイムライン一覧 -->
            <div class="space-y-3 max-h-[360px] overflow-y-auto pr-1">
                @forelse($activities as $act)
                    <div class="flex items-center gap-3 p-3 rounded-2xl {{ $act['type'] === 'purchased' ? 'bg-emerald-50/50 border border-emerald-100' : 'bg-blue-50/40 border border-blue-100' }}">
                        <span class="text-xl">
                            {{ $act['type'] === 'purchased' ? '🛒' : '➕' }}
                        </span>
                        <div class="flex-1">
                            <span class="text-xs font-bold text-[#2C3E50] font-rounded block">
                                <span class="text-brand-blue font-extrabold">{{ $act['user'] }}</span> が 
                                <span class="font-black text-[#2C3E50]">「{{ $act['item'] }}」</span> を {{ $act['action'] }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold">{{ $act['time'] }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">まだ履歴はありません</p>
                @endforelse
            </div>

            <!-- 家族へのクイックリアクションボタン -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-400 block mb-3 font-rounded">家族にメッセージを送る</span>
                <div class="grid grid-cols-3 gap-2">
                    <form action="{{ route('family.postReaction') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reaction" value="bought">
                        <button type="submit" class="w-full pressable py-2.5 px-3 rounded-2xl bg-blue-50 hover:bg-blue-100 border border-blue-200 text-brand-blue font-bold text-xs font-rounded transition">
                            🛒 買ったよ！
                        </button>
                    </form>

                    <form action="{{ route('family.postReaction') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reaction" value="used">
                        <button type="submit" class="w-full pressable py-2.5 px-3 rounded-2xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 font-bold text-xs font-rounded transition">
                            🧻 使ったよ！
                        </button>
                    </form>

                    <form action="{{ route('family.postReaction') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reaction" value="thanks">
                        <button type="submit" class="w-full pressable py-2.5 px-3 rounded-2xl bg-pink-50 hover:bg-pink-100 border border-pink-200 text-pink-600 font-bold text-xs font-rounded transition">
                            💖 ありがとう！
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
