<?php
function couponxl_blogs_func( $atts, $content ){
	extract( shortcode_atts( array(
		'icon' => '',
		'title' => '',
		'small_title' => '',
		'items' => '',
		'blogs_orderby' => 'date',
		'blogs_order' => 'DESC',
		'blogs_number' => '-1',
		'blogs_per_row' => 3
	), $atts ) );

	$items = explode( ",", $items );
	$col = 4;
	$offer_view = '';
	ob_start();
	include( locate_template( 'includes/box-elements/blogs.php' ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'blogs', 'couponxl_blogs_func' );

function couponxl_blogs_params(){
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
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Blog Items","couponxl"),
			"param_name" => "items",
			"value" => couponxl_get_custom_list( 'post', array(), '', 'left' ),
			"description" => __("Select blogs you wish to present","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Blogs per page","couponxl"),
			"param_name" => "blogs_number",
			"value" => '',
			"description" => __("Enter number of blogs to show (default - shows all selected items)","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Blog Items per Row","couponxl"),
			"param_name" => "blogs_per_row",
			"value" => '',
			"description" => __("Enter number of blogs to show per row","couponxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Order By","couponxl"),
			"param_name" => "blogs_orderby",
			"value" => array(
				__( 'Date Added', 'couponxl' ) => 'date',
				__( 'Title', 'couponxl' ) => 'title',
			),
			"description" => __("Select by which field to order blogs","couponxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Order","couponxl"),
			"param_name" => "blogs_order",
			"value" => array(
				__( 'Ascending', 'couponxl' ) => 'ASC',
				__( 'Descending', 'couponxl' ) => 'DESC',
			),
			"description" => __("Select how to order blogs","couponxl")
		),		
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Blogs", 'couponxl'),
	   "base" => "blogs",
	   "category" => __('Content', 'couponxl'),
	   "params" => couponxl_blogs_params()
	) );
}

?>