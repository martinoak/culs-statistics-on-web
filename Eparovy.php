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
function otev(){vzor = window.open("Eparovy.png","vzor","width=750, height=450");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Paired t-test (Student):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>


<?php switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes">  &nbsp; (enter an integer from 3 to 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<3||$n>30||!(round($n)==$n)): ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 3 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter an integer between 3 and 30, correct </font>");  break;

else: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes">  &nbsp; (enter an integer from 3 to 30)
    <br> random sample from normal distribution &nbsp;&nbsp; 
    (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> <br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>
 
  <?php break;
endif;

case 2: 

if($n<3||$n>30||!(round($n)==$n)):  ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 3 to 30)
    <input type=hidden name=a value=1>
  </form>

<?php echo("<font color=red> you did not enter an integer between 3 and 30, correct </font>"); break;

else: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n: &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>">&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 3 to 30)
    <br> random sample from normal distribution &nbsp;&nbsp; 
    (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = &mu;<sub>2</sub> <br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form><br>
<?php
     for ($i =0; $i <$n; $i++): 
    $z[$i]=$x[$i]-$y[$i];endfor; ?>

 differences &nbsp;&nbsp; Z<sub>1</sub>= X<sub>1</sub>- Y<sub>1</sub>,...,Z<sub><?php echo($n);?></sub>=X<sub><?php echo($n);?></sub>-Y<sub><?php echo($n);?></sub>:&nbsp;
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
    <br><font color=red> variance is equal to 0, this test cannot be used </font><?php 
  else:

      $t=$m/sqrt($so)*sqrt($n);    
      $zt=zaokr($t,3); ?> <br> 
      T = <?php echo($zt);?>&nbsp;&nbsp;&nbsp;&nbsp; 
      t<sub><?php echo($sv)?></sub><?php echo("(0.975) = ".$inv);?><br><?php 
      if(Max($t,-$t)>=$inv):?> 
      |T| &ge; t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub>  = &mu;<sub>2</sub>  &nbsp;&nbsp; is rejected <?php 
        else: ?> 
      |T| < t<sub><?php echo($sv)?></sub> <?php echo("(0.975)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      hypothesis &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub>  = &mu;<sub>2</sub> 	 &nbsp;&nbsp; is not rejected <?php 
    endif;
  endif;?>

<br><br>
<?php
echo'<a href="Edvouv.php?m=',$n,'& n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> two-sample t-test (Student) </a>';
?>

  <form>
    <input type=submit value="new entry">
    <input type=hidden name=a value=0>
  </form>
 <?
endif;
default:
endswitch;?>
</body>
</html>




