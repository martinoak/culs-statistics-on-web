<html>
<head>
<?php

$a = $_GET['a'];
$ir = $_GET['ir'];
$is = $_GET['is'];
$ip = $_GET['ip'];
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
function otev(){vzor = window.open("anovadvou.png","vzor","width=950, height=700");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Dvojné třídění (ANOVA) bez interakcí:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php 
switch($a):

case 0: ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; počet tříd B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; rozsah jednotlivých tříd &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (zadejte číslo od 1 do 10)
    <input type=submit value="ano">
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1:  



if($ir<2||$is<2||$ip<1||$ir>10||$is>10||$ip>10||!(round($ir)==$ir)||!(round($is)==$is)||!(round($ip)==$ip)): ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; počet tříd B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; rozsah jednotlivých tříd &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (zadejte číslo od 1 do 10)
    <input type=submit value="ano">
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste požadovaná celá čísla, opravte</font>"); break;

else: ?>


    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; počet tříd B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; rozsah jednotlivých tříd &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (zadejte číslo od 1 do 10)
    <input type=submit value="ano">
  <br> náhodné výběry z &nbsp;
<table><tr>
N(&mu;<sub>1,1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo("1,".$is);?></sub>, &sigma;&sup2;)
</tr><tr>..............................................</tr>
<tr>
N(&mu;<sub><?php echo($ir.",1");?></sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($ir.",".$is);?></sub>, &sigma;&sup2;)
</tr></table>
&nbsp;&nbsp;&nbsp;
<table><tr>
 &mu;<sub>1,1</sub> = &mu; + &alpha;<sub>1</sub>+ &beta;<sub>1</sub>,..., 
                   &mu;<sub><?php echo("1,".$is);?></sub>= &mu;+ &alpha;<sub>1</sub>+ &beta;<sub><?php echo($is);?></sub>
</tr><tr> ...............................................................</tr>
 &mu;<sub><?php echo($ir.",1") ?></sub> = &mu; + &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub>1</sub>,..., 
                   &mu;<sub><?php echo($ir.",".$is);?></sub>= &mu;+ &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub><?php echo($is);?></sub>
</tr></table>  
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0 , &nbsp;&nbsp;&nbsp;&Sigma; &beta;<sub>k</sub> = 0
    <?php for ($i =0; $i <$ir; $i++):?>
    <br><?php for ($k =0; $k <$is; $k++): ?>
    X<sub><?php echo(($i+1).",".($k+1).",1");?></sub>,...,X<sub><?php echo(($i+1).",".($k+1).",".$ip);?></sub>:
      <?php for ($j =0; $j <$ip; $j++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$is*$ip*$i+$k*$ip+$j]);?>">
    <?php endfor;?> &nbsp;&nbsp;&nbsp;&nbsp; <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($k =0; $k <$is; $k++): 
          for ($j =0; $j <$ip; $j++):
              $xx[$i][$k][$j]=$x[$is*$ip*$i+$k*$ip+$j];
    endfor;endfor;endfor;?>
    <br> 

 
    nulové hypotézy &nbsp;&nbsp; H<sub>0</sub>: &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir);?></sub> = 0
    <br>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
           H<sub>0</sub>: &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is);?></sub> = 0
    <br>

    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=2>
    </form>
<?php 
endif; 
break;

case 2: 

if($ir<2||$is<2||$ip<1||$ir>10||$is>10||$ip>10||!(round($ir)==$ir)||!(round($is)==$is)||!(round($ip)==$ip)): ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; počet tříd B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; rozsah jednotlivých tříd &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (zadejte číslo od 1 do 10)
    <input type=submit value="ano">
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste požadovaná celá čísla, opravte</font>"); break;

else: ?>

    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; počet tříd B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; rozsah jednotlivých tříd &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (zadejte číslo od 1 do 10)
    <input type=submit value="ano">
  <br> náhodné výběry z &nbsp;
<table><tr>
N(&mu;<sub>1,1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo("1,".$is);?></sub>, &sigma;&sup2;)
</tr><tr>..............................................</tr>
<tr>
N(&mu;<sub><?php echo($ir.",1");?></sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($ir.",".$is);?></sub>, &sigma;&sup2;)
</tr></table>
&nbsp;&nbsp;&nbsp;
<table><tr>
 &mu;<sub>1,1</sub> = &mu; + &alpha;<sub>1</sub>+ &beta;<sub>1</sub>,..., 
                   &mu;<sub><?php echo("1,".$is);?></sub>= &mu;+ &alpha;<sub>1</sub>+ &beta;<sub><?php echo($is);?></sub>
</tr><tr> ...............................................................</tr>
 &mu;<sub><?php echo($ir.",1") ?></sub> = &mu; + &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub>1</sub>,..., 
                   &mu;<sub><?php echo($ir.",".$is);?></sub>= &mu;+ &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub><?php echo($is);?></sub>
</tr></table>  
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0 , &nbsp;&nbsp;&nbsp;&Sigma; &beta;<sub>k</sub> = 0
    <?php for ($i =0; $i <$ir; $i++):?>
    <br><?php for ($k =0; $k <$is; $k++): ?>
    X<sub><?php echo(($i+1).",".($k+1).",1");?></sub>,...,X<sub><?php echo(($i+1).",".($k+1).",".$ip);?></sub>:
      <?php for ($j =0; $j <$ip; $j++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$is*$ip*$i+$k*$ip+$j]);?>">
    <?php endfor;?> &nbsp;&nbsp;&nbsp;&nbsp; <?php endfor;endfor;?>
    <br>

   
    nulové hypotézy &nbsp;&nbsp; H<sub>0</sub>: &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir);?></sub> = 0
    <br>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
         H<sub>0</sub>: &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is);?></sub> = 0
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($k =0; $k <$is; $k++): 
          for ($j =0; $j <$ip; $j++):
              $xx[$i][$k][$j]=$x[$is*$ip*$i+$k*$ip+$j];
    endfor;endfor;endfor;?>
    <br> 
 <input type=submit value="proveďte test">     
 <input type=hidden name=a value=2>
    </form>


    <?php 
$nnn=$ir*$is*$ip;
for ($i =0; $i <$ir; $i++):
for ($j =0; $j <$is; $j++):
 $mx[$i][$j]=sum($xx[$i][$j])/$ip; $zmx[$i][$j]=zaokr($mx[$i][$j],4);
    endfor;endfor;
for ($i =0; $i <$ir; $i++):
 $mma[$i]=sum($mx[$i])/$is; $zmma[$i]=zaokr($mma[$i],4);
    endfor; 
for ($i =0; $i <$is; $i++):
for ($j =0; $j <$ir; $j++):
$mmx[$i][$j]=$mx[$j][$i];
    endfor;endfor;
for ($j =0; $j <$is; $j++):
 $mmb[$j]=sum($mmx[$j])/$ir; $zmmb[$j]=zaokr($mmb[$j],4);
    endfor;
$mmm=sum($x)/$nnn; $zmmm=zaokr($mmm,4);
$st=sctv($x,$mmm); $zst=zaokr($st,4);
$sa=$is*$ip*sctv($mma,$mmm); $zsa=zaokr($sa,4);
$sb=$ir*$ip*sctv($mmb,$mmm); $zsb=zaokr($sb,4);
$se=$st-$sa-$sb; $zse=zaokr($se,4);
$ft=$nnn-1;
$fa=$ir-1;
$fb=$is-1;
$fe=$nnn-$ir-$is+1;
$ss=$se/$fe;  $zss=zaokr($ss,4);
?>
    <span style="text-decoration: overline">X</span>  = <?php echo($zmmm);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
    <?php for ($i =0; $i <$ir; $i++): ?>
      <span style="text-decoration: overline">X</span><sup>A</sup><sub><?php echo(($i+1)); ?></sub> = <?php echo($zmma[$i]);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    <?php endfor; ?>
    <?php for ($i =0; $i <$is; $i++): ?>
      <span style="text-decoration: overline">X</span><sup>B</sup><sub><?php echo(($i+1)); ?></sub> = <?php echo($zmmb[$i]);?> &nbsp;&nbsp;&nbsp;&nbsp; 
    <?php endfor; ?>

<table><tr>
  <?php for ($i =0; $i <$ir; $i++):?>
<tr>
<?php for ($j =0; $j <$is; $j++):?>
<td><span style="text-decoration: overline">X</span><sub><?php echo(($i+1).",".($j+1));?></sub>  = <?php echo($zmx[$i][$j]);?>&nbsp;&nbsp;&nbsp;</td>
<?php endfor;?>
</tr>
<?php endfor;?>
</table> 

    <br> 
    S<sub>A</sub> = <?php echo($zsa);?> &nbsp;&nbsp;&nbsp; 
    f<sub>A</sub> = <?php echo($fa);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    S<sub>B</sub> = <?php echo($zsb);?> &nbsp;&nbsp;&nbsp; 
    f<sub>B</sub> = <?php echo($fb);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 S<sub>e</sub> = <?php echo($zse);?> &nbsp;&nbsp;&nbsp; 
 f<sub>e</sub> = <?php echo($fe);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 s &sup2; = <?php echo($zss);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
S<sub>T</sub> = <?php echo($zst);?> &nbsp;&nbsp;&nbsp; 
 f<sub>T</sub> = <?php echo($ft);?>


<?php  if ($zss==0):?> 
    <br> <font color=red> reziduální součet čtverců se rovná 0, tento test nelze užít </font>
  <?php 
  else:  

$ffa=$sa/$fa/$ss; 
$faz=zaokr($ffa,3);
$finva=invf($fa,$fe); 
for ($i =0; $i <$ir; $i++):
for ($k =$i+1; $k <$ir; $k++):
 $roza[$i][$k]=max(($mma[$i]-$mma[$k]),($mma[$k]-$mma[$i])); $zroza[$i][$k]=zaokr($roza[$i][$k],2);  
 endfor;endfor;
 $shefa=sqrt(2*$fa/$is/$ip*$ss*$finva); $zshefa=zaokr($shefa,2);
$ffb=$sb/$fb/$ss; 
$fbz=zaokr($ffb,3);
$finvb=invf($fb,$fe); 
for ($i =0; $i <$is; $i++):
for ($k =$i+1; $k <$is; $k++):
$rozb[$i][$k]=max(($mmb[$i]-$mmb[$k]),($mmb[$k]-$mmb[$i])); $zrozb[$i][$k]=zaokr($rozb[$i][$k],2);
endfor;endfor;
 $shefb=sqrt(2*$fb/$ir/$ip*$ss*$finvb); $zshefb=zaokr($shefb,2);
?>
    
<br> 
F<sub>A</sub> = <?php echo($faz);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fa.",".$fe)?></sub> <?php echo("(0.95) = ".$finva);?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
F<sub>B</sub> = <?php echo($fbz);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fb.",".$fe)?></sub> <?php echo("(0.95) = ".$finvb);?>
    <br>
  <?php 
    if($ffa>=$finva): ?> 
      F<sub>A</sub> &ge;   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir); ?></sub> = 0  
         &nbsp;&nbsp;zamítneme
<br>&nbsp;&nbsp;&nbsp;&nbsp; post hoc test (Sheffého metoda):&nbsp;&nbsp;&nbsp;&nbsp; 
<br>    
<?php for ($i =0; $i <$ir; $i++):
  for ($k =$i+1; $k <$ir; $k++): 
if($roza[$i][$k] > $shefa):?>
&nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub><sup>A</sup>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub><sup>A</sup>|= 
<?php echo($zroza[$i][$k]." > ".$zshefa); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; je rozdíl 
<?php else: ?>
&nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub><sup>A</sup>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub><sup>A</sup>|= 
<?php echo($zroza[$i][$k]." &le; ".$zshefa); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; není rozdíl 
<?php endif;?>
<br>
<?php    endfor;endfor;?>
    <?php 
    else: ?> 
      F<sub>A</sub> <   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir); ?></sub> = 0  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>
    <br>
  <?php 
    if($ffb>=$finvb): ?> 
      F<sub>B</sub> &ge;   F<sub><?php echo($fb.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;       

hypotézu &nbsp;&nbsp; H<sub>0</sub> : &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is); ?></sub> = 0  
         &nbsp;&nbsp;zamítneme

<br>&nbsp;&nbsp;&nbsp;&nbsp; post hoc test (Sheffého metoda):&nbsp;&nbsp;&nbsp;&nbsp; 
<br>    
<?php for ($i =0; $i <$is; $i++):
  for ($k =$i+1; $k <$is; $k++): 
if($rozb[$i][$k] > $shefb):?>
&nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub><sup>B</sup>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub><sup>B</sup>|= 
<?php echo($zrozb[$i][$k]." > ".$zshefb); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; je rozdíl 
<?php else: ?>
&nbsp;&nbsp;&nbsp;&nbsp;|<span style="text-decoration: overline">X</span><sub><?php echo($i+1);?></sub><sup>B</sup>-<span style="text-decoration: overline">X</span><sub><?php echo($k+1);?></sub><sup>B</sup>|= 
<?php echo($zrozb[$i][$k]." &le;  ".$zshefb); ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; není rozdíl 
<?php endif;?>
<br>
<?php    endfor;endfor;?>

    <?php 
    else: ?> 
      F<sub>B</sub> <   F<sub><?php echo($fb.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is); ?></sub> = 0  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>

<?php   endif; 
endif;
?> 

<br><br>
<?php
echo'<a href="anovadvojinter.php?ir=',$ir,'&  is=',$is,'& ip=',$ip,'&';
for ($i =0; $i <$ir; $i++): 
for ($k =0; $k <$is; $k++): 
for ($j =0; $j <$ip; $j++): 
echo'x%5B%5D=',$x[($is)*($ip)*($i)+($ip)*($k)+$j],'&';
endfor;endfor;endfor;
echo'a=1"> Dvojné třídění (ANOVA) s interakcemi </a>';
?>

    <form>
    <input type=submit value="nové zadání"> 
    <input type=hidden name=a value=0>
    </form>
<?php break;
endswitch;?>

</body>
</html>



 