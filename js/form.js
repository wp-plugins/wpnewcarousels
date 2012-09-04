/**
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 * @since: 1.0
 */

function addSlides(em){
	var formname=em.name;
	var count=document.forms[formname].elements["numberofslideadd"].value;
	jQuery(document).ready(function() {
		jQuery.ajax({
			type:"POST",  
			url: blogpath+"includes/DisplaySlides.php",  
			data:"pcount="+count,
			success: function(data){
				var curdata=document.getElementById("ajaxslide").innerHTML;
				document.getElementById("ajaxslide").innerHTML=curdata+data;
			}
		});
	});
	return false;
}