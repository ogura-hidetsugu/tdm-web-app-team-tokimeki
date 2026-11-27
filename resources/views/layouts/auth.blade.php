<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'くらすと')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: M PLUS Rounded 1c & Zen Maru Gothic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;500;700;800;900&family=Zen+Maru+Gothic:wght@500;700;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        rounded: ['"M PLUS Rounded 1c"', '"Zen Maru Gothic"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#6D9EE3',
                            blueDark: '#5587D1',
                            blueSoft: '#88B2EB',
                            blueLight: '#E8F1FC',
                            yellow: '#FAD074',
                            text: '#58677A',
                            textDark: '#46566A',
                            textMuted: '#7E8E9F',
                            border: '#CBDDF3',
                        }
                    },
                    boxShadow: {
                        'auth-card': '0 14px 40px -8px rgba(100, 140, 200, 0.14), 0 4px 12px -2px rgba(100, 140, 200, 0.06)',
                        'auth-btn': '0 6px 18px -3px rgba(110, 160, 230, 0.45)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'M PLUS Rounded 1c', 'Zen Maru Gothic', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #FAF8F4;
            -webkit-tap-highlight-color: transparent;
        }

        .pressable {
            transition: transform 0.16s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.16s ease, opacity 0.16s ease;
        }
        .pressable:active {
            transform: scale(0.97);
        }

        @keyframes gentle-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }
        .animate-float-slow {
            animation: gentle-float 4.5s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen text-brand-text relative overflow-x-hidden flex flex-col justify-between antialiased selection:bg-brand-blueLight selection:text-brand-blueDark">

    <!-- 背景のパステル水玉デコレーション -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
        <div class="absolute -top-6 left-[3%] w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-[#E1EEFD]"></div>
        <div class="absolute top-[52%] -left-6 w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#E5F0FD]"></div>
        <div class="absolute -top-8 -right-8 w-44 h-44 sm:w-56 sm:h-56 rounded-full bg-[#DCEAFB]"></div>
        <div class="absolute top-[45%] right-[12%] w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-[#E3EFFD]"></div>
        <div class="absolute bottom-[18%] left-[20%] w-16 h-16 rounded-full bg-[#EBF3FD] hidden sm:block"></div>
    </div>

    <!-- 下部のやさしい水色ウェーブ -->
    <div class="fixed bottom-0 inset-x-0 pointer-events-none z-0 leading-none" aria-hidden="true">
        <svg viewBox="0 0 1440 160" preserveAspectRatio="none" class="w-full h-20 sm:h-28 md:h-32">
            <path d="M0,72 C240,12 420,115 720,90 C1020,65 1230,10 1440,56 L1440,160 L0,160 Z" fill="#D9E7FA" />
        </svg>
    </div>

    <!-- メインコンテンツエリア -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-10 sm:py-14">
        <div class="relative w-full max-w-[980px] flex items-center justify-center">

            <!-- 周辺イラスト（PC・タブレット表示） -->
            @yield('illustrations')

            <!-- 中央の認証カード -->
            <section class="relative z-20 w-full max-w-[490px] bg-white/95 backdrop-blur-sm rounded-[34px] px-7 sm:px-11 pt-9 pb-8 shadow-auth-card border border-[#EBF2FB]">

                <!-- カード左上・右上の黄色いアクセント線 -->
                <svg class="absolute top-6 left-7 w-7 h-7 text-brand-yellow pointer-events-none" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M10 18L4 12" stroke="currentColor" stroke-width="4.2" stroke-linecap="round" />
                    <path d="M18 11L15 3" stroke="currentColor" stroke-width="4.2" stroke-linecap="round" />
                </svg>
                <svg class="absolute top-6 right-7 w-7 h-7 text-brand-yellow pointer-events-none" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M14 11L17 3" stroke="currentColor" stroke-width="4.2" stroke-linecap="round" />
                    <path d="M22 18L28 12" stroke="currentColor" stroke-width="4.2" stroke-linecap="round" />
                </svg>

                <!-- 上部のゆるやかな水色アーチライン -->
                <div class="flex justify-center mt-4 mb-2.5" aria-hidden="true">
                    <svg class="w-56 sm:w-64 h-6" viewBox="0 0 260 24" fill="none">
                        <path d="M10 20C82 4 178 4 250 20" stroke="#C5DDF9" stroke-width="4.5" stroke-linecap="round" />
                    </svg>
                </div>

                @yield('card-content')
            </section>
        </div>
    </main>

</body>
</html>
