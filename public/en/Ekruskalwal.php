<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kruskal - Wallis Test</title>
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
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= filemtime('../assets/css/style.css') ?>">

    <script>
        function otev() {
            vzor = window.open("Ekruskalw.png", "vzor", "width=750, height=450");
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
                    <a href="EcelekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← List of Tests
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Kruskal - Wallis Test
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
switch($a):

case 0: ?>

        <!-- Step 1: Number of Categories Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in ?? ''); ?>" 
                           class="input-field" min="3" max="10" required>
                    <p class="text-slate-400 text-sm mt-1">Enter an integer from 3 to 10</p>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
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
                <p class="text-red-300">⚠️ You did not enter an integer between 3 and 10, please correct.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                    <p class="text-slate-400 text-sm mt-1">Enter an integer from 3 to 10</p>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php else: ?>

        <!-- Step 2: Sample Sizes Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range of categories n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i] ?? ''); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                    <p class="text-slate-400 text-sm mt-1">Enter integers from 2 to 10</p>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
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
                <p class="text-red-300">⚠️ You did not enter an integer between 3 and 10, please correct.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php elseif ($va < 2 || $vb > 10 || !$vc == 0): ?>

        <!-- Error: Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ You did not enter integers between 2 and 10, please correct.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range of categories n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i]); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
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
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range of categories n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
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
                        Random samples from r = <?php echo $in; ?> continuous distributions
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
                        Null hypothesis H<sub>0</sub>: all distributions are the same
                    </p>
                </div>
                <button type="submit" class="btn-primary">Run the test</button>
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
                <p class="text-red-300">⚠️ You did not enter an integer between 3 and 10, please correct.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php elseif ($va < 2 || $vb > 10 || !$vc == 0): ?>

        <!-- Error: Invalid Sample Sizes -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <div class="error-box mb-4">
                <p class="text-red-300">⚠️ You did not enter integers between 2 and 10, please correct.</p>
            </div>
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range of categories:
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php for ($i = 0; $i < $in; $i++): ?>
                            <input type="number" name="n[]" value="<?php echo htmlspecialchars($n[$i]); ?>"
                                   class="input-field" min="2" max="10" required>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Continue</button>
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

        <!-- Step 3: Data Input Form (Always Visible) -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Number of categories <em>r</em>:
                    </label>
                    <input type="number" name="in" value="<?php echo htmlspecialchars($in); ?>"
                           class="input-field" min="3" max="10" required>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range of categories n<sub>1</sub>, ..., n<sub><?php echo $in; ?></sub>:
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
                        Random samples from r = <?php echo $in; ?> continuous distributions
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
                        Null hypothesis H<sub>0</sub>: all distributions are the same
                    </p>
                </div>
                <button type="submit" class="btn-primary">Run the test</button>
                <input type="hidden" name="a" value="3">
            </form>
        </div>

        <!-- Results Display -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <div class="result-box mb-6">
                <h3 class="text-lg font-bold text-white mb-3">📊 Sample X with ranks:</h3>
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
                                <td class="text-slate-400">Ranks:</td>
                                <?php for ($k = 0; $k < $n[$i]; $k++): ?>
                                    <td class="rank"><?php echo $p[$s[$i - 1] + $k]; ?></td>
                                <?php endfor; ?>
                                <td class="text-slate-300">T<sub><?php echo ($i + 1); ?></sub> = <span class="data-value"><?php echo $tt[$i]; ?></span></td>
                            </tr>
                        </table>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="result-box mb-6">
                <h3 class="text-lg font-bold text-white mb-3">🔍 Test Statistic:</h3>
                <div class="text-slate-300 space-y-2">
                    <p>Q = <span class="data-value"><?php echo $zq; ?></span></p>
                    <p>χ²<sub><?php echo ($in - 1); ?></sub>(0.95) = <span class="data-value"><?php echo $chinv; ?></span></p>
                </div>
            </div>

<?php if ($q >= $chinv): ?>
            <div class="success-box mb-6">
                <p class="text-green-300">✓ Q ≥ χ²<sub><?php echo ($in - 1); ?></sub>(0.95)</p>
                <p class="text-green-300 mt-2">→ Hypothesis H<sub>0</sub>: same distribution is <strong>rejected</strong></p>
            </div>
<?php else: ?>
            <div class="result-box mb-6">
                <p class="text-slate-300">Q < χ²<sub><?php echo ($in - 1); ?></sub>(0.95)</p>
                <p class="text-slate-300 mt-2">→ Hypothesis H<sub>0</sub>: same distribution is <strong>not rejected</strong></p>
            </div>
<?php endif; ?>

            <!-- Related Tests -->
            <div>
                <p class="text-slate-400 text-sm mb-3">🔗 Related tests with the same data:</p>
                <div class="flex flex-wrap gap-3">
                    <a href="Eanova.php?in=<?php echo $in; ?>&<?php
                        for ($i = 0; $i < $in; $i++) {
                            echo 'n%5B%5D=' . $n[$i] . '&';
                            $sss[$i + 1] = $sss[$i] + $n[$i];
                        }
                        for ($j = 0; $j < $in; $j++) {
                            for ($k = 0; $k < $n[$j]; $k++) {
                                echo 'x%5B%5D=' . $x[$sss[$j] + $k] . '&';
                            }
                        }
                    ?>a=2" class="link-button">One-way ANOVA</a>
                </div>
            </div>
        </div>

        <!-- New Entry Button -->
        <div class="glass-card rounded-2xl p-6 text-center">
            <form method="get">
                <button type="submit" class="btn-primary">🔄 New entry</button>
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

