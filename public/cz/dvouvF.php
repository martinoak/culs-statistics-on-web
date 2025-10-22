<html>
<head>
<?php 

$a = $_GET['a'] ?? null;
$m = $_GET['m'] ?? null;
$x = $_GET['x'] ?? null;
$n = $_GET['n'] ?? null;
$y = $_GET['y'] ?? null;

function mean($xvar)
  {$s =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k]; endfor;
  $s=$s/$mez;
  return $s;} 

function sctv($xvar,$me)
  {$sc =0; $mez =count($xvar);
  for ($k =0;$k <$mez; $k++): $sc =$sc + pow(($xvar[$k]-$me),2); endfor;
  return $sc;} 

function smodch($xvar)
  {$s =0;$sc =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++):
  $s =$s + $xvar[$k];
  $sc =$sc + pow($xvar[$k],2);
  endfor;
  $sp=($sc-$s*$s/$mez)/($mez-1);
  return $sp;}


function invf1($sv,$sw)
  {$fis=FOpen("fis1.txt", "r");
  $stav=($sw-1)*211+($sv-1)*7;
  FSeek($fis,$stav);
  $inv=FRead($fis,6);
  FClose($fis);
  return $inv;} 

function invf2($sv,$sw)
  {$fis=FOpen("fis4.txt", "r");
  $stav=($sw-1)*151+($sv-1)*5;
  FSeek($fis,$stav);
  $inv=FRead($fis,4);
  FClose($fis);
  return $inv;} 

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>

<script>
function otev(){vzor = window.open("dvouvF.png","vzor","width=750, height=300");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Dvouvýběrový F-test (Fisherův):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; m: &nbsp; 
  <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($m<2||$n<2||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)): ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; m: &nbsp; 
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste celá čísla mezi 2 a 30, opravte</font>"); break;

else: ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 30)
    <br> náhodný výběr z N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>: &nbsp;
    <?php for ($i =0; $i <$m; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> náhodný výběr z N(&mu;<sub>2</sub>, &sigma;<sub>2</sub>&sup2) &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &sigma;<sub>1</sub> = &sigma;<sub>2</sub> <br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>
  
  <?php break;
endif;

case 2: 

if($m<2||$n<2||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)):  ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 <br> rozsah &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 30)
    <input type=hidden name=a value=1>
  </form>
  
  <?php echo("<font color=red>nezadali jste celá čísla mezi 2 a 30, opravte</font>");  break;

else:?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 30)
    <br> náhodný výběr z N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2;)  &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$m; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> náhodný výběr z N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2)  &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &sigma;<sub>1</sub> = &sigma;<sub>2</sub><br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>

  <br> <?php
  $m=count($x);$n=count($y);
  $sv=$m-1;$sw=$n-1;
  $m1=mean($x); $m2=mean($y);$zm1=zaokr($m1,4);$zm2=zaokr($m2,4);
  $so1=smodch($x);$so2=smodch($y);$zso1=zaokr($so1,4);$zso2=zaokr($so2,4);
  $finv2=invf1($sv,$sw);
  $finv3=invf2($sv,$sw); ?>
  <span style="text-decoration: overline">X</span> = <?php echo($zm1);?> &nbsp;&nbsp;&nbsp;&nbsp; 
  <span style="text-decoration: overline">Y</span> = <?php echo($zm2);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>X</sub>&sup2; = <?php echo($zso1);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>Y</sub>&sup2; = <?php echo($zso2);?> 
  <?php
  if ($so1*$so2==0):?> <br> <font color=red>rozptyly se rovnají 0, tento test nelze užít</font><?php 
  else: $f=$so1/$so2; $fz=zaokr($f,3); ?> &nbsp;&nbsp;&nbsp;&nbsp; 
  F=<?php echo($fz);?> &nbsp;&nbsp;&nbsp;&nbsp; 
  F<sub><?php echo($sv.",".$sw)?></sub><?php echo("(0.025) = ".$finv2);?> &nbsp;&nbsp;&nbsp;&nbsp; 
  F<sub><?php echo($sv.",".$sw)?></sub><?php echo("(0.975) = ".$finv3);?> <br>
  <?php 
    if($f<=$finv2||$f>=$finv3):?> 
      F &le;   F<sub><?php echo($sv.",".$sw)?></sub>(0.025)  &nbsp;&nbsp; nebo &nbsp;&nbsp;
      F &ge;   F<sub><?php echo($sv.",".$sw)?></sub>(0.975) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypotézu &nbsp;&nbsp; H<sub>0</sub>: &sigma;<sub>1</sub> = &sigma;<sub>2</sub> &nbsp;&nbsp;zamítneme <?php 
    else: ?>
      F<sub><?php echo($sv.",".$sw)?></sub>(0.025)  < F
       < F<sub><?php echo($sv.",".$sw)?></sub>(0.975) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypotézu &nbsp;&nbsp; H<sub>0</sub>: &sigma;<sub>1</sub> = &sigma;<sub>2</sub> &nbsp;&nbsp;nezamítneme <?php 
    endif;
  endif; ?>

<br><br>
<?php
echo'<a href="dvouv.php?m=',$m,'& n=',$n,'&';
for ($i =0; $i <$m; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> dvouvýběrový t-test (Studentův) </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="wildvouv.php?m=',$m,'& n=',$n,'&';
for ($i =0; $i <$m; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> Wilcoxonův dvouvýběrový test (Mannův - Whitneyův test) </a>';
?>

  <form>
    <input type=submit value="nové zadání">
    <input type=hidden name=a value=0>
  </form> 
<?php 
endif;
default:
endswitch;?>
</body>
</html>


















