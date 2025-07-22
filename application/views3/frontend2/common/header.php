<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Gomart: Online Grocery</title>
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="<?php echo base_url("frontend/assets/images/favicon.ico");?>" type="image/x-icon">
<!-- global stylesheet -->
<link href="<?php echo base_url("frontend/assets/css/bootstrap.css");?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url("frontend/assets/css/all.css");?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url("frontend/assets/css/themify-icons.css");?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url("frontend/assets/css/site-icons.css");?>" rel="stylesheet">
<link href="<?php echo base_url("frontend/assets/css/menu.css");?>" rel="stylesheet" type="text/css" />
<!-- <link href="<?php echo base_url("frontend/assets/css/jquery.mobile-menu.css");?>" rel="stylesheet"> -->

<!-- FlexSlider -->
<link rel="stylesheet" href="<?php echo base_url("frontend/assets/css/flexslider.css");?>" type="text/css" media="screen" />
<!-- owl-carousel css -->
<link rel="stylesheet" href="<?php echo base_url("frontend/assets/css/owl.carousel.min.css");?>">
<link rel="stylesheet" href="<?php echo base_url("frontend/assets/css/owl.theme.default.min.css");?>">

<!-- stylesheet main -->
<link href="<?php echo base_url("frontend/assets/css/main.css");?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url("frontend/assets/css/notifIt.min.css");?>" rel="stylesheet" type="text/css">

<!-- main javascript -->
<script src="<?php echo base_url("frontend/assets/js/jquery-2.2.4.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/popper.min.js");?>"></script>
<script src="<?php echo base_url("frontend/assets/js/bootstrap.min.js");?>"></script>
<!-- <script src="<?php echo base_url("frontend/assets/js/angular.min.js");?>"></script>
<script type="text/javascript">
	var baseUrl="<?php echo base_url();?>";
</script>
<script src="<?php echo base_url("frontend/assets/js/pages/index.js");?>"></script> -->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body ng-app="myApp" ng-controller="Ctrl">
<main>
	<div id="overlay"></div>

	<!-- header -->
	<header>
		<!-- top-header -->
		<section class="top-header">
			<div class="container">
				<div class="row">
					<div class="col-xl-12">
						<div class="t-content">
							<!-- <p>Order before <b>9 p.m</b> and get next day delivery on your selected time slot.</p> -->
							<div class="dropdown">
						        <a href="#" style="color:#fff;" class="btn-sm btn-success btn dropdown-toggle" data-toggle="dropdown">Kolkata</a>
						        <div class="dropdown-menu">
						        	<h5><i class="ti-alarm-clock"></i> Delivery Time Slots</h5>
								    <span class="dropdown-item"><b>SLOT 1:</b> 07 AM - 10 AM</span>
								    <span class="dropdown-item"><b>SLOT 2:</b> 10 AM - 01 PM</span>
								    <span class="dropdown-item"><b>SLOT 3:</b> 04 PM - 07 PM</span>
								    <span class="dropdown-item"><b>SLOT 4:</b> 07 PM - 09 PM</span>
						        </div>
						    </div>
						    <div>
						    	<ul>
						    		<li><img src="<?php echo base_url("frontend/assets/images/whatsapp.png");?>"> 9007005318</li>
						    		<li><img src="<?php echo base_url("frontend/assets/images/phone.png");?>"> 033 40707010</li>
						    		<li><img src="<?php echo base_url("frontend/assets/images/email.png");?>"> info@gomart.in</li>
						    	</ul>
						    </div>
							<p>Download the App <a href="#"><img src="<?php echo base_url();?>frontend/assets/images/playstore.png" class="img-fluid" alt="playstore"></a></p>
						</div>
					</div>
				</div>
			</div>
			<!-- Update News -->
			<section>
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="news">
								<b>Update News: </b><marquee><?php echo $additional["scrolling_text"];?></marquee>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- //End Update News -->
		</section>
		<!-- //top-header -->

		<!-- logo & category -->
		<section class="logo-main">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-xl-3 col-md-3">						
						<div class="row">
							<div class="col-sm-11 col-8"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>frontend/assets/images/logo.png" class="img-fluid" alt="gomart"></a></div>
							<div class="col-4"><a href="#" id="humbarger-icon" class="hamber"><i class="fa fa-bars"></i> </a></div>
						</div>
					</div>
					<div class="col-xl-5 col-md-5">
						<form class="search">
							<input type="text" ng-model="search_val" ng-keyup="searchProduct()" placeholder="Search Any Product..">
							<button type="submit"><i class="ti-search"></i></button>
						</form>
						<div class="ajs-instantsearch" ng-if="search_val!='' ">
							<div class="ajs-inner" >
								<div class="ajs-headline" >
			                        Showing results for {{search_val}}
			                    </div>
			                    <div class="ajs-searchentry" ng-repeat="row in searchList">
		                         <div class="ajs-se-img">
		                         <a href="{{baseUrl}}product/{{row.slug_name}}.html">
		                            <img src="{{row.image}}">
		                         </a>
		                          </div>
		                           <div class="ajs-se-title">
		                           <a href="{{baseUrl}}product/{{row.slug_name}}.html">
		                            {{row.name}}
		                           </a>
		                          </div>
		                          <div class="ajs-se-weight">
		                            {{row.selected_option.option_name}}
		                          </div>
		                          <div class="ajs-se-price">
		                            Rs. {{row.selected_option.price}}
		                          </div>
		                          <div class="ajs-se-qty">
		                            <label>Qty</label>
		                            <div class="ajs-se-qtybox">
		                              <input readonly="readonly" class="form-control input-sm" type="text" min="1" max="10" value="1" style="width:40px" id="s_quantity_178">
		                            </div>
		                          </div>
		                          <div class="ajs-se-buynow" ng-init="row.variant_id=row.selected_option.id">
		                          	
		                            <button type="button" ng-click="addToCart2('+',row)"  id="btn-send-cart-178" class="btn" style="background: #8EC640; color: #fff;">Add</button>
		                          </div>
		                        </div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-3">
						<div class="cart-main">
							<div class="item">
								<!--a data-toggle="modal" data-target="#loginmodal"><i class="fas fa-user"></i> Account</a-->
								<div class="myaccount">
									<ul>
										<?php if($this->session->userdata("name")==""){ ?>
										<li><a data-toggle="modal" data-target="#loginmodal">Login / Signup</a></li>
										<?php } ?>
										<?php if($this->session->userdata("name")!=""){ ?>
										<li class="drop">
											<a href="#"><b>Hi 
											<?php if($this->session->userdata("name")==""){
												echo "Guest";
											}else{
												echo strtoupper($this->session->userdata("name"));
											}?>
											</b> <i class="fas fa-sort-down"></i></a>
											<div class="dropdownContain">
												<div class="dropOut">
													<ul>
														<li><img src="<?php echo base_url();?>frontend/assets/images/user-ico.png" class="mr-2"> 
														<?php 
														if($this->session->userdata("name")==""){
															echo "Guest";
														}else{
															echo strtoupper($this->session->userdata("name"));
														}?></li>
														<li><a href="<?php echo base_url("Home/myOrder");?>"><i class="ti-view-list-alt"></i> My Order</a></li>
														<li><a href="<?php echo base_url("Home/my_profile");?>"><i class="ti-user"></i> My Profile</a></li>
														<li><a href="<?php echo base_url("Account/logout");?>"><i class="ti-lock"></i> Logout</a></li>
													</ul>
												</div>
											</div>
										</li>
										<?php }?>
									</ul>
								</div>
							</div>
							<div class="item">
								<a data-toggle="modal" data-target="#cartpanel">
								<img src="<?php echo base_url();?>frontend/assets/images/cart-ico.png">
								<span>{{cartDetails.length}}</span>
								</a>
							</div>
							<!-- <div class="item">
								<a>
								<img src="<?php echo base_url();?>frontend/assets/images/wish-ico.png">
								<span>10</span>
								</a>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- //logo & category -->
	</header>
	<!-- //end header -->
	

    <!-- mobile -->
    <nav class="mobile-background-nav">
        <div class="mobile-inner">
            <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
            <ul class="menu-accordion">
            	<?php foreach($category_list_recursive as $category){?>
				<?php main_menu_elements($category,"mobile"); ?>
				<?php } ?>
                <!-- <li><a href="index.html">Home</a></li>
                <li><a href="about-us.html">About Us</a></li>
                <li><a href="#"  class="has-submenu">Opportunities<i class="fa fa-angle-down"></i></a>
                 <ul class="dropdown">
                        <li><a href="#">Autopool-1 Income</a></li>
                        <li><a href="#">Autopool-2 Income</a></li>
                        <li><a href="#">Autopool-3 Income</a></li>
                        <li><a href="#">Royalty Income</a></li>
                        <li><a href="#">Club Membership Income</a></li>
                    </ul>
                
                </li>
                <li><a href="benefits.html" class="has-submenu">Benefits<i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown">
                        <li><a href="#">Utility Services</a></li>
                        <li><a href="#">Insurance</a></li>
                    </ul>
                </li>
                <li><a href="regional-experts.html">Regional Experts</a></li>
                <li><a href="faq.html">FAQ</a></li>
                <li><a href="contact-us.html">Contact</a></li>
                <li><a href="subscriber/index.html">Login</a></li> -->
           </ul>
        </div>
    </nav>

    <!-- destop -->
    <div class="main-menu-area">
        <div class="container">
            <div class="menu-logo">
                <nav id="easy-menu">
                    <ul class="menu-list">
                    	<?php foreach($category_list_recursive as $category){?>
						<?php main_menu_elements($category,"desktop"); ?>
						<?php } ?>
                        <!-- <li><a href="index.html">Home</a></li>
                        <li><a href="about-us.html">About Us</a></li>
                        <li><a href="opportunities.html"  class="has-submenu">Opportunities</a>
	                        <ul class="dropdown">
	                            <li><a href="#">Autopool-1 Income</a></li>
		                        <li><a href="#">Autopool-2 Income</a></li>
		                        <li><a href="#">Autopool-3 Income</a></li>
		                        <li><a href="#">Royalty Income</a></li>
		                        <li><a href="#">Club Membership Income</a></li>
	                        </ul>                        
                        </li>
                        <li><a href="benefits.html" class="has-submenu">Benefits</a>
                            <ul class="dropdown">
                                <li><a href="utility-services.html">Utility Services</a></li>
                                <li><a href="insurance.html">Insurance</a></li>
                            </ul>
                        </li>
                        <li><a href="regional-experts.html">Regional Experts</a></li>
                        <li><a href="contact-us.html">Contact</a></li> -->
                   </ul>
               </nav><!--#easy-menu-->
            </div>
        </div>
    </div>