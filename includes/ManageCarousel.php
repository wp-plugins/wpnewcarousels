<?php
/**
 * @author :  Arjun Jain  < http://www.arjunjain.info >
 * @license:  GNU GENERAL PUBLIC LICENSE Version 3
 * 
 */

class ManageCarousel{
	var $DataObject;
	var $table1;
	var $table2;
	
	function ManageCarousel(){
		global $wpdb;
		$this->DataObject=$wpdb;
		$this->table1=$this->DataObject->prefix."WPNewCarousels";
		$this->table2=$this->DataObject->prefix."WPNewCaroselsData";
	}
	
	/**
	 * 
	 * @return carousel table name 
	 */
	public function GetCarouselTable(){
		return $this->table1;
	}
	
	/**
	 * 
	 * @return carousel data table name
	 */
	public function GetCarouselDataTable(){
		return $this->table2;
	}
	
	/*
	 * @return string containg the HTML of Carosel select page
	 */
	public function DisplayCarouselOptions($id=""){
 
		$data  = '<script type="text/javascript">var blogpath="'.plugin_dir_url(dirname(__FILE__)).'";</script>'
			   	.'<script type="text/javascript" src="'.plugins_url("js/form.js",dirname(__FILE__)).'" ></script>'
			   	.'<script type="text/javascript" src="../wp-includes/js/jquery/jquery.js"></script>'
				.'<link rel="stylesheet" href="'.plugins_url("css/style.css",dirname(__FILE__)).'" type="text/css" title="ui-theme" />'
			   	.'<div style="background-color:#ECECEC; margin:10px 10px 10px 0px;padding:5px 0px 5px 10px; ">'
				.'<form name="carouselOption" action="" method="post"  >'
			    .'Select Carousel: <select name="selCarouselId" id="selCarouselId"  >'
			   	.$this->GetOptionsString("SELECT Id,CarouselName from $this->table1 WHERE IsActive=1","Id","CarouselName",$id)
			   	.'</select>'
			    .'&nbsp;&nbsp;<input type="submit" class="button" name="submitCarouselId" value="Go"></form></div>';
		return $data;
	}
	
	
	public function DisplayCarouselSlides($Id){
		$results=$this->DataObject->get_results("SELECT * FROM $this->table2 WHERE CarouselId=$Id",ARRAY_A);
		$data ='<div style="background-color:#ECECEC; margin:10px 10px 10px 0px;padding:5px 10px 5px 10px; ">'
			  
			  .'<div>
			  	<form action="" method="POST" name="displayslidesform">'
			  .'<input type="hidden" id="carouselid" name="carouselid" value="'.$Id.'">'
			  .'<table class="table1">
			  		<tbody>';
		if(sizeof($results)==0){
			$data .= '<tr><td colspan="2"><b>Slide-1:</b></td></tr>'
				  .$this->getSlide()
				  .'<tr><td colspan="2"><b>Slide-2:</b></td></tr>'
				  .$this->getSlide()
				  .'<tr><td colspan="2"><b>Slide-3:</b></td></tr>'
				  .$this->getSlide()
				  .'</tbody>
				</table>'
			   .'<span id="ajaxslide"></span>';				
			$data .='<hr /><input type="submit" class="button" style="float:left" name="saveCarousel" value="Save">
					</form>
					 or 
					<form style="display:inline;" name="addmoreslideform" action="POST" onsubmit="return addSlides(this);">
					 	<input type="text" value="1" maxlength="1" size="1" name="numberofslideadd">
						<input type="submit" class="button" name="addmoreslides" value="Add" />
					</form>
					</div>';				
		}
		else {
			foreach ($results as $result){
				$post_data=array();
				$post_data['Id']=$result['Id'];
				$post_data['BackgroundImageURL']=addslashes($result['BackgroundImageURL']);
				$post_data['BackgroundImageLink']=addslashes($result['BackgroundImageLink']);
				$post_data['BackgroundImageAltText']=addslashes($result['BackgroundImageAltText']);
				$post_data['TitleText']=$result['TitleText'];
				$data .= '<tr><td colspan="2"><b>Slide:</b></td></tr>'
					  .$this->getSlide($post_data);				
			}
			$data .='</tbody></table><span id="ajaxslide"></span>
					<hr /><input type="submit" class="button" style="float:left" name="saveCarousel" value="Save">
					</form>
					 or 
					<form style="display:inline;" name="addmoreslideform" action="POST" onsubmit="return addSlides(this);">
					 	<input type="text" value="1" maxlength="1" size="1" name="numberofslideadd">
						<input type="submit" class="button" name="addmoreslides" value="Add" />
					</form>
					</div>';
		}
		$data .="</div>";
		return $data;
	}
	
	public function getSlide($post_data=""){
		$data = "<tr>
					<td>Background image URL*:<br /><input style='width:80%;height:30px;' type='text' name='BackgroundImageURL[]' value='".@$post_data['BackgroundImageURL']."' ></td>
			  		<td>Background image Link:<br /><input type='text' name='BackgroundImageLink[]' style='width:80%;height:30px;' value='".@$post_data['BackgroundImageLink']."' ></td>
			  	</tr>
			  	<tr>
			  		<td>Background Image Alt text:<br /><input type='text' name='BackgroundImageAltText[]' style='width:80%;height:30px;' value='".@$post_data['BackgroundImageAltText']."' ></td>
			  		<td>Image title text:<br /><input type='text' name='TitleText[]' style='width:80%;height:30px;' value='".@$post_data['TitleText']."' >
			  		<input type='hidden' name='Id[]' value='".@$post_data['Id']."'></td>
			  	</tr>";
		return $data;
	}
	
	public function InsertCarouselSlides($carouselId,$BackgroundImageURL,$BackgroundImageLink,$BackgroudImageAltText,$TitleText){
		try{
			$query="INSERT INTO $this->table2(CarouselId,BackgroundImageURL,BackgroundImageLink,BackgroundImageAltText,TitleText) VALUES('$carouselId','$BackgroundImageURL','$BackgroundImageLink','$BackgroudImageAltText','$TitleText')";
			$this->DataObject->query($this->DataObject->prepare($query));		
		}catch (Exception $e){
			echo "Error: ".$e->getMessage(); 
		}
	}
	
	public function UpdateCarouselSlides($Id,$carouselId,$BackgroundImageURL,$BackgroundImageLink,$BackgroudImageAltText,$TitleText){
		try{
			$query="UPDATE $this->table2 SET BackgroundImageURL='".stripslashes($BackgroundImageURL)."',BackgroundImageLink='".stripslashes($BackgroundImageLink)."',BackgroundImageAltText='".stripslashes($BackgroudImageAltText)."',TitleText='".stripslashes($TitleText)."' WHERE CarouselId=$carouselId and Id=$Id";
			$this->DataObject->query($this->DataObject->prepare($query));		
		}catch (Exception $e){
			echo "Error: ".$e->getMessage(); 
		}
	}
	
	public function DeleteCarouselSlides($Id){
		try{
			$query="DELETE FROM $this->table2 WHERE Id=".$Id;
			$this->DataObject->query($this->DataObject->prepare($query));
		}
		catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
	}
	
	public function CheckError($carouselname,$carouselwidth,$carouselheight){
		if($carouselwidth=="" || $carouselheight=="" || $carouselname=="")
			return "Please enter required fields";
		if(preg_match ("/[^0-9]/",$carouselwidth))
			return "Please enter valid width";
		
		if(preg_match ("/[^0-9]/",$carouselheight))
			return "Please enter valid height";
		
		$value=0;
		$value=$this->DataObject->get_var("SELECT Id FROM $this->table1 WHERE CarouselName = '$carouselname'");
		if($value!=0){
			return "Carousel Name already exists";
		}
		return "false";
	}
	
	public function DisplayInsert($error="",$carouselname="",$carouselwidth="",$carouselheight=""){
		$data = '<form action="" method="post">'
			  .'<fieldset style="border:1px solid #777; margin:10px;padding:10px;"><legend>Add New Carousel:</legend>'  
			  .'<div style="color:red; font-size:10px;">'.$error.'</div>'
			  .'<table width="100%"><tr><td>Carousel Name :</td><td><input type="text" name="txtCarouselName" style="width:59%;height:28px;" value="'.$carouselname.'"></td></tr>'
			  .'<tr><td>Default Width:</td><td><input type="text" name="txtCarouselWidth" style="width:59% height:28px;" value="'.$carouselwidth.'"></td></tr>'
 			  .'<tr><td>Default Height:</td><td><input type="text" name="txtCarouselHeight" style="width:59% height:28px;" value="'.$carouselheight.'"></td></tr>'
			  .'<td colspan="2" align="center"> <input type="submit"  class="button"  name="txtAddCarousel" value="Add New Carousel"></td></tr></table>';
		return $data.'</fieldset></form>'; 	  	
	}

	public function InsertNewCarousel($carouselname,$carouselwidth,$carouselheight){
		try{
			$this->DataObject->query("INSERT INTO $this->table1(CarouselName,CarouselWidth,CarouselHeight,IsActive) VALUES('$carouselname',$carouselwidth,$carouselheight,1)");	
		}
		catch (Exception $e){
			echo "Error: ".$e->getMessage();
		}	
	}
	
	public function DisplayDeleteCarousel(){
		$data='<form action="" method="post">'
			   .'<fieldset style="border:1px solid #777;margin:10px;padding:10px;"><legend>Delete Carosuel:</legend>'  
			   .'<p>Select Carousel: <select name="carouselid" style="height:28px;width:59%;" >'
			   .$this->GetOptionsString("SELECT Id,CarouselName FROM $this->table1 WHERE IsActive=1","Id","CarouselName","")   		
			   .'</select></p>'
			   .'<p>Deactivate: <input checked="checked" type="Radio" value="DACT" name="type">&nbsp;&nbsp;&nbsp;Delete:<input type="Radio" value="DEL" name="type"> </p>'
			   .'<p><input type="submit"  class="button"  name="delCarousel" value="Go"></p></fieldset></form>';
		return $data;
	}
	
	public function DeleteCarousel($carouselId,$type){
		if($type=="DEL")
			$this->DataObject->query("DELETE FROM $this->table1 WHERE Id=$carouselId");
		else if($type=="DACT")
			$this->DataObject->query("UPDATE $this->table1 SET IsActive=0 WHERE Id=$carouselId");
	}
	
	public function DisplayActivateCarousel(){		
		$data='<form action="" method="post">'
			   .'<fieldset style="border:1px solid #777;margin:10px;padding:10px;"><legend>Activate Carosuel:</legend>'  
			   .'<p>Select Carousel: <select name="carouselid" style="height:28px;width:59%;" >'
			   .$this->GetOptionsString("SELECT Id,CarouselName FROM $this->table1 WHERE IsActive=0","Id","CarouselName","")   		
			   .'</select></p>'
			   .'<p><input type="submit" name="actCarousel"  class="button" value="Activate"></p></fieldset></form>';
		return $data;
	}
	
	public function ActivateCarousel($carouselId){
			$this->DataObject->query("UPDATE $this->table1 SET IsActive=1 WHERE Id=$carouselId");
	}
	
	public function CreateTable(){
		$sql="";
		$table1=$this->DataObject->prefix."WPNewCarousels";
		$table2=$this->DataObject->prefix."WPNewCaroselsData";
		if($this->DataObject->get_var("SHOW TABLES LIKE '{$table1}'") != $table1){
			$sql .="CREATE TABLE $table1 ("
				 ."Id INT NOT NULL AUTO_INCREMENT,"	
				 ."CarouselName VARCHAR(100) NOT NULL,"
				 ."CarouselWidth INT NOT NULL,"
				 ."CarouselHeight INT NOT NULL,"
				 ."IsActive TINYINT(2),"
				 ."PRIMARY KEY (Id))ENGINE=INNODB;";
		}
		if($this->DataObject->get_var("SHOW TABLES LIKE '{$table2}'") != $table2){	
			$sql .="CREATE TABLE $table2 ("
				 ."Id INT NOT NULL AUTO_INCREMENT,"
				 ."CarouselId INT NOT NULL,"
				 ."BackgroundImageURL varchar(255),"
				 ."BackgroundImageLink varchar(255),"
				 ."BackgroundImageAltText varchar(255),"
				 ."TitleText varchar(255),"
				 ."FOREIGN KEY (CarouselId) REFERENCES $table1(Id) ON UPDATE CASCADE ON DELETE CASCADE,"
				 ."PRIMARY KEY (Id,CarouselId))ENGINE=INNODB;";			
		}
		if ($sql != ""){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	public function GetOptionsString($query, $keyCol, $valueCol, $selectedKey){
   		$results=$this->DataObject->get_results($query, ARRAY_A);
		$optionsString = "";
   		$isArray = is_array($selectedKey);
   		foreach($results as $result)
   		{
      		if($isArray)
       		$selected = (array_search($result[$keyCol], $selectedKey) !== false)? " selected" : "";
      		else
       		$selected = ($result[$keyCol]==$selectedKey)? " selected" : "";
       		$optionsString .= "<option value='$result[$keyCol]' title='$result[$valueCol]'" . $selected . ">$result[$valueCol]</option>";
   		}	
   		return $optionsString;
	}
}