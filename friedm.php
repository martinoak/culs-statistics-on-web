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
function otev(){vzor = window.open("friedman.png","vzor","width=600, height=350");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Friedmanův test:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php 
switch($a):

case 0: ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1:  



if($ir<2||$is<2||$ir>10||$is>10||!(round($ir)==$ir)||!(round($is)==$is)): ?>
    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste požadovaná celá čísla, opravte</font>"); break;

else: ?>


    <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10) 
  <br> náhodné výběry ze spojitých rozdělení &nbsp;


    <?php for ($i =0; $i <$ir; $i++):?>
    <br>
    X<sub><?php echo(($i+1)."1");?></sub>,...,X<sub><?php echo(($i+1).",".$is);?></sub>:
      <?php for ($j =0; $j <$is; $j++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$is*$i+$j]);?>">
     <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($j =0; $j <$is; $j++):
              $xx[$i][$j]=$x[$is*$i+$j];
    endfor;endfor;?>
    <br> 
    nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  
           rozdělení řádků jsou stejná
    <br>

    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=2>
    </form>
<?php 
endif; 
break;

case 2: 

if($ir<2||$is<2||$ir>10||$is>10||!(round($ir)==$ir)||!(round($is)==$is)): ?>
   <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10) 
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>nezadali jste požadovaná celá čísla, opravte</font>"); break;

else: ?>

   <form method=get>  hladina testu &nbsp;&nbsp; &alpha; = 0,05 
    <br> počet tříd &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="ano"> &nbsp; (zadejte čísla od 2 do 10) 
  <br> náhodné výběry ze spojitých rozdělení &nbsp;


    <?php for ($i =0; $i <$ir; $i++):?>
    <br>
    X<sub><?php echo(($i+1)."1");?></sub>,...,X<sub><?php echo(($i+1).",".$is);?></sub>:
      <?php for ($j =0; $j <$is; $j++): ?>
       <input type=double name="x[]" size=1 value="<?echo($x[$is*$i+$j]);?>">
     <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($j =0; $j <$is; $j++):
              $xx[$i][$j]=$x[$is*$i+$j];
    endfor;endfor;?>
    <br> 
    nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  
           rozdělení řádků jsou stejná
    <br>

 <input type=submit value="proveďte test">     
 <input type=hidden name=a value=2>
    </form>


    <?php 
for ($i =0; $i <$is; $i++):
for ($j =0; $j <$ir; $j++):
 $pom[$j]=$xx[$j][$i]; 
    endfor;
 $por=razeni($pom); 
for ($j =0; $j <$ir; $j++):
 $pp[$j][$i]=$por[$j]; 
    endfor; 
endfor;?>


    <?php 
for ($i =0; $i <$ir; $i++):
   for ($j =0; $j <$is; $j++):
       $sou[$j]=$pp[$i][$j]; 
       endfor;
   $t=sum($sou); $tt[$i]=$t; 
endfor;
for ($i =0; $i <$ir; $i++):  $st = $st + $tt[$i]*$tt[$i]; endfor;  
$q=12/$ir/$is/($ir+1)*$st-3*$is*($ir+1); $zq=zaokr($q,4); 
$chinv=invchi3(($ir-1));
?>




      <?php for ($i =0; $i <$ir; $i++):?>
   <table>
    <tr><td>X<sub><?php echo(($i+1));?>,k</sub>:&nbsp;&nbsp;&nbsp;</td>
        <?php for($k=0;$k< $is;$k++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo($xx[$i][$k]);?></td> <?php endfor; ?> 
    <td></td></tr> 
    <tr><td><font color=green> pořadí:</font>&nbsp;&nbsp;&nbsp;</td> 
        <?php for($k=0;$k<$is;$k++): ?>
        <td>&nbsp;&nbsp;&nbsp;<?php echo("<font color=green>".$pp[$i][$k]."</font>");?></td> <?php endfor; ?> 
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; T<sub><?php echo(($i+1)); ?></sub> = <?php echo($tt[$i]);?></td></tr>
  </table>
    <?php endfor;?>
    




<br> 
Q = <?php echo($zq);?> &nbsp;&nbsp;&nbsp;&nbsp;
&chi;&sup2;<sub><?php echo(($ir-1));?></sub>(0.95) = <?php echo($chinv);?>
    <br>



  <?php 
    if($q >=$chinv): ?> 


      Q &ge;  &chi;&sup2;<sub><?php echo(($ir-1));?></sub>(0.95)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 

       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; stejné rozdělení  
         &nbsp;&nbsp;zamítneme



    <?php 
    else: ?> 

      Q <  &chi;&sup2;<sub><?php echo(($ir-1));?></sub>(0.95)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
       hypotézu &nbsp;&nbsp; H<sub>0</sub> :  stejné rozdělení  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>


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







 