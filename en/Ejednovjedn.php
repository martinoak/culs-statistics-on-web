<html>
<head>
<?php 

$a = $_GET['a'] ?? null;
$c = $_GET['c'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;
$aa = $_GET['aa'] ?? null;

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
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k]; $sc =$sc + pow($xvar[$k],2); endfor;
  $sp=($sc-$s*$s/$mez)/($mez-1);
  return $sp;}

function invt2($sv)
  {$stud=FOpen("stud1.txt", "r");
  $stav=($sv-1)*7;
  FSeek($stud,$stav);
  $inv=FRead($stud,5);
  FClose($stud);
  return $inv;}  

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>
<script>
function otev(){vzor = window.open("Ejednovjedn.png","vzor","width=750, height=550");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2> One-sample t-test (Student) one-tailed:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp;(enter an integer from 2 to 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<2||$n>30||!(round($n)==$n)): ?>
<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 2 to 30)
  <input type=hidden name=a value=1>
</form>
<?php echo("<font color=red> you did not enter an integer between 2 and 30, correct </font>"); break;

else: ?>
<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 2 to 30)
  <br> random sample from z N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
  <?php for ($i =0; $i <$n; $i++): ?>
   <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
  <?php endfor;?> 
<table>
<tr><td> null hypothesis &nbsp;&nbsp; H<sub>0</sub> :</td>
<td><input type="radio" name="c" value=1 <?php  if($c==1):?> checked <?php  endif;?>> &mu; &le;</td>
<td rowspan=2><input type=double size=1 name=aa value="<?php echo($aa);?>"></td></tr>
<tr><td></td><td><input type="radio" name="c" value=2 <?php  if($c==2):?> checked <?php  endif;?>>  &mu; &ge; </td></tr>
</table> 
  <input type=submit value="perform the test">
  <input type=hidden name=a value=2>
</form>
<?php break;
endif;

case 2: 

if($n<2||$n>30||!(round($n)==$n)): ?>
<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n:  &nbsp;
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 2 to 30)
  <input type=hidden name=a value=1>
</form>
<?php echo("<font color=red> you did not enter an integer between 2 and 30, correct </font>"); break;

else: ?>
<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 2 to 30)
  <br> random sample from N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X <sub>1</sub>,...,X<sub><?php echo($n);?></sub> : 
  <?php for ($i =0; $i <$n; $i++): ?>
   <input type=double name="x[]" size=1  value="<?php echo($x[$i]);?>">
  <?php endfor;?> 
<table>
<tr><td>null hypothesis &nbsp;&nbsp; H<sub>0</sub> :</td>
<td><input type="radio" name="c" value=1 <?php  if($c==1):?> checked <?php  endif;?> > &mu; &le;</td>
<td rowspan=2><input type=double size=1 name=aa value="<?php echo($aa);?>"></td></tr>
<tr><td></td><td><input type="radio" name="c" value=2 <?php  if($c==2):?> checked <?php  endif;?> >  &mu; &ge; </td></tr>
</table>
  <input type=submit value="perform the test">
  <input type=hidden name=a value=2>
</form>
<?php
  $m=mean($x); $zm=zaokr($m,4);
  $so=smodch($x); $zso=zaokr($so,4) ?> 
  <br>
   <span style="text-decoration: overline">X</span> = <?php echo($zm);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    S<sub>x</sub>&sup2; = <?php echo($zso); ?>
<?php 
  if ($zso==0):?> 
    <br> <font color=red> variance is equal to 0, this test cannot be used </font>
  <?php 
  else:
    $t=($m-$aa)/sqrt($so)*sqrt($n);
    $zt=zaokr($t,3);
    $sv=count($x)-1;
    $inv=invt2($sv);
    $konf1=$m-sqrt($so)*$inv/sqrt($n);
    $konf2=$m+sqrt($so)*$inv/sqrt($n);
    $zkonf1=zaokr($konf1,4);
    $zkonf2=zaokr($konf2,4);
  ?>

   <?php if($c==1): ?> 
    <br> 
    T = <?php echo($zt);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    t<sub><?php echo($sv)?></sub> <?php echo("(0.95) = ".$inv);?>
    <br>
  <?php 
    if($t>=$inv): ?> 
      T &ge; t<sub><?php echo($sv)?></sub> <?php echo("(0.95)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &mu; &le; <?php echo($aa);?> &nbsp;&nbsp; is rejected
    <?php 
    else: ?> 
      T < t<sub><?php echo($sv)?></sub> <?php echo("(0.95)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &mu; &le; <?php echo($aa);?> &nbsp;&nbsp; is not rejected
    <?php 
    endif; ?>
    <br><br> interval of confidence (95%):&nbsp;&nbsp;&nbsp;&nbsp; 
    <?php echo("(".$zkonf1." ; &infin; )");?>

    <?php elseif($c==2): ?> 
    <br> 
    T = <?php echo($zt);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    t<sub><?php echo($sv)?></sub> <?php echo("(0.05) = -".$inv);?>
    <br>
  <?php 
    if($t<=-$inv): ?> 
      T &le; t<sub><?php echo($sv)?></sub> <?php echo("(0.05)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &mu; &ge; <?php echo($aa);?> &nbsp;&nbsp; is rejected
    <?php 
    else: ?> 
      T > t<sub><?php echo($sv)?></sub> <?php echo("(0.05)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &mu; &ge; <?php echo($aa);?> &nbsp;&nbsp; is not rejected
    <?php 
    endif; ?>
    <br><br> interval of confidence (95%):&nbsp;&nbsp;&nbsp;&nbsp; 
    <?php echo("( -&infin; ; ".$zkonf2.")");?>



    <?php else: ?> 
    <br> <font color=red> you did not enter inequality in one-tailed test </font>
    <?php  endif; ?> 
<?php   endif; ?> 

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
echo'<a href="EjednovS.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample test for variance </a>';
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



 