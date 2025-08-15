<html>
<head>
<?php 

$a = $_GET['a'];
$m = $_GET['m'];
$x = $_GET['x'];
$n = $_GET['n'];
$y = $_GET['y'];

function razeni($x)
  {$n=count($x);
  for ($i =0; $i <$n; $i++): $p[$i]=0.5;
    for ($k =0; $k <$n; $k++): 
      if($x[$k]<$x[$i]):  $p[$i]=$p[$i]+1;	            
      elseif($x[$k]==$x[$i]): $p[$i]=$p[$i]+0.5;
      endif; 
    endfor;
  endfor;
  return $p;}

function wildv($sv,$sw)
  {$wil=FOpen("wildvo1.txt",r);
  $stav=($sv-4)*110+($sw-4)*4;
  FSeek($wil,$stav);
  $inv=FRead($wil,4);
  FClose($wil);
  return $inv;}  

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>
<script>
function otev(){vzor = window.open("Ewildvou.png","vzor","width=750, height=250");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Wilcoxon sum-rank test (Mann-Whitney test):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp; 
    <input type=integer size=1 name=m value="<?echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 4 to 30)
    <input type=hidden name=a value=1>
  </form>
  <?php break;

case 1: 

if($m<4||$n<4||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)): ?>
  
  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=m value="<?echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 4 to 30)
    <input type=hidden name=a value=1>
  </form>

<?php echo("<font color=red> you did not enter integers between 4 and 30, correct </font>"); break;

else:?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp; 
    <input type=integer size=1 name=m value="<?echo($m);?>"> &nbsp;&nbsp; n: &nbsp; 
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 4 to 30)
    <br> random sample from continuous distribution &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$m; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>"> <?php endfor;?> 
    <br>  random sample from continuous distribution  &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: the ditribution of X<sub>k</sub> and Y<sub>k</sub> are the same <br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>

<?php break;
endif;

case 2: 

if($m<4||$n<4||$m>30||$n>30||!(round($m)==$m)||!(round($n)==$n)): ?>
  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp;  
    <input type=integer size=1 name=m value="<?echo($m);?>"> &nbsp;&nbsp; n:
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 4 to 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter integers between 4 and 30, correct  </font>"); break;

else: ?>

  <form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> range &nbsp;&nbsp; m: &nbsp; 
    <input type=integer size=1 name=m value="<?echo($m);?>"> &nbsp;&nbsp; n: &nbsp;
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="yes"> &nbsp; (enter integers from 4 to 30)
    <br>  random sample from continuous distribution  &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($m);?></sub>: &nbsp; 
    <?php for ($i =0; $i <$m; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br>  random sample from continuous distribution  &nbsp;&nbsp; Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>: the distribution of  X<sub>k</sub> and Y<sub>k</sub> are the same <br>
    <input type=submit value="perform the test">
    <input type=hidden name=a value=2>
  </form>
  <br> <?php
  $m=count($x);$n=count($y);$no=$m+$n;
  for ($i =0; $i <$m; $i++): $z[$i]=$x[$i]; $u[$i]=1;  $v[$i]=0; endfor;
  for ($i =0; $i <$n; $i++): $j=$i+$m;$z[$j]=$y[$i]; $u[$j]=0;  $v[$j]=1; endfor;
  $p=razeni($z);
  $inv=wildv($m,$n);
  for ($i =0; $i <$no; $i++):
   $s1=$s1+$u[$i]*$p[$i];
   $s2=$s2+$v[$i]*$p[$i]; 
  endfor;
?>

  <table>
    <tr><td>X<sub>k</sub>:&nbsp;&nbsp;&nbsp;</td> 
        <?php for($i=0;$i<=$m-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($z[$i]);?></td> <?php endfor; ?> 
    <td></td></tr> 
    <tr><td><font color=green> rank :</font>&nbsp;&nbsp;&nbsp;</td> 
        <?php for($i=0;$i<=$m-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$p[$i]."</font>");?></td> <?php endfor; ?> 
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; T<sub>1</sub> = <?php echo($s1); ?> </td></tr>
  </table>

  <table>
    <tr><td>Y<sub>k</sub>:&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=$m;$i<=$no;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($z[$i]);?></td> <?php endfor; ?> 
    <td></td></tr> 
    <tr><td><font color=green>rank :</font>&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=$m;$i<=$no;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$p[$i]."</font>");?></td> <?php endfor; ?> 
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; T<sub>2</sub> = <?php echo($s2); ?></td></tr>
  </table> <br><?php

  $u1=$m*$n+0.5*$m*($m+1)-$s1;
  $u2=$m*$n+0.5*$n*($n+1)-$s2;
  $min=min($u1,$u2);?>
  U<sub>1</sub> = <?php echo($u1); ?>&nbsp;&nbsp;&nbsp;&nbsp; 
  U<sub>2</sub> = <?php echo($u2); ?>&nbsp;&nbsp;&nbsp;&nbsp;
  min(U<sub>1</sub>,U<sub>2</sub>) = <?php echo($min);?>&nbsp;&nbsp;&nbsp;&nbsp; 
  k<sub><?php echo($m.",".$n)?></sub><?php echo(" = ".$inv);?> <br> <?php 

  if($min<$inv): ?> 
         min(U<sub>1</sub>,U<sub>2</sub>)   &le; k<sub><?php echo($m.",".$n)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         hypothesis &nbsp;&nbsp; H<sub>0</sub>: the distribution of X<sub>k</sub> and Y<sub>k</sub> are the same &nbsp;&nbsp; rejected <?php 
  else: ?> 
         min(U<sub>1</sub>,U<sub>2</sub>)    > k<sub><?php echo($m.",".$n)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         hypothesis &nbsp;&nbsp; H<sub>0</sub>: distribution X<sub>k</sub> and Y<sub>k</sub> are the same  &nbsp;&nbsp;are not rejected <?php 
  endif; ?>

<br><br>
<?php
echo'<a href="Edvouv.php?m=',$m,'& n=',$n,'&';
for ($i =0; $i <$m; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> two-sample t-test (Student) </a>';
?>
&nbsp;&nbsp;
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


















