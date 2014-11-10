<?php
echo "<pre>";

ini_set('memory_limit', '-1');

/*
$lines = file("columns.txt");
$line = $lines[0];

$columns = explode("\t", $line);
echo "'" . implode("', '", $columns) . "'";
exit;
*/

$connect = mysql_connect("localhost", "root", "");
mysql_set_charset('utf8',$connect);
mysql_select_db("vfoutlet_import", $connect);


$products_data = array();

$product_labels = array("sku","_store","_attribute_set","_type","_category","_root_category","_product_websites","amconf_simple_price","color","cost","country_of_manufacture","created_at","custom_design","custom_design_from","custom_design_to","custom_layout_update","description","enable_googlecheckout","exclude_from_sitemap","gallery","gift_message_available","has_options","image","image_label","length","manufacturer","media_gallery","meta_description","meta_keyword","meta_title","minimal_price","model","msrp","msrp_display_actual_price_type","msrp_enabled","name","news_from_date","news_to_date","options_container","page_layout","price","required_options","shirt_size","short_description","small_image","small_image_label","special_from_date","special_price","special_to_date","status","tax_class_id","thumbnail","thumbnail_label","updated_at","url_key","url_path","visibility","weight","qty","min_qty","use_config_min_qty","is_qty_decimal","backorders","use_config_backorders","min_sale_qty","use_config_min_sale_qty","max_sale_qty","use_config_max_sale_qty","is_in_stock","notify_stock_qty","use_config_notify_stock_qty","manage_stock","use_config_manage_stock","stock_status_changed_auto","use_config_qty_increments","qty_increments","use_config_enable_qty_inc","enable_qty_increments","is_decimal_divided","_links_related_sku","_links_related_position","_links_crosssell_sku","_links_crosssell_position","_links_upsell_sku","_links_upsell_position","_associated_sku","_associated_default_qty","_associated_position","_tier_price_website","_tier_price_customer_group","_tier_price_qty","_tier_price_price","_group_price_website","_group_price_customer_group","_group_price_price","_media_attribute_id","_media_image","_media_lable","_media_position","_media_is_disabled","_super_products_sku","_super_attribute_code","_super_attribute_option","_super_attribute_price_corr");

//echo count($product_labels);
//exit;


$empty_array = array();

for($i=0; $i<count($product_labels); $i++) {
	$empty_array[] = '';
}

$empty_data = array_combine($product_labels, $empty_array);


$result = mysql_query("SELECT * FROM `bali_bra` group by `style`");

$products_data[] = $product_labels;


while($row = mysql_fetch_array($result)) {

	$data = $empty_data;

	$prod_added = false;

	//print_r($row);


	$configurable_sku = trim($row['style']);

	//exit;
	// configurable products
	
	$data['sku'] = $configurable_sku;
	$data['_attribute_set'] = trim($row['department']);
	$data['_type'] =  'configurable';
	$data['_category'] = trim($row['department']).'/'.trim($row['main_category']).'/'.trim($row['sub_category']);
	$data['_root_category'] = 'Root Catalog';
	$data['_product_websites'] = 'base';
	$data['name'] = trim(html_entity_decode(trim($row['item_title']))); 
	//preg_replace('/[^a-zA-Z0-9 .\/]/', " ", $row['item_title']);

	if($row['description'] != '')
	{
	//	$data['description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
	//  $data['description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['description'] = trim(html_entity_decode(trim($row['description'])));
	//	$data['short_description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['short_description'] = trim(html_entity_decode(trim($row['description'])));
	}
	else{
		$data['description'] = trim(html_entity_decode(trim($row['item_title'])));
	//	$data['short_description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['short_description'] = trim(html_entity_decode(trim($row['item_title'])));
	}
	$data['enable_googlecheckout'] =  1;
	$data['has_options'] =  1;
	if(isset($row['image']))
	{
		$images = explode(",", $row['image']);
		
		$data['image'] =  '/' . trim($images[0]);
		$data['small_image'] =  '/' . trim($images[0]);
		$data['thumbnail'] =  '/' . trim($images[0]);
		$data['media_gallery'] =  '/' . trim($images[0]);
		
	}
	else
	{
		$data['media_gallery'] = 'inner';
	}
	$data['meta_title'] = '';
	$data['msrp_display_actual_price_type'] =  'Use config';
	$data['msrp_enabled'] =  'Use config';
	
	$data['options_container'] =  'Block after Info Column';


	//$prices = explode("$", $row['price']);
	$prices = explode("$", $row['you_pay']);
	$data['price'] =  $prices[1];
	
	/*$data['_tier_price_website'] = 'all';
	$data['_tier_price_customer_group'] = 'all';
	$data['_tier_price_qty'] = 3;
	$data['_tier_price_price'] = 21;*/
	
	//$sprices = explode("$", $row['special_price']);
	//$data['special_from_date'] = '9/10/2013 0:00';
	//$data['special_price'] = trim($sprices[1]);
	$data['required_options'] = 1;
	$data['status'] = 2;
	$data['url_key'] = '';

	$data['tax_class_id'] = 1;
	$data['visibility'] =4;


	$data['qty'] = 0;
	$data['min_qty'] = 0;
	$data['use_config_min_qty'] = 1;
	$data['is_qty_decimal'] = 0;
	$data['backorders'] = 0;
	$data['use_config_backorders'] = 1;
	$data['is_decimal_divided'] = 0;

	$data['min_sale_qty'] = 1;
	$data['use_config_min_sale_qty'] = 1;
	$data['max_sale_qty'] = 0;
	$data['use_config_max_sale_qty'] = 1;
	$data['is_in_stock'] = 1;
	$data['notify_stock_qty'] = '';
	$data['use_config_notify_stock_qty'] = 1;
	$data['manage_stock'] = 0;
	$data['use_config_manage_stock'] = 1;
	$data['stock_status_changed_auto'] = 0;
	$data['use_config_qty_increments'] = 1;
	$data['qty_increments'] = 0;
	$data['use_config_enable_qty_inc'] = 1;
	$data['enable_qty_increments'] = 0;
	$data['is_decimal_divided'] = 0;

	if(isset($row['image']))
	{
		$data['_media_attribute_id'] = '703';
		$data['_media_image'] = '/' . trim($images[0]);
		$data['_media_position'] = 1;
		$data['_media_is_disabled'] = 0;
	}
	$data['media_gallery'] =  'no_selection';

	
	$sql = "select * from bali_bra where style ='".$row['style']."'";
	$simp = mysql_query($sql);
	$int = 0;
	$img_index = 1;
	$config_main_data = array();
	while($val = mysql_fetch_array($simp))
	{
	
		if($int == 0)
		{
			$data['_super_products_sku'] = $val['upc'];
			$data['_super_attribute_code'] = 'color';
			$data['_super_attribute_option'] = trim($val['color']);
			$config_main_data[] = $data;
			$new_row = $empty_data;
			if($val['size'] != '')
			{
				$new_row['_super_products_sku'] = $val['upc'];
				$new_row['_super_attribute_code'] = 'shirt_size';
				if($val['sizecode'] != '')
				{
					$new_row['_super_attribute_option'] = trim($val['size']).'/'. trim($val['sizecode']);
				}
				else
				{
					$new_row['_super_attribute_option'] = trim($val['size']);
				}
				if(isset($row['image']))
				{
					if(isset($images[$img_index]))
					{
						$new_row['_media_image'] = '/' . trim($images[$img_index]);
						$new_row['_media_position'] = $img_index + 1;
						$new_row['_media_is_disabled'] = 0;
						$new_row['_media_attribute_id'] = '703';
						$img_index++;
					}
				}
				$config_main_data[] = $new_row;
			}
			
			$new_row = $empty_data;
			if(isset($val['length'] ))
			{
				$new_row['_super_products_sku'] = $val['upc'];
				$new_row['_super_attribute_code'] = 'length';
				$new_row['_super_attribute_option'] = $val['length'];
				if($row['image'] != '')
				{
					if(isset($images[$img_index]))
					{
						$new_row['_media_image'] = '/' . trim($images[$img_index]);
						$new_row['_media_position'] = $img_index + 1;
						$new_row['_media_is_disabled'] = 0;
						$new_row['_media_attribute_id'] = '703';
						$img_index++;
					}
				}
				$config_main_data[] = $new_row;
			}
			//print_r($data); exit();
		}
		else
		{
			$new_row = $empty_data;
			$new_row['_super_products_sku'] = $val['upc'];
			$new_row['_super_attribute_code'] = 'color';
			$new_row['_super_attribute_option'] = trim($val['color']);
			if(isset($row['image']))
			{
				if(isset($images[$img_index]))
				{
					$new_row['_media_image'] = '/' . trim($images[$img_index]);
					$new_row['_media_position'] = $img_index + 1;
					$new_row['_media_is_disabled'] = 0;
					$new_row['_media_attribute_id'] = '703';
					$img_index++;
				}
			}
			$config_main_data[] = $new_row;
			$new_row = $empty_data;
			if($val['size'] != '')
			{
				$new_row['_super_products_sku'] = $val['upc'];
				$new_row['_super_attribute_code'] = 'shirt_size';
				if($val['sizecode'] != '')
				{
					$new_row['_super_attribute_option'] = trim($val['size']).'/'. trim($val['sizecode']);
				}
				else
				{
					$new_row['_super_attribute_option'] = trim($val['size']);
				}
				if(isset($row['image']))
				{
					if(isset($images[$img_index]))
					{
						$new_row['_media_image'] = '/' . trim($images[$img_index]);
						$new_row['_media_position'] = $img_index + 1;
						$new_row['_media_is_disabled'] = 0;
						$new_row['_media_attribute_id'] = '703';
						$img_index++;
					}
				}
				$config_main_data[] = $new_row;
			}
			$new_row = $empty_data;
			if(isset($val['length']))
			{
				$new_row['_super_products_sku'] = $val['upc'];
				$new_row['_super_attribute_code'] = 'length';
				$new_row['_super_attribute_option'] = $val['length'];
				if($row['image'] != '')
				{
					if($images[$img_index])
					{
						$new_row['_media_image'] = '/' . trim($images[$img_index]);
						$new_row['_media_position'] = $img_index + 1;
						$new_row['_media_is_disabled'] = 0;
						$new_row['_media_attribute_id'] = '703';
						$img_index++;
					}
				}
				$config_main_data[] = $new_row;
			}
		}
		$int = 1;
	}

	$img_index =1;
	$simp = mysql_query($sql);
	while($row = mysql_fetch_array($simp))
	{
		$data = $empty_data;
		$data['sku'] = $row['upc'];
		$data['_attribute_set'] = $row['department'];
		$data['_type'] =  'simple';
		$data['_category'] = $row['department'].'/'.$row['main_category'].'/'.$row['sub_category'];
		$data['_root_category'] = 'Root Catalog';
		$data['_product_websites'] = 'base';
		$data['name'] = trim(html_entity_decode(trim($row['item_title']))); 
		//preg_replace('/[^a-zA-Z0-9 .\/]/', " ", $row['item_title']);
		if($row['description'] != '')
		{
	//	$data['description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
	//  $data['description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['description'] = trim(html_entity_decode(trim($row['description'])));
	//	$data['short_description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['short_description'] = trim(html_entity_decode(trim($row['description'])));
		}
		else{
		$data['description'] = trim(html_entity_decode(trim($row['item_title'])));
	//	$data['short_description'] = preg_replace('/[^a-zA-Z0-9 .\/]/', " ", strip_tags($row['description']));
		$data['short_description'] = trim(html_entity_decode(trim($row['item_title'])));
		}
		$data['enable_googlecheckout'] =  1;
		$data['has_options'] =  1;
		
		if(isset($row['image'] ))
		{
			$images = explode(",", $row['image']);
			$data['image'] =  '/' . trim($images[0]);
			$data['small_image'] =  '/' . trim($images[0]);
			$data['thumbnail'] =  '/' . trim($images[0]);
			$data['media_gallery'] =  '/' . trim($images[0]);
		}
		else
		{
			$data['media_gallery'] = 'no_selection';

		}
		$data['meta_title'] = '';
		$data['msrp_display_actual_price_type'] =  'Use config';
		$data['msrp_enabled'] =  'Use config';
	//	$data['name'] =  preg_replace('/[^a-zA-Z0-9 .\/]/', " ", $row['item_title']);
		$data['options_container'] =  'Block after Info Column';
		
		$prices = explode("$", trim($row['you_pay']));
		$data['price'] =  $prices[1];
		//$sprices = explode("$", $row['comp']);
		#$data['special_from_date'] = '9/10/2013 0:00';
		//$data['special_price'] = trim($sprices[1]);
		
		$data['weight'] = 1.0;
		$data['qty'] = 0;
		$data['color'] = trim($row['color']);
		if($row['sizecode'] != '')
		{
			$data['shirt_size'] = trim($row['size']).'/'.trim($row['sizecode']);
		}
		else
		{
			$data['shirt_size'] = trim($row['size']);
		}
		#$data['length'] = $row['length'];
		$data['required_options'] = 0;
		$data['status'] = 2;
		$data['url_key'] = '';
		$data['tax_class_id'] = 1;
		$data['visibility'] =1;
		$data['min_qty'] = 0;
		$data['use_config_min_qty'] = 1;
		$data['is_qty_decimal'] = 0;
		$data['backorders'] = 0;
		$data['use_config_backorders'] = 1;
		$data['is_decimal_divided'] = 0;
		$data['min_sale_qty'] = 1;
		$data['use_config_min_sale_qty'] = 1;
		$data['max_sale_qty'] = 0;
		$data['use_config_max_sale_qty'] = 1;
		$data['is_in_stock'] = 1;
		$data['notify_stock_qty'] = '';
		$data['use_config_notify_stock_qty'] = 1;
		$data['manage_stock'] = 0;
		$data['use_config_manage_stock'] = 1;
		$data['stock_status_changed_auto'] = 0;
		$data['use_config_qty_increments'] = 1;
		$data['qty_increments'] = 0;
		$data['use_config_enable_qty_inc'] = 1;
		$data['enable_qty_increments'] = 0;
		$data['is_decimal_divided'] = 0;
		if(isset($row['image']))
		{
			$data['_media_attribute_id'] = '703';
			$data['_media_image'] = '/' . trim($images[0]);
			$data['_media_position'] = 1;
			$data['_media_is_disabled'] = 0;
			
		}
		$data['media_gallery'] =  'no_selection';
		$products_data[] = $data;
		if(isset($row['image']))
		{
				for($img = 1;$img < count($images);$img++)
				{
					$data = $empty_data;
					$data['_media_image'] = '/' . trim($images[$img]);
					$data['_media_position'] = $img + 1;
					$data['_media_is_disabled'] = 0;
					$data['_media_attribute_id'] = '703';
					$products_data[] = $data;
					//print_r($products_data);
				}
		}
		
	}
	if(count($config_main_data) > 0) {

		foreach($config_main_data as $conf_data) {
			$products_data[] = $conf_data;
		}
	}
	
//	print_r($products_data);
//	exit;

}


/* Create TXT FILE with product data */

if(count($products_data) > 0) {

	$fp = fopen('bali_bra.txt', 'w');

	foreach ($products_data as $product) {
		//	print_r($product);
			fputcsv($fp, $product, "\t");
	}

	fclose($fp);
}

echo "DONE!";



