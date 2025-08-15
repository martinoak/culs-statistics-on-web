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

function sctv($xvar,$me)
  {$sc =0; $mez =count($xvar);
  for ($k =0;$k <$mez; $k++): $sc =$sc + pow(($xvar[$k]-$me),2); endfor;
  return $sc;} 


function invf($sv,$sw)
  {$fis=FOpen("fis3.txt",r);
  $stav=($sw-1)*151+($sv-1)*5;
  FSeek($fis,$stav);
  $inv=FRead($fis,4);
  FClose($fis);
  return $inv;} 

function zaokr($cislo,$des)
  {$moc=pow(10,$des);
  $vysl=round($cislo*$moc)/$moc;
  return $vysl;} 

?>

<script>
function otev(){vzor = window.open("anova.png","vzor","width=850, height=500");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Jednoduché třídění (Analýza rozptylu ANOVA):</h2></td>
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
    <br> náhodné výběry z &nbsp;N(&mu;<sub>1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($in);?></sub>, &sigma;&sup2;),   
&nbsp;&nbsp;&nbsp; &mu;<sub>1</sub> = &mu; + &alpha;<sub>1</sub>,..., &mu;<sub><?php echo($in);?></sub>= &mu;+ &alpha;<sub><?php echo($in);?></sub>,  
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0
    <?php for ($i =0; $i <$in; $i++):?>
    <br>X<sub><?php echo(($i+1));?>,1</sub>,...,X<sub><?php echo(($i+1));?>,<?php echo($n[$i]);?></sub>:&nbsp;
    <?php for ($k =0; $k <$n[$i]; $k++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$s[$i-1]+$k]);?>">
    <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$in; $i++):
          for ($k =$s[$i-1]; $k <$s[$i]; $k++): $ind[$i][$k]=1;
    endfor;endfor;?>
    nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = ... = &mu;<sub><?php echo($in);?></sub>
&nbsp;&nbsp;&nbsp; neboli &nbsp;&nbsp;&nbsp;&alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($in);?></sub> = 0
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
    <br> náhodné výběry z &nbsp;N(&mu;<sub>1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($in);?></sub>, &sigma;&sup2;),   
&nbsp;&nbsp;&nbsp; &mu;<sub>1</sub> = &mu; + &alpha;<sub>1</sub>,..., &mu;<sub><?php echo($in);?></sub>= &mu;+ &alpha;<sub><?php echo($in);?></sub>,  
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0  

  
      <?php for ($i =0; $i <$in; $i++):?>
  <br>X<sub><?php echo(($i+1));?>,1</sub>,...,X<sub><?php echo(($i+1));?>,<?php echo($n[$i]);?></sub>:&nbsp;<?php for ($k =0; $k <$n[$i]; $k++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$s[$i-1]+$k]);?>">
    <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$in; $i++):
          for ($k =0; $k <$s[$in-1]; $k++): 
    if($s[$i-1]<=$k && $k<$s[$i]): $ind[$i][$k]=1; else: $ind[$i][$k]=0; endif;
    endfor;endfor;?>
  nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>: &mu;<sub>1</sub> = ... = &mu;<sub><?php echo($in);?></sub>
&nbsp;&nbsp;&nbsp; neboli &nbsp;&nbsp;&nbsp;&alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($in);?></sub> = 0
    <br>  
    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=3>
    </form>
<br> 

    <?php 
$nnn=$s[$in-1];
for ($i =0; $i <$in; $i++):
for ($k =0; $k <$s[$in-1]; $k++):
 $mx[$i][$k]=$x[$k]*$ind[$i][$k];
    endfor;endfor;
for ($i =0; $i <$in; $i++):
 $mmx[$i]=sum($mx[$i])/$n[$i]; $zmmx[$i]=zaokr($mmx[$i],4);
    endfor;
$mmm=sum($x)/$nnn; $zmmm=zaokr($mmm,4);
$st=sctv($x,$mmm);
$sa=0;
for ($i =0; $i <$in; $i++):
 $sa=$sa+pow(($mmx[$i]-$mmm),2)*$n[$i];
    endfor;
$se=$st-$sa;
$ft=$nnn-1;
$fa=$in-1;
$fe=$nnn-$in;
$ss=$se/$fe;
$zsa=zaokr($sa,4); $zse=zaokr($se,4); $zst=zaokr($st,4);$zss=zaokr($ss,4);
?>
    <span style="text-decoration: overline">X</span>  = <?php echo($zmmm);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
    <?php for ($i =0; $i <$in; $i++):?>
      <span style="text-decoration: overline">X</span>  <sub><?php echo(($i+1)); ?></sub> = <?php echo($zmmx[$i]);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    <?php endfor;  ?>
    <br> 
 S<sub>A</sub> = <?php echo($zsa);?> &nbsp;&nbsp;&nbsp; 
 f<sub>A</sub> = <?php echo($fa);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 S<sub>e</sub> = <?php echo($zse);?> &nbsp;&nbsp;&nbsp; 
 f<sub>e</sub> = <?php echo($fe);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 s &sup2; = <?php echo($zss);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 S<sub>T</sub> = <?php echo($zst);?> &nbsp;&nbsp;&nbsp; 
 f<sub>T</sub> = <?php echo($ft);?>

<?php  if ($ss==0):?> 
    <br> <font color=red> reziduální součet čtverců se rovná 0, tento test nelze užít </font>
  <?php 
  else:  
$ff=$sa/$fa/$ss; 
$zf=zaokr($ff,3);
$finv=invf($fa,$fe); $pom=sqrt($fa*$ss*$finv);
for ($i =0; $i <$in; $i++):
for ($k =$i+1; $k <$in; $k++):
 $roz[$i][$k]=max(($mmx[$i]-$mmx[$k]),($mmx[$k]-$mmx[$i])); $zroz[$i][$k]=zaokr($roz[$i][$k],2);
 $shef[$i][$k]=sqrt(1/($n[$i])+1/($n[$k]))*$pom; $zshef[$i][$k]=zaokr($shef[$i][$k],2);
endfor;endfor;
?>
    
<br> 
F = <?php echo($zf);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fa.",".$fe)?></sub> <?php echo("(0.95) = ".$finv);?>
    <br>
  <?php 
    if($ff>=$finv): ?>
      F &ge;   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        hypotézu &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($in); ?></sub> = 0  
         &nbsp;&nbsp;zamítneme

<br> &nbsp;&nbsp;&nbsp;&nbsp; post hoc test (Sheffého metoda):&nbsp;&nbsp;&nbsp;&nbsp; 
<br>    
<?php for ($i =0; $i <$in; $i++):
  for ($k =$i+1; $k <$in; $k++): 
if($roz[$i][$k] > $shef[$i][$k]):?>
   &nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub>|= 
<?php echo($zroz[$i][$k]." > ".$zshef[$i][$k]); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; je rozdíl 
<?php else: ?>
   &nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub>|= 
<?php echo($zroz[$i][$k]." &le; ".$zshef[$i][$k]); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; není rozdíl 
<?php endif;?>
<br>
<?php    endfor;endfor;?>

    <?php 
    else: ?> 
      F <   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($in); ?></sub> = 0  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>

<?php   endif; ?> 

<br><br>
<?php
echo'<a href="kruskalwal.php?in=',$in,'&';
for ($i =0; $i <$in; $i++):  
echo'n%5B%5D=',$n[$i],'&'; $sss[$i+1]=$sss[$i]+$n[$i];
endfor;
for ($j=0; $j < $in; $j++):
for ($k =0; $k < $n[$j]; $k++): 
echo' x%5B%5D=',$x[$sss[$j]+$k],'&';
endfor;endfor;
echo'a=2"> Kruskalův - Wallisův test </a>';
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



