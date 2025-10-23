<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilcoxon signed-rank test</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$x = $_GET['x'] ?? null;
$n = $_GET['n'] ?? null;
$aa = $_GET['aa'] ?? null;

/**
 * Rank absolute differences (handling zeros and ties)
 */
function razeni1(array $x): array
{
    $n = count($x);
    $p = [];

    for ($i = 0; $i < $n; $i++) {
        if ($x[$i] == 0) {
            $p[$i] = 0;
        } else {
            $p[$i] = 0.5;
            for ($k = 0; $k < $n; $k++) {
                if ($x[$k] == 0) {
                    // Skip zeros
                } elseif ($x[$k] < $x[$i]) {
                    $p[$i] = $p[$i] + 1;
                } elseif ($x[$k] == $x[$i]) {
                    $p[$i] = $p[$i] + 0.5;
                }
            }
        }
    }

    return $p;
}

/**
 * Get critical value for Wilcoxon signed-rank test
 */
function wiljed(int $sv): int
{
    $wil = fopen("../samples/wiljed1.txt", "r");
    $position = ($sv - 6) * 5;
    fseek($wil, $position);
    $inv = fread($wil, 3);
    fclose($wil);

    return (int) $inv;
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

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 0.5rem;
            text-align: center;
            color: white;
        }

        .data-table tr:first-child td:first-child {
            text-align: left;
            color: #94a3b8;
        }

        .data-table tr:last-child td:first-child {
            text-align: left;
            color: #10b981;
        }

        .data-table tr:last-child td {
            color: #10b981;
        }
    </style>

    <script>
        function otev() {
            vzor = window.open("img/Ewiljedn.png", "vzor", "width=650, height=250");
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
                    <a href="EcelekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← List of tests
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Wilcoxon signed-rank test
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
                        Level of test: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="6" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 6 to 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        if ($n < 6 || $n > 30 || !(round($n) == $n)):
?>

        <!-- Step 1: Error - Invalid Sample Size -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Level of test: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="6" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 6 to 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ You did not enter an integer between 6 and 30, correct</p>
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
                        Level of test: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="6" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 6 to 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Random sample from continuous distribution &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?= $n ?></sub>:
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
                        Null hypothesis &nbsp;&nbsp; H<sub>0</sub> : the distribution is symmetric around
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" step="any" class="input-field w-32" name="aa" value="<?= $aa ?? '' ?>" placeholder="value">
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Run the test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>
        </div>
<?php
        break;
        endif;

    case 2:
        if ($n < 6 || $n > 30 || !(round($n) == $n)):
?>

        <!-- Step 2: Error - Invalid Sample Size -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Level of test: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="6" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 6 to 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ You did not enter an integer between 6 and 30, correct</p>
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
                        Level of test: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Range <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?php echo($n);?>" min="6" max="30">
                        <button type="submit" class="btn-primary">Yes</button>
                        <span class="text-slate-400 text-sm">(enter an integer from 6 to 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Random sample from continuous distribution &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>:
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
                        Null hypothesis &nbsp;&nbsp; H<sub>0</sub> : the distribution is symmetric around
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" step="any" class="input-field w-32" name="aa" value="<?php echo($aa ?? '');?>" placeholder="value">
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Run the test</button>
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

                $n = count($x);

                // Calculate differences from hypothesized median
                for ($i = 0; $i < $n; $i++) {
                    $x[$i] = $x[$i] - $aa;
                }

                $no = $n; // Count of non-zero differences
                $kl = []; // Positive differences indicator
                $zp = []; // Negative differences indicator

                for ($i = 0; $i < $n; $i++) {
                    if ($x[$i] == 0) {
                        $kl[$i] = 0;
                        $zp[$i] = 0;
                        $no = $no - 1;
                    } elseif ($x[$i] > 0) {
                        $kl[$i] = 1;
                        $zp[$i] = 0;
                    } else {
                        $kl[$i] = 0;
                        $zp[$i] = 1;
                    }
                }

                // Calculate absolute values
                $absx = [];
                for ($i = 0; $i < $n; $i++) {
                    $absx[$i] = max($x[$i], -$x[$i]);
                }

                // Get ranks
                $pe = razeni1($absx);

                // Get critical value
                $inv = wiljed($no);
                ?>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📊 Differences and ranks:</h3>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <tr>
                                <td>Differences:</td>
                                <?php for($i = 0; $i < $n; $i++): ?>
                                    <td><?php echo(zaokr($x[$i], 3));?></td>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <td>Rank:</td>
                                <?php for($i = 0; $i < $n; $i++): ?>
                                    <td>
                                        <?php
                                        if($pe[$i] == 0) {
                                            echo("-");
                                        } else {
                                            echo($pe[$i]);
                                        }
                                        ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php
                // Calculate rank sums
                $skl = 0; // Sum of positive ranks
                $szp = 0; // Sum of negative ranks
                for ($i = 0; $i < $n; $i++) {
                    $skl = $skl + $kl[$i] * $pe[$i];
                    $szp = $szp + $zp[$i] * $pe[$i];
                }
                $min = min($skl, $szp);

                if($no < 6): ?>
                    <div class="error-box">
                        <p class="text-red-300 font-medium">⚠️ A number of non-zero differences is too small, this test cannot be used</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6 text-center mt-6">
                        <form>
                            <button type="submit" class="btn-primary">🔄 New entry</button>
                            <input type="hidden" name="a" value="0">
                        </form>
                    </div>
                <?php else: ?>

                    <div class="result-box">
                        <h3 class="text-white font-semibold mb-3">🔬 Test statistic:</h3>
                        <div class="grid md:grid-cols-4 gap-4 text-blue-100">
                            <div>
                                <span class="text-slate-300">S<sup>+</sup> =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($skl);?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">S<sup>-</sup> =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($szp);?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">min(S<sup>+</sup>,S<sup>-</sup>) =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($min);?></span>
                            </div>
                            <div>
                                <span class="text-slate-300">k<sub><?php echo($no)?></sub> =</span>
                                <span class="font-mono text-lg ml-2"><?php echo($inv);?></span>
                            </div>
                        </div>
                    </div>

                    <div class="result-box <?php echo(($min < $inv) ? 'border-red-400' : 'border-green-400');?>">
                        <h3 class="text-white font-semibold mb-3">✓ Test conclusion:</h3>
                        <div class="text-blue-100">
                            <?php if($min < $inv): ?>
                                <p class="mb-2">
                                    <span class="font-mono">min(S<sup>+</sup>,S<sup>-</sup>) ≤ k<sub><?php echo($no)?></sub></span>
                                </p>
                                <p class="text-lg font-semibold text-red-300">
                                    → Hypothesis H<sub>0</sub> : the distribution is symmetric around <?php echo($aa);?> <strong>is rejected</strong>
                                </p>
                            <?php else: ?>
                                <p class="mb-2">
                                    <span class="font-mono">min(S<sup>+</sup>,S<sup>-</sup>) &gt; k<sub><?php echo($no)?></sub></span>
                                </p>
                                <p class="text-lg font-semibold text-green-300">
                                    → Hypothesis H<sub>0</sub> : the distribution is symmetric around <?php echo($aa);?> <strong>is not rejected</strong>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Related tests with the same data:</h3>
            <div class="flex flex-wrap gap-3">
                <?php
                echo'<a href="Ejednov.php?n=',$n,'&';
                for ($i = 0; $i < $n; $i++):
                    echo'x%5B%5D=',($x[$i] + $aa),'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">One-sample t-test two-tailed</a>';
                ?>

                <?php
                echo'<a href="Ejednovjedn.php?n=',$n,'&';
                for ($i = 0; $i < $n; $i++):
                    echo'x%5B%5D=',($x[$i] + $aa),'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">One-sample t-test one-tailed</a>';
                ?>

                <?php
                echo'<a href="EjednovS.php?n=',$n,'&';
                for ($i = 0; $i < $n; $i++):
                    echo'x%5B%5D=',($x[$i] + $aa),'&';
                endfor;
                echo'aa=',$aa,'& a=1" class="link-button">One-sample test for variance</a>';
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

                <?php endif; ?>
<?php
endif;
default:
endswitch;?>
    </div>
</body>
</html>


