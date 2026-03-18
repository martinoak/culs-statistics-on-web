<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jednoduché třídění (Analýza rozptylu ANOVA)</title>
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
 * Calculate sum of squared deviations from mean
 */
function sctv(array $xvar, float $mean): float
{
    $sc = 0;
    foreach ($xvar as $value) {
        $sc += pow($value - $mean, 2);
    }
    return $sc;
}

/**
 * Get critical value from F-distribution
 */
function invf(int $sv, int $sw): float
{
    $fis = fopen("../samples/fis/fis3.txt", "r");
    $stav = ($sw - 1) * 151 + ($sv - 1) * 5;
    fseek($fis, $stav);
    $inv = fread($fis, 4);
    fclose($fis);
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

        .data-row {
            color: #cbd5e1;
        }

        .data-value {
            font-weight: 600;
        }
    </style>

    <script>
        function otev() {
            vzor = window.open("pdf/anovajedn.pdf", "vzor", "width=800, height=600");
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
                        Jednoduché třídění (Analýza rozptylu ANOVA)
                    </h1>
                </div>
                <div class="flex gap-2">
                    <button onclick="otev()" class="btn-secondary text-sm">
                        📐 Vzorce
                    </button>
                    <button onclick="zav()" class="btn-secondary text-sm">
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
                        Náhodné výběry z N(μ<sub>1</sub>, σ²), ..., N(μ<sub><?php echo $in; ?></sub>, σ²)<br>
                        <span class="text-sm text-slate-400">
                            μ<sub>1</sub> = μ + α<sub>1</sub>, ..., μ<sub><?php echo $in; ?></sub> = μ + α<sub><?php echo $in; ?></sub>, Σα<sub>k</sub> = 0
                        </span>
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
                </div>
                <div class="mt-4">
                    <p class="text-slate-300">
                        Nulová hypotéza H<sub>0</sub>: μ<sub>1</sub> = ... = μ<sub><?php echo $in; ?></sub><br>
                        <span class="text-sm text-slate-400">neboli α<sub>1</sub> = ... = α<sub><?php echo $in; ?></sub> = 0</span>
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

    // Calculate statistics
    $nnn = $s[$in - 1];
    for ($i = 0; $i < $in; $i++) {
        for ($k = 0; $k < $s[$in - 1]; $k++) {
            $mx[$i][$k] = $x[$k] * $ind[$i][$k];
        }
    }

    for ($i = 0; $i < $in; $i++) {
        $mmx[$i] = sum($mx[$i]) / $n[$i];
        $zmmx[$i] = zaokr($mmx[$i], 4);
    }

    $mmm = sum($x) / $nnn;
    $zmmm = zaokr($mmm, 4);
    $st = sctv($x, $mmm);
    $sa = 0;

    for ($i = 0; $i < $in; $i++) {
        $sa = $sa + pow(($mmx[$i] - $mmm), 2) * $n[$i];
    }

    $se = $st - $sa;
    $ft = $nnn - 1;
    $fa = $in - 1;
    $fe = $nnn - $in;
    $ss = $se / $fe;

    $zsa = zaokr($sa, 4);
    $zse = zaokr($se, 4);
    $zst = zaokr($st, 4);
    $zss = zaokr($ss, 4);
?>

        <!-- Step 3: Data Input with Results -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
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
                        Náhodné výběry z N(μ<sub>1</sub>, σ²), ..., N(μ<sub><?php echo $in; ?></sub>, σ²)<br>
                        <span class="text-sm text-slate-400">
                            μ<sub>1</sub> = μ + α<sub>1</sub>, ..., μ<sub><?php echo $in; ?></sub> = μ + α<sub><?php echo $in; ?></sub>, Σα<sub>k</sub> = 0
                        </span>
                    </p>
                    <?php for ($i = 0; $i < $in; $i++): ?>
                        <div class="mb-3">
                            <label class="block text-slate-300 mb-2">
                                X<sub><?php echo ($i + 1); ?>,1</sub>, ..., X<sub><?php echo ($i + 1); ?>,<?php echo $n[$i]; ?></sub>:
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <?php for ($k = 0; $k < $n[$i]; $k++): ?>
                                    <input type="number" step="any" name="x[]"
                                           value="<?php echo htmlspecialchars($x[$s[$i - 1] + $k]); ?>"
                                           class="input-field" required>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="mt-4">
                    <p class="text-slate-300">
                        Nulová hypotéza H<sub>0</sub>: μ<sub>1</sub> = ... = μ<sub><?php echo $in; ?></sub><br>
                        <span class="text-sm text-slate-400">neboli α<sub>1</sub> = ... = α<sub><?php echo $in; ?></sub> = 0</span>
                    </p>
                </div>
                <button type="submit" class="btn-primary">Proveďte test</button>
                <input type="hidden" name="a" value="3">
            </form>

            <!-- Results Section -->
            <div class="mt-6">
                <div class="result-box">
                    <h3 class="text-lg font-bold text-white mb-3">📊 Výsledky analýzy</h3>
                    <div class="text-slate-300 space-y-2">
                        <p><span style="text-decoration: overline">X</span> = <span class="data-value"><?php echo $zmmm; ?></span></p>
                        <div class="flex flex-wrap gap-4">
                            <?php for ($i = 0; $i < $in; $i++): ?>
                                <span><span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub> = <span class="data-value"><?php echo $zmmx[$i]; ?></span></span>
                            <?php endfor; ?>
                        </div>
                        <p class="mt-4">S<sub>A</sub> = <span class="data-value"><?php echo $zsa; ?></span> &nbsp;&nbsp; f<sub>A</sub> = <span class="data-value"><?php echo $fa; ?></span></p>
                        <p>S<sub>e</sub> = <span class="data-value"><?php echo $zse; ?></span> &nbsp;&nbsp; f<sub>e</sub> = <span class="data-value"><?php echo $fe; ?></span></p>
                        <p>s² = <span class="data-value"><?php echo $zss; ?></span></p>
                        <p>S<sub>T</sub> = <span class="data-value"><?php echo $zst; ?></span> &nbsp;&nbsp; f<sub>T</sub> = <span class="data-value"><?php echo $ft; ?></span></p>
                    </div>
                </div>

<?php if ($ss == 0): ?>
                <div class="error-box mt-4">
                    <p class="text-red-300">⚠️ Reziduální součet čtverců se rovná 0, tento test nelze užít.</p>
                </div>
<?php else:
    $ff = $sa / $fa / $ss;
    $zf = zaokr($ff, 3);
    $finv = invf($fa, $fe);
    $pom = sqrt($fa * $ss * $finv);

    for ($i = 0; $i < $in; $i++) {
        for ($k = $i + 1; $k < $in; $k++) {
            $roz[$i][$k] = max(($mmx[$i] - $mmx[$k]), ($mmx[$k] - $mmx[$i]));
            $zroz[$i][$k] = zaokr($roz[$i][$k], 2);
            $shef[$i][$k] = sqrt(1 / ($n[$i]) + 1 / ($n[$k])) * $pom;
            $zshef[$i][$k] = zaokr($shef[$i][$k], 2);
        }
    }
?>
                <div class="result-box mt-4">
                    <div class="text-slate-300 space-y-2">
                        <p>F = <span class="data-value"><?php echo $zf; ?></span></p>
                        <p>F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95) = <span class="data-value"><?php echo $finv; ?></span></p>
                    </div>
                </div>

<?php if ($ff >= $finv): ?>
                <div class="success-box mt-4">
                    <p class="text-green-300">✓ F ≥ F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95)</p>
                    <p class="text-green-300 mt-2">→ Hypotézu H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $in; ?></sub> = 0 <strong>zamítneme</strong></p>
                </div>

                <div class="result-box mt-4">
                    <h4 class="text-lg font-bold text-white mb-3">🔍 Post hoc test (Sheffého metoda):</h4>
                    <div class="text-slate-300 space-y-1">
                        <?php for ($i = 0; $i < $in; $i++):
                            for ($k = $i + 1; $k < $in; $k++):
                                if ($roz[$i][$k] > $shef[$i][$k]): ?>
                                    <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub> - <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub>| = <?php echo $zroz[$i][$k] . " > " . $zshef[$i][$k]; ?> → <span class="text-green-300 font-semibold">je rozdíl</span></p>
                                <?php else: ?>
                                    <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub> - <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub>| = <?php echo $zroz[$i][$k] . " ≤ " . $zshef[$i][$k]; ?> → není rozdíl</p>
                                <?php endif;
                            endfor;
                        endfor; ?>
                    </div>
                </div>
<?php else: ?>
                <div class="result-box mt-4">
                    <p class="text-slate-300">F < F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95)</p>
                    <p class="text-slate-300 mt-2">→ Hypotézu H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $in; ?></sub> = 0 <strong>nezamítneme</strong></p>
                </div>
<?php endif; ?>
<?php endif; ?>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Související testy se stejnými daty:</h3>
            <div class="flex flex-wrap gap-3">
                <a href="kruskalwal.php?in=<?php echo $in; ?>&<?php
                    for ($i = 0; $i < $in; $i++) {
                        echo 'n%5B%5D=' . $n[$i] . '&';
                        $sss[$i + 1] = $sss[$i] + $n[$i];
                    }
                    for ($j = 0; $j < $in; $j++) {
                        for ($k = 0; $k < $n[$j]; $k++) {
                            echo 'x%5B%5D=' . $x[$sss[$j] + $k] . '&';
                        }
                    }
                ?>a=2" class="link-button">Kruskalův-Wallisův test</a>
            </div>
        </div>

        <!-- New Entry Button -->
        <div class="glass-card rounded-2xl p-6 text-center">
            <form method="get">
                <button type="submit" class="btn-primary">🔄 Nové zadání</button>
                <input type="hidden" name="a" value="0">
            </form>
        </div>

<?php
    endif;
break;

endswitch;
?>

    </div>
</body>
</html>

