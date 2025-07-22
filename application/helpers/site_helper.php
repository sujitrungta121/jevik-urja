<?php 
function main_menu_elements($array,$browser){
	//print_r($array);
?>
<?php if(!empty($array["child_data"])){?>
	<li><a href="<?php echo base_url("category/".$array["slug_name"]);?>.html"  class="has-submenu"><?php echo $array["name"];?>
<?php if($browser=="mobile"){?>
	<i class="fa fa-angle-down"></i>
<?php } ?>
</a>
	     <ul class="dropdown">
	     	<?php foreach($array["child_data"] as $ch_data){
				main_menu_elements($ch_data,$browser);
			  } 
		?>
	     </ul>
     </li>
<?php }else{?>
	<li><a href="<?php echo base_url("category/".$array["slug_name"]);?>.html">
		<?php echo $array["name"]; ?>	
		</a></li>
<?php } 
}


function get_url_query_string(){
	$queryString = $_SERVER['QUERY_STRING'];
	return $queryString;
}

 function seoString($string){
        //Lower case everything
        $string = strtolower(trim($string));
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", " ", $string);
        //$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}



?>