<html>
<head>
<?php 

$a = $_GET['a'];
$n = $_GET['n'];
$x = $_GET['x'];
$y = $_GET['y'];

function razeni($x)
  {$n=count($x);
  for ($i =0; $i <$n; $i++): $p[$i]=0.5;
    for ($k =0; $k <$n; $k++): 
      if($x[$k]<$x[$i]): $p[$i]=$p[$i]+1;	            
      elseif($x[$k]==$x[$i]): $p[$i]=$p[$i]+0.5;
      endif; 
    endfor;
  endfor;
  return $p;}

function spear($sv)
  {$spea=FOpen("spea.txt",r);
  $stav=($sv-6)*8;
  FSeek($spea,$stav);
  $inv=FRead($spea,6);
  FClose($spea);
  return $inv;}  

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>
<script>
function otev(){vzor = window.open("spearman.png","vzor","width=550, height=250");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Test Spearmannova korelačního koeficientu:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano">  &nbsp; (zadejte číslo od 5 do 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<5||$n>30||!(round($n)==$n)): ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 5 do 30)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste celé číslo mezi 5 a 30, opravte</font>");  break;

else: ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 5 do 30)
    <br> náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; 
    (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé <br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>
 
  <?php break;
endif;

case 2: 

if($n<5||$n>30||!(round($n)==$n)):  ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:  &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 5 do 30)
    <input type=hidden name=a value=1>
  </form>

<?php echo("<font color=red>nezadali jste celé číslo mezi 5 a 30, opravte</font>"); break;

else: ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n: &nbsp;  
    <input type=integer size=1 name=n value="<?echo($n);?>">&nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 5 do 30)
    <br> náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; (X<sub>1</sub>,Y<sub>1</sub>),...,(X<sub><?php echo($n);?></sub>,Y<sub><?php echo($n);?></sub>) 
    <br> X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?echo($x[$i]);?>">
    <?php endfor;?> 
    <br> Y<sub>1</sub>,...,Y<sub><?php echo($n);?></sub>: &nbsp;&nbsp;&nbsp;&nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="y[]" size=1 value="<?echo($y[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  X<sub>k</sub>,  Y<sub>k</sub> jsou nezávislé <br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>
  <br> <?php
  $n=count($x);
  $p=razeni($x);
  $q=razeni($y);?>

  <table>
    <tr><td> X<sub>k</sub>:&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($x[$i]);?></td>
        <?php endfor; ?> 
    </tr> 
    <tr><td><font color=green> pořadí:</font>&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$p[$i]."</font>");?></td>
        <?php endfor; ?> 
    </tr>
  </table>

  <table>
    <tr><td> Y<sub>k</sub>:&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n-1;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($y[$i]);?></td>
        <?php endfor; ?> 
    </tr> 
    <tr><td><font color=green>pořadí:</font>&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$q[$i]."</font>");?></td>
        <?php endfor; ?> 
    </tr>
  </table>

  <br><?php
  for ($i =0; $i <$n; $i++): $s=$s+pow(($p[$i]-$q[$i]),2); endfor;
  $r=1-6*$s/$n/(pow($n,2)-1);
  $zr=zaokr($r,4);
  $inv=spear($n);?>
  r<sub>S</sub> = <?php echo($zr); ?> &nbsp;&nbsp;&nbsp;&nbsp; 
  k<sub><?php echo($n)?></sub><?php echo(" = ".$inv);?> <br> <?php 

  if(Max($r,-$r)>=$inv): ?> 
   |r<sub>S</sub>|&ge;k<sub><?php echo($n)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
hypotézu &nbsp;&nbsp; H<sub>0</sub>: X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé &nbsp;&nbsp;zamítneme <?php 
  else: ?> 
   |r<sub>S</sub>|< k<sub><?php echo($n)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
hypotézu &nbsp;&nbsp; H<sub>0</sub>: X<sub>k</sub>, Y<sub>k</sub> jsou nezávislé  &nbsp;&nbsp;nezamítneme <?php 
  endif;?>

<br><br>
<?php
echo'<a href="pears.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> Pearsonův korelační koeficient </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="linreg.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i],'&';
endfor;
for ($j =0; $j <$n; $j++): 
echo'y%5B%5D=',$y[$j],'&';
endfor;
echo 'a=1"> lineární regrese </a>';
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


















