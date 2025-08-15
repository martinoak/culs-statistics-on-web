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
function otev(){vzor = window.open("Eanovadvouinter.png","vzor","width=950, height=700");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Two-way ANOVA and interactions:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>

<?php 
switch($a):

case 0: ?>
    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; number of categories B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (enter integers from 2 to 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; range od categories &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (enter an integer from 2 to 10)
    <input type=submit value="yes">
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1: 

if($ir<2||$is<2||$ip<2||$ir>10||$is>10||$ip>10||!(round($ir)==$ir)||!(round($is)==$is)||!(round($ip)==$ip)): ?>
    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; number of categories B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (enter integers from 2 to 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp;  range od categories &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (enter an integer from 2 to 10)
    <input type=submit value="yes">
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter required integers, correct</font>"); break;

else: ?>

    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; number of categories B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (zadejte čísla od 2 do 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; range of categories &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>"> &nbsp; (zadejte číslo od 2 do 10)
    <input type=submit value="yes">
  <br> random samples from &nbsp;
<table><tr>
N(&mu;<sub>1,1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo("1,".$is);?></sub>, &sigma;&sup2;)
</tr><tr>..............................................</tr>
<tr>
N(&mu;<sub><?php echo($ir.",1");?></sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($ir.",".$is);?></sub>, &sigma;&sup2;)
</tr></table>
&nbsp;&nbsp;&nbsp;
<table><tr>
 &mu;<sub>1,1</sub> = &mu; + &alpha;<sub>1</sub>+ &beta;<sub>1</sub>+ &gamma;<sub>1,1</sub>,..., 
                   &mu;<sub><?php echo("1,".$is);?></sub>= &mu;+ &alpha;<sub>1</sub>+ &beta;<sub><?php echo($is);?></sub>+ &gamma;<sub><?php echo("1,".$is);?></sub>
</tr><tr> ...............................................................................</tr>
 &mu;<sub><?php echo($ir.",1") ?></sub> = &mu; + &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub>1</sub>+ &gamma;<sub><?php echo($ir.",1");?></sub>,..., 
                   &mu;<sub><?php echo($ir.",".$is);?></sub>= &mu;+ &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub><?php echo($is);?></sub>+ &gamma;<sub><?php echo($ir.",".$is);?></sub>
</tr></table>  
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0 , &nbsp;&nbsp;&nbsp;&Sigma; &beta;<sub>k</sub> = 0 , &nbsp;&nbsp;&nbsp;&Sigma; &gamma;<sub>k,l</sub> = 0
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

   
    null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir);?></sub> = 0
    <br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          H<sub>0</sub>: &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is);?></sub> = 0
    <br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          H<sub>0</sub>: &gamma;<sub>1,1</sub> = ... = &gamma;<sub><?php echo($ir.",".$is);?></sub> = 0
    <br>
    <input type=submit value="perform the test"> 
    <input type=hidden name=a value=2>
    </form>
<?php 
endif;
break;

case 2: 


if($ir<2||$is<2||$ip<2||$ir>10||$is>10||$ip>10||!(round($ir)==$ir)||!(round($is)==$is)||!(round($ip)==$ip)): ?>
    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; number of categories B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (enter integers from 2 to 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; range of categories &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (enter an integer from 2 to 10)
    <input type=submit value="yes">
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter required integers, correct</font>"); break;

else: ?>

    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories A &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; number of categories B &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> &nbsp; (enter integers from 2 to 10) ,
    &nbsp;&nbsp;&nbsp;&nbsp; range of categories &nbsp;&nbsp; p: &nbsp;
    <input type=integer size=1 name=ip value="<?echo($ip);?>">      &nbsp; (enter an integer from 2 to 10)
    <input type=submit value="yes">
  <br> random samples from &nbsp;
<table><tr>
N(&mu;<sub>1,1</sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo("1,".$is);?></sub>, &sigma;&sup2;)
</tr><tr>..............................................</tr>
<tr>
N(&mu;<sub><?php echo($ir.",1");?></sub>, &sigma;&sup2;) ,...,  N(&mu;<sub><?php echo($ir.",".$is);?></sub>, &sigma;&sup2;)
</tr></table>
&nbsp;&nbsp;&nbsp;
<table><tr>
 &mu;<sub>1,1</sub> = &mu; + &alpha;<sub>1</sub>+ &beta;<sub>1</sub>+ &gamma;<sub>1,1</sub>,..., 
                   &mu;<sub><?php echo("1,".$is);?></sub>= &mu;+ &alpha;<sub>1</sub>+ &beta;<sub><?php echo($is);?></sub>+ &gamma;<sub><?php echo("1,".$is);?></sub>
</tr><tr> ...............................................................................</tr>
 &mu;<sub><?php echo($ir.",1") ?></sub> = &mu; + &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub>1</sub>+ &gamma;<sub><?php echo($ir.",1");?></sub>,..., 
                   &mu;<sub><?php echo($ir.",".$is);?></sub>= &mu;+ &alpha;<sub><?php echo($ir);?></sub>+ &beta;<sub><?php echo($is);?></sub>+ &gamma;<sub><?php echo($ir.",".$is);?></sub>
</tr></table>   
&nbsp;&nbsp;&nbsp;&nbsp;&Sigma; &alpha;<sub>k</sub> = 0 , &nbsp;&nbsp;&nbsp;&Sigma; &beta;<sub>k</sub> = 0
    <?php for ($i =0; $i <$ir; $i++):?>
    <br><?php for ($k =0; $k <$is; $k++): ?>
    X<sub><?php echo(($i+1).",".($k+1).",1");?></sub>,...,X<sub><?php echo(($i+1).",".($k+1).",".$ip);?></sub>:
      <?php for ($j =0; $j <$ip; $j++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$is*$ip*$i+$k*$ip+$j]);?>">
    <?php endfor;?> &nbsp;&nbsp;&nbsp;&nbsp; <?php endfor;endfor;?>
    <br>

   
    null hypothesis &nbsp;&nbsp; H<sub>0</sub>: &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir);?></sub> = 0
    <br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
         H<sub>0</sub>: &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is);?></sub> = 0
    <br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
         H<sub>0</sub>: &gamma;<sub>1,1</sub> = ... = &gamma;<sub><?php echo($ir.",".$is);?></sub> = 0
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($k =0; $k <$is; $k++): 
          for ($j =0; $j <$ip; $j++):
              $xx[$i][$k][$j]=$x[$is*$ip*$i+$k*$ip+$j];
    endfor;endfor;endfor;?>
    <br> 
 <input type=submit value="perform the  test">     
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
$ft=$nnn-1;
$fa=$ir-1;
$fb=$is-1;
$se=0;
for ($i =0; $i <$ir; $i++):
for ($j =0; $j <$is; $j++):
for ($k =0; $k <$ip; $k++):
$se=$se+pow($xx[$i][$j][$k]-$mx[$i][$j],2);
endfor;endfor;endfor; $zse=zaokr($se,4);
$fe=$nnn-$ir*$is;
$ss=$se/$fe;  $zss=zaokr($ss,4);
$sab=$st-$sa-$sb-$se; $zsab=zaokr($sab,4);
$fab=($ir-1)*($is-1);
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
    S<sub>AB</sub> = <?php echo($zsab);?> &nbsp;&nbsp;&nbsp; 
    f<sub>AB</sub> = <?php echo($fab);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 S<sub>e</sub> = <?php echo($zse);?> &nbsp;&nbsp;&nbsp; 
 f<sub>e</sub> = <?php echo($fe);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
 s &sup2; = <?php echo($zss);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
S<sub>T</sub> = <?php echo($zst);?> &nbsp;&nbsp;&nbsp; 
 f<sub>T</sub> = <?php echo($ft);?>


<?php  if ($ss==0):?> 
    <br> <font color=red> residual sum of squares is equal to  0, this test cannot be used </font>
  <?php 
  else:  

$ffa=$sa/$fa/$ss; 
$faz=zaokr($ffa,3);
$finva=invf($fa,$fe); 
$ffb=$sb/$fb/$ss; 
$fbz=zaokr($ffb,3);
$finvb=invf($fb,$fe); 
$ffab=$sab/$fab/$ss; 
$fabz=zaokr($ffab,3);
$finvab=invf($fab,$fe); 
?>
    
<br> 
F<sub>A</sub> = <?php echo($faz);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fa.",".$fe)?></sub> <?php echo("(0.95) = ".$finva);?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
F<sub>B</sub> = <?php echo($fbz);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fb.",".$fe)?></sub> <?php echo("(0.95) = ".$finvb);?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
F<sub>AB</sub> = <?php echo($fabz);?> &nbsp;&nbsp;&nbsp;&nbsp;
F<sub><?php echo($fab.",".$fe)?></sub> <?php echo("(0.95) = ".$finvab);?>
    <br>
  <?php 
    if($ffa>=$finva): ?> 
      F<sub>A</sub> &ge;   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
hypothesis &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir); ?></sub> = 0  
         &nbsp;&nbsp;is rejected
    <?php 
    else: ?> 
      F<sub>A</sub> <   F<sub><?php echo($fa.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
hypothesis &nbsp;&nbsp; H<sub>0</sub> : &alpha;<sub>1</sub> = ... = &alpha;<sub><?php echo($ir); ?></sub> = 0  
        &nbsp;&nbsp;is not rejected
    <?php 
    endif; ?>
    <br>
  <?php 
    if($ffb>=$finvb): ?> 
      F<sub>B</sub> &ge;   F<sub><?php echo($fb.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is); ?></sub> = 0  
         &nbsp;&nbsp;is rejected
    <?php 
    else: ?>
      F<sub>B</sub> <   F<sub><?php echo($fb.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &beta;<sub>1</sub> = ... = &beta;<sub><?php echo($is); ?></sub> = 0  
        &nbsp;&nbsp;is not rejected
    <?php 
    endif; ?>
    <br>
  <?php 
    if($ffab>=$finvab): ?> 
      F<sub>AB</sub> &ge;   F<sub><?php echo($fab.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
hypothesis &nbsp;&nbsp; H<sub>0</sub> : &gamma;<sub>1,1</sub> = ... = &gamma;<sub><?php echo($ir.",".$is); ?></sub> = 0  
         &nbsp;&nbsp;is rejected
    <?php 
    else: ?> 
      F<sub>AB</sub> <   F<sub><?php echo($fab.",".$fe)?></sub>(0.95) 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &gamma;<sub>1,1</sub> = ... = &gamma;<sub><?php echo($ir.",".$is); ?></sub> = 0    
        &nbsp;&nbsp;is not rejected
    <?php     endif; ?>
<?php   endif; ?> 

<br><br>
<?php
echo'<a href="Eanovadvoj.php?ir=',$ir,'&  is=',$is,'& ip=',$ip,'&';
for ($i =0; $i <$ir; $i++): 
for ($k =0; $k <$is; $k++): 
for ($j =0; $j <$ip; $j++): 
echo'x%5B%5D=',$x[$is*$ip*$i+$ip*$k+$j],'&';
endfor;endfor;endfor;
echo'aa=',$aa,'& a=1"> Two-way ANOVA without interaction </a>';
?>

    <form>
    <input type=submit value="new entry"> 
    <input type=hidden name=a value=0>
    </form>
<?php 
endif;
break;
endswitch;?>

</body>
</html>



 