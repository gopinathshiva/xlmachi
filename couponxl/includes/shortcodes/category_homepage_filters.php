<?php

function couponxl_category_homepage_filters_func( $atts, $content ){
	extract( shortcode_atts( array(
		'custom_category_title' => '',
		'ajax_categories' => '',
		'offers_per_row' => 3,
		'offers_number' => 20
	), $atts ) );

	$ajax_categories = explode( ",", $ajax_categories );
		
	ob_start();
	include( locate_template( 'includes/box-elements/category_homepage_filters.php' ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'category_homepage_filters', 'couponxl_category_homepage_filters_func' );

function couponxl_category_homepage_filters_params(){	
	$offers_per_row = array();
	$offers_per_row['3'] = 3;
	$offers_per_row['4'] = 4;	
	return array(				
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Custom Category Title","couponxl"),
			"param_name" => "custom_category_title",
			"value" => '',
			"description" => __("Input Custom Category Title. eg) Top Coupons, Most Clicked Coupons","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Number of Offers to show","couponxl"),
			"param_name" => "offers_number",
			"value" => '',
			"description" => __("Input no. of offers to show on home page filter. default - 20","couponxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Items per row","couponxl"),
			"param_name" => "offers_per_row",
			"value" => $offers_per_row,
			"description" => __("Enter the number of Offers per row. Default is 3","couponxl")
		),
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Ajax Enabled HomePage Categories","couponxl"),
			"param_name" => "ajax_categories",
			"value" => couponxl_get_custom_tax_list( 'offer_cat', 'left' ),
			"description" => __("Select Categories to show in HomePage with Ajax enabled","couponxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Ajax HomePage Categories Filter", 'couponxl'),
	   "base" => "category_homepage_filters",
	   "category" => __('Content', 'couponxl'),
	   "params" => couponxl_category_homepage_filters_params()
	) );
}

?>