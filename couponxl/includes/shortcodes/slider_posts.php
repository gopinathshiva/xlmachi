<?php

function couponxl_slider_posts_func( $atts, $content ){
	extract( shortcode_atts( array(
		'posts' => '',
	), $atts ) );

	$slider_posts = explode( ",", $posts );
	if(wp_is_mobile()){
		return;
	}
	ob_start();
	include( locate_template( 'includes/box-elements/slider_posts.php' ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'slider_posts', 'couponxl_slider_posts_func' );

function couponxl_slider_posts_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Slider Posts ID","couponxl"),
			"param_name" => "posts",
			"value" => "",
			"description" => __("Input post ID here.","couponxl")
		)
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Slider Posts", 'couponxl'),
	   "base" => "slider_posts",
	   "category" => __('Content', 'couponxl'),
	   "params" => couponxl_slider_posts_params()
	) );
}

?>
