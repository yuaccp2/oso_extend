<?php
/*
  $Id: database_tables.php,v 1.1.1.1 2004/03/04 23:40:38 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

define('TABLE_VISUAL_VERIFY_CODE', 'visual_verify_code');

// define the database table names used in the project
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_DESCRIPTION', 'banners_description');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTER', 'counter');
  define('TABLE_COUNTER_HISTORY', 'counter_history');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_RELATED', 'products_related');
   define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_TEXT', 'products_options_text');
//  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_value');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TEXT', 'products_options_value_text');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

// Added for Xsell Products Mod
  define('TABLE_PRODUCTS_XSELL', 'products_xsell');

// Lango Added for template and infobox mod
  define('TABLE_INFOBOX_CONFIGURATION', 'infobox_configuration');
  define('TABLE_TEMPLATE', 'template');
  define('TABLE_INFOBOX_HEADING', 'infobox_heading');
  
// Lango Added for Salemaker mod
  define('TABLE_SALEMAKER_SALES', 'salemaker_sales');

// Lango Added for Featured Products
  define('TABLE_FEATURED', 'featured');

// Lango Added for Wishlist
  define('TABLE_WISHLIST', 'customers_wishlist');
  define('TABLE_WISHLIST_ATTRIBUTES', 'customers_wishlist_attributes');

// VJ Links Manager v1.00 begin
  define('TABLE_LINK_CATEGORIES', 'link_categories');
  define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');
  define('TABLE_LINKS', 'links');
  define('TABLE_LINKS_DESCRIPTION', 'links_description');
  define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');
// VJ Links Manager v1.00 end

// BMC CC Mod Start
  define('TABLE_BLACKLIST', 'card_blacklist');
// BMC CC Mod End
  define('TABLE_INFORMATION', 'information');
//added for GV
  define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');
  define('TABLE_COUPON_GV_QUEUE', 'coupon_gv_queue');
  define('TABLE_COUPON_REDEEM_TRACK', 'coupon_redeem_track');
  define('TABLE_COUPON_EMAIL_TRACK', 'coupon_email_track');
  define('TABLE_COUPONS', 'coupons');
  define('TABLE_COUPONS_DESCRIPTION', 'coupons_description');
  
// define the database table names used in the contribution
  define('TABLE_AFFILIATE', 'affiliate_affiliate');
  define('TABLE_AFFILIATE_NEWS', 'affiliate_news');
  define('TABLE_AFFILIATE_NEWS_CONTENTS', 'affiliate_news_contents');
// if you change this -> affiliate_show_banner must be changed too
  define('TABLE_AFFILIATE_BANNERS', 'affiliate_banners');
  define('TABLE_AFFILIATE_BANNERS_HISTORY', 'affiliate_banners_history');
  define('TABLE_AFFILIATE_CLICKTHROUGHS', 'affiliate_clickthroughs');
  define('TABLE_AFFILIATE_SALES', 'affiliate_sales');
  define('TABLE_AFFILIATE_PAYMENT', 'affiliate_payment');
  define('TABLE_AFFILIATE_PAYMENT_STATUS', 'affiliate_payment_status');
  define('TABLE_AFFILIATE_PAYMENT_STATUS_HISTORY', 'affiliate_payment_status_history');
//CALENDAR
define('TABLE_EVENTS_CALENDAR', 'events_calendar');

// Added for FAQ System 2.1 DMG
define('TABLE_FAQ','faq');
define('TABLE_FAQ_DESCRIPTION', 'faq_description');

// VJ faq manager added
  define('TABLE_FAQ_CATEGORIES', 'faq_categories');
  define('TABLE_FAQ_CATEGORIES_DESCRIPTION', 'faq_categories_description');
  define('TABLE_FAQ_TO_CATEGORIES', 'faq_to_categories');

//Added for Article Manager

  define('TABLE_ARTICLE_REVIEWS', 'article_reviews');
  define('TABLE_ARTICLE_REVIEWS_DESCRIPTION', 'article_reviews_description');
  define('TABLE_ARTICLES', 'articles');
  define('TABLE_ARTICLES_DESCRIPTION', 'articles_description');
  define('TABLE_ARTICLES_TO_TOPICS', 'articles_to_topics');
  define('TABLE_ARTICLES_XSELL', 'articles_xsell');
  define('TABLE_AUTHORS', 'authors');
  define('TABLE_AUTHORS_INFO', 'authors_info');
  define('TABLE_TOPICS', 'topics');
  define('TABLE_TOPICS_DESCRIPTION', 'topics_description');
  
// START: Product Extra Fields  DMG
    define('TABLE_PRODUCTS_EXTRA_FIELDS', 'products_extra_fields');
    define('TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS', 'products_to_products_extra_fields');
// END: Product Extra Fields

// Contact US Email Subject : DMG
  define('TABLE_EMAIL_SUBJECTS', 'email_subjects');

  // Eversun mod for sppc and qty price breaks
  define('TABLE_PRODUCTS_GROUPS', 'products_groups');
  define('TABLE_SPECIALS_RETAIL_PRICES', 'specials_retail_prices');
  define('TABLE_PRODUCTS_GROUP_PRICES', 'products_group_prices_cg_');
  define('TABLE_CUSTOMERS_GROUPS', 'customers_groups');
  // this will define the maximum time in minutes between updates of a products_group_prices_cg_# table
  // changes in table specials will trigger an immediate update if a query needs this particular table
  define('MAXIMUM_DELAY_UPDATE_PG_PRICES_TABLE', '15');
  // Eversun mod end for sppc and qty price breaks

// VJ  CRE Page Manager begin
  define('TABLE_PAGES_CATEGORIES', 'pages_categories');
  define('TABLE_PAGES_CATEGORIES_DESCRIPTION', 'pages_categories_description');
  define('TABLE_PAGES', 'pages');
  define('TABLE_PAGES_DESCRIPTION', 'pages_description');
  define('TABLE_PAGES_TO_CATEGORIES', 'pages_to_categories');
// VJ Page Manager end

// modified by benny 
define('TABLE_PRODUCTS_PRICE_PROPOSAL','products_price_proposal');
define('TABLE_NEWSLETTERS_SUBSCRIBERS','newsletters_subscribers');
define('TABLE_SEARCH_HOT','search_hot');

define('TABLE_PRODUCTS_DES_ATTRIBUTES','products_des_attributes');
define('TABLE_PRODUCTS_DES_OPTIONS','products_des_options');
define('TABLE_PRODUCTS_DES_OPTIONS_VALUES','products_des_options_values');
define('TABLE_PRODUCTS_DES_OPTIONS_VALUES_TO_PRODUCTS_DES_OPTIONS','products_des_options_values_to_products_des_options');
//end

//-MS- SEO-G Added
  define('TABLE_SEO_URL', 'seo_url');
  define('TABLE_SEO_REDIRECT', 'seo_redirect');
  define('TABLE_SEO_TYPES', 'seo_types');
  define('TABLE_SEO_EXCLUDE', 'seo_exclude');
  define('TABLE_SEO_TO_CATEGORIES','seo_to_categories');
  define('TABLE_SEO_TO_PRODUCTS','seo_to_products');
  define('TABLE_SEO_TO_MANUFACTURERS','seo_to_manufacturers');
//-MS- SEO-G Added EOM

//-MS- SEO-G Support for Articles Manager
  define('TABLE_SEO_TO_TOPICS','seo_to_topics');
  define('TABLE_SEO_TO_ARTICLES','seo_to_articles');
  define('TABLE_SEO_TO_AUTHORS','seo_to_authors');
//-MS- SEO-G Support for Articles Manager EOM

//-MS- SEO-G Support for Information Pages Unlimited
  define('TABLE_SEO_TO_INFORMATION','seo_to_information');
//-MS- SEO-G Support for Information Pages Unlimited EOM

//-MS- SEO-G Support for Links Manager
  define('TABLE_SEO_TO_LINKS','seo_to_links');
//-MS- SEO-G Support for Links Manager EOM

// BOF: Alex 2009-7-24 Added for Featured category MOD
  define('TABLE_FEATURED_CATEGORIES', 'featured_categories');
// EOF: Alex 2009-7-24 Added for Featured category MOD

// BOF: Alex 2009-7-31 Added for Products Tags MOD
  define('TABLE_PRODUCTS_TAGS', 'products_tags');
// EOF: Alex 2009-7-31 Added for Products Tags MOD
// BOF: Alex 2009-8-13 Added for Products New & Products Hot MOD
  define('TABLE_PRODUCTS_NEW', 'products_new');
  define('TABLE_PRODUCTS_HOT', 'products_hot');
// EOF: Alex 2009-8-13 Added for Products New & Products Hot MOD
// BOF: Alex 2009-9-15 Added for Products ordered MOD
  define('TABLE_PRODUCTS_ORDERED', 'products_ordered');
// EOF: Alex 2009-9-15 Added for Products ordered MOD

// BOF: Alex 2009-10-19 Added for newsletter MOD
define('TABLE_NEWSLETTERS','newsletters');
// BOF: Alex2009-10-22 Added for products bind for sale MOD
define('TABLE_PRODUCTS_BIND','products_bind');
define('TABLE_PRODUCTS_BIND_OPTIONAL','products_bind_optional');
//Alex 2009-12-7 added for extra customers email
define('TABLE_CUSTOMERS_EMAIL_EXTRA','customers_email_extra');
define('TABLE_NEWSLETTERS_SEND_EMAIL_LOG','newsletters_send_email_log');
//alex 2009-12-23 modified 
define('TABLE_PRODUCTS_DISCOUNT','products_discount');
define('TABLE_SEARCH_HOT_RESULT', 'search_hot_result');
define('TABLE_SEARCH_HOT', 'search_hot');

//consumer integral  BY nathan 2011-8-30
define('TABLE_MEMBERSHIP_POINT_RULE', 'membership_point_rule');
define('TABLE_MEMBERSHIP_POINT_RULE_DESCRIPTION', 'membership_point_rule_description');
define('TABLE_MEMBERSHIP_POINT_LOG', 'membership_point_log');
define('TABLE_CUSTOMERS_LEVEL', 'customers_level');
define('TABLE_CUSTOMERS_LEVEL_DESCRIPTION', 'customers_level_description');
define('TABLE_CUSTOMERS_COUPONS', 'customers_coupons');
define('TABLE_COUPONS_RULE', 'coupons_rule');
define('TABLE_COUPONS_RULE_DESCRIPTION', 'coupons_rule_description');
define('TABLE_CUSTOMERS_EXCHANGE_PRODUCTS', 'customers_exchange_products');
define('TABLE_CUSTOMERS_EXCHANGE_PRODUCTS_LOG', 'customers_exchange_products_log');

define('TABLE_PRODUCTS_SECKILL','products_seckill');
define('TABLE_PRODUCTS_SECKILL_DESCRIPTION','products_seckill_description');
define('TABLE_CUSTOMERS_WISHLIST', 'customers_wishlist');
define('TABLE_CUSTOMERS_WISHLIST_ATTRIBUTES', 'customers_wishlist_attributes');

define('TABLE_REVIEWS_MEDIA', 'reviews_media');
define('TABLE_CUSTOMERS_INQUERY', 'customers_inquery');
define('TABLE_CUSTOMERS_MESSAGE', 'customers_message');
define('TABLE_CUSTOMERS_PRESALE', 'customers_presale');

?>
