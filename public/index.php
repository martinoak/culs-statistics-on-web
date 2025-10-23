<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testování Hypotéz - Hypothesis Testing</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        body {
            font-family: 'Space Grotesk', sans-serif;
        }

        .mesh-bg {
            background:
                linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .neon-border {
            position: relative;
        }

        .neon-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1.5rem;
            padding: 2px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6, #ec4899);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .neon-border:hover::before {
            opacity: 1;
        }

        .split-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .split-layout {
                grid-template-columns: 1fr 1fr;
            }
        }

        .lang-option {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .lang-option:hover {
            transform: scale(1.02);
        }

        .accent-line {
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            border-radius: 2px;
        }
    </style>
</head>
<body class="mesh-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-7xl w-full">
            <!-- Main Card -->
            <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 text-white px-8 py-12 md:px-12 md:py-16">
                    <div class="max-w-4xl mx-auto text-center">
                        <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full mb-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm font-semibold uppercase tracking-wider">Statistical Analysis</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-bold mb-4 tracking-tight">
                            Hypothesis Testing
                        </h1>
                        <div class="accent-line w-24 mx-auto mb-6"></div>
                        <p class="text-lg md:text-xl text-slate-300">
                            Advanced statistical tools for research and education
                        </p>
                    </div>
                </div>

                <!-- Language Selection -->
                <div class="p-8 md:p-12">
                    <div class="max-w-5xl mx-auto">
                        <div class="split-layout">
                            <!-- Czech Option -->
                            <a href="cz/celekTEST.php" class="block">
                                <div class="lang-option neon-border bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 h-full">
                                    <div class="flex flex-col h-full">
                                        <div class="flex justify-center mb-6">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-blue-400 blur-xl opacity-30 rounded-full"></div>
                                                <img src="assets/cz.png" alt="Czech" class="relative w-32 h-auto rounded-xl shadow-lg">
                                            </div>
                                        </div>
                                        <div class="text-center flex-grow">
                                            <div class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-3">
                                                Čeština
                                            </div>
                                            <h3 class="text-3xl font-bold text-slate-800 mb-4">
                                                TESTOVÁNÍ<br/>HYPOTÉZ
                                            </h3>
                                            <p class="text-slate-600 mb-6">
                                                Kompletní sada statistických testů v českém jazyce
                                            </p>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-bold text-center hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg">
                                                Vstoupit do aplikace →
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <!-- English Option -->
                            <a href="en/EcelekTEST.php" class="block">
                                <div class="lang-option neon-border bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-8 h-full">
                                    <div class="flex flex-col h-full">
                                        <div class="flex justify-center mb-6">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-purple-400 blur-xl opacity-30 rounded-full"></div>
                                                <img src="assets/gb.png" alt="English" class="relative w-32 h-auto rounded-xl shadow-lg">
                                            </div>
                                        </div>
                                        <div class="text-center flex-grow">
                                            <div class="inline-block px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-3">
                                                English
                                            </div>
                                            <h3 class="text-3xl font-bold text-slate-800 mb-4">
                                                HYPOTHESIS<br/>TESTING
                                            </h3>
                                            <p class="text-slate-600 mb-6">
                                                Complete suite of statistical tests in English
                                            </p>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 px-6 rounded-xl font-bold text-center hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg">
                                                Enter Application →
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-100 px-8 py-8 border-t border-slate-200">
                    <div class="max-w-5xl mx-auto text-center">
                        <p class="text-lg font-bold text-slate-800 mb-3">
                            &copy; František Mošna
                        </p>
                        <div class="text-sm text-slate-600 space-y-1">
                            <p>
                                Česká Zemědělská Univ. v Praze, Karlova Univ. v Praze, Pedagogická fak., Česká rep.
                            </p>
                            <p>
                                Czech Univ. of Life Sciences in Prague, Charles Univ. in Prague, Fac. of Education, Czech rep.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
