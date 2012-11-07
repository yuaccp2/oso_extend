<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<base href="http://www.pathgadget.com/"> -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$product_info['products_name'] ? $product_info['products_name'] : $product_info['products_head_title_tag']?></title>
<meta name="Keywords" content="<?=$product_info['products_head_keywords_tag']?>" />
<meta name="Description" content="<?=$product_info['products_head_desc_tag']?>" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="bgbox">
<div id="allbox">

<!--headerbox start-->
<div id="headerbox">
	<div id="headerleftbox"><a href="index.html"><img src="Dimages/logo.gif" alt="#" /></a></div>
    <div id="headermidbox"><a href="Contact_Us.html">Contact Us</a>|<a href="Shipping_Method.html">Shipping</a>|<a href="Payment.html">Payment</a>|  <a href="About_Us.html">About Us</a>| <a href="http://www.facebook.com/path.gadget" target="_blank"><img src="Dimages/facebook-like-button.gif" alt="find us on facebook pathgadget"/></a></div>
    <div id="headerrightbox"><a href="http://www.pathgadget.com/viewCart.php" action="_blank"><img src="Dimages/cart1.gif" alt="view your shopping cart"/></a></div>
</div>
<!--headerbox end-->
<!--menubox start-->
<div id="menubox">	<ul>
    	<li><a  style="padding-left:35px;" href="index.html">Home</a></li>
        <li><a href="spy-watch-page-1.html">Spy Watch</a></li>
        <li><a href="spy-pen-page-1.html">Spy Pen</a></li>
        <li><a href="spy-gadgets-page-1.html">Spy Gadgets</a></li>
	<li><a href="spy-clock-page-1.html">Spy Clock</a></li>
    <li><a href="cell-phone-jammer-page-1.html">Cell Phone Jammer</a></li>
    </ul>
</div>
<!--menubox end-->
<!--right start-->
<div id="rightbox">

<div class="rightbox01">
    <div id="pro_largeimg"><img src="images/large/<?=$product_info['products_model']?>-l.JPG" width="400" height="400"/></div>
    <div id="pro_text">
        <h1><?=$product_info['products_name']?></h1>        
		<img src="Dimages/write-a-review.gif" alt="write a review" id="review-image" /> &nbsp; 
		<a id="review-a" href="#disqus_comment">Write a Review</a> </br></br>
		<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.pathgadget.com%2F<?=urlencode($product_info['seo_name'])?>.html&amp;send=false&amp;layout=standard&amp;width=293&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:293px; height:35px;" allowTransparency="true"></iframe></br> 
		<g:plusone size="medium" annotation="inline"></g:plusone>
        <dl>
            <dt>Item No. :</dt><dd><?=$product_info['products_model']?></dd>
            <dt>Weight:</dt><dd><?=unit_conversion($product_info['products_weight'])?> pounds</dd>
            <dt>Retail price:</dt><dd><b><?=$retail_price?></b></dd>
            <dt>Our Price: </dt><dd><strong><?=$out_price?></strong></dd>
        </dl>
		<form action="./cart/shoppingCart.php" method="post" target="_blank">
		  <input name="PHPSESSID" type="hidden" value="">
		  <input name="step" type="hidden" value="addToCart">
		  <input name="item_code" type="hidden" value="<?=$product_info['products_model']?>">
		  <input name="item_price" type="hidden" value="<?=$new_price?>">
		  <input name="item_qty" type="hidden" value="1">
		  <input name="item_name" type="hidden" value="<?=$product_info['products_name']?>">
			<p class="p1"><input type="image" src="Dimages/cart.gif" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Contact_Us.html" target="_blank">Got a Question</a></p>
			<p class="p1"><img src="Dimages/m1.gif" /><br /><img src="Dimages/m2.gif" /></p>
		</form>
    </div>
</div>


<div id="desc_title"><b>Description</b></div>
<div class="rightbox01">
	<div id="desc_textbox">
	<?=stripslashes($product_info['products_description'])?>
	<a name="disqus_comment"></a>
<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'pathgadget'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
    </div>
</div>

</div>
<!--right end-->
<!--left start-->
<div id="leftbox"><div class="leftbox01">
	<div id="tiltebar"><img src="Dimages/hot_products.gif" width="185" height="32" /></div>
    <div id="objectbox">
    <ul>
    <li class="pro_left"><a href="wholesale-new-micro-car-key-spy-camera-dvr-supporting-30fps-for-dvr-720-480.html" id="a1"><img src="Dimages/hot.gif" width="22" height="10" class="hot"/></a></li>
    <li class="pro_right"><a href="wholesale-1-8-inch-tft-lcd-4gb-spy-watch-with-hidden-camera-dvr-mp4.html" id="a2"><img src="Dimages/hot.gif" width="22" height="10" class="hot"/></a></li>
    <li class="pro_left"><a href="wholesale-8gb-hd-1280x960-motion-detection-spy-pen-camera.html" id="a3"></a></li>
    <li class="pro_right"><a href="wholesale-multi-functional-spy-clock-camera-with-thermometer-display-motion-detection-webcamera-remote-control-black.html" id="a4"></a></li>
    <li class="pro_left"><a href="wholesale-8gb-ir-night-vision-waterproof-spy-watch-hidden-camera-shockproof-dvr-black.html" id="a5"></a></li>
    <li class="pro_right"><a href="wholesale-clothes-hanger-hook-spy-camera-support-up-to-16gb.html" id="a6"></a></li>
    <li class="pro_left"><a href="wholesale-8gb-mini-digital-camcorder-spy-pen-interview-recorder-black.html" id="a7"></a></li>
    <li class="pro_right"><a href="wholesale-5-mega-pixels-4gb-hd-spy-glasses-camera.html" id="a8"></a></li>
    <li class="pro_left"><a href="wholesale-button-spy-camera-with-audio-and-video-with-4gb-memory-hidden-camera.html" id="a9"></a></li>
    <li class="pro_right"><a href="wholesale-8gb-waterproof-underwater-50m-spy-watch-high-resolution-hidden-camera.html" id="a10"></a></li>
    <li class="pro_left"><a href="wholesale-4gb-spy-cap-sports-hidden-camera-with-remote-control.html" id="a11"></a></li>
    <li class="pro_right"><a href="wholesale-spy-clock-camera-with-remote-control-and-motion-detection-support-up-to-8gb.html" id="a12"></a></li>
    <li class="pro_left"><a href="wholesale-4gb-flash-drive-spy-audio-recorder-up-to-240-hours.html" id="a13"></a></li>
    <li class="pro_right"><a href="more.html" class="more"><img src="Dimages/more_arrow.png" width="16" height="10" />more</a></li>
    </ul>
</div>
</div>

<div class="leftbox02">
 <h2 style="font-size:13px; background-color: #D9E1F4; width:100%; line-height:2em; text-align:center;">Top Selling Items</h2>
 <div class="left-top-items">
   <img src="Dimages/small/ESVMD22.jpg" alt="New Micro Car Key Spy Camera DVR Supporting 30FPS for DVR 720*480" />
   <div class="item-name"><a href="wholesale-new-micro-car-key-spy-camera-dvr-supporting-30fps-for-dvr-720-480.html">New Micro Car Key Spy Camera DVR Supporting 30FPS for DVR 720*480</a></div>
   <div class="item-price">US$10.99</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/ESPSPR99.jpg" alt="8GB HD 1280x960 Motion Detection Spy Pen Camera" />
   <div class="item-name"><a href="wholesale-8gb-hd-1280x960-motion-detection-spy-pen-camera.html">8GB HD 1280x960 Motion Detection Spy Pen Camera</a></div>
   <div class="item-price">US$39.99</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/ESPSPR131B.jpg" alt="Multi-functional Clock Camera DVR with Thermometer Display + Motion Detection + Webcamera + Remote Control(Black)" />
   <div class="item-name"><a href="wholesale-multi-functional-spy-clock-camera-with-thermometer-display-motion-detection-webcamera-remote-control-black.html">Multi-functional Clock Camera DVR with Thermometer Display + Motion Detection + Webcamera + Remote Control(Black)</a></div>
   <div class="item-price">US$62.18</div>
 </div>
</div>
</div>
<!--left end-->
<!--bottom start-->
<div id="bottombox">

		<div id="bottombox01">
        <dl>
            <dt>General info</dt>
            <dd><a href="About_Us.html">About Us</a></dd>
            <dd><a href="Contact_Us.html">Contact Us</a></dd>
            <dd><a href="Product_Quality_Control.html">Product Quality Control</a></dd>
        </dl>
        <dl>
			<dt>Security & Privacy</dt>
            <dd><a href="Privacy_Policy.html">Privacy Policy</a></dd>
            <dd><a href="Security_Guarantee.html">Security Guarantee</a></dd>
            <dd><a href="Terms_of_Use.html">Terms of Use</a></dd>
        </dl> 
        <dl>
            <dt>About Orders</dt>
            <dd><a href="Ordering.html">Ordering</a></dd>
            <dd><a href="Payment.html">Payment</a></dd>
        </dl>
        <dl>
			<dt>Refund & Returns</dt>
            <dd><a href="Refund_Policy.html">Refund Policy</a></dd>
            <dd><a href="Return_Policy.html">Return Policy</a></dd>
            <dd><a href="RMA_Procedure.html">RMA Procedure</a></dd>
        </dl> 
        <dl>
            <dt>Shipping</dt>
            <dd><a href="Shipping_Method.html">Shipping Method</a></dd>
            <dd><a href="Locations_We_Ship_To.html">Locations We Ship To</a></dd>
            <dd><a href="Frequently_Asked_Questions.html">FAQs</a></dd>
        </dl>
    </div>
    
    <div id="bottombox02"><a href="About_Us.html">About Us</a>|<a href="Contact_Us.html">Contact us</a><br />
    Buy Right, Pay Smart ! - Copyright © 2000-2011 www.pathgadget.com 
      <div id="paymentimg">
<a href="#"><img src="Dimages/bnr_paymentsBy.gif" /></a><a href="#"><img src="Dimages/worldpay.gif" /></a><a href="#"><img src="Dimages/usps.png" /></a><a href="#"><img src="Dimages/logo3.jpg" /></a><a href="#"><img src="Dimages/hkpost.gif" /></a><a href="#"><img src="Dimages/royalmail.gif" /></a>
	</div>
    </div>
    
    
</div>
<!--bottom end-->
</div>
</div>
<script type="text/javascript" src="getid.js"></script>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=8244869; 
var sc_invisible=0; 
var sc_security="838f717d"; 
</script>
<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><div class="statcounter"><a title="tumblr page
counter" href="http://statcounter.com/tumblr/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/8244869/0/838f717d/0/"
alt="tumblr page counter"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<script type="text/javascript">
  window.___gcfg = {lang: 'en-US'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</body>
</html>
