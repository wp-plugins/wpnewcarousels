<?php 
/*
 * @author :  Arjun Jain  < http://www.arjunjain.info >
 * @license:  GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */

require_once 'ManageCarousel.php';
require_once '../../../../wp-load.php';
$mc=new ManageCarousel();
$carouselid=$_POST['carouselid'];
$Ids=json_decode(stripcslashes($_POST['id']),TRUE);
$backgroundimageurl=json_decode(stripcslashes($_POST['backgroundimageurl']),TRUE);
$backgroundimagelink=json_decode(stripcslashes($_POST['backgroundimagelink']),TRUE);
$backgroundimagealttext=json_decode(stripcslashes($_POST['backgroundimagealttext']),TRUE);
$titletext=json_decode(stripcslashes($_POST['titletext']),TRUE);
for($i=0;$i<sizeof($backgroundimageurl);$i++){
	if((trim($backgroundimageurl["backgroundimageurl_".$i])=="") && (trim($backgroundimagelink["backgroundimagelink_".$i])=="") && (trim($backgroundimagealttext["backgroundimagealttext_".$i])=="") && (trim($titletext["titletext_".$i])==""))
	{}
	else{
		if($Ids["Id_".$i] == ""){	
			$mc->InsertCarouselSlides($carouselid,$backgroundimageurl["backgroundimageurl_".$i],$backgroundimagelink["backgroundimagelink_".$i],$backgroundimagealttext["backgroundimagealttext_".$i],$titletext["titletext_".$i]);			
		}
		else{
			$mc->UpdateCarouselSlides($Ids["Id_".$i],$carouselid,$backgroundimageurl["backgroundimageurl_".$i],$backgroundimagelink["backgroundimagelink_".$i],$backgroundimagealttext["backgroundimagealttext_".$i],$titletext["titletext_".$i]);
		}
	}
}
echo "Carousel data updated";
?>