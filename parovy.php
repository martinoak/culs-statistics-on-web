<html>
<head>
<?php 

$a = $_GET['a'];
$x = $_GET['x'];
$n = $_GET['n'];
$y = $_GET['y'];

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

function cov($xvar,$yvar)
  {$s1 =0; $s2=0; $sn =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s1 =$s1 + $xvar[$k];$s2 =$s2 + $yvar[$k]; 
        $sn =$sn + $xvar[$k]*$yvar[$k]; endfor;
  $co=($sn-$s1*$s2/$mez)/($mez-1);
  return $co;}

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
function otev(){vzor = window.open("parovy.png","vzor","width=750, height=450");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Párový t-test (Studentův):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<3||$n>30||!(round($n)==$n)): ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 3 do 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste celé číslo mezi 3 a 30, opravte</font>");  break;

else: ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 30)
    <br> náhodný výběr z normálního rozdělení &nbsp;&nbsp; 
    (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> <br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>
 
  <?php break;
endif;

case 2: 

if($n<3||$n>30||!(round($n)==$n)):  ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 3 do 30)
    <input type=hidden name=a value=1>
  </form>

<?php echo("<font color=red>nezadali jste celé číslo mezi 3 a 30, opravte</font>"); break;

else: ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n: &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>">&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 3 do 30)
    <br> náhodný výběr z normálního rozdělení &nbsp;&nbsp; 
    (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> <br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form><br>
<?php
     for ($i =0; $i <$n; $i++): 
    $z[$i]=$x[$i]-$y[$i];endfor; ?>

 rozdíly &nbsp;&nbsp; Z<sub>1</sub>= X<sub>1</sub>- Y<sub>1</sub>,...,Z<sub><?php echo($n);?></sub>=X<sub><?php echo($n);?></sub>-Y<sub><?php echo($n);?></sub>:&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php  for ($i =0; $i <$n; $i++): ?> &nbsp;&nbsp;&nbsp;&nbsp;
   <?php echo($z[$i]);endfor; ?>
<br>

<?php
  $sv=$n-1;
  $m=mean($z);  $zm=zaokr($m,4);
  $so=smodch($z);  $zso=zaokr($so,4);
  $inv=invt1($sv);  ?>
  <span style="text-decoration: overline">Z</span>  = <?php echo($zm);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>Z</sub>&sup2; = <?php echo($zso);?> &nbsp;&nbsp;&nbsp;&nbsp;
    
  <?php 
  if($so==0):?> 
    <br><font color=red> rozptyl se rovná 0, tento test nelze užít</font><?php 
  else:

      $t=$m/sqrt($so)*sqrt($n);    
      $zt=zaokr($t,3); ?> <br> 
      T = <?php echo($zt);?>&nbsp;&nbsp;&nbsp;&nbsp; 
      t<sub><?php echo($sv)?></sub><?php echo("(0.975) = ".$inv);?><br><?php 
      if(Max($t,-$t)>=$inv):?> 
      |T| &ge; t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypotézu &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub>  = &mu;<sub>2</sub>  &nbsp;&nbsp;zamítneme <?php 
        else: ?> 
      |T| < t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypotézu &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub>  = &mu;<sub>2</sub> 	 &nbsp;&nbsp;nezamítneme<?php 
    endif;
  endif;?>

<br><br>
<?php
echo'<a href="dvouv.php?m=',$n,'& n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> dvouvýběrový t-test (Studentův) </a>';
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




