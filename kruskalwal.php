<html>
<head>
<?php

$a = $_GET['a'];
$in = $_GET['in'];
$n = $_GET['n'];
$x = $_GET['x'];

function sum($xvar)
  {$s =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k]; endfor;
  return $s;} 

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


function invchi3($sv)
  {$chi=FOpen("chi3.txt",r);
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
function otev(){vzor = window.open("kruskalw.png","vzor","width=750, height=450");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Kruskalův - Wallisův test:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>

<?php 
switch($a):

case 0: ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp;
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <input type=hidden name=a value=1>
    </form>
<?php 
$ca=0; 
break;

case 1: 
if($in<3||$in>10||!(round($in)==$in)):    ?>  
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <input type=hidden name=a value=1>
  </form>
  <?php echo("<font color=red>nezadali jste celé číslo mezi 3 a 10, opravte</font>");
else:?>
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <br> rozsah jednotlivých tříd &nbsp;&nbsp; n<sub>1</sub>, ..., n<sub><?php echo($in);?></sub>:&nbsp;
    <?php for ($i =0; $i <$in; $i++): ?>
    <input type=integer name="n[]" size=1 value="<?echo($n[$i]);?>">
    <?php endfor; ?>
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <input type=hidden name=a value=2>
  </form>
<?php
endif; 
break;

case 2:
     $va=2;$vb=10;$vc=0;
     for ($i =0; $i <$in; $i++): 
          $va=min($va,$n[$i]); $vb=max($vc,$n[$i]); $vc=$vc +($n[$i]-round($n[$i]));
     endfor; 
 
if($in<3||$in>10||!(round($in)==$in)):
?> 
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <input type=hidden name=a value=1>
  </form>
  <?php echo("<font color=red>nezadali jste celé číslo mezi 3 a 10, opravte</font>");
elseif($va < 2||$vb > 10||!$vc == 0):?>
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <br> rozsah jednotlivých tříd &nbsp;&nbsp; n<sub>1</sub>, ..., n<sub><?php echo($in);?></sub>:&nbsp;
    <?php for ($i =0; $i <$in; $i++): ?>
    <input type=integer name="n[]" size=1 value="<?echo($n[$i]);?>">
    <?php endfor; ?>
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <input type=hidden name=a value=2>
  </form>
<?php 

echo("<font color=red>nezadali jste celá čísla mezi 2 a 10, opravte</font>");
else: ?>
  <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> počet tříd &nbsp;&nbsp; r: &nbsp;
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano"> 
     &nbsp; (zadejte číslo od 3 do 10)
    <br> rozsah jednotlivých tříd &nbsp;&nbsp; n<sub>1</sub>, ..., n<sub><?php echo($in);?></sub>:&nbsp;
    <?php for ($i =0; $i <$in; $i++): ?>
    <input type=double name="n[]" size=1 value="<?echo($n[$i]);?>">
    <?php endfor; $s[0]=0;
    for ($i =0; $i <$in; $i++): $s[$i]=$s[$i-1]+$n[$i]; endfor;?>    
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <br> náhodné výběry z r = <?php echo($in) ?> spojitých rozdělení    
    <?php for ($i =0; $i <$in; $i++):?>
    <br>X<sub><?php echo(($i+1));?>,1</sub>,...,X<sub><?php echo(($i+1));?>,<?php echo($n[$i]);?></sub>:&nbsp;
    <?php for ($k =0; $k <$n[$i]; $k++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$s[$i-1]+$k]);?>">
    <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$in; $i++):
          for ($k =$s[$i-1]; $k <$s[$i]; $k++): $ind[$i][$k]=1;
    endfor;endfor;?>
    nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  všechna rozdělení jsou stejná 
    <br>
    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=3>
    </form>
<?php 
endif;
break;

case 3: 
     $va=2;$vb=10;$vc=0;
     for ($i =0; $i <$in; $i++): 
          $va=min($va,$n[$i]); $vb=max($vc,$n[$i]); $vc=$vc +($n[$i]-round($n[$i]));
     endfor; 

if($in<3||$in>10||!(round($in)==$in)):
?> 
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <input type=hidden name=a value=1>
  </form>
  <?php echo("<font color=red>nezadali jste celé číslo mezi 3 a 10, opravte</font>");


elseif($va < 2||$vb > 10||!$vc == 0):?>
    <form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte číslo od 3 do 10)
    <br> rozsah jednotlivých tříd &nbsp;&nbsp; n<sub>1</sub>, ..., n<sub><?php echo($in);?></sub>:&nbsp;
    <?php for ($i =0; $i <$in; $i++): ?>
    <input type=integer name="n[]" size=1 value="<?echo($n[$i]);?>">
    <?php endfor;?>
    <input type=submit value="ano"> 
    <input type=hidden name=a value=2>
  </form>
<?php 
echo("<font color=red>nezadali jste celá čísla mezi 2 a 10, opravte</font>");
else:
?>
<form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> počet tříd &nbsp;&nbsp; r: &nbsp;
    <input type=integer size=1 name=in value="<?echo($in);?>"> 
    <input type=submit value="ano">  &nbsp; (zadejte čísla od 3 do 10)
    <br> rozsah jednotlivých tříd &nbsp;&nbsp; n<sub>1</sub>, ..., n<sub><?php echo($in);?></sub>:&nbsp;  
    <?php for ($i =0; $i <$in; $i++): ?>
    <input type=double name="n[]" size=1 value="<?echo($n[$i]);?>">
    <?php endfor; $s[0]=0;
    for ($i =0; $i <$in; $i++): $s[$i]=$s[$i-1]+$n[$i]; endfor;?>      
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <br> náhodné výběry z r = <?php echo($in) ?> spojitých rozdělení  
 
      <?php for ($i =0; $i <$in; $i++):?>
  <br>X<sub><?php echo(($i+1));?>,1</sub>,...,X<sub><?php echo(($i+1));?>,<?php echo($n[$i]);?></sub>:&nbsp;<?php for ($k =0; $k <$n[$i]; $k++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$s[$i-1]+$k]);?>">
    <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$in; $i++):
          for ($k =0; $k <$s[$in-1]; $k++): 
    if($s[$i-1]<=$k && $k<$s[$i]): $ind[$i][$k]=1; else: $ind[$i][$k]=0; endif;
    endfor;endfor;?>
  nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: všechna rozdělení jsou stejná
    <br>  
    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=3>
    </form>
<br> 

    <?php 
$p=razeni($x);
for ($i =0; $i <$in; $i++):
for ($k =0; $k <$s[$in-1]; $k++):
 $pp[$i][$k]=$p[$k]*$ind[$i][$k];
    endfor;endfor;
for ($i =0; $i <$in; $i++):
$tt[$i]=sum($pp[$i]); 
    endfor;
for ($i =0; $i <$in; $i++):  $st = $st + $tt[$i]*$tt[$i]/$n[$i]; endfor;  
$nn=$s[($in-1)];
$q=12/$nn/($nn+1)*$st-3*($nn+1); $zq=zaokr($q,4); 
$chinv=invchi3(($in-1));
?>

      <?php for ($i =0; $i <$in; $i++):?>
   <table>
    <tr><td>X<sub><?php echo(($i+1));?>,k</sub>:&nbsp;&nbsp;&nbsp;</td>
        <?php for($k=0;$k< $n[$i];$k++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($x[$s[$i-1]+$k]);?></td> <?php endfor; ?> 
    <td></td></tr> 
    <tr><td><font color=green> pořadí:</font>&nbsp;&nbsp;&nbsp;</td> 
        <?php for($k=0;$k<$n[$i];$k++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$p[$s[$i-1]+$k]."</font>");?></td> <?php endfor; ?> 
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; T<sub><?php echo(($i+1)); ?></sub> = <?php echo($tt[$i]);?></td></tr>
  </table>
    <?php endfor;?>
    
<br> 
Q = <?php echo($zq);?> &nbsp;&nbsp;&nbsp;&nbsp;
&chi;&sup2;<sub><?php echo(($in-1));?></sub>(0.95) = <?php echo($chinv);?>
    <br>



  <?php 
    if($q >=$chinv): ?> 


      Q &ge;  &chi;&sup2;<sub><?php echo(($in-1));?></sub>(0.95)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 

       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; stejné rozdělení  
         &nbsp;&nbsp;zamítneme



    <?php 
    else: ?> 

      Q <  &chi;&sup2;<sub><?php echo(($in-1));?></sub>(0.95)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
       hypotézu &nbsp;&nbsp; H<sub>0</sub> :  stejné rozdělení  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>

<br><br>
<?php
echo'<a href="anova.php?in=',$in,'&';
for ($i =0; $i <$in; $i++):  
echo'n%5B%5D=',$n[$i],'&'; $sss[$i+1]=$sss[$i]+$n[$i];
endfor;
for ($j=0; $j < $in; $j++):
for ($k =0; $k < $n[$j]; $k++): 
echo' x%5B%5D=',$x[$sss[$j]+$k],'&';
endfor;endfor;
echo'a=2"> Jednoduché třídění (ANOVA) </a>';
?>



    <form>
    <input type=submit value="nové zadání"> 
    <input type=hidden name=a value=0>
    </form>
<?php 
endif;
break;
endswitch;?>

</body>
</html>





