/*
 * @author: Arjun Jain ( http://www.arjunjain.info ) 
 * @license: GNU GENERAL PUBLIC LICENSE Version 3
 *
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

function saveSlides(em){
	var formname=em.name;
	var headerlength=document.getElementsByName("BackgroundImageURL").length;
	var carouselid=document.getElementById("carouselid").value;	
	var id={};
	var backgroundimageurl={};
	var backgroundimagelink={};
	var backgroundimagealttext={};
	var titletext={};
	for(var i=0;i<headerlength;i++){
		id["Id_"+i]=document.getElementsByName("Id")[i].value;
		backgroundimageurl["backgroundimageurl_"+i]=document.getElementsByName("BackgroundImageURL")[i].value;
		backgroundimagelink["backgroundimagelink_"+i]=document.getElementsByName("BackgroundImageLink")[i].value;
		backgroundimagealttext["backgroundimagealttext_"+i]=document.getElementsByName("BackgroundImageAltText")[i].value;
		titletext["titletext_"+i]=document.getElementsByName("TitleText")[i].value;
	}
	id_=array2json(id);
	backgroundimageurl_=array2json(backgroundimageurl);
	backgroundimagelink_=array2json(backgroundimagelink);
	backgroundimagealttext_=array2json(backgroundimagealttext);
	titletext_=array2json(titletext);
	jQuery(document).ready(function() {
		jQuery.ajax({
			type:"POST",  
			url: blogpath+"includes/InsertSlides.php",  
			data:"id="+id_+"&carouselid="+carouselid+"&backgroundimageurl="+backgroundimageurl_+"&backgroundimagelink="+backgroundimagelink_+"&backgroundimagealttext="+backgroundimagealttext_+"&titletext="+titletext_,
			success : function(data){
				alert(data);
				window.location=window.location;
			}
		});
	});
	return false;
}


function array2json(arr) {
    var parts = [];
    var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

    for(var key in arr) {
    	var value = arr[key];
        if(typeof value == "object") { //Custom handling for arrays
            if(is_list) parts.push(array2json(value)); 
            else parts[key] = array2json(value);
        } else {
            var str = "";
            if(!is_list) str = '"' + key + '":';

            //Custom handling for multiple data types
            if(typeof value == "number") str += value; //Numbers
            else if(value === false) str += 'false'; //The booleans
            else if(value === true) str += 'true';
            else str += '"' + value + '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

            parts.push(str);
        }
    }
    var json = parts.join(",");
    
    if(is_list) return '[' + json + ']';//Return numerical JSON
    return '{' + json + 
    '}';//Return associative JSON
}