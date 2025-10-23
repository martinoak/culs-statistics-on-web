<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pearson Correlation Coefficient Test</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;
$y = $_GET['y'] ?? null;

/**
 * Calculate mean (average) of array values
 */
function mean(array $xvar): float
{
    $s = 0;
    $mez = count($xvar);
    
    for ($k = 0; $k < $mez; $k++) {
        $s = $s + $xvar[$k];
    }
    
    $s = $s / $mez;
    return $s;
}

/**
 * Calculate sum of squared deviations from mean
 */
function sctv(array $xvar, float $me): float
{
    $sc = 0;
    $mez = count($xvar);
    
    for ($k = 0; $k < $mez; $k++) {
        $sc = $sc + pow(($xvar[$k] - $me), 2);
    }
    
    return $sc;
}

/**
 * Calculate sample variance
 */
function smodch(array $xvar): float
{
    $s = 0;
    $sc = 0;
    $mez = count($xvar);
    
    for ($k = 0; $k < $mez; $k++) {
        $s = $s + $xvar[$k];
        $sc = $sc + pow($xvar[$k], 2);
    }
    
    $sp = ($sc - $s * $s / $mez) / ($mez - 1);
    return $sp;
}

/**
 * Calculate covariance between two arrays
 */
function cov(array $xvar, array $yvar): float
{
    $s1 = 0;
    $s2 = 0;
    $sn = 0;
    $mez = count($xvar);
    
    for ($k = 0; $k < $mez; $k++) {
        $s1 = $s1 + $xvar[$k];
        $s2 = $s2 + $yvar[$k];
        $sn = $sn + $xvar[$k] * $yvar[$k];
    }
    
    $co = ($sn - $s1 * $s2 / $mez) / ($mez - 1);
    return $co;
}

/**
 * Get inverse t-distribution value from lookup table
 */
function invt1(int $sv): float
{
    $stud = fopen("../samples/stud/stud2.txt", "r");
    $stav = ($sv - 1) * 7;
    fseek($stud, $stav);
    $inv = fread($stud, 5);
    fclose($stud);
    
    return (float) $inv;
}

/**
 * Round number to specified decimal places
 */
function zaokr(float $cislo, int $des): float
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
            vzor = window.open("img/Ekorel.png", "vzor", "width=800, height=400");
        }

        function zav() {
            if (typeof vzor !== 'undefined' && !vzor.closed) {
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
                    <a href="EcelekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← List of tests
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Pearson Correlation Coefficient Test
                    </h1>
                </div>
                <div class="flex gap-2">
                    <button onmouseover="otev()" class="btn-secondary text-sm">
                        📐 Formulas
                    </button>
                    <button onmouseover="zav()" class="btn-secondary text-sm">
                        ✕ Close
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
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 3 to 30)</span>
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
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 3 to 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ You did not enter an integer between 3 and 30, correct</p>
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
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 3 to 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Random sample from normal distribution &nbsp;&nbsp; (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
                    </label>

                    <div class="mb-4">
                        <label class="text-white mb-2 block">
                            X<sub>1</sub>,...,X<sub><?= $n ?></sub>:
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            <?php for ($i = 0; $i < $n; $i++): ?>
                                <input type="number" step="any" name="x[]" class="input-field text-center"
                                       value="<?= $x[$i] ?? '' ?>" placeholder="X<?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div>
                        <label class="text-white mb-2 block">
                            Y<sub>1</sub>,...,Y<sub><?= $n ?></sub>:
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            <?php for ($i = 0; $i < $n; $i++): ?>
                                <input type="number" step="any" name="y[]" class="input-field text-center"
                                       value="<?= $y[$i] ?? '' ?>" placeholder="Y<?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Null hypothesis H<sub>0</sub>:</strong> ρ = 0
                    </p>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Perform the test</button>
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
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 3 to 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ You did not enter an integer between 3 and 30, correct</p>
            </div>
        </div>

<?php
        break;
        else:
            // Calculate statistics
            $sv = $n - 2;
            $m1 = mean($x);
            $m2 = mean($y);
            $zm1 = zaokr($m1, 4);
            $zm2 = zaokr($m2, 4);
            $so1 = smodch($x);
            $so2 = smodch($y);
            $zso1 = zaokr($so1, 4);
            $zso2 = zaokr($so2, 4);
            $sxy = cov($x, $y);
            $zsxy = zaokr($sxy, 4);
            $inv = invt1($sv);
?>

        <!-- Step 3: Results Display -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-6">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 3 to 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Random sample from normal distribution &nbsp;&nbsp; (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
                    </label>

                    <div class="mb-4">
                        <label class="text-white mb-2 block">
                            X<sub>1</sub>,...,X<sub><?= $n ?></sub>:
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            <?php for ($i = 0; $i < $n; $i++): ?>
                                <input type="number" step="any" name="x[]" class="input-field text-center"
                                       value="<?= $x[$i] ?? '' ?>" placeholder="X<?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div>
                        <label class="text-white mb-2 block">
                            Y<sub>1</sub>,...,Y<sub><?= $n ?></sub>:
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            <?php for ($i = 0; $i < $n; $i++): ?>
                                <input type="number" step="any" name="y[]" class="input-field text-center"
                                       value="<?= $y[$i] ?? '' ?>" placeholder="Y<?= $i + 1 ?>">
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Null hypothesis H<sub>0</sub>:</strong> ρ = 0
                    </p>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Perform the test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>

            <!-- Results Section -->
            <div class="mt-8 space-y-4">
                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📊 Calculated statistics:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">Mean X:</span>
                            <span class="font-mono text-lg ml-2"><span style="text-decoration: overline">X</span> = <?= $zm1 ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">Mean Y:</span>
                            <span class="font-mono text-lg ml-2"><span style="text-decoration: overline">Y</span> = <?= $zm2 ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">Variance X:</span>
                            <span class="font-mono text-lg ml-2">S<sub>X</sub>² = <?= $zso1 ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">Variance Y:</span>
                            <span class="font-mono text-lg ml-2">S<sub>Y</sub>² = <?= $zso2 ?></span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-slate-300">Covariance:</span>
                            <span class="font-mono text-lg ml-2">S<sub>XY</sub> = <?= $zsxy ?></span>
                        </div>
                    </div>
                </div>

                <?php if ($so1 * $so2 == 0): ?>
                    <div class="error-box">
                        <p class="text-red-300 font-medium">⚠️ At least one variance is equal to 0, this test cannot be used</p>
                    </div>
                <?php else:
                    $r = $sxy / sqrt($so1) / sqrt($so2);
                    if ($r >= 0.9999 || $r <= -0.9999):
                ?>
                    <div class="error-box">
                        <p class="text-red-300 font-medium">⚠️ Correlation is equal to 1 or -1, this test cannot be used</p>
                    </div>
                <?php else:
                    $t = $r / sqrt(1 - $r * $r) * sqrt($sv);
                    $zr = zaokr($r, 4);
                    $zt = zaokr($t, 3);
                ?>
                    <div class="result-box">
                        <h3 class="text-white font-semibold mb-3">🔬 Test statistic:</h3>
                        <div class="grid md:grid-cols-3 gap-4 text-blue-100">
                            <div>
                                <span class="text-slate-300">r =</span>
                                <span class="font-mono text-lg ml-2"><?= $zr ?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">T =</span>
                                <span class="font-mono text-lg ml-2"><?= $zt ?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">t<sub><?= $sv ?></sub>(0.975) =</span>
                                <span class="font-mono text-lg ml-2"><?= $inv ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="result-box <?= max($t, -$t) >= $inv ? 'border-red-400' : 'border-green-400' ?>">
                        <h3 class="text-white font-semibold mb-3">✓ Test conclusion:</h3>
                        <div class="text-blue-100">
                            <?php if (max($t, -$t) >= $inv): ?>
                                <p class="mb-2">
                                    <span class="font-mono">|T| ≥ t<sub><?= $sv ?></sub>(0.975)</span>
                                </p>
                                <p class="text-lg font-semibold text-red-300">
                                    → Hypothesis H<sub>0</sub> : ρ = 0 is <strong>rejected</strong>
                                </p>
                            <?php else: ?>
                                <p class="mb-2">
                                    <span class="font-mono">|T| &lt; t<sub><?= $sv ?></sub>(0.975)</span>
                                </p>
                                <p class="text-lg font-semibold text-green-300">
                                    → Hypothesis H<sub>0</sub> : ρ = 0 is <strong>not rejected</strong>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                    endif;
                endif;
                ?>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Related tests with the same data:</h3>
            <div class="flex flex-wrap gap-3">
                <?php
                echo '<a href="Espearman.php?n=', $n, '&';
                for ($i = 0; $i < $n; $i++) {
                    echo 'x%5B%5D=', $x[$i], '&';
                }
                for ($j = 0; $j < $n; $j++) {
                    echo 'y%5B%5D=', $y[$j], '&';
                }
                echo 'a=1" class="link-button">Spearman correlation coefficient</a>';
                ?>

                <?php
                echo '<a href="Elinreg.php?n=', $n, '&';
                for ($i = 0; $i < $n; $i++) {
                    echo 'x%5B%5D=', $x[$i], '&';
                }
                for ($j = 0; $j < $n; $j++) {
                    echo 'y%5B%5D=', $y[$j], '&';
                }
                echo 'a=1" class="link-button">Linear regression</a>';
                ?>
            </div>
        </div>

        <!-- New Entry Button -->
        <div class="glass-card rounded-2xl p-6 text-center">
            <form>
                <button type="submit" class="btn-primary">🔄 New entry</button>
                <input type="hidden" name="a" value="0">
            </form>
        </div>

<?php
        endif;
        break;
    default:
endswitch;
?>
    </div>
</body>
</html>

