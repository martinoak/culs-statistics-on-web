<html>
<head>
<?php 

$a = $_GET['a'] ?? null;
$n = $_GET['n'] ?? null;
$x = $_GET['x'] ?? null;
$y = $_GET['y'] ?? null;
$c = $_GET['c'] ?? null;
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

function cov($xvar,$yvar)
  {$s1 =0; $s2=0; $sn =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s1 =$s1 + $xvar[$k];$s2 =$s2 + $yvar[$k]; 
        $sn =$sn + $xvar[$k]*$yvar[$k]; endfor;
  $co=($sn-$s1*$s2/$mez)/($mez-1);
  return $co;}

function invt1($sv)
  {$stud=FOpen("stud2.txt", "r");
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
function otev(){vzor = window.open("Ereg.png","vzor","width=800, height=550");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2> Linear regression:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get>  
  range &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes">  &nbsp; (enter an integer from 3 to 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<3||$n>30||!(round($n)==$n)): ?>

  <form method=get>  
    <br> range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 3 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter an integer between 3 and 30, correct </font>");  break;

else: ?>

  <form method=get> 
    range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes">  &nbsp; (enter an integer from 3 to 30)
    <br> explanatory variable and random variable of response variable from normal distribution &nbsp;&nbsp; 
    (x<sub>1</sub>,Y<sub>1</sub>),...,(x<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> x<sub>1</sub>,...,x<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>">
    <?php endfor;?> 
    <br> 
<table>
<tr><td>type of regression </td>
<td><input type="radio" name="c" value=1 <?php  if($c==1):?> checked <?php  endif;?> > y=ax +b </td> </tr>
<tr><td></td><td><input type="radio" name="c" value=2 <?php  if($c==2):?> checked <?php  endif;?> >  y=ax </td></tr>
</table>
given vakue x<sub>0</sub> = <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>
 
  <?php break;
endif;

case 2: 

if($n<3||$n>30||!(round($n)==$n)):  ?>
  <form method=get>  
    <br> range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 3 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter an integer between 3 and 30, correct </font>");  break;

else: ?>

  <form method=get> 
     range &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes">  &nbsp; (enter an integer from 3 to 30)
    <br>  explanatory variable and random variable of response variable from normal distribution  &nbsp;&nbsp; 
    (x<sub>1</sub>,Y<sub>1</sub>),...,(x<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> x<sub>1</sub>,...,x<sub><?php echo($n);?></sub> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?php echo($y[$i]);?>">
    <?php endfor;?> 
    <br> 
<table>
<tr><td>type of regression </td>
<td><input type="radio" name="c" value=1 <?php  if($c==1):?> checked <?php  endif;?> > y=ax +b </td> </tr>
<tr><td></td><td><input type="radio" name="c" value=2 <?php  if($c==2):?> checked <?php  endif;?> >  y=ax </td></tr>
</table>
given value x<sub>0</sub> = <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>




  <?php
  $sv=$n-2;
  $m1=mean($x); $m2=mean($y); $zm1=zaokr($m1,4);$zm2=zaokr($m2,4);
  $so1=smodch($x);$so2=smodch($y);$zso1=zaokr($so1,4);$zso2=zaokr($so2,4);
  $sxy=cov($x,$y); $zsxy=zaokr($sxy,4);
  $inv=invt1($sv);  
  ?>
  <span style="text-decoration: overline">x</span> = <?php echo($zm1);?> &nbsp;&nbsp;&nbsp;&nbsp; 
  <span style="text-decoration: overline">Y</span> = <?php echo($zm2);?> &nbsp;&nbsp;&nbsp;&nbsp;
  s<sub>x</sub>&sup2; = <?php echo($zso1);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>Y</sub>&sup2; = <?php echo($zso2);?> &nbsp;&nbsp;&nbsp;&nbsp;
  S<sub>xY</sub> = <?php echo($zsxy);?>
  
<?php if($c==1): ?> 
   <?php 
   if($so1*$so2==0):?> 
    <br><font color=red> at least one variance is equal to 0, regression cannot be used </font><?php 
   else:
      $aaa=$sxy/$so1; $bbb=$m2-$aaa*$m1; $abbb=abs($bbb);
      $r=$sxy/sqrt($so1)/sqrt($so2);
      $rrr=$r*$r;    
      $zrrr=zaokr($rrr,4);
      $zaaa=zaokr($aaa,3); 
      $zbbb=zaokr($bbb,3);
      $zabbb=zaokr($abbb,3); 
      $yo=$aaa*$aa+$bbb;
      $zyo=zaokr($yo,4);
      $sr=(1-$rrr)*$so2*($sv+1)/$sv;
      $eps=$inv*sqrt($sr)*sqrt(1/$n+($aa-$m1)*($aa-$m1)/(($n-1)*$so1));
      $zeps=zaokr($eps,4);
      $inv=invt1($sv);      
      $aeps=sqrt($sr/$so1/($n-1))*$inv;
      $beps=sqrt($sr*(1/$n + $m1/$so1/($n-1)))*$inv;
      $zaeps=zaokr($aeps,3);
      $zbeps=zaokr($beps,3);
      if($bbb>0):   $bbbb="+".$zabbb; elseif($bbb<0): $bbbb=$zbbb; else: $bbbb=""; endif; 
      ?> 
      <br> 
      equation: &nbsp;&nbsp;  Y = <?php echo($zaaa);?> x 
      <?php echo($bbbb);?>  &nbsp;&nbsp;&nbsp;&nbsp; <br> 
      confidence interval (95 %) of slope:   &nbsp;&nbsp; <?php echo($zaaa);?> &plusmn;  <?php echo($zaeps);?> <br>
      confidence interval (95 %) of intercept: &nbsp;&nbsp; <?php echo($zbbb);?> &plusmn;  <?php echo($zbeps);?> <br> 
      coefficient of determination: &nbsp;&nbsp;R&sup2= <?php echo($zrrr);?> <br>
      confidence interval (95 %) of expected value for x<sub>0</sub> = <?php echo($aa)?> : &nbsp;&nbsp;&nbsp; 
           
      <?php echo($zyo)?> &plusmn;  <?php echo($zeps);?>
  
<br>

<?php 
$Ax=30; $Bx=230; $Cy=30; $Dy=100;
$xmin=Min($x); $xmax=Max($x);$ymin=Min($y); $ymax=Max($y);
$xr1=$xmin-$Ax*($xmax-$xmin)/$Bx; $xr2=$xmax+$Ax*($xmax-$xmin)/$Bx;
$yr1=$aaa*$xr1 + $bbb;  $yr2=$aaa*$xr2 + $bbb;
$gxr1=2*$Ax; $gxr2=4*$Ax+ $Bx;
$gyr1=2*$Cy+$Dy*($ymax-$yr1)/($ymax-$ymin);
$gyr2=2*$Cy+$Dy*($ymax-$yr2)/($ymax-$ymin);
for ($i =0; $i <$n; $i++): 
$gx[$i]= 3*$Ax + ($x[$i]-$xmin)*$Bx/($xmax-$xmin); 
$gy[$i]=2*$Cy+$Dy*($ymax-$y[$i])/($ymax-$ymin);
endfor;
$gaa= 3*$Ax + ($aa-$xmin)*$Bx/($xmax-$xmin);
$gyo1=2*$Cy+$Dy*($ymax-($yo-$eps))/($ymax-$ymin);
$gyo2=2*$Cy+$Dy*($ymax-($yo+$eps))/($ymax-$ymin);

$graf=ImageCreate((5*$Ax+$Bx),(6*$Cy+$Dy));
$zluta=ImageColorAllocate($graf,255,255,150);
$cerna=ImageColorAllocate($graf,0,0,0);
for ($i =0; $i <$n; $i++): 
ImageRectangle($graf,$gx[$i],$gy[$i],$gx[$i]+5,$gy[$i]+5,$cerna);
endfor; 
ImageFilledEllipse($graf,$gaa,(($gyo1+$gyo2)/2),6,6,$cerna);
ImageLine($graf,0,(4*$Cy +$Dy),(5*$Ax+$Bx),(4*$Cy+$Dy),$cerna);
ImageLine($graf,$Ax,0,$Ax,(5*$Cy+$Dy),$cerna);

ImageLine($graf,$gaa,$gyo1,$gaa,$gyo2,$cerna);
ImageLine($graf,($gaa-3),$gyo1,($gaa+3),$gyo1,$cerna);
ImageLine($graf,($gaa-3),$gyo2,($gaa+3),$gyo2,$cerna);
ImageLine($graf,$gaa,(4*$Cy +$Dy-3),$gaa,(4*$Cy +$Dy+3),$cerna);
  if($zbbb<0): ImageString($graf,5,70,(5*$Cy+$Dy),"y=".$zaaa."x".$zbbb,$cerna); else: ImageString($graf,5,70,(5*$Cy+$Dy),"y=".$zaaa."x+".$zbbb,$cerna); endif;
ImageString($graf,5,250,(5*$Cy+$Dy),"R^2=".$zrrr,$cerna);
ImageSetThickness($graf,4);
ImageLine($graf,$gxr1,$gyr1,$gxr2,$gyr2,$cerna);

ImagePng($graf,"Elinreg.png");
ImageDestroy($graf);
?>

<br>
<img src="img/Elinreg.png">
<br> warning: the browser Explorer usually does not load the restored image, it is necessarry to update the web-site (on the toolbar or by key F5)
 <?php endif;?>

 <?php elseif($c==2): ?> 

   <?php 
   if($so1*$so2==0):?> 
    <br><font color=red>  at least one variance is equal to 0, regression cannot be used  </font><?php 
   else:
      $svv=$n-1; 
      $invv=invt1($svv);
      $sqx=($n-1)*$so1+$n*$m1*$m1;
      $sqy=($n-1)*$so2+$n*$m2*$m2;
      $mxy=($n-1)*$sxy+$n*$m1*$m2;
      $aaaa=$mxy/$sqx; 
      $ssr=($sqy-$aaaa*$mxy)/($n-1);
      $aaeps=sqrt($ssr/$sqx)*$invv;
      $zaaaa=zaokr($aaaa,3);
      $zaaeps=zaokr($aaeps,3); 
      $yoo=$aaaa*$aa;
      $zyoo=zaokr($yoo,4);
      ?> 
     <br> 
      equation: &nbsp;&nbsp;  Y = <?php echo($zaaaa);?> x &nbsp;&nbsp;&nbsp;&nbsp; <br> 
      slope: &nbsp;&nbsp;   <?php echo($zaaaa);?> &plusmn;  <?php echo($zaaeps);?> &nbsp;&nbsp;&nbsp;&nbsp; <br>
      expected value for x<sub>0</sub> = <?php echo($aa);?> : &nbsp;&nbsp;&nbsp;             
      <?php echo($zyoo);?> 
    <?php 
    endif;?>


 <?php else: ?> 
    <br> <font color=red> you did not enter the type of regression </font>
 <?php  endif; ?> 

<br><br>
<?php
echo'<a href="Epears.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> Pearson correlation coefficient </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="Espearman.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> Spearman correlation coefficient </a>';
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




