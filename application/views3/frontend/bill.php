<?php // print_r($order_details); exit(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Bill details | <?php echo SITE_TITLE;?></title>
<meta name="description" content="">
<meta name="author" content="">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<style type="text/css">
	body{
		margin: 0;
		padding: 0;
		font-size: 14px;
		font-family: 'Roboto', sans-serif;
	}
	.wrap{
		margin: 0 auto;
		width: 900px;
	}
</style>
</head>
<body>
	<div class="wrap" id="printArea">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr style="border:1px solid #bfbfbf; display:block;">
		  	<td width="30%" style="padding-left: 10px">
		  		<img src="<?php echo base_url();?>frontend/assets/images/logo.png" width="200px">
		  	</td>
		    <td width="40%" align="center">
		    	<h1 style="color: #f00;font-size: 26px;text-transform: uppercase;margin: 0;">Bill No: <?php echo $order_details["order_id"];?></h1>
		    	<h4 style="margin:0;">GST Number: 19AAVFG3656M1ZH</h4></td>	
		    </td>
		    <td width="30%">
		    	<h2 style="margin: 0 0 5px; font-size: 20px; color: #f00;"><?php echo $site["name"];?></h2>
		    	<p style="margin: 0; font-size: 13px;"><?php echo $site["address"];?></p>
		    </td>
		  </tr>
		</table>
		<div style="margin-top: 20px;"></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr valign="top">
		    <td width="30%">
		    	<h2 style="margin:0 0 10px">Billing To</h2>
		        <h3 style="color:#f00; margin:0;"><?php echo $order_details["name"];?></h3>
		        <p style="margin:0;"><?php echo $order_details["address"];?><br>
		        <?php echo $order_details["city"];?><br>
		        <?php echo $order_details["pin_code"];?><br>	
		        <?php echo $order_details["state_name"];?>,<?php echo $order_details["country_name"];?><br>

		        <?php echo $order_details["mobile_no"];?></p>
		    </td>
		    <td width="40%">
		    	<h2 style="margin:0 0 10px">Shipping To</h2>
		        <h3 style="color:#f00; margin:0;"><?php echo $order_details["name"];?></h3>
		        <p style="margin:0;"><?php echo $order_details["address"];?><br>
		       	<?php echo $order_details["city"];?><br>
		        <?php echo $order_details["pin_code"];?><br>	
		        <?php echo $order_details["state_name"];?>,<?php echo $order_details["country_name"];?><br>

		        <?php echo $order_details["mobile_no"];?></p>
		    </td>
		    <td width="30%">
		   	  <table width="100%" border="0" cellspacing="0" cellpadding="5" style="margin-top: 20px;">
		   	  	<tr>
		   	  		<td>Bill Date : </td>
		   	  		<td><?php echo date("d/m/Y");?></td>
		   	  	</tr>
		   	  	<tr>
		   	  		<td>Order Date : </td>
		   	  		<td><?php echo $order_details["order_date"];?></td>
		   	  	</tr>
		   	  	<tr>
		   	  		<td>Order ID : </td>
		   	  		<td><?php echo $order_details["order_id"];?></td>
		   	  	</tr>
		   	  	<?php if($order_details["delivery_person_name"]!=""){?>
		   	  	<tr>
		   	  		<td>Delivered By : </td>
		   	  		<td><?php echo $order_details["delivery_person_name"];?></td>
		   	  	</tr>
		   	  	<?php } ?>

		   	  </table>
		    </td>
		  </tr>
		</table>
		<div style="margin-top: 20px;"></div>
		<table width="100%" border="0" cellspacing="5" cellpadding="3">
		  <tr valign="top">
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px; text-align:center;">
		    	<b>#</b>
		    </td>
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px;">
		    	<b>Item</b>
		    </td>
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px; text-align:center;">
		    	<b>Unit</b>
		    </td>
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px; text-align:center;">
		    	<b>Unit  Cost</b>
		    </td>
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px; text-align:center;">
		    	<b>Quantity</b>
		    </td>
		    <td style="border-radius:5px; background:#e0e0e0; font-size:18px; padding:10px; text-align:center;">
		    	<b>Total</b>
		    </td>
		  </tr>
		  <tbody>
		  	<?php $count=0;?>
		  	<?php foreach($item_list as  $row){$count++; ?>
		  	<tr>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; padding:10px;"><?php echo $count;?></td>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; padding:10px;">
			        <p style="margin:0;"><?php echo $row["product_name"];?></p>
			    </td>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; text-align:center; padding:10px;">
			   	  <?php echo $row["variant_name"];?>
			    </td>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; text-align:center; padding:10px;">
			   	  ₹<?php echo $row["unit_price"];?>
			    </td>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; text-align:center; padding:10px;">
			   	  <?php echo $row["count"];?>
			    </td>
			    <td style="border-radius:5px; background:#f5f5f5; font-size:16px; text-align:center; padding:10px;">
			   	  ₹<?php echo $row["total_price"];?>
			    </td>
			</tr>
			<?php } ?>
		  </tbody>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="68%">
		    	<p>Payment Method: <?php echo $order_details["payment_mode_name"];?></p>
		        <p></p>
		        <h3 style="color:#1fb5ad;"><i>!!Thank you for choosing Gomart, have a Nice Day!!</i></h3>
		    </td>
		    <td width="32%" align="right">
		    	<p>INCLUSIVE OF ALL TAXES</p>
		    	<div style="padding:10px; background:#ddd; margin-bottom:5px; border-radius:5px;">
		        	Delivery Charges : ₹<?php echo $order_details["delivery_charges"];?>
		        </div>
		        <div style="padding:10px; color:#fff; background:#1fb5ad; margin-bottom:5px; border-radius:5px;">
		        	<h2 style="margin:5px 0;">Grand Total : ₹<?php echo $order_details["grand_total"];?></h2>
		        </div>
		    </td>
		  </tr>
		</table>
	</div>
	<center>
		<button onclick="PrintElem('printArea')" class="btn btn-primary">Print</button>
	</center>
<!-- 	<a href="javascript:demoFromHTML()" class="button">Create Pdf</a> -->


<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<script>
function PrintElem(elem){
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}

function demoFromHTML() {
var doc = new jsPDF('p', 'in', 'letter');
var source = $('#testcase').first();
var specialElementHandlers = {
'#bypassme': function(element, renderer) {
return true;
}
};

doc.fromHTML(
source, // HTML string or DOM elem ref.
0.5, // x coord
0.5, // y coord
{
'width': 7.5, // max width of content on PDF
'elementHandlers': specialElementHandlers
});

doc.output('dataurl');
}
</script>


</body>
</html>