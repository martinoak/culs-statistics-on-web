<html>
<head>
<?php 

$a = $_GET['a'];
$n = $_GET['n'];
$x = $_GET['x'];
$aa = $_GET['aa'];

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

function invt1($sv)
  {$stud=FOpen("stud2.txt",r);
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
function otev(){vzor = window.open("jednov.png","vzor","width=700, height=400");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Jednovýběrový t-test (Studentův) oboustranný:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp;(zadejte číslo od 2 do 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<2||$n>30||!(round($n)==$n)): ?>
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 2 do 30)
  <input type=hidden name=a value=1>
</form>
<?php echo("<font color=red>nezadali jste celé číslo mezi 2 a 30, opravte</font>"); break;

else: ?>
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 2 do 30)
  <br> náhodný výběr z N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
  <?php for ($i =0; $i <$n; $i++): ?>
   <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
  <?php endfor;?> 
  <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : &mu; = <input type=double size=1 name=aa value="<?echo($aa);?>"> <br>
  <input type=submit value="proveďte test">
  <input type=hidden name=a value=2>
</form>
<?php break;
endif;

case 2: 

if($n<2||$n>30||!(round($n)==$n)): ?>
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n:  &nbsp;
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 2 do 30)
  <input type=hidden name=a value=1>
</form>
<?php echo("<font color=red>nezadali jste celé číslo mezi 2 a 30, opravte</font>"); break;

else: ?>
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n:  &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 2 do 30)
  <br> náhodný výběr z N(&mu;, &sigma;&sup2;) &nbsp;&nbsp; X <sub>1</sub>,...,X<sub><?php echo($n);?></sub> : 
  <?php for ($i =0; $i <$n; $i++): ?>
   <input type=double name="x[]" size=1  value="<?echo($x[$i]);?>">
  <?php endfor;?> 
  <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub> : &mu; = <input type=double size=1 name=aa value="<?echo($aa);?>"><br>
  <input type=submit value="proveďte test">
  <input type=hidden name=a value=2>
</form>
<?php
  $m=mean($x); $zm=zaokr($m,4);
  $so=smodch($x); $zso=zaokr($so,4) ?> 
  <br>
   <span style="text-decoration: overline">X</span> = <?php echo($zm);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    S<sub>x</sub>&sup2; = <?php echo($zso); ?>
<?php 
  if ($so==0):?> 
    <br> <font color=red> rozptyl se rovná 0, tento test nelze užít </font>
  <?php 
  else:
    $t=($m-$aa)/sqrt($so)*sqrt($n);
    $zt=zaokr($t,3); 
    $sv=count($x)-1;
    $inv=invt1($sv);
    $konf1=$m-sqrt($so)*$inv/sqrt($n);
    $konf2=$m+sqrt($so)*$inv/sqrt($n);
    $zkonf1=zaokr($konf1,4);
    $zkonf2=zaokr($konf2,4);
  ?>
    <br> 
    T = <?php echo($zt);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    t<sub><?php echo($sv)?></sub> <?php echo("(0.975) = ".$inv);?>
    <br>
  <?php 
    if(Max($t,-$t)>=$inv): ?> 
      |T| &ge; t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; hypotézu &nbsp;&nbsp; H<sub>0</sub> : &mu; = <?php echo($aa);?> &nbsp;&nbsp;zamítneme
    <?php 
    else: ?> 
      |T| < t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; hypotézu &nbsp;&nbsp; H<sub>0</sub> : &mu; = <?php echo($aa);?> &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>
    <br><br> konfidenční interval (95%):&nbsp;&nbsp;&nbsp;&nbsp; 
    <?php echo("( ".$zkonf1." ; ".$zkonf2." )");
  endif; ?> 

<br><br>
<?php
echo'<a href="jednovjedn.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový t-test jednnostranný </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="jednovS.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový test pro rozptyl </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="wiljednov.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový Wilcoxonův test </a>';
?>

<form>
  <input type=submit value="nové zadání">
  <input type=hidden name=a value=0>
</form>

<?
endif;
default:
endswitch;?>
</body>
</html>



 