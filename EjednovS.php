<html>
<head>
<?php 

$a = $_GET['a'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;
$aa = $_GET['aa'] ?? null;

function mean($xvar)
  {$s =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k];endfor;
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

function invchi1($sv)
  {$chi=FOpen("chi1.txt", "r");
  $stav=($sv-1)*7;
  FSeek($chi,$stav);
  $inv=FRead($chi,5);
  FClose($chi);
  return $inv;}  

function invchi2($sv)
  {$chi=FOpen("chi4.txt", "r");
  $stav=($sv-1)*7;
  FSeek($chi,$stav);
  $inv=FRead($chi,5);
  FClose($chi);
  return $inv;}  

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 
?>
<script>
function otev(){vzor = window.open("EjednovS.png","vzor","width=750, height=450");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2> One-sample test for variance:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> 
       &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <input type=hidden name=a value=1>
</form>
<?php break;

case 1: 

if($n<3||$n>30||!(round($n)==$n)): ?>

<form method=get> test level  &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> 
     &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <input type=hidden name=a value=1>
</form>

<?php
echo("<font color=red> you did not enter an integer between 3 and 30, correct</font>"); break;

else: ?>

<form method=get> test level  &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>">
     &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <br> random sample from N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: 
  <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
  <?php endfor;?> 
  <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &sigma;&sup2; = <input type=double size=1 name=aa value="<?php echo($aa);?>"> &nbsp;&nbsp; (enter positive number)<br>
  <input type=submit value="perform the test">
  <input type=hidden name=a value=2>
</form>
<?php break;
endif;

case 2: 

if($n<3||$n>30||!(round($n)==$n)): ?>

<form method=get> test level  &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> 
      &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <input type=hidden name=a value=1>
</form>
<?php echo("<font color=red> you did not enter an integer between 3 and 30, correct </font>"); 
break;

elseif($aa<=0):?>

<form method=get> test level  &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> 
      &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <br> random sample from N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: 
  <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1  value="<?php echo($x[$i]);?>">
  <?php endfor;?> 
  <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &sigma;&sup2; = <input type=double size=1 name=aa value="<?php echo($aa);?>"> &nbsp;&nbsp; (enter positive number)<br>
  <input type=submit value="perform the test">
  <input type=hidden name=a value=2>
</form>
<?php echo("<font color=red> you did not enter positive number, correct </font>");
 
break;

else: ?>

<form method=get> test level  &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> 
     &nbsp; (enter an integer from 3 to 30) &nbsp;&nbsp;
  <input type=submit value="yes"> 
  <br> random sample from N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: 
  <?php for ($i =0; $i <$n; $i++): ?>
     <input type=double name="x[]" size=1  value="<?php echo($x[$i]);?>">
  <?php endfor;?> 
  <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &sigma;&sup2; = <input type=double size=1 name=aa value="<?php echo($aa);?>"> &nbsp;&nbsp; (enter positive number) <br> 
  <input type=submit value="perform the test">
  <input type=hidden name=a value=2>
</form>

<br>
<?php
$sv=count($x)-1;
$m=mean($x); $zm=zaokr($m,4);
$so=smodch($x);$zso=zaokr($so,4);
$v=$so/$aa*($sv);
$zv=zaokr($v,3);
$inv1=invchi1($sv);
$inv2=invchi2($sv);
$konf1=$so*$sv/$inv2;
$konf2=$so*$sv/$inv1;
$zkonf1=zaokr($konf1,4);
$zkonf2=zaokr($konf2,4);
?>
<span style="text-decoration: overline">X</span> = <?php echo($zm);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    S<sub>x</sub>&sup2; = <?php echo($zso); ?> <br> 
V= <?php echo($zv);?> &nbsp;&nbsp;&nbsp;&nbsp; 
&chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.025) = ".$inv1);?> &nbsp;&nbsp;&nbsp;&nbsp; 
&chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.975) = ".$inv2);?> <br>
<?php 
if($v<=$inv1||$v>=$inv2):?> 
      V &le; &chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.025)");?>  &nbsp;&nbsp; nebo &nbsp;&nbsp;
      V &ge; &chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

hypothesis &nbsp;&nbsp; H<sub>0</sub>: &sigma;&sup2; = <?php echo($aa);?> &nbsp;&nbsp; is rejected
<?php 
else:?> 
      &chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.025)");?>   <
         V  <  &chi;&sup2;<sub><?php echo($sv)?></sub><?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
hypothesis &nbsp;&nbsp; H<sub>0</sub>: &sigma;&sup2; = <?php echo($aa);?> &nbsp;&nbsp; is not rejected
<?php 
endif;?>
<br><br>  interval of confidence (95%):&nbsp;&nbsp;&nbsp;&nbsp; 
<?php echo("( ".$zkonf1." ; ".$zkonf2." )");?> 

<br><br>
<?php
echo'<a href="Ejednov.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample t-test two-tailed </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="Ejednovjedn.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample t-test one-tailed </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="EWiljednov.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> Wilcoxon signed-rank test </a>';
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



