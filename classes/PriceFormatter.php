<?php
/*
  $Id: PriceFormatter.php,v 1.6 2003/06/25 08:29:26 petri Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
    PriceFormatter.php - module to support quantity pricing

    Created 2003, Beezle Software based on some code mods by WasaLab Oy (Thanks!)
*/

class PriceFormatter {
	var $hiPrice;
	var $lowPrice;
	var $quantity;
	var $hasQuantityPrice;
	var $hasCustomersGroupPrice;
	var $customers_group_thePrice;
	var $customers_group_lowPrice;
	var $customers_group_hiPrice;
	//
	var $attPrice;
	var $attPrefix;
  
 
	function PriceFormatter( $prices=NULL ) {
		$this->productsID = -1;
		$this->hasQuantityPrice=false;
		$this->hasSpecialPrice=false;
		$this->hasCustomersGroupPrice=false;
		$this->hiPrice=-1;
		$this->lowPrice=-1;
		$this->customers_group_thePrice=-1;
		$this->customers_group_lowPrice=-1;
		$this->customers_group_hiPrice=-1;
		$this->attPrice=0;
		$this->attPrefix='';
		for ($i=1; $i<=11; $i++){
			$this->quantity[$i] = -1;
			$this->prices[$i] = -1;
		}
		$this->thePrice = -1;
		$this->specialPrice = -1;
		$this->qtyBlocks = 1;
		if($prices){ $this->parse($prices);}
	}

	function encode() {
		$str = $this->productsID . ":"
			 . (($this->hasQuantityPrice == true) ? "1" : "0") . ":"
			 . (($this->hasSpecialPrice == true) ? "1" : "0") . ":"
			 . $this->quantity[1] . ":"
			 . $this->quantity[2] . ":"
			 . $this->quantity[3] . ":"
			 . $this->quantity[4] . ":"
			 . $this->quantity[5] . ":"
			 . $this->quantity[6] . ":"
			 . $this->quantity[7] . ":"
			 . $this->quantity[8] . ":"
			 . $this->quantity[9] . ":"
			 . $this->quantity[10] . ":"
			 . $this->quantity[11] . ":"
			 . $this->price[1] . ":"
			 . $this->price[2] . ":"
			 . $this->price[3] . ":"
			 . $this->price[4] . ":"
			 . $this->price[5] . ":"
			 . $this->price[6] . ":"
			 . $this->price[7] . ":"
			 . $this->price[8] . ":"
			 . $this->price[9] . ":"
			 . $this->price[10] . ":"
			 . $this->price[11] . ":"
			 . $this->thePrice . ":"
			 . $this->specialPrice . ":"
			 . $this->qtyBlocks . ":"
			 . $this->taxClass;
		return $str;
	}

	function decode($str) {
		list($this->productsID,
		$this->hasQuantityPrice,
		$this->hasSpecialPrice,
		$this->quantity[1],
		$this->quantity[2],
		$this->quantity[3],
		$this->quantity[4],
		$this->quantity[5],
		$this->quantity[6],
		$this->quantity[7],
		$this->quantity[8],
		$this->quantity[9],
		$this->quantity[10],
		$this->quantity[11],
		$this->price[1],
		$this->price[2],
		$this->price[3],
		$this->price[4],
		$this->price[5],
		$this->price[6],
		$this->price[7],
		$this->price[8],
		$this->price[9],
		$this->price[10],
		$this->price[11],
		$this->thePrice,
		$this->specialPrice,
		$this->qtyBlocks,
		$this->taxClass) = explode(":", $str);
		
		$this->hasQuantityPrice = (($this->hasQuantityPrice == 1) ? true : false);
		$this->hasSpecialPrice = (($this->hasSpecialPrice == 1) ? true : false);
	}

	function parse($prices) {
		$this->productsID = $prices['products_id'];
		$this->hasQuantityPrice = false;
		$this->hasSpecialPrice = false;
		$this->hasCustomersGroupPrice = 'false';

		// BOF Price added the property price 2010-08-11
		if (!empty($this -> attPrice)){
			if ($this -> attPrefix == '-' && abs($prices['products_price']) > abs($this -> attPrice)){
				$prices['products_price'] -= $this -> attPrice;
			}elseif ($this -> attPrefix == '+'){
				$prices['products_price'] += $this -> attPrice;
			}
		}
		// EOF Price added the property price 2010-08-11
		$this->thePrice = $prices['products_price'];
		//    $this->specialPrice=$prices['specials_new_products_price'];
		// BOF QTY Price Break
		$this->specialPrice=tep_get_products_special_price($prices['products_id']);
		// BOF Special price added the property price 2010-08-11
		if (!empty($this -> attPrice) && tep_not_null($this->specialPrice) ){
			if ($this -> attPrefix == '-' && abs($this->specialPrice) > abs($this -> attPrice)){
				$this->specialPrice -= $this -> attPrice;
			}elseif ($this -> attPrefix == '+'){
				$this->specialPrice += $this -> attPrice;
			}
		}
		// EOF Special price added the property price 2010-08-11
		
		// EOF QTY Price Break
		$this -> hasSpecialPrice = tep_not_null($this->specialPrice);
		
		if ( $prices['customers_group_flag']=='true') {
			$this->hasCustomersGroupPrice='true';
			$this->customers_group_thePrice= $prices['customers_group_price'];
			$this->customers_group_price[1]= $prices['customers_group_price1'];
			$this->customers_group_price[2]= $prices['customers_group_price2'];
			$this->customers_group_price[3]= $prices['customers_group_price3'];
			$this->customers_group_price[4]= $prices['customers_group_price4'];
			$this->customers_group_price[5]= $prices['customers_group_price5'];
			$this->customers_group_price[6]= $prices['customers_group_price6'];
			$this->customers_group_price[7]= $prices['customers_group_price7'];
			$this->customers_group_price[8]= $prices['customers_group_price8'];
			$this->customers_group_price[9]= $prices['customers_group_price9'];
			$this->customers_group_price[10]= $prices['customers_group_price10'];
			$this->customers_group_price[11]= $prices['customers_group_price11'];
			// BOF Wholesale price added the property price 2010-08-11
			if (!empty($this -> attPrice)){
				if ($this -> attPrefix == '-' && abs($this -> customers_group_thePrice) > abs($this -> attPrice)){
					$this -> customers_group_thePrice -= $this -> attPrice;
				}elseif ($this -> attPrefix == '+'){
					$this -> customers_group_thePrice += $this -> attPrice;
				}
				for($i = 1; $i <= 11; $i++) {
					if ($this -> attPrefix == '-' && abs($this -> customers_group_price[$i]) > abs($this -> attPrice)){
						$this -> customers_group_price[$i] -= $this -> attPrice;
					}elseif ($this -> attPrefix == '+'){
						$this -> customers_group_price[$i] += $this -> attPrice;
					}
				}
			}
			// EOF Wholesale price added the property price 2010-08-11
		} else {
			$this->hasCustomersGroupPrice='false';
			$this->customers_group_thePrice=0;
			$this->customers_group_price[1]= 0;
			$this->customers_group_price[2]= 0;
			$this->customers_group_price[3]= 0;
			$this->customers_group_price[4]= 0;
			$this->customers_group_price[5]= 0;
			$this->customers_group_price[6]= 0;
			$this->customers_group_price[7]= 0;
			$this->customers_group_price[8]= 0;
			$this->customers_group_price[9]= 0;
			$this->customers_group_price[10]= 0;
			$this->customers_group_price[11]= 0;
		}
		// Eversun mod end for SPPP Qty Price Break Enhancement
		$this->qtyBlocks=$prices['products_qty_blocks'];
		$this->taxClass=$prices['products_tax_class_id'];

		if ($this->quantity[1] > 0) {
			$this->hasQuantityPrice = true;
			$this->hiPrice = $this->thePrice;
			$this->lowPrice = $this->thePrice;
			// Eversun mod for SPPP Qty Price Break Enhancement
			if ( $prices['customers_group_flag']=='true') {
				$this->customers_group_hiPrice = $this->customers_group_thePrice;
				$this->customers_group_lowPrice = $this->customers_group_thePrice;
				// echo $this->customers_group_lowPrice.'{'.$this->customers_group_thePrice;
			}  else {
				$this->customers_group_hiPrice = 0;
				$this->customers_group_lowPrice = 0;
			}
			// Eversun mod end for SPPP Qty Price Break Enhancement
			for($i=1; $i<=11; $i++) {
				if($this->quantity[$i] > 0) {
					if ($this->price[$i] > $this->hiPrice) {
						$this->hiPrice = $this->price[$i];
					}
					if ($this->price[$i] < $this->lowPrice) {
						$this->lowPrice = $this->price[$i];
					}
				}
			}
		
			// Eversun mod for SPPP Qty Price Break Enhancement
			if ( $prices['customers_group_flag']=='true'){
				for($i=1; $i<=11; $i++) {
				// echo '{'.$this->customers_group_lowPrice.'--'.$this->customers_group_thePrice.'--'.$this->customers_group_hiPrice.'}';
					if($this->quantity[$i] > 0) {
						if ($this->customers_group_price[$i] > $this->customers_group_hiPrice) {
							$this->customers_group_hiPrice = $this->customers_group_price[$i];
						}
						if ($this->customers_group_price[$i] < $this->customers_group_lowPrice) {
							$this->customers_group_lowPrice = $this->customers_group_price[$i];
						}
					}
				}
			}
			// Eversun mod end for SPPP Qty Price Break Enhancement
		}
		
	}
	/* Set Additional price */
	function setAdditionalPrice($aPrice,$aPriceMod){
	}
	
	function loadProduct($product_id, $language_id = 1, $attCode = '') {
		global $customer_id;
		if ($customer_id == '') {
			$customer_group_id = 0;
		} else {    
			$getcustomer_GroupID_query = tep_db_query("select customers_group_id from " . TABLE_CUSTOMERS . " where customers_id='" . (int)$customer_id . "'");
			$getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);
			$customer_group_id = $getcustomer_GroupID['customers_group_id'];
		}  
		$sql = "SELECT pd.products_name, p.products_model, p.products_image, p.products_id," .
				" p.manufacturers_id, p.products_price, p.products_weight," .
				" p.products_qty_blocks," .
				" p.products_tax_class_id,p.products_free_shipping," .
				" IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price," .
				" IF(s.status, s.specials_new_products_price, p.products_price) as final_price" .
				" FROM " . TABLE_PRODUCTS . " p " .
				" LEFT JOIN " . TABLE_SPECIALS . " s using(products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd " .
				" WHERE  p.products_id = '" . (int)$product_id . "'" .
				" AND pd.products_id = '" . (int)$product_id . "'" .
				" AND pd.language_id = '". (int)$language_id . "'";
		$product_info_query = tep_db_query($sql);
		$product_info = tep_db_fetch_array($product_info_query);
		// Eversun mod for SPPP Qty Price Break Enhancement
		$product_info['customers_group_price1'] = 0;
		$product_info['customers_group_price2'] = 0;
		$product_info['customers_group_price3'] = 0;
		$product_info['customers_group_price4'] = 0;
		$product_info['customers_group_price5'] = 0;
		$product_info['customers_group_price6'] = 0;
		$product_info['customers_group_price7'] = 0;
		$product_info['customers_group_price8'] = 0;
		$product_info['customers_group_price9'] = 0;
		$product_info['customers_group_price10'] = 0;
		$product_info['customers_group_price11'] = 0;
		$product_info['customers_group_flag'] = 'false';
		//echo  $sql;
		if ($customer_group_id > 0) {
			$sql1 = "select  pg.customers_group_price , pg.customers_group_price1 ,pg.customers_group_price2 ,pg.customers_group_price3 ,pg.customers_group_price4 , pg.customers_group_price5 ,pg.customers_group_price6 ,pg.customers_group_price7 ,pg.customers_group_price8 ,pg.customers_group_price9 ,pg.customers_group_price10 ,pg.customers_group_price11" . " from " .TABLE_PRODUCTS_GROUPS ." pg where pg.products_id = '". (int)$product_id ."' and pg.customers_group_id = '". $customer_group_id ."'"  ;
			$scustomer_group_price_query = tep_db_query($sql1);
			if (tep_db_num_rows($scustomer_group_price_query) > 0) {
				$scustomer_group_price = tep_db_fetch_array($scustomer_group_price_query) ;      
				$product_info['customers_group_price']= $scustomer_group_price['customers_group_price'];
				$product_info['customers_group_price1']= $scustomer_group_price['customers_group_price1'];
				$product_info['customers_group_price2']= $scustomer_group_price['customers_group_price2'];
				$product_info['customers_group_price3']= $scustomer_group_price['customers_group_price3'];
				$product_info['customers_group_price4']= $scustomer_group_price['customers_group_price4'];
				$product_info['customers_group_price5']= $scustomer_group_price['customers_group_price5'];
				$product_info['customers_group_price6']= $scustomer_group_price['customers_group_price6'];
				$product_info['customers_group_price7']= $scustomer_group_price['customers_group_price7'];
				$product_info['customers_group_price8']= $scustomer_group_price['customers_group_price8'];
				$product_info['customers_group_price9']= $scustomer_group_price['customers_group_price9'];
				$product_info['customers_group_price10']= $scustomer_group_price['customers_group_price10'];
				$product_info['customers_group_price11']= $scustomer_group_price['customers_group_price11'];
				$product_info['customers_group_flag']='true';
			}
		}
		// Eversun mod end for SPPP Qty Price Break Enhancement
		// 根据属性获取价格 BOF
		if (!empty($attCode)){
			$att_query_sql = sprintf("SELECT DISTINCT * FROM products_attributes WHERE products_model LIKE '%s' AND options_value='%s'",$product_info['products_model'],str_replace('-',',',$attCode));
			$options_query = tep_db_query($att_query_sql);
			$options_query_num = tep_db_num_rows($options_query);
			if ($options_query_num > 0){
				$item_info = tep_db_fetch_array($options_query);
				if (!empty($item_info['options_weight'])){
					if ($item_info['options_weight_prefix'] == '-' && abs($product_info['products_weight']) > abs($item_info['options_weight'])){
						$product_info['products_weight'] -= $item_info['options_weight'];
					}elseif ($item_info['options_weight_prefix'] == '+'){
						$product_info['products_weight'] += $item_info['options_weight'];
					}
				}
				if (!empty($item_info['options_price'])){
					$this -> attPrice = $item_info['options_price'];
					$this -> attPrefix = $item_info['options_price_prefix'];
				}
			}
		}
		// 根据属性获取价格 EOF
		$this -> parse($product_info);
		return $product_info;
	}

	function computePrice($qty) {
		$qty = $this->adjustQty($qty);
		// Compute base price, taking into account the possibility of a special
		$price = ($this->hasSpecialPrice === TRUE) ? $this->specialPrice : $this->thePrice;
  
		$parent_id = tep_db_fetch_array(tep_db_query("select products_parent_id from " . TABLE_PRODUCTS . " where products_id = '" . $this->productsID . "'"));
		if ($parent_id['products_parent_id'] != '0') {
			return $price;
		}//  print_r($this->price);
		for ($i=1; $i<=11; $i++) {
			if (($this->price[$i] > 0) && ($this->quantity[$i] > 0) && ((($qty > $this->quantity[$i]) && ($i != 1)) || (($qty >= $this->quantity[$i]) && ($i == 1)))) {
				$price = $this->price[$i];
			}
		}
		return $price;
	}

	function adjustQty($qty) {
		// Force QTY_BLOCKS granularity
		$qb = $this->getQtyBlocks();
		if ($qty < 1){$qty = 1;}
		if ($qb >= 1){
			if ($qty < $qb){$qty = $qb;}
			if (($qty % $qb) != 0){$qty += ($qb - ($qty % $qb));}
		}
		return $qty;
	}

	function getQtyBlocks() {
		return $this->qtyBlocks;
	}
	
	function getPrice() {
		return $this->thePrice;
	}
	
	function getLowPrice() {
		return $this->lowPrice;
	}
	
	function getHiPrice() {
		return $this->hiPrice;
	}
	
	function hasSpecialPrice() {
		return $this->hasSpecialPrice;
	}
	
	function hasQuantityPrice() {
		return $this->hasQuantityPrice;
	}
	// modified by benny 2009/03/19
	function getSinglePrice(){
		global $currencies;
		if ($this->hasSpecialPrice == true) {
			return $currencies->display_price($this->specialPrice,tep_get_tax_rate($this->taxClass));
		}
		if($this->hasCustomersGroupPrice=='true'){
			return $currencies->display_price($this->customers_group_thePrice,tep_get_tax_rate($this->taxClass));
		}
		return $currencies->display_price($this->thePrice,tep_get_tax_rate($this->taxClass));
	}

	function getSinglePriceValue(){
		
		if ($this->hasSpecialPrice == true) {
			return tep_add_tax($this->specialPrice, tep_get_tax_rate($this->taxClass));
		}
		if($this->hasCustomersGroupPrice=='true'){
			return tep_add_tax($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass));
		}
		return tep_add_tax($this->thePrice, tep_get_tax_rate($this->taxClass));
	}
  
    function getRetailSinglePrice($rate){
		global $currencies;
		if ($this->hasSpecialPrice == true) {
			if($rate == PRODUCTS_RATE){
				return $currencies->display_price($this->thePrice,tep_get_tax_rate($this->taxClass));
			}

			return $currencies->display_price($this->specialPrice *$rate,tep_get_tax_rate($this->taxClass)); //alex 2011-7-27 modified
		}
		if($this->hasCustomersGroupPrice=='true'){
			return $currencies->display_price($this->customers_group_thePrice *$rate,tep_get_tax_rate($this->taxClass));
		}
		return $currencies->display_price($this->thePrice * $rate,tep_get_tax_rate($this->taxClass));
	}
	//end
	function getPriceString($style='productPriceInBox') {
		global $currencies;
		if ($this->hasSpecialPrice == true) {
			$lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
			$lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">';
			$lc_text .= '&nbsp;<s>'
			. $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
			. '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
			. $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
			. '</span>&nbsp;'
			.'</td></tr>';
		} else {
			// Eversun mod  for SPPP Qty Price Break Enhancement
			if($this->hasCustomersGroupPrice=='true')  {
				$lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
				$lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">'
				. $currencies->display_price($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass))
				. '</td></tr>';
			}  else {
				$lc_text = '<table align="top" border="1" cellspacing="0" cellpadding="0">';
				$lc_text .= '<tr><td align="center" class=' . $style. ' colspan="2">'  . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))  . '</td></tr>';
			}
			// Eversun end mod  for SPPP Qty Price Break Enhancement
		}
		// If you want to change the format of the price/quantity table
		// displayed on the product information page, here is where you do it.
		if($this->hasQuantityPrice == true) {
			// Eversun mod  for SPPP Qty Price Break Enhancement
			if($this->hasCustomersGroupPrice=='true')  {
				for($i=1; $i<=11; $i++) {
					if($this->quantity[$i] > 0) {
						$lc_text .= '<tr><td class='.$style.'>'
						. $this->quantity[$i]
						.'+&nbsp;</td><td class='.$style.'>'
						. $currencies->display_price($this->customers_group_price[$i],  tep_get_tax_rate($this->taxClass))  .'</td></tr>';
					}
				}
			} else {
				for($i=1; $i<=11; $i++) {
					if($this->quantity[$i] > 0) {
						$lc_text .= '<tr><td class='.$style.'>'
						. $this->quantity[$i]
						.'+&nbsp;</td><td class='.$style.'>'
						. $currencies->display_price($this->price[$i],  tep_get_tax_rate($this->taxClass)) .'</td></tr>';
					}
				}
			}
			// Eversun mod end for SPPP Qty Price Break Enhancement
			$lc_text .= '</table>';
		} else {
			if ($this->hasSpecialPrice == true) {
				$lc_text = '&nbsp;<s>'
				. $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
				. '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
				. $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
				. '</span>&nbsp;';
			}  else {
				// Eversun mod for SPPP Qty Price Break Enhancement
				if($this->hasCustomersGroupPrice=='true')  {
					$lc_text = '&nbsp;'
					. $currencies->display_price($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass))  . '&nbsp;';
				}  else {
					$lc_text = '&nbsp;'
					. $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
				}
				// Eversun mod end for SPPP Qty Price Break Enhancement
			}
		}
		return $lc_text;
	}

	// mdofied by benny 2009/03/19
	function getPriceTableString($style='productPriceInBox') {
	    global $currencies;
		$lc_text = '
			<table id="price_table" width="300" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#DDDDDD" style="margin:10px 0px 0px 0px; text-align:center;">
				<tr>
					<td bgcolor="#ECECEC"><b>Qty.Range(unit)</b></td>
					<td bgcolor="#ECECEC"><b>Price(per unit)</b></td>
				</tr>';
	    if ($this->hasSpecialPrice == true) {
			// $lc_text = '';
			/* $lc_text .= '<tr>
		<td bgcolor="ECECEC">1</td><td bgcolor="ECECEC"><s>'
	      . $currencies->display_price($this->thePrice,tep_get_tax_rate($this->taxClass))
	      . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
	      . $currencies->display_price($this->specialPrice,tep_get_tax_rate($this->taxClass))
	      . '</span>&nbsp;'
	      .'</td></tr>';*/
			$lc_text .= '<tr><td bgcolor="#FFFFFF">1 - 9</td><td bgcolor="#FFFFFF">'.$currencies->display_price($this->specialPrice,tep_get_tax_rate($this->taxClass)).'</td></tr>';
	    } else {
			// Eversun mod  for SPPP Qty Price Break Enhancement
			if($this->hasCustomersGroupPrice=='true')  {
				$lc_text .= '<tr><td bgcolor="#FFFFFF">1 - 9</td><td bgcolor="#FFFFFF">'.$currencies->display_price($this->customers_group_thePrice,tep_get_tax_rate($this->taxClass)).'</td></tr>';
			} else {
				$lc_text .= '<tr><td bgcolor="#FFFFFF">1 - 9</td><td bgcolor="#FFFFFF">'.$currencies->display_price($this->thePrice,tep_get_tax_rate($this->taxClass)).'</td></tr>';
			}
			// Eversun end mod  for SPPP Qty Price Break Enhancement
	    }
		// If you want to change the format of the price/quantity table
		// displayed on the product information page, here is where you do it.
	    if ($this->hasQuantityPrice == true) {
			// Eversun mod  for SPPP Qty Price Break Enhancement
			if($this->hasCustomersGroupPrice=='true')  {
				for($i=1; $i<=2; $i++) {
					$next_quantity =isset($this->quantity[$i+1]) ? ($this -> quantity[$i+1] - 1) : " Above";
					if($this->quantity[$i] > 0) {
						$lc_text .= '<tr><td bgcolor="#FFFFFF">'.$this->quantity[$i] . ' - ' .$next_quantity . '</td><td bgcolor="#FFFFFF">'. $currencies->display_price($this->customers_group_price[$i],  tep_get_tax_rate($this->taxClass))  .'</td></tr>';
					}
				}
			} else {
				for($i=1; $i<=2; $i++) {
					//$next_quantity = (isset($this -> quantity[$i+1]) ? ($this ->quantity[$i+1] - 1): "Above");
					if($this->quantity[$i] > 0) {
						// modifed by benny 
						//	if($this->price[$i]< $this->price[$i-1])
						//	{
							if($this->price[$i] == $this->price[$i]-1) {break;}
							$lc_text .= '<tr><td bgcolor="#FFFFFF">'
							. $this->quantity[$i] . ' - Above</td><td bgcolor="#FFFFFF">'
							. $currencies->display_price($this->price[$i],  tep_get_tax_rate($this->taxClass)) .'</td></tr>';
						//	}
						//end
					}
				}
				/*  $lc_text .= '<tr><td bgcolor="#FFFFFF">'
	            	. 'Above'. '</td><td bgcolor="#FFFFFF">'
	            	. $currencies->display_price($this->price[11],  tep_get_tax_rate($this->taxClass)) .'</td></tr>';*/
			}
			// Eversun mod end for SPPP Qty Price Break Enhancement
	    	$lc_text .= '</table>';
	    } else {
			if ($this->hasSpecialPrice == true) {
				$lc_text = '&nbsp;<s>--'
				. $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))
				. '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'
				. $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass))
				. '</span>&nbsp;';
			} else {
				// Eversun mod for SPPP Qty Price Break Enhancement
				if($this->hasCustomersGroupPrice=='true')  {
					$lc_text .= '<tr><td bgcolor="#FFFFFF">10 - Above</td><td bgcolor="#FFFFFF">'  . $currencies->display_price($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass))  . '</td></tr>';
				 // $lc_text = '&nbsp;'. $currencies->display_price($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass))  . '&nbsp;';
				} else {
					$lc_text .= '<tr><td bgcolor="#FFFFFF">10 - Above</td><td bgcolor="#FFFFFF">'  . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass))  . '</td></tr>';
				//  $lc_text = '&nbsp;'. $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
				}
				// Eversun mod end for SPPP Qty Price Break Enhancement
			}
	    }
	    $lc_text .='</table>';
	    return $lc_text;
	}
	//end
	function getPriceStringShort() {
		global $currencies, $customer_group_id;
		if ($this->hasSpecialPrice == true) {
			$lc_text = '<s>' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">'  . $currencies->display_price($this->specialPrice, tep_get_tax_rate($this->taxClass)) . '</span>&nbsp;';
		} else {
			if($this->hasQuantityPrice == true) {
				if($this->hasCustomersGroupPrice=='true')  {
					if ($this->customers_group_lowPrice != $this->customers_group_hiPrice && $this->customers_group_lowPrice != 0) {
						$lc_text = '&nbsp;' . $currencies->display_price($this->customers_group_lowPrice, tep_get_tax_rate($this->taxClass)) . ' - ' . $currencies->display_price($this->customers_group_hiPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
					} else {
						$lc_text = '&nbsp;' . $currencies->display_price($this->customers_group_lowPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
					}
				} else {
					if ($this->lowPrice != $this->hiPrice && $this->lowPrice != 0) {
					$lc_text = '&nbsp;' . $currencies->display_price($this->lowPrice, tep_get_tax_rate($this->taxClass)) . ' - '. $currencies->display_price($this->hiPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
					} else {
					$lc_text = '&nbsp;' . $currencies->display_price($this->hiPrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
					}
				}
			} else {
				if($this->hasCustomersGroupPrice=='true')  {
					$lc_text = '&nbsp;' . $currencies->display_price($this->customers_group_thePrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
				}  else {
					$lc_text = '&nbsp;' . $currencies->display_price($this->thePrice, tep_get_tax_rate($this->taxClass)) . '&nbsp;';
				}
			}
		}
		return $lc_text;
	}
	// Eversun mod  for SPPP Qty Price Break Enhancement
	function getCustomerGroupPrice() {
		return $this->customers_group_thePrice;
	}
	function getCustomerGroupLowPrice() {
		return $this->customers_group_lowPrice;
	}
	function getCustomerGroupHiPrice() {
		return $this->customers_group_hiPrice;
	}
	function hasCustomerGroupPrice() {
		return $this->hasCustomersGroupPrice;
	}
	function hasCustomerGroupcomputePrice($qty){
		$qty = $this->adjustQty($qty);
		// Compute base price, taking into account the possibility of a special
		$price = ($this->hasSpecialPrice === TRUE) ? $this->specialPrice : $this->thePrice;

		for ($i=1; $i<=11; $i++){
			if (($this->quantity[$i] > 0) && ($qty >= $this->quantity[$i])){ $price = $this->price[$i];}
		}
		// Eversun mod  for SPPP Qty Price Break Enhancement
		if($this->hasCustomersGroupPrice=='true') {
			$price =$this->customers_group_thePrice;
			for ($i=1; $i<=11; $i++){
				if (($this->quantity[$i] > 0) && ($qty > $this->quantity[$i])){ $price = $this->customers_group_price[$i];}
			}
		}
		// Eversun mod end for SPPP Qty Price Break Enhancement
		//echo '{price='.$price.'}';
		if ($this->hasSpecialPrice === TRUE && $this->specialPrice < $price) {
		  $price = $this->specialPrice;
		}
		 return $price;
	}
	// Eversun mod end for SPPP Qty Price Break Enhancement
}
?>