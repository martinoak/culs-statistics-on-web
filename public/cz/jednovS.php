<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jednovýběrový test pro rozptyl</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;
$aa = $_GET['aa'] ?? null;

/**
 * Calculate mean (average) of array values
 */
function mean(array $xvar): float
{
    $sum = 0;
    $count = count($xvar);

    foreach ($xvar as $value) {
        $sum += $value;
    }

    return $sum / $count;
}

/**
 * Calculate sum of squared deviations from mean
 */
function sctv(array $xvar, float $mean): float
{
    $sumSquares = 0;

    foreach ($xvar as $value) {
        $sumSquares += pow($value - $mean, 2);
    }

    return $sumSquares;
}

/**
 * Calculate sample variance
 */
function smodch(array $xvar): float
{
    $sum = 0;
    $sumSquares = 0;
    $count = count($xvar);

    foreach ($xvar as $value) {
        $sum += $value;
        $sumSquares += pow($value, 2);
    }

    return ($sumSquares - ($sum * $sum / $count)) / ($count - 1);
}

/**
 * Get inverse chi-square distribution value (lower tail)
 */
function invchi1(int $sv): float
{
    $chi = fopen("../samples/chi/chi1.txt", "r");
    $position = ($sv - 1) * 7;
    fseek($chi, $position);
    $inv = fread($chi, 5);
    fclose($chi);

    return (float) $inv;
}

/**
 * Get inverse chi-square distribution value (upper tail)
 */
function invchi2(int $sv): float
{
    $chi = fopen("../samples/chi/chi4.txt", "r");
    $position = ($sv - 1) * 7;
    fseek($chi, $position);
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
            vzor = window.open("img/jednovS.png", "vzor", "width=700, height=400");
        }

        function zav() {
            if (!vzor.closed) {
                vzor.close();
            }
        }
    </script>
</head>

<body class="p-4 md:p-8" onunload="zav()">

    <div class="relative z-10 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <a href="celekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← Seznam testů
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Jednovýběrový test pro rozptyl
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

        <!-- Step 1: Sample Size Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        if ($n < 3 || $n > 30 || !(round($n) == $n)):
?>

        <!-- Step 1: Error - Invalid Sample Size -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celé číslo mezi 3 a 30, opravte</p>
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
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ, σ²) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?= $n ?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $n; $i++): ?>
                            <input type="number" step="any" name="x[]" class="input-field text-center"
                                   value="<?= $x[$i] ?? '' ?>" placeholder="X<?= $i + 1 ?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : σ² =
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" step="any" class="input-field w-32" name="aa" value="<?= $aa ?? '' ?>" placeholder="hodnota">
                        <span class="text-slate-400 text-sm">(zadejte kladné číslo)</span>
                    </div>
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
        if ($n < 3 || $n > 30 || !(round($n) == $n)):
?>

        <!-- Step 2: Error - Invalid Sample Size -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celé číslo mezi 3 a 30, opravte</p>
            </div>
        </div>
<?php break;

elseif($aa<=0): ?>

        <!-- Step 2: Error - Invalid Variance Value -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-6">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ, σ²) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $n; $i++): ?>
                            <input type="number" step="any" name="x[]" class="input-field text-center"
                                   value="<?php echo(isset($x[$i]) ? $x[$i] : '');?>" placeholder="X<?php echo($i+1);?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : σ² =
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" step="any" class="input-field w-32" name="aa" value="<?php echo($aa ?? '');?>" placeholder="hodnota">
                        <span class="text-slate-400 text-sm">(zadejte kladné číslo)</span>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Proveďte test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste kladné číslo, opravte</p>
            </div>
        </div>
<?php break;

else: ?>
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
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr z N(μ, σ²) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>:
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php for ($i = 0; $i < $n; $i++): ?>
                            <input type="number" step="any" name="x[]" class="input-field text-center"
                                   value="<?php echo(isset($x[$i]) ? $x[$i] : '');?>" placeholder="X<?php echo($i+1);?>">
                        <?php endfor; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : σ² =
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" step="any" class="input-field w-32" name="aa" value="<?php echo($aa ?? '');?>" placeholder="hodnota">
                        <span class="text-slate-400 text-sm">(zadejte kladné číslo)</span>
                    </div>
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
                $x = array_values($x); // Re-index array

                $sv = count($x) - 1;
                $m = mean($x); $zm = zaokr($m, 4);
                $so = smodch($x); $zso = zaokr($so, 4);
                $v = $so / $aa * ($sv);
                $zv = zaokr($v, 3);
                $inv1 = invchi1($sv);
                $inv2 = invchi2($sv);
                $konf1 = $so * $sv / $inv2;
                $konf2 = $so * $sv / $inv1;
                $zkonf1 = zaokr($konf1, 4);
                $zkonf2 = zaokr($konf2, 4);
                ?>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📊 Vypočtené statistiky:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">Průměr:</span>
                            <span class="font-mono text-lg ml-2"><span style="text-decoration: overline">X</span> = <?php echo($zm);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">Výběrový rozptyl:</span>
                            <span class="font-mono text-lg ml-2">S<sub>x</sub>² = <?php echo($zso);?></span>
                        </div>
                    </div>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">🔬 Testová statistika:</h3>
                    <div class="grid md:grid-cols-3 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">V =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($zv);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">χ²<sub><?php echo($sv)?></sub>(0.025) =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($inv1);?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">χ²<sub><?php echo($sv)?></sub>(0.975) =</span>
                            <span class="font-mono text-lg ml-2"><?php echo($inv2);?></span>
                        </div>
                    </div>
                </div>

                <div class="result-box <?php echo(($v<=$inv1||$v>=$inv2) ? 'border-red-400' : 'border-green-400');?>">
                    <h3 class="text-white font-semibold mb-3">✓ Závěr testu:</h3>
                    <div class="text-blue-100">
                        <?php if($v<=$inv1||$v>=$inv2): ?>
                            <p class="mb-2">
                                <span class="font-mono">V ≤ χ²<sub><?php echo($sv)?></sub>(0.025)</span>
                                <span class="mx-2">nebo</span>
                                <span class="font-mono">V ≥ χ²<sub><?php echo($sv)?></sub>(0.975)</span>
                            </p>
                            <p class="text-lg font-semibold text-red-300">
                                → Hypotézu H<sub>0</sub> : σ² = <?php echo($aa);?> <strong>zamítneme</strong>
                            </p>
                        <?php else: ?>
                            <p class="mb-2">
                                <span class="font-mono">χ²<sub><?php echo($sv)?></sub>(0.025) &lt; V &lt; χ²<sub><?php echo($sv)?></sub>(0.975)</span>
                            </p>
                            <p class="text-lg font-semibold text-green-300">
                                → Hypotézu H<sub>0</sub> : σ² = <?php echo($aa);?> <strong>nezamítneme</strong>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-2">📈 Konfidenční interval (95%):</h3>
                    <p class="text-blue-100 font-mono text-lg">
                        ( <?php echo($zkonf1);?> ; <?php echo($zkonf2);?> )
                    </p>
                </div>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Související testy se stejnými daty:</h3>
            <div class="flex flex-wrap gap-3">
                <?php
                echo'<a href="jednov.php?n=',$n,'&';
                for ($i =0; $i <$n; $i++):
                if(isset($x[$i])) echo'x%5B%5D=',$x[$i],'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">Jednovýběrový t-test oboustranný</a>';
                ?>

                <?php
                echo'<a href="jednovjedn.php?n=',$n,'&';
                for ($i =0; $i <$n; $i++):
                if(isset($x[$i])) echo'x%5B%5D=',$x[$i],'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">Jednovýběrový t-test jednostranný</a>';
                ?>

                <?php
                echo'<a href="wiljednov.php?n=',$n,'&';
                for ($i =0; $i <$n; $i++):
                if(isset($x[$i])) echo'x%5B%5D=',$x[$i],'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">Jednovýběrový Wilcoxonův test</a>';
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


