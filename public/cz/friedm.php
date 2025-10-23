<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friedmanův test</title>
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
    $mez = count($xvar);
    
    for ($k = 0; $k < $mez; $k++) {
        $s = $s + $xvar[$k];
    }
    
    return $s;
}

/**
 * Calculate ranks for array values
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
 * Get inverse chi-square value from lookup table
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
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= filemtime('../assets/css/style.css') ?>">

    <script>
        function otev() {
            vzor = window.open("img/friedman.png", "vzor", "width=700, height=400");
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
                        Friedmanův test
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

        <!-- Step 1: Initial Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <strong>r:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        <span class="text-slate-400">m:</span>
                        <input type="number" class="input-field w-24" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 10)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        if ($ir < 2 || $is < 2 || $ir > 10 || $is > 10 || !(round($ir) == $ir) || !(round($is) == $is)):
?>

        <!-- Step 1: Error - Invalid Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <strong>r:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        <span class="text-slate-400">m:</span>
                        <input type="number" class="input-field w-24" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 10)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste požadovaná celá čísla, opravte</p>
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
                        Počet tříd <strong>r:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        <span class="text-slate-400">m:</span>
                        <input type="number" class="input-field w-24" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 10)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodné výběry ze spojitých rozdělení:
                    </label>
                    <?php for ($i = 0; $i < $ir; $i++): ?>
                        <div class="mb-3">
                            <label class="text-white mb-2 block">
                                X<sub><?= ($i + 1) . "1" ?></sub>,...,X<sub><?= ($i + 1) . "," . $is ?></sub>:
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                <?php for ($j = 0; $j < $is; $j++): ?>
                                    <input type="number" step="any" name="x[]" class="input-field text-center"
                                           value="<?= $x[$is * $i + $j] ?? '' ?>" placeholder="<?= $is * $i + $j + 1 ?>">
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Nulová hypotéza H<sub>0</sub>:</strong> rozdělení řádků jsou stejná
                    </p>
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
        if ($ir < 2 || $is < 2 || $ir > 10 || $is > 10 || !(round($ir) == $ir) || !(round($is) == $is)):
?>

        <!-- Step 2: Error - Invalid Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-2">
                        Hladina testu: <strong class="text-white">α = 0,05</strong>
                    </label>
                </div>
                <div>
                    <label class="block text-slate-300 mb-2">
                        Počet tříd <strong>r:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        <span class="text-slate-400">m:</span>
                        <input type="number" class="input-field w-24" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 10)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste požadovaná celá čísla, opravte</p>
            </div>
        </div>

<?php
        break;
        else:
            // Prepare data matrix
            for ($i = 0; $i < $ir; $i++) {
                for ($j = 0; $j < $is; $j++) {
                    $xx[$i][$j] = $x[$is * $i + $j];
                }
            }

            // Calculate ranks for each column
            for ($i = 0; $i < $is; $i++) {
                for ($j = 0; $j < $ir; $j++) {
                    $pom[$j] = $xx[$j][$i];
                }
                $por = razeni($pom);
                for ($j = 0; $j < $ir; $j++) {
                    $pp[$j][$i] = $por[$j];
                }
            }

            // Calculate rank sums for each row
            $st = 0;
            for ($i = 0; $i < $ir; $i++) {
                for ($j = 0; $j < $is; $j++) {
                    $sou[$j] = $pp[$i][$j];
                }
                $t = sum($sou);
                $tt[$i] = $t;
            }

            // Calculate test statistic Q
            for ($i = 0; $i < $ir; $i++) {
                $st = $st + $tt[$i] * $tt[$i];
            }
            $q = 12 / $ir / $is / ($ir + 1) * $st - 3 * $is * ($ir + 1);
            $zq = zaokr($q, 4);
            $chinv = invchi3(($ir - 1));
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
                        Počet tříd <strong>r:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="ir" value="<?= $ir ?? '' ?>" min="2" max="10">
                        <span class="text-slate-400">m:</span>
                        <input type="number" class="input-field w-24" name="is" value="<?= $is ?? '' ?>" min="2" max="10">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte čísla od 2 do 10)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodné výběry ze spojitých rozdělení:
                    </label>
                    <?php for ($i = 0; $i < $ir; $i++): ?>
                        <div class="mb-3">
                            <label class="text-white mb-2 block">
                                X<sub><?= ($i + 1) . "1" ?></sub>,...,X<sub><?= ($i + 1) . "," . $is ?></sub>:
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                <?php for ($j = 0; $j < $is; $j++): ?>
                                    <input type="number" step="any" name="x[]" class="input-field text-center"
                                           value="<?= $x[$is * $i + $j] ?? '' ?>" placeholder="<?= $is * $i + $j + 1 ?>">
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="result-box">
                    <p class="text-blue-100">
                        <strong>Nulová hypotéza H<sub>0</sub>:</strong> rozdělení řádků jsou stejná
                    </p>
                </div>

                <div>
                    <button type="submit" class="btn-primary">Proveďte test</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>

            <!-- Results Section -->
            <div class="mt-8 space-y-4">
                <div class="result-box">
                    <h3 class="text-white font-semibold mb-4">📊 Data a pořadí:</h3>
                    <?php for ($i = 0; $i < $ir; $i++): ?>
                        <table class="data-table mb-4">
                            <tr>
                                <td class="text-slate-300">X<sub><?= ($i + 1) ?>,k</sub>:</td>
                                <?php for ($k = 0; $k < $is; $k++): ?>
                                    <td class="text-center"><?= $xx[$i][$k] ?></td>
                                <?php endfor; ?>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-left rank">Pořadí:</td>
                                <?php for ($k = 0; $k < $is; $k++): ?>
                                    <td class="text-center rank"><?= $pp[$i][$k] ?></td>
                                <?php endfor; ?>
                                <td class="text-blue-200 pl-4">T<sub><?= ($i + 1) ?></sub> = <?= $tt[$i] ?></td>
                            </tr>
                        </table>
                    <?php endfor; ?>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">🔬 Testová statistika:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">Q =</span>
                            <span class="font-mono text-lg ml-2"><?= $zq ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">χ²<sub><?= ($ir - 1) ?></sub>(0.95) =</span>
                            <span class="font-mono text-lg ml-2"><?= $chinv ?></span>
                        </div>
                    </div>
                </div>

                <div class="result-box <?= $q >= $chinv ? 'border-red-400' : 'border-green-400' ?>">
                    <h3 class="text-white font-semibold mb-3">✓ Závěr testu:</h3>
                    <div class="text-blue-100">
                        <?php if ($q >= $chinv): ?>
                            <p class="mb-2">
                                <span class="font-mono">Q ≥ χ²<sub><?= ($ir - 1) ?></sub>(0.95)</span>
                            </p>
                            <p class="text-lg font-semibold text-red-300">
                                → Hypotézu H<sub>0</sub> : stejné rozdělení <strong>zamítneme</strong>
                            </p>
                        <?php else: ?>
                            <p class="mb-2">
                                <span class="font-mono">Q &lt; χ²<sub><?= ($ir - 1) ?></sub>(0.95)</span>
                            </p>
                            <p class="text-lg font-semibold text-green-300">
                                → Hypotézu H<sub>0</sub> : stejné rozdělení <strong>nezamítneme</strong>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
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
        break;
    default:
endswitch;
?>
    </div>
</body>
</html>

