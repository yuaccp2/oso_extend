<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$product_info['products_name']?></title>
<meta name="Description" content="<?=$product_info['products_head_desc_tag']?>" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="bgbox">
<div id="allbox">

<!--headerbox start-->
<div id="headerbox">
	<div id="headerleftbox"><a href="http://www.dmbox.fr/"><img src="Dimages/logo.gif" alt="dreambox satellite receiver" /></a></div>
    <div id="headermidbox"><a href="Contact_Us.html">Contactez-nous</a>|<a href="Shipping_Method.html">Mode de livraison</a>|<a href="Payment.html">Paiement </a>|  <a href="About_Us.html">A propos de nous</a></div>
    <div id="headerrightbox"><a href="http://www.dmbox.fr/viewCart.php" action="_blank"><img src="Dimages/cart1.gif" alt="view your shopping cart"/></a></div>
</div>
<!--headerbox end-->
<!--menubox start-->
<div id="menubox">	<ul>
    	<li><a href="dreambox-800-hd-dm800-hd-pvr-v72-supports-hdtv-2-5-sata-hdd-esata.html">DM 800S HD-V75</a>|</li>
        <li><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">DM 500S-Black</a>|</li>
        <li><a href="dreambox-dm500s-500s-dreambox-dreambox-500-dreambox-500-s.html">DM 500S-Silver</a>|</li>
        <li><a href="blackbox-500-satellite-receiver.html">BlackBox 500S</a>|</li>
	<li><a href="skybox-s10-hd-pvr-digital-satellite-receiver.html">SKYBOX S10</a>|</li>
    <li><a href="morebox-301d-dongle-dvb-satellite-receiver-with-network-search-function.html">Morebox 301D</a></li>
    </ul>
</div>
<!--menubox end-->
<!--right start-->
<div id="rightbox">

<div class="rightbox01">
    <div id="pro_largeimg"><img src="http://192.168.0.22/espow.com/images/large/<?=$product_info['products_model']?>-l.JPG" width="400" height="400"  /></div>
    <div id="pro_text">
        <h1><?=$product_info['products_name']?></h1>
		<img src="Dimages/write-a-review.gif" alt="write a review" id="review-image" /> &nbsp; <a id="review-a" href="#disqus_comment">Write a Review</a> </br></br>
		<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.dmbox.fr%2F<?=urlencode($product_info['seo_name'])?>.html&amp;send=false&amp;layout=standard&amp;width=293&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:293px; height:35px;" allowTransparency="true"></iframe></br> 
		<g:plusone size="medium" annotation="inline"></g:plusone>
        <dl>
            <dt>Item No. :</dt><dd><?=$product_info['products_model']?></dd>
            <dt>Weight:</dt><dd><?=(int)$product_info['products_weight']?> (g)</dd>
            <dt>Retail price:</dt><dd><b><?=$retail_price?></b></dd>
            <dt>Our Price: </dt><dd><strong><?=$out_price?></strong></dd>
        </dl>
		<form action="./cart/shoppingCart.php" method="post" target="_blank">
		  <input name="PHPSESSID" type="hidden" value="">
		  <input name="step" type="hidden" value="addToCart">
		  <input name="item_code" type="hidden" value="<?=$product_info['products_model']?>">
		  <input name="item_price" type="hidden" value="<?=$product_info['products_price']?>">
		  <input name="item_qty" type="hidden" value="1">
		  <input name="item_name" type="hidden" value="<?=$product_info['products_name']?>">
			<p class="p1"><input type="image" src="Dimages/cart.gif" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Contact_Us.html">Got a Question</a></p>
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
    var disqus_shortname = 'myclickshop'; // required: replace example with your forum shortname

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
<div id="leftbox">
  <div class="leftbox01">
	<div id="tiltebar">Achat par Série</div>    
    <ul id="objectbox">
    	        <li><a href="dm-receiver-800-hd-series.html">DM 800 HD Series&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">DM 500S Black&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="skybox-openbox-receiver.html">Skybox/Openbox&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="blackbox-500-satellite-receiver.html">BlackBox 500S</a></li>
                <li><a href="hd-satellite-receiver.html">HD Récepteur</a></li>
		        <li><a href="sd-satellite-receiver.html">SD Récepteur</a></li>
		        <li><a href="set-top-box-receiver.html">Set Top Box</a></li>
                <li><a href="morebox-301d-dongle-dvb-satellite-receiver-with-network-search-function.html">Morebox 301D</a></li>
    </ul>
    <p class="p3"></p>
  </div>
  <div class="leftbox01">
	<div id="tiltebar">Achat par Fonction</div>    
    <ul id="objectbox">
    	        <li><a href="dvbs-dvbt-combo-receiver.html">Satellite & Terrestrial (DVB)</a></li>
                <li><a href="cable-dvbc-receiver.html">Cable (DVB)</a></li>
                <li><a href="satellite-dvbs-receiver.html">Satellite (DVB)</a></li>
                <li><a href="terrestrial-dvbt-receiver.html">Terrestrial (DVB)</a></li>
                <li><a href="iptv-box-with-abs-surface-hdmi-and-cvbs-interface-google-s-android-system.html">IPTV</a></li>
    </ul>
    <p class="p3"></p>
  </div>
<div class="leftbox02">
 <h2 style="font-size:13px; background-color: #D9E1F4; width:100%; line-height:2em; text-align:center;">Meilleure vente</h2>
 <div class="left-top-items">
   <img src="Dimages/small/EDMBOX2.jpg" alt="Dreambox 500S DM500 Satellite Receiver with SCART + RS232 Interface" />
   <div class="item-name"><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">Dreambox 500S DM500 Satellite Receiver with SCART + RS232 Interface</a></div>
   <div class="item-price">£35.85</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/EDMBOX1.jpg" alt="Dreambox 800 HD DM800 HD PVR V72 Supports HDTV / 2.5 SATA HDD / eSATA" />
   <div class="item-name"><a href="dreambox-800-hd-dm800-hd-pvr-v72-supports-hdtv-2-5-sata-hdd-esata.html">Dreambox 800 HD DM800 HD PVR V75 Supports HDTV / 2.5 SATA HDD / eSATA</a></div>
   <div class="item-price">£134.93</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/EDMBOX6.jpg" alt="Blackbox DM500 HD Digital Satellite Receiver" />
   <div class="item-name"><a href="blackbox-500-satellite-receiver.html">Blackbox 500 Satellite Receiver</a></div>
   <div class="item-price">£33.29</div>
 </div>
</div>
</div>
<!--left end-->
<!--bottom start-->
<div id="bottombox">

		<div id="bottombox01">
        <dl>
            <dt>infos générales</dt>
            <dd><a href="About_Us.html">A propos de nous</a></dd>
            <dd><a href="Contact_Us.html">Contactez-nous</a></dd>
        </dl>
        <dl>
			<dt>sécurité et confidentialité</dt>
            <dd><a href="Privacy_Policy.html">Politique de confidentialité</a></dd>
            <dd><a href="Security_Guarantee.html">    Conditions d'utilisation</a></dd>
            <dd><a href="Terms_of_Use.html">Conditions d'utilisation</a></dd>
        </dl> 
        <dl>
            <dt>sur les commandes</dt>
            <dd><a href="Ordering.html">Commande</a></dd>
            <dd><a href="Payment.html">Paiement</a></dd>
        </dl>
        <dl>
			<dt>retrait et retour</dt>
            <dd><a href="Refund_Policy.html">Politique de remboursement</a></dd>
            <dd><a href="Return_Policy.html">Politique de retour</a></dd>
            <dd><a href="RMA_Procedure.html">Procédure de RMA</a></dd>
        </dl> 
        <dl>
            <dt>transport</dt>
            <dd><a href="Shipping_Method.html">Mode de livraison</a></dd>
            <dd><a href="Locations_We_Ship_To.html">Délai de livraison</a></dd>
            <dd><a href="Frequently_Asked_Questions.html">Questions fréquemment posées</a></dd>
        </dl>
    </div>
    
    <div id="bottombox02"><a href="About_Us.html">A propos de nous</a>|<a href="Contact_Us.html">Contactez-nous</a><br />
    acheter bien ,payer sagement! - Copyright © 2000-2012 www.dmbox.fr
      <div id="paymentimg">
<img src="Dimages/bnr_paymentsBy.gif" /><img src="Dimages/worldpay.gif" /><img src="Dimages/usps.png" /><img src="Dimages/logo3.jpg" /><img src="Dimages/hkpost.gif" /><img src="Dimages/royalmail.gif" />
	</div>
    </div>
    
    
</div>
<!--bottom end-->
</div>
</div>
<script type="text/javascript" src="getid.js"></script>
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=7278449; 
var sc_invisible=1; 
var sc_security="7d5e2573"; 
var sc_https=1; 
var sc_remove_link=1; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost +
"statcounter.com/counter/counter.js'></"+"script>");</script>
<noscript><div class="statcounter"><img class="statcounter"
src="https://c.statcounter.com/7278449/0/7d5e2573/1/"
alt="web counter"></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
<script type="text/javascript">
  window.___gcfg = {lang: 'fr'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</body>
</html>
