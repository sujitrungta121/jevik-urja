<?php 
function main_menu_elements($array){
	//print_r($array);
?>
<?php if(!empty($array["child_data"])){?>
	<li class="drop">
		<a href="#"><?php echo $array["name"];?></a>

		<div class="dropdownContain">
			<div class="dropOut">
				<ul>
				<?php foreach($array["child_data"] as $ch_data){
						main_menu_elements($ch_data);
					  } 
				?>
					
				</ul>
			</div>
		</div>
	</li>
<?php }else{?>
	<li><a href="#">
		<?php echo $array["name"]; ?>	
		</a></li>
<?php } 
}
?>