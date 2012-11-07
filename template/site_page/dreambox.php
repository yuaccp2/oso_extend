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
    	<li><a href="dreambox-800-dm800-hd-v75-pvr-satellite-receiver-with-sim-2-01.html">DM 800S HD-V75</a>|</li>
        <li><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">DM 500S-Black</a>|</li>
        <li><a href="dreambox-dm500s-500s-dreambox-dreambox-500-dreambox-500-s.html">DM 500S-Silver</a>|</li>
        <li><a href="blackbox-500-satellite-receiver.html">BlackBox 500S</a>|</li>
	<li><a href="skybox-s10-hd-pvr-digital-satellite-receiver.html">SKYBOX S10</a>|</li>
    <li><a href="hd-set-top-box-with-fully-dvb-t-and-mpeg-4-compliant-wide-screen-16-9-and-4-3-aspect-ratio.html">HD Set-Top-Box</a></li>
    </ul>
</div>
<!--menubox end-->
<!--right start-->
<div id="rightbox">

<div class="rightbox01">
    <div id="pro_largeimg"><img src="http://192.168.0.22/espow.com/images/large/<?=$product_info['products_model']?>-l.JPG" width="400" height="400"  /></div>
    <div id="pro_text">
        <h1><?=$product_info['products_name']?></h1>
		<img src="Dimages/write-a-review.gif" alt="write a review" id="review-image" /> &nbsp; <a id="review-a" href="#disqus_comment">Écrire un commentaire</a> </br></br>
		<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.dmbox.fr%2F<?=urlencode($product_info['seo_name'])?>.html&amp;send=false&amp;layout=standard&amp;width=293&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:293px; height:35px;" allowTransparency="true"></iframe></br> 
		<g:plusone size="medium" annotation="inline"></g:plusone>
        <dl>
            <dt>article #:</dt><dd><?=$product_info['products_model']?></dd>
            <dt>poids:</dt><dd><?=(int)$product_info['products_weight']?> (g)</dd>
            <dt>prix au détail:</dt><dd><b><?=$retail_price?></b></dd>
            <dt>Notre Prix:</dt><dd><strong><?=$out_price?></strong></dd>
        </dl>
		<form action="./cart/shoppingCart.php" method="post" target="_blank">
		  <input name="PHPSESSID" type="hidden" value="">
		  <input name="step" type="hidden" value="addToCart">
		  <input name="item_code" type="hidden" value="<?=$product_info['products_model']?>">
		  <input name="item_price" type="hidden" value="<?=$new_price?>">
		  <input name="item_qty" type="hidden" value="1">
		  <input name="item_name" type="hidden" value="<?=$product_info['products_name']?>">
			<p class="p1"><input type="image" src="Dimages/cart.gif" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Contact_Us.html">Question?</a></p>
			<p class="p1"><img src="Dimages/m1.gif" /><br /><img src="Dimages/m2.gif" /></p>
		</form>
    </div>
</div>


<div id="desc_title"><b>Description</b></div>
<div class="rightbox01">
	<div id="desc_textbox">
	<?=stripslashes($product_info['products_description'])?>
<strong>Dreambox pour les débutants</strong><br />
Une fois installer et lancer l'utilitaire , les utilisateurs seront portées à une interface simple . Pour le départ, vous aurez besoin de connaître la bonne IP adresse de votre décodeur qui peut être trouvé en accédant à Menu–〉Réglages-〉Expert Setup –〉Configuration de la communication de votre télécommande avec affichage sur écran de télévision. Ensuite , connectez un droit normal Ethernet Câble entre PC et le décodeur et allez dans " Options " pour définir l'adresse IP en conséquence dans l'adresse " IP de Dreambox " sur le terrain. Les utilisateurs peuvent tester la connexion IP en cliquant sur le bouton lui-même . Après cela, cliquez sur " FTP bouton " apportera une nouvelle interface et suivie par un clic supplémentaire sur " recevoir des fichiers d' Dreambox " sera capable de vous récupérer tous les contenus du décodeur raccordé.<br />
Comme prévu, vous serez en mesure de récupérer toutes les informations de haut niveau comme le service, l'emballage, chaînes satellite , ainsi que les détails de bas niveau comme fréquence, la polarité , Symbolrate , FEC , Satellite Position , code PID et beaucoup plus. Outre simplement afficher toutes ces informations utiles , la fonction d'utilité principale est sa capacité à modifier les canaux en cliquant sur les listes respectives , beaucoup plus facilement et ensuite les recharger Retour au décodeur , ce qui simplifie tout le processus fastidieux de le faire en fonction de la télécommande . En plus de cela , les utilisateurs peuvent créer / supprimer des bouquets , ajouter de nouveaux services , modifier des données du transpondeur et même basculer parental contrôle comme un tout- en-un guichet unique solution adaptée à tous les niveaux d'utilisateurs , y compris débutants avertis non -tech . Ce n'est pas tout , il ya même des moyens de recherche, les utilisateurs et les fichiers d'importation Bouquet SatcoDX , et même exporter tous les services / fichiers Bouquet en Exceller un format lisible pour référence future.<br />
Une fois que tout est mis , revenir en arrière et cliquez sur FTP icône suivi par " envoyer des fichiers à Dreambox " se chargera de retour tous les changements dans décodeur Dreambox . Pour rappel , il est
nécessaire de redémarrer en sélectionnant « Reload »ou« paramètres de redémarrage Dreambox " pour que le changement soit efficace. Compatible à la plupart des modèles de Dreambox DM800 HD , même version , DreamboxEdit est gratuit pour les télécharger.<br />
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
    	        <li><a href="dreambox-800-dm800-hd-v75-pvr-satellite-receiver-with-sim-2-01.html">DM 800 HD V75&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">DM 500S Black&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="skybox-openbox-receiver.html">Skybox/Openbox&nbsp;<img src="Dimages/hot.jpg" /></a></li>
                <li><a href="blackbox-500-satellite-receiver.html">BlackBox 500S</a></li>
                <li><a href="hd-satellite-receiver.html">HD Récepteur</a></li>
		        <li><a href="sd-satellite-receiver.html">SD Récepteur</a></li>
		        <li><a href="set-top-box-receiver.html">Set Top Box</a></li>
                <li><a href="hd-set-top-box-with-fully-dvb-t-and-mpeg-4-compliant-wide-screen-16-9-and-4-3-aspect-ratio.html">HD Set-Top-Box</a></li>
    </ul>
    <p class="p3"></p>
  </div>
  <div class="leftbox01">
	<div id="tiltebar">Achat par Fonction</div>    <ul id="objectbox">
    	        <li><a href="dvbs-dvbt-combo-receiver.html">Satellite & Terrestre (DVB)</a></li>
                <li><a href="cable-dvbc-receiver.html">Filaire (DVB)</a></li>
                <li><a href="satellite-dvbs-receiver.html">Satellite (DVB)</a></li>
                <li><a href="terrestrial-dvbt-receiver.html">Terrestre (DVB)</a></li>
                <li><a href="iptv-box-with-abs-surface-hdmi-and-cvbs-interface-google-s-android-system.html">IPTV</a></li>
    </ul>
    <p class="p3"></p>
  </div>
<div class="leftbox02">
 <h2 style="font-size:13px; background-color: #D9E1F4; width:100%; line-height:2em; text-align:center;">Meilleure vente</h2>
 <div class="left-top-items">
   <img src="Dimages/small/EDMBOX2.JPG" alt="DM 500S DM500 récepteur satellite avec SCART + RS232 Interface (Noir)" />
   <div class="item-name"><a href="dreambox-500s-dm500-satellite-receiver-with-scart-rs232-interface.html">DM 500S DM500 récepteur satellite avec SCART + RS232 Interface (Noir)</a></div>
   <div class="item-price">€47.24</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/EDMBOX5.JPG" alt="DM800 HD V75 PVR récepteur satellite avec SIM 2.01 Supports HDTV DVI eSATA" />
   <div class="item-name"><a href="dreambox-800-dm800-hd-v75-pvr-satellite-receiver-with-sim-2-01.html">DM800 HD V75 PVR récepteur satellite avec SIM 2.01 Supports HDTV DVI eSATA</a></div>
   <div class="item-price">€168.95</div>
 </div>
  <div class="left-top-items">
   <img src="Dimages/small/EDMBOX6.JPG" alt="BlackBox 500S récepteur satellite avec RS232 AV Interface" />
   <div class="item-name"><a href="blackbox-500-satellite-receiver.html">BlackBox 500S récepteur satellite avec RS232 AV Interface</a></div>
   <div class="item-price">€41.64</div>
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
