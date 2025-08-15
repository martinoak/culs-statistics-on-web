<?php 
header ("Content-Type:image/png"); 
$graf=ImageCreate(100,150); 
$black=ImageColorAllocate ($graf, 0, 0, 0); 
ImagePng($graf); 
ImageDestroy($graf); 
?> 
 



 