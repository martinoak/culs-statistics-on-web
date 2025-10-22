<html>
<head>
<?php

$a = $_GET['a'] ?? null;
$ir = $_GET['ir'] ?? null;
$is = $_GET['is'] ?? null;
$ip = $_GET['ip'] ?? null;
$x = $_GET['x'] ?? null;

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
  {$chi=FOpen("chi3.txt", "r");
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
function otev(){vzor = window.open("Efriedman.png","vzor","width=600, height=350");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Friedman test:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>


<?php 
switch($a):

case 0: ?>
    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?php echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?php echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 10)
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1:  



if($ir<2||$is<2||$ir>10||$is>10||!(round($ir)==$ir)||!(round($is)==$is)): ?>
    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?php echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?php echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 10)
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red>you did not enter required numbers, correct </font>"); break;

else: ?>


    <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?php echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?php echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 10) 
  <br> random samples from continuous distributions &nbsp;


    <?php for ($i =0; $i <$ir; $i++):?>
    <br>
    X<sub><?php echo(($i+1)."1");?></sub>,...,X<sub><?php echo(($i+1).",".$is);?></sub>:
      <?php for ($j =0; $j <$is; $j++): ?>
       <input type=double name="x[]" size=1 value="<?php echo($x[$is*$i+$j]);?>">
     <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($j =0; $j <$is; $j++):
              $xx[$i][$j]=$x[$is*$i+$j];
    endfor;endfor;?>
    <br> 
    null hypothesis &nbsp;&nbsp; H<sub>0</sub>:  
           distribution of rows are the same
    <br>

    <input type=submit value="perform the test"> 
    <input type=hidden name=a value=2>
    </form>
<?php 
endif; 
break;

case 2: 

if($ir<2||$is<2||$ir>10||$is>10||!(round($ir)==$ir)||!(round($is)==$is)): ?>
   <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?php echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?php echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 10) 
    <input type=hidden name=a value=1>
  </form>

  <?php echo("<font color=red> you did not enter required numbers, correct</font>"); break;

else: ?>

   <form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
    <br> number of categories &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?php echo($ir);?>">  
    &nbsp;&nbsp;&nbsp;&nbsp; m: &nbsp;
    <input type=integer size=1 name=is value="<?php echo($is);?>"> 
    &nbsp;&nbsp;&nbsp;&nbsp; 
    <input type=submit value="yes"> &nbsp; (enter integers from 2 to 10) 
  <br> random samples from continuous distributions &nbsp;


    <?php for ($i =0; $i <$ir; $i++):?>
    <br>
    X<sub><?php echo(($i+1)."1");?></sub>,...,X<sub><?php echo(($i+1).",".$is);?></sub>:
      <?php for ($j =0; $j <$is; $j++): ?>
       <input type=double name="x[]" size=1 value="<?php echo($x[$is*$i+$j]);?>">
     <?php endfor;endfor;?>
    <br>
    <?php for ($i =0; $i <$ir; $i++):
          for ($j =0; $j <$is; $j++):
              $xx[$i][$j]=$x[$is*$i+$j];
    endfor;endfor;?>
    <br> 
    null hypothesis &nbsp;&nbsp; H<sub>0</sub>:  
           distributions of rows are the same
    <br>

 <input type=submit value="perform the test">     
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
    <tr><td><font color=green> order:</font>&nbsp;&nbsp;&nbsp;</td> 
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

       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; the distributions are the same  
         &nbsp;&nbsp; is rejected



    <?php 
    else: ?> 

      Q <  &chi;&sup2;<sub><?php echo(($ir-1));?></sub>(0.95)
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
       hypothesis &nbsp;&nbsp; H<sub>0</sub> :  the distributions are the same  
        &nbsp;&nbsp; is not rejected
    <?php 
    endif; ?>


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







 