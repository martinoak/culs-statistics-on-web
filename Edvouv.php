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
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k];endfor;
  $s=$s/$mez;
  return $s;} 

function sctv($xvar,$me)
  {$sc =0; $mez =count($xvar);
  for ($k =0;$k <$mez; $k++): $sc =$sc + pow(($xvar[$k]-$me),2);endfor;
  return $sc;} 

function smodch($xvar)
  {$s =0;$sc =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k]; $sc =$sc + pow($xvar[$k],2); endfor;
  $sp=($sc-$s*$s/$mez)/($mez-1);
  return $sp;}

function invt1($sv)
  {$stud1=FOpen("stud2.txt", "r");
  $stav=($sv-1)*7+0;
  FSeek($stud1,$stav);
  $inv=FRead($stud1,5);
  FClose($stud1);
  return $inv;}  

function invf1($sv,$sw)
  {$fis=FOpen("fis1.txt", "r");
  $stav=($sv-1)*211+($sw-1)*7;
  FSeek($fis,$stav);
  $inv=FRead($fis,6);
  FClose($fis);
  return $inv;} 

function invf2($sv,$sw)
  {$fis=FOpen("fis4.txt", "r");
  $stav=($sv-1)*151+($sw-1)*5;
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
function otev(){vzor = window.open("Edvouv.png","vzor","width=750, height=300");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Two-sample t-test (Student):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; m: &nbsp;
  <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter integers from 2 to 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($m<2||$n<2||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)): ?>
  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp; 
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter integers between 2 and 30, correct </font>"); break;

else: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 30)
    <br> random sample from N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>:&nbsp; 
    <?php for ($i =0; $i <$m; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>"> <?php endfor;?> 
    <br> random sample from N(&mu;<sub>2</sub>, &sigma;<sub>2</sub>&sup2;) &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>:&nbsp; 
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>"> <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> 
    <br> <font color=red> the test can be used only on condition &sigma;<sub>1</sub>&sup2; = &sigma;<sub>2</sub>&sup2;</font><br> 
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>

  <?php break;
endif;

case 2: 

if($m<2||$n<2||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)):?>
  
  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter integers between 2 and 30, correct </font>"); break;

else: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?php echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 30)
    <br> random sample from N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2;)  &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$m; $i++): ?> 
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>"> 
    <?php endfor;?> 
    <br> random sample from N(&mu;<sub>1</sub>, &sigma;<sub>1</sub>&sup2;)  &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> 
    <br><font color=red> the test can be used only on condition &sigma;<sub>1</sub>&sup2; = &sigma;<sub>2</sub>&sup2;</font>
    <br> <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>

  <br><?php
  $m=count($x);$n=count($y);$sv=$m+$n-2;
  $m1=mean($x); $m2=mean($y); $zm1=zaokr($m1,4);$zm2=zaokr($m2,4);
  $so1=smodch($x);$so2=smodch($y);$zso1=zaokr($so1,4);$zso2=zaokr($so2,4);
  $so=(($m-1)*$so1+($n-1)*$so2)/$sv; $zso=zaokr($so,4);
  $inv=invt1($sv);
  $finv2=invf1($m-1,$n-1);
  $finv3=invf2($m-1,$n-1); ?>
  <span style="text-decoration: overline">X</span> = <?php echo($zm1);?> &nbsp;&nbsp;&nbsp;&nbsp; 
  <span style="text-decoration: overline">Y</span> = <?php echo($zm2);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>X</sub><sup>2</sup> = <?php echo($zso1);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>Y</sub><sup>2</sup> = <?php echo($zso2);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sup>2</sup> =
  <?php echo($zso);

  if($so1*$so2==0):?> 
    <br><font color=red> variances are equal to 0, this test cannot be used</font><?php 
  else:
    $t=($m1-$m2)/sqrt($so)*sqrt(($n*$m)/($n+$m));
    $tz=zaokr($t,3);
    $f=$so1/$so2; ?>
    <br> T= <?php echo($tz);?>&nbsp;&nbsp;&nbsp;&nbsp; 
    t<sub><?php echo($sv)?></sub><?php echo("(0.975) = ".$inv);?><br><?php 
      if(Max($t,-$t)>=$inv):?> 
      |T| &ge; t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> &nbsp;&nbsp; is rejected <?php 
      else: ?> 
      |T| < t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> &nbsp;&nbsp; is not rejected <?php 
      endif;

      if($f<=$finv2||$f>=$finv3):?>
      <br> <font color=red> variances are not the same, this test is not suitable for this case </font><?php
      endif;      
   endif;?>

<br><br>
<?php
echo'<a href="EdvouvF.php?m=',$m,'& n=',$n,'&';
for ($i =0; $i <$m; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> two-sample F-test (Fischer) </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="Ewildvouv.php?m=',$m,'& n=',$n,'&';
for ($i =0; $i <$m; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> Wilcoxon sum-rank test (Mann-Whitney test) </a>';
?>

  <form>
    <input type=submit value="new entry">
    <input type=hidden name=a value=0>
  </form> 
<?php 
endif;
default:
endswitch;?>
</body>
</html>


















