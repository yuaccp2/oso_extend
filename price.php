<?php
$languages_id = 1;
include('./classes/PriceFormatter.php');
$pf = new PriceFormatter;
$pf->loadProduct($product_info['products_id'],$languages_id);

$retail_price = $product_info['products_discount'] > 0 ?number_format((1/(1-$product_info['products_discount']/100)),2,'.','') : PRODUCTS_RATE;
if ( $product_info['products_type'] != 'A' ){
$pf->getRetailSinglePrice($retail_price);
}
$pf->getSinglePrice();