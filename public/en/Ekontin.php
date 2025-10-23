<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contingency Tables (Crosstab)</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
<?php

// Get input parameters
$a = $_GET['a'] ?? null;
$ir = $_GET['ir'] ?? null;
$is = $_GET['is'] ?? null;
$x = $_GET['x'] ?? null;

/**
 * Sum array values
 */
function sum(array $xvar): float
{
    $s = 0;
    $mez = count($xvar);
    for ($k = 0; $k < $mez; $k++) {
        $s = $s + $xvar[$k];
    }
    return $s;
}

/**
 * Get chi-square critical value from lookup table
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

        .contingency-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .contingency-table td {
            padding: 0.5rem;
            text-align: center;
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .contingency-table td:first-child {
            background: rgba(59, 130, 246, 0.1);
            font-weight: 600;
        }

        .contingency-table tr:first-child td {
            background: rgba(59, 130, 246, 0.1);
            font-weight: 600;
        }

        .contingency-table tr:last-child td {
            background: rgba(139, 92, 246, 0.1);
            font-weight: 600;
        }

        .contingency-table td:last-child {
            background: rgba(139, 92, 246, 0.1);
            font-weight: 600;
        }

        .contingency-table input {
            width: 80px;
            min-width: 80px;
        }
    </style>

    <script>
        function otev() {
            vzor = window.open("img/Ekonting.png", "vzor", "width=850, height=350");
        }

        function zav() {
            if (typeof vzor !== 'undefined' && !vzor.closed) {
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
                    <a href="EcelekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← List of tests
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Contingency Tables (Crosstab)
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

        <!-- Step 1: Dimensions Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Test level: <strong class="text-white">α = 0.05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-3">
                        Contingency table dimensions:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">r:</label>
                            <input type="number" class="input-field w-20" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-white">s:</label>
                            <input type="number" class="input-field w-20" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        </div>
                        <button type="submit" class="btn-primary">Yes</button>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        // Calculate row and column sums for display
        $np = [];
        $nq = [];
        $nn = 0;

        if (is_array($x)) {
            for ($i = 0; $i < $ir; $i++) {
                for ($k = 0; $k < $is; $k++) {
                    $cet[$i][$k] = $x[$is * $i + $k] ?? 0;
                }
            }

            for ($i = 0; $i < $is; $i++) {
                for ($k = 0; $k < $ir; $k++) {
                    $tec[$i][$k] = $cet[$k][$i];
                }
            }

            for ($i = 0; $i < $ir; $i++) {
                $np[$i] = sum($cet[$i]);
            }

            for ($i = 0; $i < $is; $i++) {
                $nq[$i] = sum($tec[$i]);
            }

            for ($i = 0; $i < $ir; $i++) {
                $nn = $nn + $np[$i];
            }
        }
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
                    <label class="block text-slate-300 mb-3">
                        Contingency table dimensions:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">r:</label>
                            <input type="number" class="input-field w-20" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-white">s:</label>
                            <input type="number" class="input-field w-20" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        </div>
                        <button type="submit" class="btn-primary">Yes</button>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Frequencies in classes: <span class="text-sm">(enter integers at least 5)</span>
                    </label>

                    <div class="overflow-x-auto">
                        <table class="contingency-table">
                            <tr>
                                <td></td>
                                <?php for ($i = 0; $i < $is; $i++): ?>
                                    <td><?= $i + 1 ?></td>
                                <?php endfor; ?>
                                <td>Σ</td>
                            </tr>
                            <?php for ($i = 0; $i < $ir; $i++): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <?php for ($j = 0; $j < $is; $j++): ?>
                                        <td>
                                            <input type="number" name="x[]" class="input-field text-center"
                                                   value="<?= $x[$is * $i + $j] ?? '' ?>" min="0">
                                        </td>
                                    <?php endfor; ?>
                                    <td><?= $np[$i] ?? '' ?></td>
                                </tr>
                            <?php endfor; ?>
                            <tr>
                                <td>Σ</td>
                                <?php for ($i = 0; $i < $is; $i++): ?>
                                    <td><?= $nq[$i] ?? '' ?></td>
                                <?php endfor; ?>
                                <td><?= $nn ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Null hypothesis H<sub>0</sub>:</strong> variables are independent
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

    case 2:
        // Calculate contingency table statistics
        for ($i = 0; $i < $ir; $i++) {
            for ($k = 0; $k < $is; $k++) {
                $cet[$i][$k] = $x[$is * $i + $k];
            }
        }

        for ($i = 0; $i < $is; $i++) {
            for ($k = 0; $k < $ir; $k++) {
                $tec[$i][$k] = $cet[$k][$i];
            }
        }

        for ($i = 0; $i < $ir; $i++) {
            $np[$i] = sum($cet[$i]);
        }

        for ($i = 0; $i < $is; $i++) {
            $nq[$i] = sum($tec[$i]);
        }

        $nn = 0;
        for ($i = 0; $i < $ir; $i++) {
            $nn = $nn + $np[$i];
        }

        // Calculate chi-square statistic
        $ch = 0;
        for ($i = 0; $i < $ir; $i++) {
            for ($j = 0; $j < $is; $j++) {
                $ch = $ch + pow($tec[$j][$i], 2) / $np[$i] / $nq[$j];
            }
        }
        $chi = $nn * $ch - $nn;
        $chiz = zaokr($chi, 2);
        $df = ($ir - 1) * ($is - 1);
        $chinv = invchi3($df);
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
                    <label class="block text-slate-300 mb-3">
                        Contingency table dimensions:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">r:</label>
                            <input type="number" class="input-field w-20" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-white">s:</label>
                            <input type="number" class="input-field w-20" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        </div>
                        <button type="submit" class="btn-primary">Yes</button>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Frequencies in classes: <span class="text-sm">(enter integers at least 5)</span>
                    </label>

                    <div class="overflow-x-auto">
                        <table class="contingency-table">
                            <tr>
                                <td></td>
                                <?php for ($i = 0; $i < $is; $i++): ?>
                                    <td><?= $i + 1 ?></td>
                                <?php endfor; ?>
                                <td>Σ</td>
                            </tr>
                            <?php for ($i = 0; $i < $ir; $i++): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <?php for ($j = 0; $j < $is; $j++): ?>
                                        <td>
                                            <input type="number" name="x[]" class="input-field text-center"
                                                   value="<?= $x[$is * $i + $j] ?? '' ?>" min="0">
                                        </td>
                                    <?php endfor; ?>
                                    <td><?= $np[$i] ?></td>
                                </tr>
                            <?php endfor; ?>
                            <tr>
                                <td>Σ</td>
                                <?php for ($i = 0; $i < $is; $i++): ?>
                                    <td><?= $nq[$i] ?></td>
                                <?php endfor; ?>
                                <td><?= $nn ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Null hypothesis H<sub>0</sub>:</strong> variables are independent
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
                    <h3 class="text-white font-semibold mb-3">🔬 Test statistic:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">χ² =</span>
                            <span class="font-mono text-lg ml-2"><?= $chiz ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">χ²<sub><?= $df ?></sub>(0.95) =</span>
                            <span class="font-mono text-lg ml-2"><?= $chinv ?></span>
                        </div>
                    </div>
                </div>

                <div class="result-box <?= $chi >= $chinv ? 'border-red-400' : 'border-green-400' ?>">
                    <h3 class="text-white font-semibold mb-3">✓ Test conclusion:</h3>
                    <div class="text-blue-100">
                        <?php if ($chi >= $chinv): ?>
                            <p class="mb-2">
                                <span class="font-mono">χ² ≥ χ²<sub><?= $df ?></sub>(0.95)</span>
                            </p>
                            <p class="text-lg font-semibold text-red-300">
                                → Hypothesis H<sub>0</sub> : variables are independent is <strong>rejected</strong>
                            </p>
                        <?php else: ?>
                            <p class="mb-2">
                                <span class="font-mono">χ² &lt; χ²<sub><?= $df ?></sub>(0.95)</span>
                            </p>
                            <p class="text-lg font-semibold text-green-300">
                                → Hypothesis H<sub>0</sub> : variables are independent is <strong>not rejected</strong>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
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
        break;
    default:
endswitch;
?>
    </div>
</body>
</html>

