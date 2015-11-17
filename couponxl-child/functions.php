<?php

function new_excerpt_length($length) {
	return 15;
}
add_filter('excerpt_length', 'new_excerpt_length');

function adding_custom_scripts() {
	wp_register_script('custom-script', 'http://localhost/CouponMachi/wp-content/themes/couponxl-child/js/custom-script.js','',null, true);
	wp_enqueue_script('custom-script');
}
add_action( 'wp_enqueue_scripts', 'adding_custom_scripts' ); 

add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {

        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();

        $searchform = '<form method="get" action="http://couponmachi.com/search-page/" class="clearfix"> 
                                    <i class="fa fa-search icon-margin" style="position: absolute;right: 3px;top: 5px;"></i>
                                    <div class="">
                                        <input style="border-radius: 8px;outline: 0;border: 1px solid rgba(0, 0, 0, 0.3);text-align:center" type="text" class="" value="" placeholder="Search" name="keyword">
                                    </div>
                                </form>';

        $items .= '<li style="position:relative">' . $searchform . '</li>';

    return $items;
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 3 );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/*

SITE CUSTOMISATIONS DONE IN MAIN THEME

1. ADDED SEARCH INPUT IN HOME PAGE
2. RENAMED 'SUBMIT' TO 'SUBMIT OFFERS'
3. LOCATION INPUT IS HIDDEN IN HEADER & BODY
4. Shortcode changes done in deals,coupons and blogs -> number of deals/coupons/blogs per row
5. added search in nav menu
6. changed content padding
7. added deal info and coupon info

*/

/* edited in includes/offers/deals.php */

/* adding slider short code function to enable dynamic items in slider short code, edited main theme's slider.php and featured-slider.php */

function couponxl_slider_func( $atts, $content ){
		extract( shortcode_atts( array(
			'icon' => '',
			'title' => '',
			'small_title' => '',
			'items' => '',
			'blogs_orderby' => 'date',
			'blogs_order' => 'DESC'
		), $atts ) );
		ob_start();
		$items = explode( ",", $items );
		
		include( locate_template( 'includes/featured-slider.php' ) );
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}


/* adding Indian currency symbol to theme */
function couponxl_format_price_number( $price ){
		
	if( !is_numeric( $price ) ){
		return $price;
	}
	$unit_position = couponxl_get_option( 'unit_position' );
	$unit = '₹';
	
	if( $unit_position == 'front' ){
		return $unit.number_format( $price, 2 );
	}
	else{
		return $unit.number_format( $price, 2 );
	}
}

/* adding this to block html content in comment section */
add_filter( 'pre_comment_content', 'esc_html' );

/* adding post revision limit to 5 */
define( 'WP_POST_REVISIONS', 5);

/* increasing auto save interval to 2 minutes from default 1 minute */
define( 'AUTOSAVE_INTERVAL', 120 );

/* hiding admin bar in site pages */
add_filter('show_admin_bar', '__return_false');

/* removing emoji + emoticons */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
if(!function_exists(debug_to_console)){
	function debug_to_console( $data ) {

	    if ( is_array( $data ) )
	        $output = "<script>console.log( 'Debug child-fn.php: " . implode( ',', $data) . "' );</script>";
	    else
	        $output = "<script>console.log( 'Debug child-fn.php: " . $data . "' );</script>";

	    echo $output;
	}
}

/* adding google analytics */

//add_action('wp_footer', 'add_google_analytics');
function add_google_analytics() { ?>
 
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-69206439-1', 'auto');
  ga('send', 'pageview');

</script>
 
<?php }

?>