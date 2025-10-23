<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dvojné třídění (ANOVA) bez interakcí</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$ir = $_GET['ir'] ?? null;
$is = $_GET['is'] ?? null;
$ip = $_GET['ip'] ?? null;
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

        .data-value {
            color: #cbd5e1;
            font-weight: 600;
        }

        .data-grid {
            display: grid;
            gap: 0.5rem;
            margin: 1rem 0;
        }
    </style>

    <script>
        function otev() {
            vzor = window.open("anovadvou.png", "vzor", "width=950, height=700");
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
                        Dvojné třídění (ANOVA) bez interakcí
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

        <!-- Step 1: Parameters Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 mb-2">
                            Počet tříd A <em>r</em>:
                        </label>
                        <input type="number" name="ir" value="<?php echo htmlspecialchars($ir ?? ''); ?>" 
                               class="input-field" min="2" max="10" required>
                        <p class="text-slate-400 text-sm mt-1">Od 2 do 10</p>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">
                            Počet tříd B <em>s</em>:
                        </label>
                        <input type="number" name="is" value="<?php echo htmlspecialchars($is ?? ''); ?>" 
                               class="input-field" min="2" max="10" required>
                        <p class="text-slate-400 text-sm mt-1">Od 2 do 10</p>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">
                            Rozsah tříd <em>p</em>:
                        </label>
                        <input type="number" name="ip" value="<?php echo htmlspecialchars($ip ?? ''); ?>" 
                               class="input-field" min="1" max="10" required>
                        <p class="text-slate-400 text-sm mt-1">Od 1 do 10</p>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
break;

case 1:
    if ($ir < 2 || $is < 2 || $ip < 1 || $ir > 10 || $is > 10 || $ip > 10 || 
        !(round($ir) == $ir) || !(round($is) == $is) || !(round($ip) == $ip)): ?>

        <!-- Error: Invalid Parameters -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste požadovaná celá čísla, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd A <em>r</em>:</label>
                        <input type="number" name="ir" value="<?php echo htmlspecialchars($ir); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd B <em>s</em>:</label>
                        <input type="number" name="is" value="<?php echo htmlspecialchars($is); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Rozsah tříd <em>p</em>:</label>
                        <input type="number" name="ip" value="<?php echo htmlspecialchars($ip); ?>"
                               class="input-field" min="1" max="10" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php break;
    else:
        // Build 3D array structure
        for ($i = 0; $i < $ir; $i++) {
            for ($k = 0; $k < $is; $k++) {
                for ($j = 0; $j < $ip; $j++) {
                    $xx[$i][$k][$j] = $x[$is * $ip * $i + $k * $ip + $j] ?? '';
                }
            }
        }
?>

        <!-- Step 2: Data Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd A <em>r</em>:</label>
                        <input type="number" name="ir" value="<?php echo htmlspecialchars($ir); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd B <em>s</em>:</label>
                        <input type="number" name="is" value="<?php echo htmlspecialchars($is); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Rozsah tříd <em>p</em>:</label>
                        <input type="number" name="ip" value="<?php echo htmlspecialchars($ip); ?>"
                               class="input-field" min="1" max="10" required>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-slate-300 mb-2">Náhodné výběry z:</p>
                    <p class="text-slate-300 mb-2">Náhodné výběry z:</p>
                    <div class="flex space-x-8 text-slate-400">
                        <p>N(μ<sub>1,1</sub>, σ²), ..., N(μ<sub>1,<?php echo $is; ?></sub>, σ²)</p>
                        <p>...</p>
                        <p>N(μ<sub><?php echo $ir; ?>,1</sub>, σ²), ..., N(μ<sub><?php echo $ir; ?>,<?php echo $is; ?></sub>, σ²)</p>
                    </div>
                    <div class="flex space-x-8 text-slate-400 mt-2">
                        <p>μ<sub>1,1</sub> = μ + α<sub>1</sub> + β<sub>1</sub>, ..., μ<sub>1,<?php echo $is; ?></sub> = μ + α<sub>1</sub> + β<sub><?php echo $is; ?></sub></p>
                        <p>...</p>
                        <p>μ<sub><?php echo $ir; ?>,1</sub> = μ + α<sub><?php echo $ir; ?></sub> + β<sub>1</sub>, ..., μ<sub><?php echo $ir; ?>,<?php echo $is; ?></sub> = μ + α<sub><?php echo $ir; ?></sub> + β<sub><?php echo $is; ?></sub></p>
                    </div>
                    <p class="text-slate-400 mt-2">Σα<sub>k</sub> = 0, Σβ<sub>k</sub> = 0</p>
                </div>

                <div class="mt-4 space-y-3">
                    <?php for ($i = 0; $i < $ir; $i++): ?>
                        <div class="space-y-2">
                            <?php for ($k = 0; $k < $is; $k++): ?>
                                <div>
                                    <label class="block text-slate-300 mb-1 text-sm">
                                        X<sub><?php echo ($i + 1) . "," . ($k + 1); ?>,1</sub>, ..., X<sub><?php echo ($i + 1) . "," . ($k + 1) . "," . $ip; ?></sub>:
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        <?php for ($j = 0; $j < $ip; $j++): ?>
                                            <input type="number" step="any" name="x[]"
                                                   value="<?php echo htmlspecialchars($xx[$i][$k][$j]); ?>"
                                                   class="input-field" required>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="mt-4">
                    <p class="text-slate-300">Nulové hypotézy:</p>
                    <p class="text-slate-400 text-sm">H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $ir; ?></sub> = 0</p>
                    <p class="text-slate-400 text-sm">H<sub>0</sub>: β<sub>1</sub> = ... = β<sub><?php echo $is; ?></sub> = 0</p>
                </div>

                <button type="submit" class="btn-primary">Proveďte test</button>
                <input type="hidden" name="a" value="2">
            </form>
        </div>

<?php
    endif;
break;

case 2:
    if ($ir < 2 || $is < 2 || $ip < 1 || $ir > 10 || $is > 10 || $ip > 10 ||
        !(round($ir) == $ir) || !(round($is) == $is) || !(round($ip) == $ip)): ?>

        <!-- Error: Invalid Parameters -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ Nezadali jste požadovaná celá čísla, opravte prosím.</p>
            </div>
            <form method="get" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd A <em>r</em>:</label>
                        <input type="number" name="ir" value="<?php echo htmlspecialchars($ir); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd B <em>s</em>:</label>
                        <input type="number" name="is" value="<?php echo htmlspecialchars($is); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Rozsah tříd <em>p</em>:</label>
                        <input type="number" name="ip" value="<?php echo htmlspecialchars($ip); ?>"
                               class="input-field" min="1" max="10" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Pokračovat</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php break;
    else:
        // Build 3D array structure
        for ($i = 0; $i < $ir; $i++) {
            for ($k = 0; $k < $is; $k++) {
                for ($j = 0; $j < $ip; $j++) {
                    $xx[$i][$k][$j] = $x[$is * $ip * $i + $k * $ip + $j];
                }
            }
        }

        // Calculate means for each cell
        $nnn = $ir * $is * $ip;
        for ($i = 0; $i < $ir; $i++) {
            for ($j = 0; $j < $is; $j++) {
                $mx[$i][$j] = sum($xx[$i][$j]) / $ip;
                $zmx[$i][$j] = zaokr($mx[$i][$j], 4);
            }
        }

        // Calculate row means (factor A)
        for ($i = 0; $i < $ir; $i++) {
            $mma[$i] = sum($mx[$i]) / $is;
            $zmma[$i] = zaokr($mma[$i], 4);
        }

        // Transpose for column means
        for ($i = 0; $i < $is; $i++) {
            for ($j = 0; $j < $ir; $j++) {
                $mmx[$i][$j] = $mx[$j][$i];
            }
        }

        // Calculate column means (factor B)
        for ($j = 0; $j < $is; $j++) {
            $mmb[$j] = sum($mmx[$j]) / $ir;
            $zmmb[$j] = zaokr($mmb[$j], 4);
        }

        // Calculate overall mean and sums of squares
        $mmm = sum($x) / $nnn;
        $zmmm = zaokr($mmm, 4);
        $st = sctv($x, $mmm);
        $zst = zaokr($st, 4);
        $sa = $is * $ip * sctv($mma, $mmm);
        $zsa = zaokr($sa, 4);
        $sb = $ir * $ip * sctv($mmb, $mmm);
        $zsb = zaokr($sb, 4);
        $se = $st - $sa - $sb;
        $zse = zaokr($se, 4);

        // Degrees of freedom
        $ft = $nnn - 1;
        $fa = $ir - 1;
        $fb = $is - 1;
        $fe = $nnn - $ir - $is + 1;
        $ss = $se / $fe;
        $zss = zaokr($ss, 4);
?>

        <!-- Results Display -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-4 mb-6">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd A <em>r</em>:</label>
                        <input type="number" name="ir" value="<?php echo htmlspecialchars($ir); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Počet tříd B <em>s</em>:</label>
                        <input type="number" name="is" value="<?php echo htmlspecialchars($is); ?>"
                               class="input-field" min="2" max="10" required>
                    </div>
                    <div>
                        <label class="block text-slate-300 mb-2">Rozsah tříd <em>p</em>:</label>
                        <input type="number" name="ip" value="<?php echo htmlspecialchars($ip); ?>"
                               class="input-field" min="1" max="10" required>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-slate-300 mb-2">Náhodné výběry z:</p>
                    <div class="flex space-x-8 text-slate-400">
                        <p>N(μ<sub>1,1</sub>, σ²), ..., N(μ<sub>1,<?php echo $is; ?></sub>, σ²)</p>
                        <p>...</p>
                        <p>N(μ<sub><?php echo $ir; ?>,1</sub>, σ²), ..., N(μ<sub><?php echo $ir; ?>,<?php echo $is; ?></sub>, σ²)</p>
                    </div>
                    <div class="flex space-x-8 text-slate-400 mt-2">
                        <p>μ<sub>1,1</sub> = μ + α<sub>1</sub> + β<sub>1</sub>, ..., μ<sub>1,<?php echo $is; ?></sub> = μ + α<sub>1</sub> + β<sub><?php echo $is; ?></sub></p>
                        <p>...</p>
                        <p>μ<sub><?php echo $ir; ?>,1</sub> = μ + α<sub><?php echo $ir; ?></sub> + β<sub>1</sub>, ..., μ<sub><?php echo $ir; ?>,<?php echo $is; ?></sub> = μ + α<sub><?php echo $ir; ?></sub> + β<sub><?php echo $is; ?></sub></p>
                    </div>
                    <p class="text-slate-400 mt-2">Σα<sub>k</sub> = 0, Σβ<sub>k</sub> = 0</p>
                </div>

                <div class="mt-4 space-y-3">
                    <?php for ($i = 0; $i < $ir; $i++): ?>
                        <div class="space-y-2">
                            <?php for ($k = 0; $k < $is; $k++): ?>
                                <div>
                                    <label class="block text-slate-300 mb-1 text-sm">
                                        X<sub><?php echo ($i + 1) . "," . ($k + 1); ?>,1</sub>, ..., X<sub><?php echo ($i + 1) . "," . ($k + 1) . "," . $ip; ?></sub>:
                                    </label>
                                    <div class="flex flex-wrap gap-2">
                                        <?php for ($j = 0; $j < $ip; $j++): ?>
                                            <input type="number" step="any" name="x[]"
                                                   value="<?php echo htmlspecialchars($xx[$i][$k][$j]); ?>"
                                                   class="input-field" required>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="mt-4">
                    <p class="text-slate-300">Nulové hypotézy:</p>
                    <p class="text-slate-400 text-sm">H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $ir; ?></sub> = 0</p>
                    <p class="text-slate-400 text-sm">H<sub>0</sub>: β<sub>1</sub> = ... = β<sub><?php echo $is; ?></sub> = 0</p>
                </div>

                <button type="submit" class="btn-primary">Proveďte test</button>
                <input type="hidden" name="a" value="2">
            </form>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">📊 Průměry:</h3>
                <div class="text-slate-300 space-y-2">
                    <p>
                        <span style="text-decoration: overline">X</span> = <span class="data-value"><?php echo $zmmm; ?></span>
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <?php for ($i = 0; $i < $ir; $i++): ?>
                            <span>
                                <span style="text-decoration: overline">X</span><sup>A</sup><sub><?php echo ($i + 1); ?></sub> =
                                <span class="data-value"><?php echo $zmma[$i]; ?></span>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <?php for ($i = 0; $i < $is; $i++): ?>
                            <span>
                                <span style="text-decoration: overline">X</span><sup>B</sup><sub><?php echo ($i + 1); ?></sub> =
                                <span class="data-value"><?php echo $zmmb[$i]; ?></span>
                            </span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">📋 Průměry buněk:</h3>
                <div class="overflow-x-auto">
                    <table class="text-slate-300">
                        <?php for ($i = 0; $i < $ir; $i++): ?>
                            <tr>
                                <?php for ($j = 0; $j < $is; $j++): ?>
                                    <td class="pr-4 py-1">
                                        <span style="text-decoration: overline">X</span><sub><?php echo ($i + 1) . "," . ($j + 1); ?></sub> =
                                        <span class="data-value"><?php echo $zmx[$i][$j]; ?></span>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </div>
            </div>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">🔢 Součty čtverců a stupně volnosti:</h3>
                <div class="text-slate-300 space-y-2">
                    <p>S<sub>A</sub> = <span class="data-value"><?php echo $zsa; ?></span> &nbsp;&nbsp; f<sub>A</sub> = <span class="data-value"><?php echo $fa; ?></span></p>
                    <p>S<sub>B</sub> = <span class="data-value"><?php echo $zsb; ?></span> &nbsp;&nbsp; f<sub>B</sub> = <span class="data-value"><?php echo $fb; ?></span></p>
                    <p>S<sub>e</sub> = <span class="data-value"><?php echo $zse; ?></span> &nbsp;&nbsp; f<sub>e</sub> = <span class="data-value"><?php echo $fe; ?></span> &nbsp;&nbsp; s² = <span class="data-value"><?php echo $zss; ?></span></p>
                    <p>S<sub>T</sub> = <span class="data-value"><?php echo $zst; ?></span> &nbsp;&nbsp; f<sub>T</sub> = <span class="data-value"><?php echo $ft; ?></span></p>
                </div>
            </div>

<?php if ($zss == 0): ?>
            <div class="error-box">
                <p class="text-red-300">⚠️ Reziduální součet čtverců se rovná 0, tento test nelze užít.</p>
            </div>
<?php else:
        // Calculate F-statistics and critical values
        $ffa = $sa / $fa / $ss;
        $faz = zaokr($ffa, 3);
        $finva = invf($fa, $fe);

        // Scheffé test for factor A
        for ($i = 0; $i < $ir; $i++) {
            for ($k = $i + 1; $k < $ir; $k++) {
                $roza[$i][$k] = abs($mma[$i] - $mma[$k]);
                $zroza[$i][$k] = zaokr($roza[$i][$k], 2);
            }
        }
        $shefa = sqrt(2 * $fa / $is / $ip * $ss * $finva);
        $zshefa = zaokr($shefa, 2);

        $ffb = $sb / $fb / $ss;
        $fbz = zaokr($ffb, 3);
        $finvb = invf($fb, $fe);

        // Scheffé test for factor B
        for ($i = 0; $i < $is; $i++) {
            for ($k = $i + 1; $k < $is; $k++) {
                $rozb[$i][$k] = abs($mmb[$i] - $mmb[$k]);
                $zrozb[$i][$k] = zaokr($rozb[$i][$k], 2);
            }
        }
        $shefb = sqrt(2 * $fb / $ir / $ip * $ss * $finvb);
        $zshefb = zaokr($shefb, 2);
?>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">🔍 Testové statistiky:</h3>
                <div class="text-slate-300 space-y-2">
                    <p>F<sub>A</sub> = <span class="data-value"><?php echo $faz; ?></span> &nbsp;&nbsp;
                       F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95) = <span class="data-value"><?php echo $finva; ?></span></p>
                    <p>F<sub>B</sub> = <span class="data-value"><?php echo $fbz; ?></span> &nbsp;&nbsp;
                       F<sub><?php echo $fb . "," . $fe; ?></sub>(0.95) = <span class="data-value"><?php echo $finvb; ?></span></p>
                </div>
            </div>

            <!-- Test for Factor A -->
<?php if ($ffa >= $finva): ?>
            <div class="success-box">
                <p class="text-green-300">✓ F<sub>A</sub> ≥ F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95)</p>
                <p class="text-green-300 mt-2">→ Hypotézu H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $ir; ?></sub> = 0 <strong>zamítneme</strong></p>
            </div>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">📊 Post hoc test (Scheffého metoda) pro faktor A:</h3>
                <div class="text-slate-300 space-y-1 text-sm">
                    <?php for ($i = 0; $i < $ir; $i++):
                        for ($k = $i + 1; $k < $ir; $k++):
                            if ($roza[$i][$k] > $shefa): ?>
                                <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub><sup>A</sup> -
                                   <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub><sup>A</sup>| =
                                   <span class="data-value"><?php echo $zroza[$i][$k]; ?></span> > <?php echo $zshefa; ?>
                                   <span class="text-green-300">→ je rozdíl</span></p>
                            <?php else: ?>
                                <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub><sup>A</sup> -
                                   <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub><sup>A</sup>| =
                                   <span class="data-value"><?php echo $zroza[$i][$k]; ?></span> ≤ <?php echo $zshefa; ?>
                                   <span class="text-slate-400">→ není rozdíl</span></p>
                            <?php endif;
                        endfor;
                    endfor; ?>
                </div>
            </div>
<?php else: ?>
            <div class="result-box">
                <p class="text-slate-300">F<sub>A</sub> < F<sub><?php echo $fa . "," . $fe; ?></sub>(0.95)</p>
                <p class="text-slate-300 mt-2">→ Hypotézu H<sub>0</sub>: α<sub>1</sub> = ... = α<sub><?php echo $ir; ?></sub> = 0 <strong>nezamítneme</strong></p>
            </div>
<?php endif; ?>

            <!-- Test for Factor B -->
<?php if ($ffb >= $finvb): ?>
            <div class="success-box">
                <p class="text-green-300">✓ F<sub>B</sub> ≥ F<sub><?php echo $fb . "," . $fe; ?></sub>(0.95)</p>
                <p class="text-green-300 mt-2">→ Hypotézu H<sub>0</sub>: β<sub>1</sub> = ... = β<sub><?php echo $is; ?></sub> = 0 <strong>zamítneme</strong></p>
            </div>

            <div class="result-box">
                <h3 class="text-lg font-bold text-white mb-3">📊 Post hoc test (Scheffého metoda) pro faktor B:</h3>
                <div class="text-slate-300 space-y-1 text-sm">
                    <?php for ($i = 0; $i < $is; $i++):
                        for ($k = $i + 1; $k < $is; $k++):
                            if ($rozb[$i][$k] > $shefb): ?>
                                <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub><sup>B</sup> -
                                   <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub><sup>B</sup>| =
                                   <span class="data-value"><?php echo $zrozb[$i][$k]; ?></span> > <?php echo $zshefb; ?>
                                   <span class="text-green-300">→ je rozdíl</span></p>
                            <?php else: ?>
                                <p>|<span style="text-decoration: overline">X</span><sub><?php echo ($i + 1); ?></sub><sup>B</sup> -
                                   <span style="text-decoration: overline">X</span><sub><?php echo ($k + 1); ?></sub><sup>B</sup>| =
                                   <span class="data-value"><?php echo $zrozb[$i][$k]; ?></span> ≤ <?php echo $zshefb; ?>
                                   <span class="text-slate-400">→ není rozdíl</span></p>
                            <?php endif;
                        endfor;
                    endfor; ?>
                </div>
            </div>
<?php else: ?>
            <div class="result-box">
                <p class="text-slate-300">F<sub>B</sub> < F<sub><?php echo $fb . "," . $fe; ?></sub>(0.95)</p>
                <p class="text-slate-300 mt-2">→ Hypotézu H<sub>0</sub>: β<sub>1</sub> = ... = β<sub><?php echo $is; ?></sub> = 0 <strong>nezamítneme</strong></p>
            </div>
<?php endif; ?>

<?php endif; ?>

            <!-- Related Tests -->
            <div class="mt-6">
                <p class="text-slate-400 text-sm mb-3">🔗 Související testy se stejnými daty:</p>
                <div class="flex flex-wrap gap-3">
                    <a href="anovadvojinter.php?ir=<?php echo $ir; ?>&is=<?php echo $is; ?>&ip=<?php echo $ip; ?>&<?php
                        for ($i = 0; $i < $ir; $i++) {
                            for ($k = 0; $k < $is; $k++) {
                                for ($j = 0; $j < $ip; $j++) {
                                    echo 'x%5B%5D=' . $x[$is * $ip * $i + $ip * $k + $j] . '&';
                                }
                            }
                        }
                    ?>a=1" class="link-button">Dvojné třídění (ANOVA) s interakcemi</a>
                </div>
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

