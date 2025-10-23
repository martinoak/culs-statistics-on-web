<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dvouvýběrový F-test (Fisherův)</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$m = $_GET['m'] ?? null;
$x = $_GET['x'] ?? null;
$n = $_GET['n'] ?? null;
$y = $_GET['y'] ?? null;

/**
 * Calculate mean of array
 */
function mean($xvar)
{
    $s = 0;
    $mez = count($xvar);
    for ($k = 0; $k < $mez; $k++):
        $s = $s + $xvar[$k];
    endfor;
    $s = $s / $mez;
    return $s;
}

/**
 * Calculate sum of squared deviations
 */
function sctv($xvar, $me)
{
    $sc = 0;
    $mez = count($xvar);
    for ($k = 0; $k < $mez; $k++):
        $sc = $sc + pow(($xvar[$k] - $me), 2);
    endfor;
    return $sc;
}

/**
 * Calculate sample variance
 */
function smodch($xvar)
{
    $s = 0;
    $sc = 0;
    $mez = count($xvar);
    for ($k = 0; $k < $mez; $k++):
        $s = $s + $xvar[$k];
        $sc = $sc + pow($xvar[$k], 2);
    endfor;
    $sp = ($sc - $s * $s / $mez) / ($mez - 1);
    return $sp;
}

/**
 * Get critical value from F-distribution (lower tail)
 */
function invf1($sv, $sw)
{
    $fis = FOpen("../samples/fis/fis1.txt", "r");
    $stav = ($sw - 1) * 211 + ($sv - 1) * 7;
    FSeek($fis, $stav);
    $inv = FRead($fis, 6);
    FClose($fis);
    return $inv;
}

/**
 * Get critical value from F-distribution (upper tail)
 */
function invf2($sv, $sw)
{
    $fis = FOpen("../samples/fis/fis4.txt", "r");
    $stav = ($sw - 1) * 151 + ($sv - 1) * 5;
    FSeek($fis, $stav);
    $inv = FRead($fis, 4);
    FClose($fis);
    return $inv;
}

/**
 * Round number to specified decimal places
 */
function zaokr($cislo, $des)
{
    $moc = pow(10, $des);
    $vysl = round($cislo * $moc) / $moc;
    return $vysl;
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
            padding: 1rem;
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
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
    </style>

    <script>
        function otev() {
            vzor = window.open("dvouvF.png", "vzor", "width=750, height=300");
        }

        function zav() {
            if (!vzor.closed) {
                vzor.close();
            }
        }
    </script>
</head>

<body class="p-4 md:p-8" onunload="zav()">

    <div class="relative z-10 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <a href="celekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← Seznam testů
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Dvouvýběrový F-test (Fisherův)
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
switch ($a):
    case 0:
?>

        <!-- Step 1: Sample Sizes Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsahy:
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <strong class="text-white">m:</strong>
                            <input type="number" class="input-field w-24" name="m" value="<?= $m ?? '' ?>" min="2" max="30">
                        </div>
                        <div class="flex items-center gap-2">
                            <strong class="text-white">n:</strong>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="2" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        if ($m < 2 || $n < 2 || $m > 30 || $n > 30 || !(round($m) == $m) || !(round($n) == $n)):
?>

        <!-- Step 1: Error - Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsahy:
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <strong class="text-white">m:</strong>
                            <input type="number" class="input-field w-24" name="m" value="<?= $m ?? '' ?>" min="2" max="30">
                        </div>
                        <div class="flex items-center gap-2">
                            <strong class="text-white">n:</strong>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="2" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celá čísla mezi 2 a 30, opravte</p>
            </div>
        </div>
<?php
        break;
        else:
?>

        <!-- Step 2: Data Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-6">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsahy:
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <strong class="text-white">m:</strong>
                            <input type="number" class="input-field w-24" name="m" value="<?= $m ?? '' ?>" min="2" max="30">
                        </div>
                        <div class="flex items-center gap-2">
                            <strong class="text-white">n:</strong>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="2" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ<sub>1</sub>, σ<sub>1</sub>²) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?= $m ?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $m; $i++): ?>
                            <input type="number" step="any" name="x[]" class="input-field text-center"
                                   value="<?= $x[$i] ?? '' ?>" placeholder="X<?= $i + 1 ?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ<sub>2</sub>, σ<sub>2</sub>²) &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?= $n ?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $n; $i++): ?>
                            <input type="number" step="any" name="y[]" class="input-field text-center"
                                   value="<?= $y[$i] ?? '' ?>" placeholder="Y<?= $i + 1 ?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : σ<sub>1</sub> = σ<sub>2</sub>
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Proveďte test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>
        </div>
<?php
        break;
        endif;

    case 2:
        if ($m < 2 || $n < 2 || $m > 30 || $n > 30 || !(round($m) == $m) || !(round($n) == $n)):
?>

        <!-- Step 2: Error - Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsahy:
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <strong class="text-white">m:</strong>
                            <input type="number" class="input-field w-24" name="m" value="<?php echo($m);?>" min="2" max="30">
                        </div>
                        <div class="flex items-center gap-2">
                            <strong class="text-white">n:</strong>
                            <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="2" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celá čísla mezi 2 a 30, opravte</p>
            </div>
        </div>
<?php
        break;
        else:
?>

        <!-- Step 3: Results Display -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-6">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsahy:
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <strong class="text-white">m:</strong>
                            <input type="number" class="input-field w-24" name="m" value="<?php echo($m);?>" min="2" max="30">
                        </div>
                        <div class="flex items-center gap-2">
                            <strong class="text-white">n:</strong>
                            <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="2" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ<sub>1</sub>, σ<sub>1</sub>²) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $m; $i++): ?>
                            <input type="number" step="any" name="x[]" class="input-field text-center"
                                   value="<?php echo(isset($x[$i]) ? $x[$i] : '');?>" placeholder="X<?php echo($i+1);?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ<sub>2</sub>, σ<sub>2</sub>²) &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $n; $i++): ?>
                            <input type="number" step="any" name="y[]" class="input-field text-center"
                                   value="<?php echo(isset($y[$i]) ? $y[$i] : '');?>" placeholder="Y<?php echo($i+1);?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : σ<sub>1</sub> = σ<sub>2</sub>
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Proveďte test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>

            <!-- Results Section -->
            <div class="mt-6 space-y-4">
                <?php
                // Filter out empty values and convert to numeric
                $x = array_filter($x, function($val) { return $val !== '' && $val !== null; });
                $x = array_map('floatval', $x);
                $x = array_values($x);

                $y = array_filter($y, function($val) { return $val !== '' && $val !== null; });
                $y = array_map('floatval', $y);
                $y = array_values($y);

                $m = count($x);
                $n = count($y);
                $sv = $m - 1;
                $sw = $n - 1;

                $m1 = mean($x);
                $m2 = mean($y);
                $zm1 = zaokr($m1, 4);
                $zm2 = zaokr($m2, 4);

                $so1 = smodch($x);
                $so2 = smodch($y);
                $zso1 = zaokr($so1, 4);
                $zso2 = zaokr($so2, 4);

                $finv2 = invf1($sv, $sw);
                $finv3 = invf2($sv, $sw);
                ?>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📈 Výběrové charakteristiky:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300"><span style="text-decoration: overline">X</span> =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($zm1);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300"><span style="text-decoration: overline">Y</span> =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($zm2);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">S<sub>X</sub>² =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($zso1);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">S<sub>Y</sub>² =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($zso2);?></span>
                        </div>
                    </div>
                </div>

                <?php if ($so1 * $so2 == 0): ?>
                    <div class="error-box">
                        <p class="text-red-300 font-medium">⚠️ Rozptyly se rovnají 0, tento test nelze užít</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6 text-center mt-6">
                        <form>
                            <button type="submit" class="btn-primary">🔄 Nové zadání</button>
                            <input type="hidden" name="a" value="0">
                        </form>
                    </div>
                <?php else:
                    $f = $so1 / $so2;
                    $fz = zaokr($f, 3);
                ?>

                    <div class="result-box">
                        <h3 class="text-white font-semibold mb-3">🔬 Testová statistika:</h3>
                        <div class="grid md:grid-cols-3 gap-4 text-blue-100">
                            <div>
                                <span class="text-slate-300">F =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($fz);?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">F<sub><?php echo($sv.",".$sw)?></sub>(0.025) =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($finv2);?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">F<sub><?php echo($sv.",".$sw)?></sub>(0.975) =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($finv3);?></span>
                            </div>
                        </div>
                    </div>

                    <div class="result-box <?php echo(($f <= $finv2 || $f >= $finv3) ? 'border-red-400' : 'border-green-400');?>">
                        <h3 class="text-white font-semibold mb-3">✓ Závěr testu:</h3>
                        <div class="text-blue-100">
                            <?php if ($f <= $finv2 || $f >= $finv3): ?>
                                <p class="mb-2">
                                    <span class="font-mono">F ≤ F<sub><?php echo($sv.",".$sw)?></sub>(0.025) &nbsp;&nbsp; nebo &nbsp;&nbsp; F ≥ F<sub><?php echo($sv.",".$sw)?></sub>(0.975)</span>
                                </p>
                                <p class="text-lg font-semibold text-red-300">
                                    → Hypotézu H<sub>0</sub> : σ<sub>1</sub> = σ<sub>2</sub> <strong>zamítneme</strong>
                                </p>
                            <?php else: ?>
                                <p class="mb-2">
                                    <span class="font-mono">F<sub><?php echo($sv.",".$sw)?></sub>(0.025) &lt; F &lt; F<sub><?php echo($sv.",".$sw)?></sub>(0.975)</span>
                                </p>
                                <p class="text-lg font-semibold text-green-300">
                                    → Hypotézu H<sub>0</sub> : σ<sub>1</sub> = σ<sub>2</sub> <strong>nezamítneme</strong>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Související testy se stejnými daty:</h3>
            <div class="flex flex-wrap gap-3">
                <?php
                echo'<a href="dvouv.php?m=',$m,'& n=',$n,'&';
                for ($i = 0; $i < $m; $i++):
                    echo'x%5B%5D=',$x[$i],'&';
                endfor;
                for ($j = 0; $j < $n; $j++):
                    echo'y%5B%5D=',$y[$j],'&';
                endfor;
                echo 'a=1" class="link-button">Dvouvýběrový t-test (Studentův)</a>';
                ?>
                <?php
                echo'<a href="wildvouv.php?m=',$m,'& n=',$n,'&';
                for ($i = 0; $i < $m; $i++):
                    echo'x%5B%5D=',$x[$i],'&';
                endfor;
                for ($j = 0; $j < $n; $j++):
                    echo'y%5B%5D=',$y[$j],'&';
                endfor;
                echo 'a=1" class="link-button">Wilcoxonův dvouvýběrový test (Mannův - Whitneyův test)</a>';
                ?>
            </div>
        </div>

        <!-- New Entry Button -->
        <div class="glass-card rounded-2xl p-6 text-center">
            <form>
                <button type="submit" class="btn-primary">🔄 Nové zadání</button>
                <input type="hidden" name="a" value="0">
            </form>
        </div>

<?php
endif;
default:
endswitch;?>
    </div>
</body>
</html>


