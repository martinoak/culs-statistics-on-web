<html>
 <head>
   <?php $a = $_GET['a']; $n = $_GET['n']; ?>
 </head>

 <body>  <?php 
   switch($a):
       case 0: ?>

           <form method=get>  
             číslo n:  
             <input type=integer size=1 name=n value="<?echo($n);?>"> 
             <input type=submit value="ano"> 
             <input type=hidden name=a value=1>
           </form>
               <?php
       break;
       case 1: ?>

           <form method=get>  
             číslo n:  
             <input type=integer size=1 name=n value="<?echo($n);?>"> 
             <input type=submit value="ano"> 
             <input type=hidden name=a value=1>
           </form>

           <?php 
                        $graf=ImageCreate(300,300);
                        $zluta=ImageColorAllocate($graf,255,255,150);
                        $cerna=ImageColorAllocate($graf,0,0,0);
                        ImageFilledEllipse($graf,$n,$n,10,10,$cerna);
                        ImageLine($graf,0,0,$n,$n,$cerna);
                        ImagePng($graf,"test.png");
                        ImageDestroy($graf);
           ?>

           n=<?php echo("$n");?>
           <br>
           <img src="test.png">
           <form>
             <input type=submit value="nové zadání">
             <input type=hidden name=a value=0>
           </form>
                           <?php 
      break; 
      default:
    endswitch;?>
 </body>
</html>




