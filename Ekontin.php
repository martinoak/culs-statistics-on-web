<html>
<head>
<?php 

$a = $_GET['a'];
$ir = $_GET['ir'];
$is = $_GET['is'];
$x = $_GET['x'];

function sum($xvar)
  {$s =0; $mez =count($xvar);
  for ($k =0;$k <$mez;$k++): $s =$s + $xvar[$k]; endfor;
  return $s;} 

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
function otev(){vzor = window.open("Ekonting.png","vzor","width=850, height=350");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Contingency tables (crosstab):</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="EcelekTEST.php">list of tests</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>formulas</a></td>
<td><a OnMouseOver=zav()>(close)</a></td>
</tr></table>


<?php 
switch($a):

case 0: ?>

<form method=get> test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="yes"> 
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1: ?> 
<form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> range &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="yes"> 
    <br> četnosti in classes: (enter integers at least 5) <br>
<table>
<tr>
<td>  </td><?php for ($i =0; $i <$is; $i++):?> <td><?php echo($i+1);?></td><?php endfor;?><td></td></tr>
<?php for ($i =0; $i <$ir; $i++):?>
<tr>
<td><?php echo($i+1);?></td><?php for ($j =0; $j <$is; $j++):?>
   <td><input type=double name="x[]" size=1 value="<?echo($x[$is*$i+$j]);?>"></td>
<?php endfor;?><td><?php echo($np[$i]);?></td>
</tr>
<?php endfor;?>
<tr><td></td>
<?php for ($i =0; $i <$is; $i++):?> <td><?php echo($nq[$i]);?></td><?php endfor;?>
<td><?php echo($nn);?></td></tr>
</table>
    
<br> null hypothesis &nbsp;&nbsp; H<sub>0</sub>:  varibales are independent <br> 
    <input type=submit value="perform the test"> 
    <input type=hidden name=a value=2>
    </form>
<?php break;

case 2: ?>
<form method=get>  test level &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="yes"> 
<?php
for ($i =0; $i <$ir; $i++):
          for ($k =0; $k <$is; $k++): $cet[$i][$k]=$x[$is*$i+$k];
    endfor;endfor;?>
<?php for ($i =0; $i <$is; $i++):
          for ($k =0; $k <$ir; $k++): $tec[$i][$k]=$cet[$k][$i];
    endfor;endfor;?>
<?php for ($i =0; $i <$ir; $i++):
          $np[$i]=sum($cet[$i]); 
endfor;
 for ($i =0; $i <$is; $i++):
$nq[$i]=sum($tec[$i]);
    endfor;
$nn=0;
for ($i =0; $i <$ir; $i++):
$nn=$nn+$np[$i];
endfor;
?>
    <br> četnosti in classes:  (enter integers at least 5) <br>
<table>
<tr>
<td>  </td><?php for ($i =0; $i <$is; $i++):?> <td><?php echo($i+1);?></td><?php endfor;?><td></td></tr>
<?php for ($i =0; $i <$ir; $i++):?>
<tr>
<td><?php echo($i+1);?></td><?php for ($j =0; $j <$is; $j++):?>
   <td><input type=double name="x[]" size=1 value="<?echo($x[$is*$i+$j]);?>"></td>
<?php endfor;?><td><?php echo($np[$i]);?></td>
</tr>
<?php endfor;?>
<tr><td></td>
<?php for ($i =0; $i <$is; $i++):?> <td><?php echo($nq[$i]);?></td><?php endfor;?>
<td><?php echo($nn);?></td></tr>
</table>    


<br> null nypothesis &nbsp;&nbsp; H<sub>0</sub>:  variables are independent <br> 

    <input type=submit value="perform the test"> 
    <input type=hidden name=a value=2>
    </form>

<?php
$ch=0; 
for ($i =0; $i <$ir; $i++):
          for ($j =0; $j <$is; $j++): 
     $ch=$ch + pow($tec[$j][$i],2)/$np[$i]/$nq[$j]; 
    endfor;endfor;
$chi=$nn*$ch-$nn;
$chiz=zaokr($chi,2);
$df=($ir-1)*($is-1);
$chinv=invchi3($df);
?>
<br>
&chi;&sup2; = <?php echo($chiz);?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   &chi;&sup2;<sub><?php echo($df);?></sub>(0.95) =  <?php echo($chinv);?>  


    <br>
  <?php 
    if($chi>=$chinv): ?> 
      &chi;&sup2;  &ge; &chi;&sup2;<sub><?php echo($df)?></sub><?php echo("(0.95)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; variables are independent   
         &nbsp;&nbsp; is rejected
    <?php 
    else: ?> 
      &chi;&sup2;  < &chi;&sup2;<sub><?php echo($df)?></sub><?php echo("(0.95)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypothesis &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; variables are independent  
        &nbsp;&nbsp; is not rejected
    <?php 
    endif; ?>



    <form>
    <input type=submit value="new entry"> 
    <input type=hidden name=a value=0>
    </form>

<?php break;
endswitch;?>

</body>
</html>


 