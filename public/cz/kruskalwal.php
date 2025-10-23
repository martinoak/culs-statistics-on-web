<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kruskalův - Wallisův test</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$in = $_GET['in'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;

/**
 * Calculate sum of array values
 */
function sum(array $xvar): float
{
    $s = 0;
    foreach ($xvar as $value) {
        $s += $value;
    }
    return $s;
}

/**
 * Calculate ranks for combined sample
 */
function razeni(array $x): array
{
    $n = count($x);
    $p = [];
    for ($i = 0; $i < $n; $i++) {
        $p[$i] = 0.5;
        for ($k = 0; $k < $n; $k++) {
            if ($x[$k] < $x[$i]) {
                $p[$i] = $p[$i] + 1;
            } elseif ($x[$k] == $x[$i]) {
                $p[$i] = $p[$i] + 0.5;
            }
        }
    }
    return $p;
}

/**
 * Get critical value from chi-square distribution
 */
function invchi3(int $sv): float
{
    $chi = fopen("../samples/chi/chi3.txt", "r");
    $stav = ($sv - 1) * 7;
    fseek($chi, $stav);
    $inv = fread($chi, 5);
    fclose($chi);
    return (float) $inv;
}

/**
 * Round number to specified decimal places
 */
function zaokr(float $cislo, int $des): float
{
    $multiplier = pow(10, $des);
    return round($cislo * $multiplier) / $multiplier;
}

?>
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

        .input-field {
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            width: 80px;
        }

        .input-field:focus {
            outline: none;
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: rgba(51, 65, 85, 0.5);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(148, 163, 184, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .result-box {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .success-box {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .link-button {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(51, 65, 85, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .link-button:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        .data-table {
            width: 100%;
            overflow-x: auto;
        }

        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 0.5rem;
            text-align: center;
            color: #cbd5e1;
        }

        .data-table .rank {
            color: #cbd5e1;
            font-weight: 600;
        }

        .data-value {
            color: #cbd5e1;
            font-weight: 600;
        }
    </style>

    <script>
        function otev() {
            vzor = window.open("kruskalw.png", "vzor", "width=750, height=450");
        }

        function zav() {
            if (typeof vzor !== 'undefined' && !vzor.closed) {
                vzor.close();
            }
        }
    </script>
</head>

<body class="p-4 md:p-8" onunload="zav()">

    <div class="relative z-10 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <a href="celekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← Seznam testů
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Kruskalův - Wallisův test
                    </h1>
                </div>
                <div class="flex gap-2">
                    <button onmouseover="otev()" class="btn-secondary text-sm">
                        📐 Vzorce
                    </button>
                    <button onmouseover="zav()" class="btn-secondary text-sm">
                        ✕ Zavřít
                    </button>
                </div>
            </div>
        </div>

<?php
switch($a):

case 0: ?>

        <!-- Step 1: Number of Categories Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in ?? ''); ?>" 
                           class="input-field" min="3" max="10" required>
                    <p class="text-slate-400 text-sm mt-1">Zadejte číslo od 3 do 10</p>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
$ca = 0;
break;

case 1:
    if ($in < 3 || $in > 10 || !(round($in) == $in)): ?>

        <!-- Error: Invalid Number of Categories -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste celé číslo mezi 3 a 10, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                    <p class="text-slate-400 text-sm mt-1">Zadejte číslo od 3 do 10</p>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php else: ?>

        <!-- Step 2: Sample Sizes Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah jednotlivých tříd n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i] ?? ''); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                    <p class="text-slate-400 text-sm mt-1">Zadejte čísla od 2 do 10</p>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="2">
            </form>
        </div>

<?php
    endif;
break;

case 2:
    $va = 2;
    $vb = 10;
    $vc = 0;
    for ($i = 0; $i < $in; $i++) {
        $va = min($va, $n[$i]);
        $vb = max($vc, $n[$i]);
        $vc = $vc + ($n[$i] - round($n[$i]));
    }

    if ($in < 3 || $in > 10 || !(round($in) == $in)): ?>

        <!-- Error: Invalid Number of Categories -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste celé číslo mezi 3 a 10, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php elseif ($va < 2 || $vb > 10 || !$vc == 0): ?>

        <!-- Error: Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste celá čísla mezi 2 a 10, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah jednotlivých tříd n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i]); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="2">
            </form>
        </div>

<?php else:
    $s[0] = 0;
    for ($i = 0; $i < $in; $i++) {
        $s[$i] = $s[$i - 1] + $n[$i];
    }
?>

        <!-- Step 3: Data Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah jednotlivých tříd n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i]); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-slate-300 mb-3">
                        Náhodné výběry z r = <?php echo $in; ?> spojitých rozdělení
                    </p>
                    <?php for ($i = 0; $i < $in; $i++): ?>
                        <div class="mb-3">
                            <label class="block text-slate-300 mb-2">
                                X<sub><?php echo ($i + 1); ?>,1</sub>, ..., X<sub><?php echo ($i + 1); ?>,<?php echo $n[$i]; ?></sub>:
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <?php for ($k = 0; $k < $n[$i]; $k++): ?>
                                    <input type="number" step="any" name="x[]"
                                           value="<?php echo htmlspecialchars($x[$s[$i - 1] + $k] ?? ''); ?>"
                                           class="input-field" required>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <?php
                    for ($i = 0; $i < $in; $i++) {
                        for ($k = $s[$i - 1]; $k < $s[$i]; $k++) {
                            $ind[$i][$k] = 1;
                        }
                    }
                    ?>
                </div>
                <div class="mt-4">
                    <p class="text-slate-300">
                        Nulová hypotéza H<sub>0</sub>: všechna rozdělení jsou stejná
                    </p>
                </div>
                <button type="submit" class="btn-primary">Proveďte test</button>
                <input type="hidden" name="a" value="3">
            </form>
        </div>

<?php
    endif;
break;

case 3:
    $va = 2;
    $vb = 10;
    $vc = 0;
    for ($i = 0; $i < $in; $i++) {
        $va = min($va, $n[$i]);
        $vb = max($vc, $n[$i]);
        $vc = $vc + ($n[$i] - round($n[$i]));
    }

    if ($in < 3 || $in > 10 || !(round($in) == $in)): ?>

        <!-- Error: Invalid Number of Categories -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste celé číslo mezi 3 a 10, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php elseif ($va < 2 || $vb > 10 || !$vc == 0): ?>

        <!-- Error: Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste celá čísla mezi 2 a 10, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah jednotlivých tříd:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i]); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="2">
            </form>
        </div>

<?php else:
    $s[0] = 0;
    for ($i = 0; $i < $in; $i++) {
        $s[$i] = $s[$i - 1] + $n[$i];
    }

    // Build indicator matrix
    for ($i = 0; $i < $in; $i++) {
        for ($k = 0; $k < $s[$in - 1]; $k++) {
            if ($s[$i - 1] <= $k && $k < $s[$i]) {
                $ind[$i][$k] = 1;
            } else {
                $ind[$i][$k] = 0;
            }
        }
    }

    // Calculate ranks and statistics
    $p = razeni($x);
    $pp = [];
    $tt = [];
    $st = 0;

    for ($i = 0; $i < $in; $i++) {
        for ($k = 0; $k < $s[$in - 1]; $k++) {
            $pp[$i][$k] = $p[$k] * $ind[$i][$k];
        }
    }

    for ($i = 0; $i < $in; $i++) {
        $tt[$i] = sum($pp[$i]);
    }

    for ($i = 0; $i < $in; $i++) {
        $st = $st + $tt[$i] * $tt[$i] / $n[$i];
    }

    $nn = $s[($in - 1)];
    $q = 12 / $nn / ($nn + 1) * $st - 3 * ($nn + 1);
    $zq = zaokr($q, 4);
    $chinv = invchi3(($in - 1));
?>

        <!-- Results Display -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">📊 Výběr X s pořadími:</h3>
                <div class="data-table">
                    <?php for ($i = 0; $i < $in; $i++): ?>
                        <table class="mb-4">
                            <tr>
                                <td class="text-slate-300">X<sub><?php echo ($i + 1); ?>,k</sub>:</td>
                                <?php for ($k = 0; $k < $n[$i]; $k++): ?>
                                    <td class="text-slate-300"><?php echo $x[$s[$i - 1] + $k]; ?></td>
                                <?php endfor; ?>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-slate-400">Pořadí:</td>
                                <?php for ($k = 0; $k < $n[$i]; $k++): ?>
                                    <td class="rank"><?php echo $p[$s[$i - 1] + $k]; ?></td>
                                <?php endfor; ?>
                                <td class="text-slate-300">T<sub><?php echo ($i + 1); ?></sub> = <span class="data-value"><?php echo $tt[$i]; ?></span></td>
                            </tr>
                        </table>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">🔍 Testová statistika:</h3>
                <div class="text-slate-300 space-y-2">
                    <p>Q = <span class="data-value"><?php echo $zq; ?></span></p>
                    <p>χ²<sub><?php echo ($in - 1); ?></sub>(0.95) = <span class="data-value"><?php echo $chinv; ?></span></p>
                </div>
            </div>

<?php if ($q >= $chinv): ?>
            <div class="success-box">
                <p class="text-green-300">✓ Q ≥ χ²<sub><?php echo ($in - 1); ?></sub>(0.95)</p>
                <p class="text-green-300 mt-2">→ Hypotézu H<sub>0</sub>: stejné rozdělení <strong>zamítneme</strong></p>
            </div>
<?php else: ?>
            <div class="result-box">
                <p class="text-slate-300">Q < χ²<sub><?php echo ($in - 1); ?></sub>(0.95)</p>
                <p class="text-slate-300 mt-2">→ Hypotézu H<sub>0</sub>: stejné rozdělení <strong>nezamítneme</strong></p>
            </div>
<?php endif; ?>

            <!-- Related Tests -->
            <div class="mt-6">
                <p class="text-slate-400 text-sm mb-3">🔗 Související testy se stejnými daty:</p>
                <div class="flex flex-wrap gap-3">
                    <a href="anova.php?in=<?php echo $in; ?>&<?php
                        for ($i = 0; $i < $in; $i++) {
                            echo 'n%5B%5D=' . $n[$i] . '&';
                            $sss[$i + 1] = $sss[$i] + $n[$i];
                        }
                        for ($j = 0; $j < $in; $j++) {
                            for ($k = 0; $k < $n[$j]; $k++) {
                                echo 'x%5B%5D=' . $x[$sss[$j] + $k] . '&';
                            }
                        }
                    ?>a=2" class="link-button">Jednoduché třídění (ANOVA)</a>
                    <form method="get" class="inline-block">
                        <button type="submit" class="btn-secondary">Nové zadání</button>
                        <input type="hidden" name="a" value="0">
                    </form>
                </div>
            </div>
        </div>

<?php
    endif;
break;

endswitch;
?>

    </div>
</body>
</html>

