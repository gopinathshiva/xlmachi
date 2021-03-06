<?php
function couponxl_featured_stores_func( $atts, $content ){
	global $shortcode_transient_lifetime;

	extract( shortcode_atts( array(
		'title' => '',
		'text' => '',
		'target' => '',
		'btn_text' => '',
		'link' => '',
		'items' => '',
		'is_carousel' => false,
		'title' => '',
		'option1' => '',
		'option2' => '',
		'option3' => '',
		'option4' => '',
		'option5' => '',
	), $atts ) );

	$transient_args = $atts;
	$transient_namespace = xl_transient_namespace();
	$transient_key = $transient_namespace .md5( serialize($transient_args) );
	if(is_localhost()){
		delete_transient( $transient_key );
	}
	if ( false === ( $content = get_transient( $transient_key ) ) ) {
		if($is_carousel){
			$items = explode( ",", $items );
		}else{
			$option_1 = explode( ",", $option1 );
			$option_2 = explode( ",", $option2 );
			$option_3 = explode( ",", $option3 );
			$option_4 = explode( ",", $option4 );
			$option_5 = explode( ",", $option5 );
			$items = array_merge($option_1, $option_2, $option_3,$option_4,$option_5);
			$items = array_unique($items);
		}
		ob_start();
		include( locate_template( 'includes/box-elements/featured-stores.php' ) );
		$content = ob_get_contents();
		ob_end_clean();
		set_transient( $transient_key, $content, $shortcode_transient_lifetime );
	}

	return $content;
}

add_shortcode( 'featured_stores', 'couponxl_featured_stores_func' );

function couponxl_featured_stores_params(){
	return array(
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
			"type" => "textarea",
			"holder" => "div",
			"class" => "",
			"heading" => __("Text","couponxl"),
			"param_name" => "text",
			"value" => '',
			"description" => __("Input text.","couponxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Select Window","couponxl"),
			"param_name" => "target",
			"value" => array(
				__( 'Same Window', 'couponxl' ) => '_self',
				__( 'New Window', 'couponxl' ) => '_blank',
			),
			"description" => __("Select window where to open the link.","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Button Text","couponxl"),
			"param_name" => "btn_text",
			"value" => '',
			"description" => __("Input button text.","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Button Link","couponxl"),
			"param_name" => "link",
			"value" => '',
			"description" => __("Input button link.","couponxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Items","couponxl"),
			"param_name" => "items",
			"value" => "",
			"description" => __("Input items you wish to show in comma separated list.","couponxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Featured Stores", 'couponxl'),
	   "base" => "featured_stores",
	   "category" => __('Content', 'couponxl'),
	   "params" => couponxl_featured_stores_params()
	) );
}

?>
