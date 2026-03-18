<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hypothesis Testing - Statistical Tests</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap');

        * {
            font-family: 'Space Grotesk', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(at 20% 30%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 70%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.1) 0px, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(148, 163, 184, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .test-link {
            position: relative;
            display: block;
            padding: 1rem 1.5rem;
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .test-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            transition: width 0.3s ease;
            z-index: 0;
        }

        .test-link:hover::before {
            width: 100%;
        }

        .test-link:hover {
            transform: translateX(8px);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .test-link span {
            position: relative;
            z-index: 1;
        }

        .category-title {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lang-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 9999px;
            transition: all 0.3s ease;
        }

        .lang-switch:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            transform: scale(1.05);
        }

        .info-badge {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="relative z-10 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <a href="../index.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← Back to home
                    </a>
                    <h1 class="text-3xl md:text-5xl font-bold text-white mb-2">
                        Hypothesis Testing
                    </h1>
                    <p class="text-slate-400">Statistical tests and analysis</p>
                </div>
                <a href="../cz/celekTEST.php" class="lang-switch text-white hover:text-blue-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    Česky
                </a>
            </div>

            <div class="info-badge mt-6">
                <p class="text-blue-300 text-sm">
                    <strong>Note:</strong> It is necessary to use decimal dot, switching among items by Tab
                </p>
            </div>
        </div>

        <!-- One-sample tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">One-sample tests</h2>
            <div class="grid gap-4">
                <a href="Ejednov.php" class="test-link">
                    <span class="text-white font-medium">One-sample t-test (Student) two-tailed</span>
                </a>
                <a href="Ejednovjedn.php" class="test-link">
                    <span class="text-white font-medium">One-sample t-test (Student) one-tailed</span>
                </a>
                <a href="EjednovS.php" class="test-link">
                    <span class="text-white font-medium">One-sample test for variance</span>
                </a>
                <a href="Ewiljednov.php" class="test-link">
                    <span class="text-white font-medium">Wilcoxon signed-rank test</span>
                </a>
            </div>
        </div>

        <!-- Two-sample and paired tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">Two-sample and paired tests</h2>
            <div class="grid gap-4">
                <a href="Edvouv.php" class="test-link">
                    <span class="text-white font-medium">Two-sample t-test (Student)</span>
                </a>
                <a href="Eparovy.php" class="test-link">
                    <span class="text-white font-medium">Paired t-test</span>
                </a>
                <a href="EdvouvF.php" class="test-link">
                    <span class="text-white font-medium">Two-sample F-test (Fischer)</span>
                </a>
                <a href="Ewildvouv.php" class="test-link">
                    <span class="text-white font-medium">Wilcoxon sum-rank test (Mann-Whitney test)</span>
                </a>
            </div>
        </div>

        <!-- ANOVA -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">ANOVA</h2>
            <div class="grid gap-4">
                <a href="Eanova.php" class="test-link">
                    <span class="text-white font-medium">One-way ANOVA</span>
                </a>
                <a href="Ekruskalwal.php" class="test-link">
                    <span class="text-white font-medium">Kruskal-Wallis test</span>
                </a>
                <a href="Eanovadvoj.php" class="test-link">
                    <span class="text-white font-medium">Two-way ANOVA without interaction</span>
                </a>
                <a href="Eanovadvojinter.php" class="test-link">
                    <span class="text-white font-medium">Two-way ANOVA and interaction</span>
                </a>
                <a href="Efriedm.php" class="test-link">
                    <span class="text-white font-medium">Friedman test</span>
                </a>
            </div>
        </div>

        <!-- Tests of independence -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">Tests of independence</h2>
            <div class="grid gap-4">
                <a href="Epears.php" class="test-link">
                    <span class="text-white font-medium">Pearson correlation coefficient test</span>
                </a>
                <a href="Espearman.php" class="test-link">
                    <span class="text-white font-medium">Spearman rank correlation coefficient test</span>
                </a>
                <a href="Ekontin.php" class="test-link">
                    <span class="text-white font-medium">Contingency table chi-square test</span>
                </a>
            </div>
        </div>

        <!-- Regression -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">Regression</h2>
            <div class="grid gap-4">
                <a href="Elinreg.php" class="test-link">
                    <span class="text-white font-medium">Linear regression</span>
                </a>
            </div>
        </div>

        <!-- Documentation -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h2 class="text-2xl font-bold category-title mb-6">Texts and documentation</h2>
            <div class="grid gap-4">
                <a href="pdf/Evzorce.pdf" class="test-link" target="_blank">
                    <span class="text-white font-medium flex items-center gap-2">
                        Concepts and formulas
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </span>
                </a>
                <a href="pdf/Etabulkyst.pdf" class="test-link" target="_blank">
                    <span class="text-white font-medium flex items-center gap-2">
                        Statistical tables
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="glass-card rounded-2xl p-6 text-center">
            <p class="text-slate-400 text-sm">
                © 2025 Czech University of Life Sciences Prague, Faculty of Environmental Sciences
            </p>
        </div>
    </div>
</body>
</html>
