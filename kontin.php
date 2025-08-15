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
function otev(){vzor = window.open("konting.png","vzor","width=850, height=350");}
function zav(){if (! vzor.closed) vzor.close();}
</script>
</head>

<body bgcolor=navajowhite link=saddlebrown alink=chocolate vlink=darkgoldenrod onunload=zav()>

<table><tr align="center">
<td><br><h2>Kontingenční tabulky:</h2></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="celekTEST.php">seznam testů</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a OnMouseOver=otev()>vzorce</a></td>
<td><a OnMouseOver=zav()>(zavřít)</a></td>
</tr></table>


<?php 
switch($a):

case 0: ?>

<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="ano"> 
    <input type=hidden name=a value=1>
  </form>
<?php break;

case 1: ?> 
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="ano"> 
    <br> četnosti ve třídách: (zadejte přirozená čísla velikosti alespoň 5) <br>
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
    
<br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  znaky jsou nezávislé <br> 
    <input type=submit value="proveďte test"> 
    <input type=hidden name=a value=2>
    </form>
<?php break;

case 2: ?>
<form method=get> hladina testu &nbsp;&nbsp; &alpha; = 0,05 
  <br> rozsah &nbsp;&nbsp; r: &nbsp; 
    <input type=integer size=1 name=ir value="<?echo($ir);?>"> 
     &nbsp;&nbsp; s: &nbsp;
    <input type=integer size=1 name=is value="<?echo($is);?>"> 
    <input type=submit value="ano"> 
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
    <br> četnosti ve třídách:  (zadejte přirozená čísla velikosti alespoň 5) <br>
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


<br> nulová hypotéza &nbsp;&nbsp; H<sub>0</sub>:  znaky jsou nezávislé <br> 

    <input type=submit value="proveďte test"> 
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
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; znaky jsou nezávislé   
         &nbsp;&nbsp;zamítneme
    <?php 
    else: ?> 
      &chi;&sup2;  < &chi;&sup2;<sub><?php echo($df)?></sub><?php echo("(0.95)");?> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       hypotézu &nbsp;&nbsp; H<sub>0</sub> : &nbsp;&nbsp; znaky jsou nezávislé  
        &nbsp;&nbsp;nezamítneme
    <?php 
    endif; ?>



    <form>
    <input type=submit value="nové zadání"> 
    <input type=hidden name=a value=0>
    </form>

<?php break;
endswitch;?>

</body>
</html>


 