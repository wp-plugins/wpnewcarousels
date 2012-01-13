<?php
/*
Plugin Name: WPNewCarousels
Plugin URI: http://wordpress.org/extend/plugins/wpnewcarousels/
Description: Provide functionality to create carousel that can be inserted to any page.
Author: Arjun Jain
Author URI: http://www.arjunjain.info
Version: 1.0
*/

require_once 'includes/ManageCarousel.php';

add_action('admin_menu', 'WPNewCarousels');

function WPNewCarousels() {
	add_menu_page('WPNewCarousels - Create carousel','WPNewCarousels', 'administrator', 'wp-new-carousel', 'AdminWPNewCarousels');
	add_submenu_page( 'wp-new-carousel','wp-new-carousel-option','Settings', 'administrator', 'wp-new-carousel-option', 'AdminWPNewCarouselsOption' );
}

function AdminWPNewCarousels(){	
	$mcObject=new ManageCarousel();
	if(isset($_POST['submitCarouselId'])){
		echo $mcObject->DisplayCarouselOptions($_POST['selCarouselId']);
		echo $mcObject->DisplayCarouselSlides($_POST['selCarouselId']);
	}
	else 
		echo $mcObject->DisplayCarouselOptions();
	
}

/*
 *  Admin section WPNewCarousel settings page
 * 
 */
function AdminWPNewCarouselsOption(){
	$data ='<div  style="width:90%;font-size:12px;background-color:#ECECEC; margin:10px 0px 10px 0px;padding:5px 0px 5px 10px;">'
		  .'<h3 align="left">WPNewCarousels</h3>';
	echo $data;
	echo '<table border="0"><tr><td style="width:50%;">';
	
	$mcObject=new ManageCarousel();
	if(isset($_POST['txtAddCarousel'])){
		$error=$mcObject->CheckError(trim($_POST['txtCarouselName']),trim($_POST['txtCarouselWidth']),trim($_POST['txtCarouselHeight']));
		if($error=="false"){
			$error="New Carousel Added";
			$mcObject->InsertNewCarousel(trim($_POST['txtCarouselName']),trim($_POST['txtCarouselWidth']),trim($_POST['txtCarouselHeight']));
			echo $mcObject->DisplayInsert($error);
		}
		else 
			echo $mcObject->DisplayInsert($error,trim($_POST['txtCarouselName']),trim($_POST['txtCarouselWidth']),trim($_POST['txtCarouselHeight']));	
		echo $mcObject->DisplayDeleteCarousel();
		echo $mcObject->DisplayActivateCarousel();	
	}
	else if(isset($_POST['delCarousel'])){
		$mcObject->DeleteCarousel($_POST['carouselid'], $_POST['type']);
		echo $mcObject->DisplayInsert();
		echo $mcObject->DisplayDeleteCarousel();
		echo $mcObject->DisplayActivateCarousel();
	}
	else if(isset($_POST['actCarousel'])){
		$mcObject->ActivateCarousel($_POST['carouselid']);
		echo $mcObject->DisplayInsert();
		echo $mcObject->DisplayDeleteCarousel();
		echo $mcObject->DisplayActivateCarousel();
	}
	else{
		echo $mcObject->DisplayInsert();
		echo $mcObject->DisplayDeleteCarousel();
		echo $mcObject->DisplayActivateCarousel();
	}
	echo '</td><td style="vertical-align:top; padding:15px 20px; width:40%;"><b>How to use :</b><br/>
		<p>1. Use Shortcode :<b> [wpnewcarousel name="YOUR_CAROUSEL_NAME" height=""  width="" ] </b>to display carousel in your web page</p>
		<p>2. name is required parameter.</p>
		<p>3. height and width are the optional parameter if pass then they will replace default height and width.</p>		  
		<br /><br /><hr />
		<p><b>Developed by : <a href="http://www.arjunjain.info" target="_blank" >Arjun Jain</a></b></p></div>
		</td></tr></table>';		
}

register_activation_hook( __FILE__, "WPNewCarousels_activate" );
function WPNewCarousels_activate(){
	
	global $wpdb; 
	$mcObject=new ManageCarousel();
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
	         $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				$mcObject->CreateTable();
			}
			switch_to_blog($old_blog);
			return;
		}
		else 
			$mcObject->CreateTable();
	}
	else
		$mcObject->CreateTable();
}

/*
 * Carousel Shortcode 
 * Accept three parametes Name, Width, Height . Width and Height will replace default width and height set for carousel
 * Height and Width are the optional Parameters  
 * [wpnewcarousel name="" width="" height=""]
 *
 * @since: 1.0
 * 
 */
add_shortcode('wpnewcarousel','WPNewCarouselShortcode');
function WPNewCarouselShortcode($atts){
	extract(shortcode_atts(array(
		'name' => '',
	    'width' =>'',
		'height' =>'',
	),$atts));
	if(trim($name)=="")
		return "Please specify the carousel name";
	
	global $wpdb;
	$mc=new ManageCarousel();
	$carouseltable=$mc->GetCarouselTable();
	$carouseldatatable=$mc->GetCarouselDataTable();
	$carouselresults=$wpdb->get_results("SELECT Id,CarouselWidth,CarouselHeight FROM $carouseltable WHERE CarouselName='$name' and IsActive=1",ARRAY_A);	
	$carouselid="";
	$carouselwidth="";
	$carouselheight="";
	if(sizeof($carouselresults)==0)
		return "Please specify the correct carousel name";
	foreach ($carouselresults as $cr){
		$carouselid=$cr['Id'];
		$carouselheight=$cr['CarouselHeight'];
		$carouselwidth=$cr['CarouselWidth'];
	}
	if(trim($height)=="")
		$height=$carouselheight;
	if(trim($width)=="")
		$width=$carouselwidth;
		
	$results=$wpdb->get_results("SELECT * FROM $carouseldatatable WHERE CarouselId=$carouselid",ARRAY_A);
	$output .= "<div class='nivoSlider' style='width:".$width."px; height:".$height."px;'>";
	foreach ($results as $result){
		if($result['BackgroundImageLink']!="")
			$output .='<a href="'.$result['BackgroundImageLink'].'">';
		$output .='<img src="'.$result['BackgroundImageURL'].'"  alt="'.$result['BackgroundImageAltText'].'" title="'.$result['TitleText'].'"/></a>';
		if($result['BackgroundImageLink']!="")
			$output .='</a>';
	}
	$output .= "</div>";
	return $output;
}

/*
 * Include js and css
 * 
 */
add_action( 'wp_head', 'WPNewCarousel_Script' );
add_action( 'wp_print_styles', 'WPNewCarousel_Styles' );
function WPNewCarousel_Script() {
	wp_register_script( 'wpnewcarousel_script_jquery',
			   '/wp-includes/js/jquery/jquery.js' , false, '1.0.0' );
	
	wp_register_script( 'wpnewcarousel_script',
			   path_join( WP_PLUGIN_URL,
				      basename( dirname( __FILE__ ) ) .
				      '/js/jquery.nivo.slider.js' ) , false, '1.0.0' );
	wp_print_scripts( array( 'wpnewcarousel_script_jquery', 'wpnewcarousel_script' ) );
}
function WPNewCarousel_Styles() {
	wp_enqueue_style( 'WPNewCarousel_style',
			  path_join( WP_PLUGIN_URL,
				     basename( dirname( __FILE__ ) ) .
				     '/css/carousel.css' ));
}
?>