<html>
<head>
<?php 

$a = $_GET['a'] ?? null;
$x = $_GET['x'] ?? null;
$n = $_GET['n'] ?? null;
$aa = $_GET['aa'] ?? null;


function razeni1($x)
  {$n=count($x);
  for ($i =0; $i <$n; $i++): 
   if($x[$i]==0): $p[$i]=0;
   else: 
     $p[$i]=0.5;
     for ($k =0; $k <$n; $k++): 
      if($x[$k]==0):     
      elseif($x[$k]<$x[$i]):$p[$i]=$p[$i]+1;	            
      elseif($x[$k]==$x[$i]):$p[$i]=$p[$i]+0.5;
      endif; 
     endfor;
   endif;
  endfor;
 return $p;}

function wiljed($sv)
  {$wil=FOpen("wiljed1.txt", "r");
  $stav=($sv-6)*5;
  FSeek($wil,$stav);
  $inv=FRead($wil,3);
  FClose($wil);
  return $inv;}  

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>

<script>
function otev(){vzor = window.open("Ewiljedn.png","vzor","width=650, height=250");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Wilcoxon signed-rank test:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 6 to 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<6||$n>30||!(round($n)==$n)): ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="yes"> &nbsp; (enter an integer from 6 to 30)
  <input type=hidden name=a value=1>
</form>

<?php echo("<font color=red> you did not enter an integer between 6 and 30, correct </font>"); break;

else:?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n: &nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 6 to 30)
    <br> random sample from continuous distribution &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp;
    H<sub>0</sub>: the distribution is symmentric around <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>
  <?php break;
endif;

case 2: 

if($n<6||$n>30||!(round($n)==$n)): ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n: &nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 6 to 30)
    <input type=hidden name=a value=1>
  </form>
  <?php echo("<font color=red> you did not enter an integer between 6 and 30, correct </font>"); break;

else:?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; n:&nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter an integer from 6 to 30)
    <br> random sample from continuous distribution &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1  value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: the distribution is symmetric around  <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>
 <br><?php

  $n=count($x);
  for ($i =0; $i <$n; $i++): $x[$i]=$x[$i]-$aa;endfor;
  $no=$n;
  for ($i =0; $i <$n; $i++): 
    if ($x[$i]==0): $kl[$i]=0; $zp[$i]=0;$no=$no-1;
    elseif ($x[$i]>0):  $kl[$i]=1; $zp[$i]=0; 
    else:  $kl[$i]=0; $zp[$i]=1;
    endif;
  endfor;
  for ($i =0; $i <$n; $i++):
    $absx[$i]=max($x[$i],-$x[$i]); 
  endfor;
  $pe=razeni1($absx);
  $inv=wiljed($no);?>

  <table>
    <tr><td>differences:&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($x[$i]);?></td>
        <?php endfor; ?> 
    </tr> 
    <tr><td><font color=green> rank:</font>&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php 
           if($pe[$i]==0):echo("<font color=green>-</font>");
           else:echo("<font color=green>".$pe[$i]."</font>");
           endif;?></td>
        <?php endfor; ?> 
    </tr>
  </table>

  <br> <?php
  for ($i =0; $i <$n; $i++): $skl=$skl+$kl[$i]*$pe[$i]; $szp=$szp+$zp[$i]*$pe[$i]; endfor;
  $min=min($skl,$szp);


  if($no<6):?> <br><font color=red> a number of non-zero differences is too small, this test cannot be used </font>

    <form>
      <input type=submit value="new entry">
      <input type=hidden name=a value=0>
    </form> 

<?php

  else: ?>

    S<sup>+</sup> = <?php echo($skl); ?>&nbsp;&nbsp;&nbsp;&nbsp; 
    S<sup>-</sup> = <?php echo($szp); ?>&nbsp;&nbsp;&nbsp;&nbsp;
    min(S<sup>+</sup>,S<sup>-</sup>) = <?php echo($min);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    k<sub><?php echo($no)?></sub><?php echo(" = ".$inv);?> <br> <?php 

    if($min<$inv):?> 
         min(S<sup>+</sup>,S<sup>-</sup>)    &le; k<sub><?php echo($no)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         hypothesis &nbsp;&nbsp; H<sub>0</sub>: the distribution is symmetric around   <?php echo($aa);?> &nbsp;&nbsp; is rejected <?php 
    else: ?>  
         min(S<sup>+</sup>,S<sup>-</sup>)    > k<sub><?php echo($no)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         hypothesis &nbsp;&nbsp; H<sub>0</sub>: the distribution is symmetric around   <?php echo($aa);?> &nbsp;&nbsp; is not rejected <?php 
    endif; ?>

<br><br>
<?php
echo'<a href="Ejednov.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample t-test two-tailed </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="Ejednovjedn.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample t-test one-tailed </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="EjednovS.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> one-sample test for variance </a>';
?>

    <form>
      <input type=submit value="new entry">
      <input type=hidden name=a value=0>
    </form> 
<?php 
  endif;
endif;
default:
endswitch;?>
</body>
</html>













