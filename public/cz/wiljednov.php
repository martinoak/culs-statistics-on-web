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
function otev(){vzor = window.open("wiljedn.png","vzor","width=650, height=250");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Wilcoxonův jednovýběrový test:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>

<?php switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 6 do 30)
  <input type=hidden name=a value=1>
</form>

<?php break;

case 1: 

if($n<6||$n>30||!(round($n)==$n)): ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; n: &nbsp; 
  <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
  <input type=submit value="ano"> &nbsp; (zadejte číslo od 6 do 30)
  <input type=hidden name=a value=1>
</form>

<?php echo("<font color=red>nezadali jste celé číslo mezi 6 a 30, opravte</font>"); break;

else:?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n: &nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 6 do 30)
    <br> náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1 value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp;
    H<sub>0</sub>: rozdělení symetrické kolem <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="proveďte test">
    <input type=hidden name=a value=2>
  </form>
  <?php break;
endif;

case 2: 

if($n<6||$n>30||!(round($n)==$n)): ?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n: &nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 6 do 30)
    <input type=hidden name=a value=1>
  </form>
  <?php echo("<font color=red>nezadali jste celé číslo mezi 6 a 30, opravte</font>"); break;

else:?>

  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> rozsah &nbsp;&nbsp; n:&nbsp; 
    <input type=integer size=1 name=n value="<?php echo($n);?>"> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type=submit value="ano"> &nbsp; (zadejte číslo od 6 do 30)
    <br> náhodný výběr ze spojitého rozdělení &nbsp;&nbsp; X<sub>1</sub>,...,X<sub><?php echo($n);?></sub>: &nbsp;
    <?php for ($i =0; $i <$n; $i++): ?>
    <input type=double name="x[]" size=1  value="<?php echo($x[$i]);?>">
    <?php endfor;?> 
    <br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: rozdělení symetrické kolem  <input type=double size=1 name=aa value="<?php echo($aa);?>"><br>
    <input type=submit value="proveďte test">
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
    <tr><td>rozdíly:&nbsp;&nbsp;&nbsp;</td>
        <?php for($i=0;$i<=$n;$i++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($x[$i]);?></td>
        <?php endfor; ?> 
    </tr> 
    <tr><td><font color=green>pořadí:</font>&nbsp;&nbsp;&nbsp;</td>
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


  if($no<6):?> <br><font color=red>počet nenulových rozdílů je příliš nízký, tento test nelze užít</font>

    <form>
      <input type=submit value="nové zadání">
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
         hypotézu &nbsp;&nbsp; H<sub>0</sub>: rozdělení symetrické kolem  <?php echo($aa);?> &nbsp;&nbsp;zamítneme<?php 
    else: ?>  
         min(S<sup>+</sup>,S<sup>-</sup>)    > k<sub><?php echo($no)?></sub>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         hypotézu &nbsp;&nbsp; H<sub>0</sub>: rozdělení symetrické kolem  <?php echo($aa);?> &nbsp;&nbsp;nezamítneme <?php 
    endif; ?>

<br><br>
<?php
echo'<a href="jednov.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový t-test oboustranný </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="jednovjedn.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový t-test jednostranný </a>';
?>
&nbsp;&nbsp;
<?php
echo'<a href="jednovS.php?n=',$n,'&';
for ($i =0; $i <$n; $i++): 
echo'x%5B%5D=',$x[$i]+$aa,'&';
endfor;
echo'aa=',$aa,'& a=1"> jednovýběrový test pro rozptyl </a>';
?>

    <form>
      <input type=submit value="nové zadání">
      <input type=hidden name=a value=0>
    </form> 
<?php 
  endif;
endif;
default:
endswitch;?>
</body>
</html>


















