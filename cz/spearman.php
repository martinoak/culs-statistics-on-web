<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Spearmanova korelačního koeficientu</title>
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
 * Get Spearman critical value from lookup table
 */
function spear(int $sv): float
{
    $spea = fopen("../samples/spea.txt", "r");
    $stav = ($sv - 6) * 8;
    fseek($spea, $stav);
    $inv = fread($spea, 6);
    fclose($spea);

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
            vzor = window.open("img/spearman.png", "vzor", "width=600, height=300");
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
                    <a href="celekTEST.php" class="text-slate-400 hover:text-blue-400 text-sm mb-2 inline-block transition-colors">
                        ← Seznam testů
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">
                        Test Spearmanova korelačního koeficientu
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
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="5" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 5 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
        break;

    case 1:
        if ($n < 5 || $n > 30 || !(round($n) == $n)):
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
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="5" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 5 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celé číslo mezi 5 a 30, opravte</p>
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
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="5" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 5 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
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
                        <strong>Nulová hypotéza H<sub>0</sub>:</strong> X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé
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
        if ($n < 5 || $n > 30 || !(round($n) == $n)):
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
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="5" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 5 do 30)</span>
                    </div>
                </div>
                <input type="hidden" name="a" value="1">
            </form>

            <div class="error-box mt-4">
                <p class="text-red-300 font-medium">⚠️ Nezadali jste celé číslo mezi 5 a 30, opravte</p>
            </div>
        </div>

<?php
        break;
        else:
            // Calculate ranks
            $n = count($x);
            $p = razeni($x);
            $q = razeni($y);

            // Calculate Spearman correlation
            $s = 0;
            for ($i = 0; $i < $n; $i++) {
                $s = $s + pow(($p[$i] - $q[$i]), 2);
            }
            $r = 1 - 6 * $s / $n / (pow($n, 2) - 1);
            $zr = zaokr($r, 4);
            $inv = spear($n);
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
                        Rozsah <strong>n:</strong>
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="5" max="30">
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 5 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-3">
                        Náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
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
                        <strong>Nulová hypotéza H<sub>0</sub>:</strong> X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé
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
                    <h3 class="text-white font-semibold mb-4">📊 Data a pořadí X:</h3>
                    <div class="data-table overflow-x-auto">
                        <table>
                            <tr>
                                <td class="text-left text-slate-300">X<sub>k</sub>:</td>
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <td><?= $x[$i] ?></td>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <td class="text-left rank">Pořadí:</td>
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <td class="rank"><?= $p[$i] ?></td>
                                <?php endfor; ?>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-4">📊 Data a pořadí Y:</h3>
                    <div class="data-table overflow-x-auto">
                        <table>
                            <tr>
                                <td class="text-left text-slate-300">Y<sub>k</sub>:</td>
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <td><?= $y[$i] ?></td>
                                <?php endfor; ?>
                            </tr>
                            <tr>
                                <td class="text-left rank">Pořadí:</td>
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <td class="rank"><?= $q[$i] ?></td>
                                <?php endfor; ?>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">🔬 Testová statistika:</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-blue-100">
                        <div>
                            <span class="text-slate-300">r<sub>S</sub> =</span>
                            <span class="font-mono text-lg ml-2"><?= $zr ?></span>
                        </div>
                        <div>
                            <span class="text-slate-300">k<sub><?= $n ?></sub> =</span>
                            <span class="font-mono text-lg ml-2"><?= $inv ?></span>
                        </div>
                    </div>
                </div>

                <div class="result-box <?= max($r, -$r) >= $inv ? 'border-red-400' : 'border-green-400' ?>">
                    <h3 class="text-white font-semibold mb-3">✓ Závěr testu:</h3>
                    <div class="text-blue-100">
                        <?php if (max($r, -$r) >= $inv): ?>
                            <p class="mb-2">
                                <span class="font-mono">|r<sub>S</sub>| ≥ k<sub><?= $n ?></sub></span>
                            </p>
                            <p class="text-lg font-semibold text-red-300">
                                → Hypotézu H<sub>0</sub> : X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé <strong>zamítneme</strong>
                            </p>
                        <?php else: ?>
                            <p class="mb-2">
                                <span class="font-mono">|r<sub>S</sub>| &lt; k<sub><?= $n ?></sub></span>
                            </p>
                            <p class="text-lg font-semibold text-green-300">
                                → Hypotézu H<sub>0</sub> : X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé <strong>nezamítneme</strong>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Tests -->
        <div class="glass-card rounded-2xl p-6 md:p-8 mb-6">
            <h3 class="text-white font-semibold mb-4">🔗 Související testy se stejnými daty:</h3>
            <div class="flex flex-wrap gap-3">
                <?php
                echo '<a href="pears.php?n=', $n, '&';
                for ($i = 0; $i < $n; $i++) {
                    echo 'x%5B%5D=', $x[$i], '&';
                }
                for ($j = 0; $j < $n; $j++) {
                    echo 'y%5B%5D=', $y[$j], '&';
                }
                echo 'a=1" class="link-button">Pearsonův korelační koeficient</a>';
                ?>

                <?php
                echo '<a href="linreg.php?n=', $n, '&';
                for ($i = 0; $i < $n; $i++) {
                    echo 'x%5B%5D=', $x[$i], '&';
                }
                for ($j = 0; $j < $n; $j++) {
                    echo 'y%5B%5D=', $y[$j], '&';
                }
                echo 'a=1" class="link-button">Lineární regrese</a>';
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
        break;
    default:
endswitch;
?>
    </div>
</body>
</html>

