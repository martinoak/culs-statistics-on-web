<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lineární regrese</title>
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
$c = $_GET['c'] ?? null;
$aa = $_GET['aa'] ?? null;

/**
 * Calculate mean of array
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
 * Calculate sum of squared deviations
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
 * Calculate covariance
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
 * Get t-distribution critical value from lookup table
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
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= filemtime('../assets/css/style.css') ?>">

    <script>
        function otev() {
            vzor = window.open("img/reg.png", "vzor", "width=800, height=550");
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
                        Lineární regrese
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
                    <label class="block text-slate-300 mb-3">
                        Rozsah vzorku:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">n:</label>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        </div>
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

        <!-- Error: Invalid n -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-3">
                        Rozsah vzorku:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">n:</label>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>
                <div class="error-box">
                    <strong>⚠️ Chyba:</strong> Nezadali jste celé číslo mezi 3 a 30, opravte
                </div>
                <input type="hidden" name="a" value="1">
            </form>
        </div>

<?php
            break;
        else:
?>

        <!-- Step 2: Data Input -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-6">
                <div>
                    <label class="block text-slate-300 mb-3">
                        Rozsah vzorku:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">n:</label>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Vysvětlující veličina a náhodný výběr vysvětlované veličiny z normálního rozdělení:
                    </label>
                    <p class="text-slate-400 text-sm mb-3">
                        (x<sub>1</sub>,Y<sub>1</sub>),...,(x<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-white mb-2">
                                x<sub>1</sub>,...,x<sub><?= $n ?></sub>:
                            </label>
                            <div class="data-grid">
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <input type="number" step="any" name="x[]" class="input-field text-center"
                                           value="<?= $x[$i] ?? '' ?>">
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div>
                            <label class="block text-white mb-2">
                                Y<sub>1</sub>,...,Y<sub><?= $n ?></sub>:
                            </label>
                            <div class="data-grid">
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <input type="number" step="any" name="y[]" class="input-field text-center"
                                           value="<?= $y[$i] ?? '' ?>">
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-white mb-3">Typ regrese:</label>
                    <div class="space-y-2">
                        <label class="radio-option">
                            <input type="radio" name="c" value="1" <?= $c == 1 ? 'checked' : '' ?>>
                            <span class="text-slate-200">y = ax + b</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="c" value="2" <?= $c == 2 ? 'checked' : '' ?>>
                            <span class="text-slate-200">y = ax</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Konfidenční interval pro x<sub>0</sub>:
                    </label>
                    <input type="number" step="any" class="input-field w-32" name="aa" value="<?= $aa ?? '' ?>">
                </div>

                <div>
                    <button type="submit" class="btn-primary">Vypočítejte</button>
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

        <!-- Error: Invalid n -->
        <div class="glass-card rounded-2xl p-6 md:p-8">
            <form method="get" class="space-y-4">
                <div>
                    <label class="block text-slate-300 mb-3">
                        Rozsah vzorku:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">n:</label>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>
                <div class="error-box">
                    <strong>⚠️ Chyba:</strong> Nezadali jste celé číslo mezi 3 a 30, opravte
                </div>
                <input type="hidden" name="a" value="1">
            </form>
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
                    <label class="block text-slate-300 mb-3">
                        Rozsah vzorku:
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-white">n:</label>
                            <input type="number" class="input-field w-24" name="n" value="<?= $n ?? '' ?>" min="3" max="30">
                        </div>
                        <button type="submit" class="btn-primary">Ano</button>
                        <span class="text-slate-400 text-sm">(zadejte číslo od 3 do 30)</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Vysvětlující veličina a náhodný výběr vysvětlované veličiny z normálního rozdělení:
                    </label>
                    <p class="text-slate-400 text-sm mb-3">
                        (x<sub>1</sub>,Y<sub>1</sub>),...,(x<sub><?= $n ?></sub>,Y<sub><?= $n ?></sub>)
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-white mb-2">
                                x<sub>1</sub>,...,x<sub><?= $n ?></sub>:
                            </label>
                            <div class="data-grid">
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <input type="number" step="any" name="x[]" class="input-field text-center"
                                           value="<?= $x[$i] ?? '' ?>">
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div>
                            <label class="block text-white mb-2">
                                Y<sub>1</sub>,...,Y<sub><?= $n ?></sub>:
                            </label>
                            <div class="data-grid">
                                <?php for ($i = 0; $i < $n; $i++): ?>
                                    <input type="number" step="any" name="y[]" class="input-field text-center"
                                           value="<?= $y[$i] ?? '' ?>">
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-white mb-3">Typ regrese:</label>
                    <div class="space-y-2">
                        <label class="radio-option">
                            <input type="radio" name="c" value="1" <?= $c == 1 ? 'checked' : '' ?>>
                            <span class="text-slate-200">y = ax + b</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="c" value="2" <?= $c == 2 ? 'checked' : '' ?>>
                            <span class="text-slate-200">y = ax</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 mb-2">
                        Zadaná hodnota x<sub>0</sub>:
                    </label>
                    <input type="number" step="any" class="input-field w-32" name="aa" value="<?= $aa ?? '' ?>">
                </div>

                <div>
                    <button type="submit" class="btn-primary">Vypočítejte</button>
                </div>
                <input type="hidden" name="a" value="2">
            </form>

            <!-- Basic Statistics -->
            <div class="mt-8 result-box">
                <h3 class="text-white font-semibold mb-3">📊 Základní statistiky:</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 text-blue-100">
                    <div>
                        <span class="text-slate-300"><span style="text-decoration: overline">x</span> =</span>
                        <span class="font-mono ml-2"><?= $zm1 ?></span>
                    </div>
                    <div>
                        <span class="text-slate-300"><span style="text-decoration: overline">Y</span> =</span>
                        <span class="font-mono ml-2"><?= $zm2 ?></span>
                    </div>
                    <div>
                        <span class="text-slate-300">s<sub>x</sub>² =</span>
                        <span class="font-mono ml-2"><?= $zso1 ?></span>
                    </div>
                    <div>
                        <span class="text-slate-300">S<sub>Y</sub>² =</span>
                        <span class="font-mono ml-2"><?= $zso2 ?></span>
                    </div>
                    <div>
                        <span class="text-slate-300">S<sub>xY</sub> =</span>
                        <span class="font-mono ml-2"><?= $zsxy ?></span>
                    </div>
                </div>
            </div>

<?php
            // Regression calculations based on type
            if ($c == 1):
                // Linear regression y = ax + b
                if ($so1 * $so2 == 0):
?>
            <div class="mt-4 error-box">
                <strong>⚠️ Chyba:</strong> Alespoň jeden rozptyl se rovná 0, regresi nelze použít
            </div>
<?php
                else:
                    $aaa = $sxy / $so1;
                    $bbb = $m2 - $aaa * $m1;
                    $abbb = abs($bbb);
                    $r = $sxy / sqrt($so1) / sqrt($so2);
                    $rrr = $r * $r;
                    $zrrr = zaokr($rrr, 4);
                    $zaaa = zaokr($aaa, 3);
                    $zbbb = zaokr($bbb, 3);
                    $zabbb = zaokr($abbb, 3);
                    $yo = $aaa * $aa + $bbb;
                    $zyo = zaokr($yo, 4);
                    $sr = (1 - $rrr) * $so2 * ($sv + 1) / $sv;
                    $eps = $inv * sqrt($sr) * sqrt(1 / $n + ($aa - $m1) * ($aa - $m1) / (($n - 1) * $so1));
                    $zeps = zaokr($eps, 4);
                    $inv = invt1($sv);
                    $aeps = sqrt($sr / $so1 / ($n - 1)) * $inv;
                    $beps = sqrt($sr * (1 / $n + $m1 / $so1 / ($n - 1))) * $inv;
                    $zaeps = zaokr($aeps, 3);
                    $zbeps = zaokr($beps, 3);

                    if ($bbb > 0) {
                        $bbbb = "+" . $zabbb;
                    } elseif ($bbb < 0) {
                        $bbbb = $zbbb;
                    } else {
                        $bbbb = "";
                    }
?>

            <!-- Regression Results for y = ax + b -->
            <div class="mt-4 space-y-4">
                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📈 Regresní rovnice:</h3>
                    <p class="text-blue-100 text-lg font-mono">
                        Y = <?= $zaaa ?> x <?= $bbbb ?>
                    </p>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">🔍 Konfidenční intervaly (95%):</h3>
                    <div class="space-y-2 text-blue-100">
                        <p>
                            <span class="text-slate-300">Směrnice:</span>
                            <span class="font-mono ml-2"><?= $zaaa ?> ± <?= $zaeps ?></span>
                        </p>
                        <p>
                            <span class="text-slate-300">Úsek na ose y:</span>
                            <span class="font-mono ml-2"><?= $zbbb ?> ± <?= $zbeps ?></span>
                        </p>
                        <p>
                            <span class="text-slate-300">Střední hodnota pro x<sub>0</sub> = <?= $aa ?>:</span>
                            <span class="font-mono ml-2"><?= $zyo ?> ± <?= $zeps ?></span>
                        </p>
                    </div>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📊 Koeficient determinace:</h3>
                    <p class="text-blue-100 text-lg">
                        R² = <span class="font-mono"><?= $zrrr ?></span>
                    </p>
                </div>
            </div>

<?php
                    // Generate regression plot
                    $Ax = 30;
                    $Bx = 230;
                    $Cy = 30;
                    $Dy = 100;
                    $xmin = min($x);
                    $xmax = max($x);
                    $ymin = min($y);
                    $ymax = max($y);
                    $xr1 = $xmin - $Ax * ($xmax - $xmin) / $Bx;
                    $xr2 = $xmax + $Ax * ($xmax - $xmin) / $Bx;
                    $yr1 = $aaa * $xr1 + $bbb;
                    $yr2 = $aaa * $xr2 + $bbb;
                    $gxr1 = 2 * $Ax;
                    $gxr2 = 4 * $Ax + $Bx;
                    $gyr1 = 2 * $Cy + $Dy * ($ymax - $yr1) / ($ymax - $ymin);
                    $gyr2 = 2 * $Cy + $Dy * ($ymax - $yr2) / ($ymax - $ymin);

                    for ($i = 0; $i < $n; $i++) {
                        $gx[$i] = 3 * $Ax + ($x[$i] - $xmin) * $Bx / ($xmax - $xmin);
                        $gy[$i] = 2 * $Cy + $Dy * ($ymax - $y[$i]) / ($ymax - $ymin);
                    }

                    $gaa = 3 * $Ax + ($aa - $xmin) * $Bx / ($xmax - $xmin);
                    $gyo1 = 2 * $Cy + $Dy * ($ymax - ($yo - $eps)) / ($ymax - $ymin);
                    $gyo2 = 2 * $Cy + $Dy * ($ymax - ($yo + $eps)) / ($ymax - $ymin);

                    $graf = imagecreate((5 * $Ax + $Bx), (6 * $Cy + $Dy));
                    $zluta = imagecolorallocate($graf, 255, 255, 150);
                    $cerna = imagecolorallocate($graf, 0, 0, 0);

                    for ($i = 0; $i < $n; $i++) {
                        imagerectangle($graf, $gx[$i], $gy[$i], $gx[$i] + 5, $gy[$i] + 5, $cerna);
                    }

                    imagefilledellipse($graf, $gaa, (($gyo1 + $gyo2) / 2), 6, 6, $cerna);
                    imageline($graf, 0, (4 * $Cy + $Dy), (5 * $Ax + $Bx), (4 * $Cy + $Dy), $cerna);
                    imageline($graf, $Ax, 0, $Ax, (5 * $Cy + $Dy), $cerna);
                    imageline($graf, $gaa, $gyo1, $gaa, $gyo2, $cerna);
                    imageline($graf, ($gaa - 3), $gyo1, ($gaa + 3), $gyo1, $cerna);
                    imageline($graf, ($gaa - 3), $gyo2, ($gaa + 3), $gyo2, $cerna);
                    imageline($graf, $gaa, (4 * $Cy + $Dy - 3), $gaa, (4 * $Cy + $Dy + 3), $cerna);

                    if ($zbbb < 0) {
                        imagestring($graf, 5, 70, (5 * $Cy + $Dy), "y=" . $zaaa . "x" . $zbbb, $cerna);
                    } else {
                        imagestring($graf, 5, 70, (5 * $Cy + $Dy), "y=" . $zaaa . "x+" . $zbbb, $cerna);
                    }

                    imagestring($graf, 5, 250, (5 * $Cy + $Dy), "R^2=" . $zrrr, $cerna);
                    imagesetthickness($graf, 4);
                    imageline($graf, $gxr1, $gyr1, $gxr2, $gyr2, $cerna);

                    imagepng($graf, "data/image.png");
                    imagedestroy($graf);
?>

            <!-- Regression Plot -->
            <div class="mt-4 result-box">
                <h3 class="text-white font-semibold mb-3">📉 Graf regrese:</h3>
                <img src="data/image.png" alt="Regresní graf" class="max-w-full h-auto">
                <p class="text-slate-400 text-sm mt-2">
                    Upozornění: Prohlížeč Explorer obvykle nenačítá obnovený obrázek, je třeba provést aktualizaci stránek (na liště nebo klávesnicí F5)
                </p>
            </div>

<?php
                endif;
            elseif ($c == 2):
                // Linear regression y = ax (through origin)
                if ($so1 * $so2 == 0):
?>
            <div class="mt-4 error-box">
                <strong>⚠️ Chyba:</strong> Alespoň jeden rozptyl se rovná 0, regresi nelze použít
            </div>
<?php
                else:
                    $svv = $n - 1;
                    $invv = invt1($svv);
                    $sqx = ($n - 1) * $so1 + $n * $m1 * $m1;
                    $sqy = ($n - 1) * $so2 + $n * $m2 * $m2;
                    $mxy = ($n - 1) * $sxy + $n * $m1 * $m2;
                    $aaaa = $mxy / $sqx;
                    $ssr = ($sqy - $aaaa * $mxy) / ($n - 1);
                    $aaeps = sqrt($ssr / $sqx) * $invv;
                    $zaaaa = zaokr($aaaa, 3);
                    $zaaeps = zaokr($aaeps, 3);
                    $yoo = $aaaa * $aa;
                    $zyoo = zaokr($yoo, 4);
?>

            <!-- Regression Results for y = ax -->
            <div class="mt-4 space-y-4">
                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📈 Regresní rovnice:</h3>
                    <p class="text-blue-100 text-lg font-mono">
                        Y = <?= $zaaaa ?> x
                    </p>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">🔍 Směrnice:</h3>
                    <p class="text-blue-100">
                        <span class="font-mono"><?= $zaaaa ?> ± <?= $zaaeps ?></span>
                    </p>
                </div>

                <div class="result-box">
                    <h3 class="text-white font-semibold mb-3">📊 Střední hodnota:</h3>
                    <p class="text-blue-100">
                        Pro x<sub>0</sub> = <?= $aa ?>: <span class="font-mono"><?= $zyoo ?></span>
                    </p>
                </div>
            </div>

<?php
                endif;
            else:
?>
            <div class="mt-4 error-box">
                <strong>⚠️ Chyba:</strong> Nezadali jste typ regrese
            </div>
<?php
            endif;
?>

            <!-- Related Tests -->
            <div class="mt-6 result-box">
                <h3 class="text-white font-semibold mb-3">🔗 Související testy:</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="pears.php?n=<?= $n ?>&<?php
                        for ($i = 0; $i < $n; $i++) {
                            echo 'x%5B%5D=' . $x[$i] . '&';
                        }
                        for ($j = 0; $j < $n; $j++) {
                            echo 'y%5B%5D=' . $y[$j] . '&';
                        }
                        ?>a=1" class="btn-secondary">
                        Pearsonův korelační koeficient
                    </a>
                    <a href="spearman.php?n=<?= $n ?>&<?php
                        for ($i = 0; $i < $n; $i++) {
                            echo 'x%5B%5D=' . $x[$i] . '&';
                        }
                        for ($j = 0; $j < $n; $j++) {
                            echo 'y%5B%5D=' . $y[$j] . '&';
                        }
                        ?>a=1" class="btn-secondary">
                        Spearmanův korelační koeficient
                    </a>
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
    default:
endswitch;
?>
    </div>
</body>
</html>

