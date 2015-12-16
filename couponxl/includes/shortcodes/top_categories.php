<?php

function couponxl_top_categories_func( $atts, $content ){
	extract( shortcode_atts( array(
		'icon' => '',
		'title' => '',
		'small_title' => '',
		'small_title_link' => '',
		'top_categories' => '',		
		'categories_number' => '4',
		'categories_per_row'=>'3',
		'top_stores'=>''
	), $atts ) );
	
	$is_shortcode = true;
	$col = 4;	
	ob_start();
	include( locate_template( 'includes/box-elements/top_categories.php' ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'top_categories', 'couponxl_top_categories_func' );

function couponxl_top_categories_params(){	
	$categories_per_row = array();
	$categories_per_row['3'] = 3;
	$categories_per_row['4'] = 4;
	return array(		
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Icon","couponxl"),
			"param_name" => "icon",
			"value" => couponxl_awesome_icons_list(),
			"description" => __("Select icon","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title","couponxl"),
			"param_name" => "title",
			"value" => "",
			"description" => __("Input title.","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Small Title","couponxl"),
			"param_name" => "small_title",
			"value" => '',
			"description" => __("Input title for the right link.","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Small Title Link","couponxl"),
			"param_name" => "small_title_link",
			"value" => '',
			"description" => __("Input link for the small title. eg) stores, categories","couponxl")
		),
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Top Categories","couponxl"),
			"param_name" => "top_categories",
			"value" => couponxl_get_custom_tax_list( 'offer_cat', 'left' ),
			"description" => __("Select Top Categories.","couponxl")
		),		
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Top Stores","couponxl"),
			"param_name" => "top_stores",
			"value" => couponxl_get_custom_list( 'store', array(), '', 'left' ),
			"description" => __("Select Top Stores.","couponxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Items per row","couponxl"),
			"param_name" => "categories_per_row",
			"value" => $categories_per_row,
			"description" => __("Enter the number of ItemsCategories/Stores per row","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Number of Items to Show","couponxl"),
			"param_name" => "categories_number",
			"value" => '',
			"description" => __("Input number of categories/stores","couponxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Top Categories", 'couponxl'),
	   "base" => "top_categories",
	   "category" => __('Content', 'couponxl'),
	   "params" => couponxl_top_categories_params()
	) );
}

?>