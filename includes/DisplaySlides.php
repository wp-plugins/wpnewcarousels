<?php
/**
 * @author :  Arjun Jain  < http://www.arjunjain.info >
 * @license:  GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */
require_once '../../../../wp-load.php';
require_once 'ManageCarousel.php';
$mc=new ManageCarousel();
$count=$_POST['pcount'];
$data="<table class='table1'>";
for ($i=0;$i<$count;$i++)
 	$data .= '<tr class="top1"><td colspan="2"><b>Slide:</b></td></tr>'.$mc->getSlide();
echo $data."</table>";
?>