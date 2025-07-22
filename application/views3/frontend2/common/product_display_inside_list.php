
<div class="card" >
	<?php echo form_open("",array("id"=>"form1","name"=>"form1","class"=>"form1"));?>
	<h6><?php echo $pr["category_name"];?></h6>
	<a href="<?php echo base_url('product/'.$pr["slug_name"].'.html');?>"><img src="<?php echo $pr["image"];?>" alt="product" class="img-fluid"></a>
	<h5><?php echo $pr["name"];?></h5>
	<div class="mb-2">
		<select name="variant_id" class="form-control">
			<?php foreach($pr["options"] as $opt){?>
			<option value="<?php echo $opt["id"];?>"><?php echo $opt["option_name"];?> - Rs. <?php echo $opt["price"];?></option>
			<?php } ?>
			<!-- <option>500 g - Rs. 40.00</option>
			<option>1 kg - Rs. 80.00</option> -->
		</select>
	</div>
	<?php foreach($pr["options"] as $opt){
		if($opt["id"]==$pr["selected_option_id"]){?>
	<ul>
		<li ><?php echo $opt["old_price"];?></li>
		<li >₹<?php echo $opt["price"];?></li>
		<li > <?php
		if($opt["old_price"]>0 && $opt["price"]>0){
			echo ($opt["old_price"]-$opt["price"])*100/$opt["old_price"];
		}?>
		% off</li>
	</ul>
		<?php } ?>
	<?php } ?>
	<div class="add-bag">
		<!-- <a href="#"><i class="ti-heart"></i></a> -->
		<!-- <a href="#" class="add_to_cart_btn" >ADD TO BAG</a> -->
		<button  class="add_to_cart_btn" type="submit"  >ADD TO BAG</button>
		<input type="hidden" name="product_id" value="<?php echo $pr["product_id"];?>">
		<input type="hidden" name="qty" value="1">
	</div>
	<?php echo form_close(); ?>
</div>
