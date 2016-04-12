<?php

$categories_data_transient_lifetime = 4 * DAY_IN_SECONDS;
$stores_data_transient_lifetime = 4 * DAY_IN_SECONDS;
$search_data_transient_lifetime = 4 * DAY_IN_SECONDS;
$shortcode_transient_lifetime = 10 * HOUR_IN_SECONDS;
$category_page_transient_lifetime = 10 * HOUR_IN_SECONDS;

$flipkart_deals_page_transient_lifetime = 3 * HOUR_IN_SECONDS;

function is_localhost() {
    $whitelist = array( '127.0.0.1', '::1' );
    if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist) )
        return true;
    else
        return false;
}

add_action('wp_ajax_search_offer', 'xl_search_offer');
add_action('wp_ajax_nopriv_search_offer', 'xl_search_offer');

//ajax call which trigger when person is searching, will output all choices at once and saved it in transients
function xl_search_offer(){
    global $search_data_transient_lifetime;

    if ( false === ( $offer_categories = get_transient( 'couponxl_offer_categories_and_stores' ) ) ) {

        $xl_offer_cats = couponxl_get_organized( 'offer_cat' );
        $post_data = array();
        foreach( $xl_offer_cats as $key => $cat){
            $offer_cat = new stdClass();
            $offer_cat->offer_cat_id = $cat->term_taxonomy_id;
            $offer_cat->offer_store_id = null;
            $offer_cat->offer_slug = esc_url( home_url('/') ).'offer_cat/'.$cat->slug;
            $offer_cat->offer_name = $cat->name;
            $offer_cat->offer_list = 'Categories';
            $post_data[] = $offer_cat;
        }

        global $wpdb;
        $stores = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title,post_name FROM {$wpdb->posts} AS posts WHERE posts.post_type = %s AND posts.post_status = %s",'store','publish'
            )
        );
        if( !empty( $stores ) ){
            foreach( $stores as $store ){
                $offer_store = new stdClass();
                $offer_store->offer_cat_id = null;
                $offer_store->offer_store_id = $store->ID;
                $offer_store->offer_slug = esc_url( home_url('/') ).'store/'.$store->post_name;
                $offer_store->offer_name = $store->post_title;
                $offer_store->offer_list = 'Website';
                $post_data[] = $offer_store;
            }
        }

        $offer_categories = json_encode($post_data);
        set_transient( 'couponxl_offer_categories_and_stores', $offer_categories, $search_data_transient_lifetime );
    }

    echo $offer_categories;
    die();
}


function new_excerpt_length($length) {
	return 15;
}
add_filter('excerpt_length', 'new_excerpt_length');

function adding_custom_scripts() {
	wp_register_script('custom-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/custom-script.js',array( "jquery" ),null, true);
    //wp_register_script('preloader-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/image-preloader.js','',null, true);
    //wp_register_script('xmas-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/snowstorm.js','',null, false);

	wp_enqueue_script('custom-script');
}
add_action( 'wp_enqueue_scripts', 'adding_custom_scripts' );

//to add search box in nav bar
add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {

        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();

        $searchform = '<form onsubmit="return false;" method="get" action="'.esc_url( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ) ).'" class="clearfix xl-search-form">
                            <i class="fa fa-search icon-margin" ></i>
                            <div class="search-loader">Loading...</div>
                            <div class="">
                                <input type="text" class="xl-search-input" value="" placeholder="Search" name="keyword">
                                <ul class="xl-search-result list-unstyled">
                                </ul>
                                <ul class="xl-search-description list-unstyled">
                                <li class="search-explanation">You can Search for</li>
                                <li><span class="left-description">Websites</span><span class="right-description">Paytm, Flipkart</span></li>
                                <li><span class="left-description">Categories</span><span class="right-description">Recharge, Bus</span></li>
                                </ul>
                            </div>
                        </form>';

        $home_url = esc_url(home_url('/'));

        $items .= '<li class="col-md-12 col-xs-12 xl-search-form-container">' . $searchform . '</li>';

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

add_action('xl_filter_text','xl_filter_text_fn');

function xl_filter_text_fn(){
    echo '<div id="xl_filter_text_container">Showing Filtered Result for: <ul class="list-unstyled" id="xl_filter_text_items"></ul></div>';
}

add_action('xl_offer_cat','xl_offer_cat_fn');

function xl_offer_cat_fn(){
    global $categories_data_transient_lifetime;

    if(is_localhost()){
        delete_transient( 'couponxl_filter_categories' );
    }

    if ( false === ( $xl_offer_cats = get_transient( 'couponxl_filter_categories' ) ) ) {
        $xl_offer_cats = couponxl_get_organized( 'offer_cat' );
        set_transient( 'couponxl_filter_categories', $xl_offer_cats, $categories_data_transient_lifetime );
    } ?>

    <div class="white-block xl-offer-cat-filter">
        <div class="white-block-content">
            <h2>Filter By Categories</h2>
            <input type="search" class="form-control xl-offer-cat-search" placeholder="Search in Categories">
            <ul class="list-unstyled xl-offer-cat-result xl-offer-list-unstyled">
            <?php foreach( $xl_offer_cats as $key => $cat){
                    if(empty($cat->children)){
                        if($cat->count){?>
                            <li data-xl-offer-count="<?php echo $cat->count ?>" class="xl-cat-<?php echo $cat->term_taxonomy_id ?>"><input type="checkbox" data-xlcategory="<?php echo $cat->term_taxonomy_id ?>"  class="xl-offer-cat-filter-checkbox"  id="xl_<?php echo $cat->slug; ?>" data-option="<?php echo $cat->name; ?>" name="store_offer_cat" value="<?php echo $cat->term_taxonomy_id; ?>"><label for="xl_<?php echo $cat->slug; ?>">&nbsp<?php echo $cat->name; ?> <span class="count"></span></label></li>
                <?php   }
             }else{?>
                    <?php foreach( $cat->children as $key => $child ){
                            if($child->count){ ?>
                                <li class="xl-cat-<?php echo $child->term_taxonomy_id ?>"><input type="checkbox" data-xlcategory="<?php echo $child->term_taxonomy_id ?>" class="xl-offer-cat-filter-checkbox" id="xl_<?php echo $child->slug; ?>" data-option="<?php echo $cat->name; ?>" name="store_offer_cat" value="<?php echo $child->term_taxonomy_id; ?>"><label for="xl_<?php echo $child->slug; ?>">&nbsp<?php echo $child->name; ?> <span class="count"></span></label></li>
                    <?php   }
                     }
                }
            } ?>
            </ul>
        </div>
    </div>
    <?php
}

add_action('xl_offer_type','xl_offer_type_fn');

function xl_offer_type_fn(){
	?>
	<div class="white-block xl-offer-type-filter">
        <div class="white-block-content">
            <h2>Filter By Type</h2>
            <ul class="list-unstyled xl-offer-type-result xl-offer-list-unstyled">
            	<li><input class="xl-offer-type-filter-radio" type="radio" name="xl-offer-type" id="xl-offer-type-all" value="all" checked><label for="xl-offer-type-all">&nbsp All <span id="xl-offer-type-all-count" class="count"></span></label></li>
				<li><input class="xl-offer-type-filter-radio" type="radio" name="xl-offer-type" id="xl-offer-type-deals" value="deal"><label for="xl-offer-type-deals">&nbsp Deals <span id="xl-offer-type-deal-count" class="count"></span></label></li>
            	<li><input class="xl-offer-type-filter-radio" type="radio" name="xl-offer-type" id="xl-offer-type-coupons" value="coupon"><label for="xl-offer-type-coupons">&nbsp Coupons <span id="xl-offer-type-coupon-count" class="count"></span></label></li>
                <li><input class="xl-offer-type-filter-radio" type="radio" name="xl-offer-type" id="xl-offer-type-cashback" value="cashback"><label for="xl-offer-type-cashback">&nbsp Cashback <span id="xl-offer-type-cashback-count" class="count"></span></label></li>
            </ul>
        </div>
    </div>
    <?php
}

add_action('xl_offer_store','xl_offer_store_fn');

function xl_offer_store_fn(){

    global $stores_data_transient_lifetime;

    if ( false === ( $stores = get_transient( 'couponxl_filter_stores' ) ) ) {

        $args = array(
            'post_type' => 'store',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'asc'
        );

        $stores = new WP_Query( $args );

        set_transient( 'couponxl_filter_stores', $stores, $stores_data_transient_lifetime );
    }

    if( $stores->have_posts() ){
        ?>
        <div class="white-block xl-offer-store-filter">
            <div class="white-block-content">
                <h2>Filter By Store</h2>
                <input type="search" class="form-control xl-offer-store-search" placeholder="Search in Store">
                <ul class="list-unstyled xl-offer-store-result xl-offer-list-unstyled">
                <?php
                while( $stores->have_posts() ){
                    $stores->the_post();
                    ?><li data-option="<?php echo the_title() ?>" class="xl-store-<?php echo get_the_ID() ?>"><input class="xl-offer-store-filter-checkbox" type="checkbox" name="xl-offer-store" id="xl-offer-store-<?php echo get_the_ID()?>" value="<?php echo get_the_ID()?>" ><label for="xl-offer-store-<?php echo get_the_ID()?>">&nbsp <?php the_title() ?> <span id="xl-offer-store-<?php echo get_the_ID()?>-count" class="count"></span></label></li>   <?php
                }
                ?>
                </ul>
            </div>
        </div><?php
    }
    wp_reset_query();
}

//wp-all-import action hook
add_action('pmxi_saved_post', 'post_saved', 10, 1);

function post_saved($id) {
    $offer_type = get_post_meta($id, 'offer_type', true);
    $offer_type = strtolower($offer_type);
    $expiry_date = get_post_meta($id,'offer_expire',true);
    $start_date = get_post_meta($id,'offer_start',true);
    $offer_in_slider = get_post_meta($id,'offer_in_slider',true);

    $post = get_post($id);
    $offer_title = $post->post_title;

    $offer_tag = '';

    $offer_title = strtolower($offer_title);

    if (strpos($offer_title, 'cashback') !== false)
        $offer_tag = 'cashback,';

    if (strpos($offer_title, 'flat') !== false)
        $offer_tag = 'flat,';

    // foreach ($offer_title as $string) {
    //     $string = strtolower($string);
    //     debug_to_console('inside loop:'.$string);
    //     if($string == 'cashback'){
    //         $offer_tag = 'cashback,';
    //     }else if($string == 'flat'){
    //         $offer_tag = 'flat,';
    //     }
    // }

    $offer_tag = rtrim($offer_tag,',');

    wp_set_object_terms($id, $offer_tag, 'offer_tag', true);
    //update_post_meta($id, 'offer_tag', $offer_tag);

    if($offer_type == 'promotion' || $offer_type == 'coupon'){
        if($offer_type == 'promotion'){
            $offer_type = 'deal';
        }else if($offer_type == 'coupon'){
            $offer_type = 'coupon';
        }
        update_post_meta($id, 'offer_type', $offer_type);
    }

    if($offer_type == 'deal'){
        $offer_link = get_post_meta($id,'coupon_link',true);
        $deal_info = get_post_meta($id,'post_content',true);
        update_post_meta($id, 'deal_link', $offer_link);
        update_post_meta($id, 'deal_in_short', $deal_info);
    }else if($offer_type == 'coupon'){
        update_post_meta($id, 'coupon_type', 'code');
    }

    $timestamp = strtotime($expiry_date. '+23 hours +59 minutes');
    update_post_meta($id, 'offer_expire', $timestamp);

    if(empty($start_date)){
        $timestamp = time();
    }else{
        $timestamp = strtotime($start_date);
    }
    update_post_meta($id, 'offer_start', $timestamp);

    if(empty($offer_in_slider)){
        update_post_meta($id, 'offer_in_slider', 'no');
    }

    update_post_meta($id, 'deal_status', 'has_items');
    update_post_meta($id,'offer_initial_payment','paid');
    //to set post expiry date while importing via wp all import
    //setting expiry after 2 days for transient caching purpose
    $timestamp = strtotime($expiry_date. '+2 days');
    xl_set_expiration($id,$timestamp);

}

//using post-expirator plugin function
//CAUTION this function won't work when post expirator is deactivated
function xl_set_expiration($id, $timestamp) {

	$date = date('m/d/Y', $timestamp);

    $formatted_date = DateTime::createFromFormat('m/d/Y', $date);

    $month   = intval($formatted_date->format('m'));
    $day     = intval($formatted_date->format('d'));
    $year    = intval($formatted_date->format('y'));

    //Manually set post to expire at the end of the day.
    $hour    = 23;
    $minute  = 59;

    $ts = get_gmt_from_date("$year-$month-$day $hour:$minute:0",'U');

    $opts = array(
        'expireType' => 'delete',
        'id' => $id
    );

    _scheduleExpiratorEvent($id, $ts, $opts);
}

add_action('offer_other_info','offer_other_info_callback');

function offer_other_info_callback(){ ?>
    <div class='offer-other-info-container'>
            <div class='offer-used-count'><i class='fa fa-shopping-cart icon-margin'></i><?php echo(rand(10,300)); ?> Uses Today</div>
            <div class='offer-verified-status'><i class='fa fa-check-circle icon-margin'></i>Verified</div>
            <div class='clear'></div>
    </div><?php
}

add_action('offer_top_info','offer_top_info_callback');

// offer_number offer_unit offer_details -> 50 off flat cashback
function offer_top_info_callback(){?>
    <?php
    $offer_tag = wp_get_post_terms(get_the_ID(), 'offer_tag', array("fields" => "names"));
    $offer_type = 'Exclusive';
    if(is_array($offer_tag)){
        $offer_unit = 'off';
        foreach ($offer_tag as $tag_value) {
            if(is_numeric($tag_value)){
                $offer_number = $tag_value;
            }else if($tag_value == 'flat'){
                $offer_flat = $tag_value;
                $offer_type = $tag_value;
            }else if($tag_value == 'cashback'){
                $offer_cashback = $tag_value;
                $offer_type = $tag_value;
            }
            // else if($tag_value == 'off'){
            //     $offer_unit = $tag_value;
            // }else if($tag_value == 'percent'){
            //     $offer_unit = $tag_value;
            // }
        }
        if($offer_flat && $offer_cashback){
            $offer_type = $offer_flat.' + '.$offer_cashback;
        }
    }
    ?>
    <div class="xl-offer-label <?php echo $offer_flat.$offer_cashback; ?>" data-xl-offer-amount="<?php echo $offer_amount; ?>" data-xl-offer-number="<?php echo $offer_number; ?>" data-xl-offer-type="<?php echo $offer_type; ?>" data-xl-offer-unit="<?php echo $offer_unit; ?>">
        <div class="xl-offer-text"><?php echo $offer_type.' offer' ?></div>
        <div class="xl-offer-wrapper">
            <div class="xl-offer-triangle-topright"></div>
            <div class="xl-offer-triangle-bottomright"></div>
        </div>
    </div>
    <?php
}

function xl_transient_namespace(){
    return substr('coupon_machi', 0, 7 );
}

//adding custom cron schedule
    function my_add_weekly( $schedules ) {
        // add a 'weekly' schedule to the existing set
        $schedules['3_hours'] = array(
            'interval' => 10800,
            'display' => __('Every 3 Hours - (Flipkart API call)')
        );
        return $schedules;
    }
    add_filter( 'cron_schedules', 'my_add_weekly' );

    //action hook to set up cron events
    add_action('get_flipkart_daily_deals','getDailyDeals');

    //calling flipkart daily deals api, if fails, call again till 5 times and store it in transient for temp storage
    function getDailyDeals(){

        global $flipkart_deals_page_transient_lifetime;
        $api_url = 'https://affiliate-api.flipkart.net/affiliate/offers/v1/dotd/json';

        function callFlipkartFeedsAPI($api_url){

            static $api_call_counter = 1;

            $api_response = wp_remote_get( $api_url ,
                 array( 'timeout' => 10,
                'headers' => array( 'Fk-Affiliate-Id' => 'couponmac',
                                   'Fk-Affiliate-Token'=> '6eb39690116842ad937da289fa4e6e74' )
            ));

            if( is_array($api_response) ) {

                $api_response = $api_response['body'];
                $api_response = json_decode($api_response, true);

                set_transient( 'flipkart_daily_deals', $api_response, $flipkart_deals_page_transient_lifetime );

                return $api_response;
            }else{
                $api_call_counter++;
                if($api_call_counter>5){
                    return '';
                }
                $api_response = callFlipkartFeedsAPI($api_url);
                return $api_response;
            }
        }

        return callFlipkartFeedsAPI($api_url);
    }

//action to show scroll top icon in all pages

add_action('xl_scroll_top','xl_scroll_top_callback');

function xl_scroll_top_callback(){
    ?>
        <a href="javascript:void(0)" class="xl-scrollup" title="scroll to top"></a>
    <?php
}

/* sidemenu for home page */

add_action('xl_side_menu','xl_side_menu_callback');

function xl_side_menu_callback(){
    ?>
    <div class="xl-sidemenu">
        <ul>
    <?php
    if(is_front_page()){?>

        <li><a data-scroll-id="top-offers" class="xl-side-menu-item"><i class="fa fa-star icon-margin" ></i><span>Featured Offers</span></a></li>
        <li><a data-scroll-id="mobile-recharge" class="xl-side-menu-item"><i class="fa fa-flash icon-margin" ></i><span>Recharge Coupons</span></a></li>
        <li><a data-scroll-id="bus" class="xl-side-menu-item"><i class="fa fa-bus icon-margin" ></i><span>Travel Offers</span></a></li>
        <li><a data-scroll-id="electronics" class="xl-side-menu-item"><i class="fa fa-television icon-margin" ></i><span>Electronics</span></a></li>
        <li><a data-scroll-id="food-ordering" class="xl-side-menu-item"><i class="fa fa-cutlery icon-margin" ></i><span>Food Coupons</span></a></li>
        <li><a data-scroll-id="fashion" class="xl-side-menu-item"><i class="fa fa-female icon-margin" ></i><span>Clothing</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'offer_tag/cashback' ?>" class="xl-side-menu-item"><i class="fa fa-inr icon-margin" ></i><span>Cashbacks</span></a></li>

    <?php }else{?>

        <!-- <li><a data-scroll-id="top-offers" class="xl-side-menu-item"><i class="fa fa-star icon-margin" ></i><span>Featured Offers</span></a></li> -->
        <li><a href="<?php echo esc_url( home_url('/') ).'recharge-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-flash icon-margin" ></i><span>Recharge Coupons</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'travel-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-bus icon-margin" ></i><span>Travel Offers</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'mobiles-tablets-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-mobile icon-margin" ></i><span>Mobiles & Tablets</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'tv-audio-video-entertainment-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-television icon-margin" ></i><span>TV & Entertainment</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'computers-laptops-gaming-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-television icon-margin" ></i><span>TV & Entertainment</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'food-dining-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-cutlery icon-margin" ></i><span>Food Coupons</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'fashion-coupons' ?>" class="xl-side-menu-item"><i class="fa fa-female icon-margin" ></i><span>Clothing</span></a></li>
        <li><a href="<?php echo esc_url( home_url('/') ).'offer_tag/cashback' ?>" class="xl-side-menu-item"><i class="fa fa-inr icon-margin" ></i><span>Cashbacks</span></a></li>
    <?php }
    ?>

        </ul>
        <div class="xl-sidemenu-left"><a href="javascript:void(0)"><i class="fa fa-arrow-circle-left icon-margin" ></i></a></div>
        <div class="xl-sidemenu-right"><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right icon-margin" ></i></a></div>
    </div><?php
}

/* adding footer stats meta */

function xl_footer_stats_callback(){ ?>

        <ul class="list-inline xl-footer-stats go-flex">
            <li><span><i class="fa fa-smile-o"></i></span><span><b>4125</b><br><small>Coupons redeemed so far</small></span></li>
            <li><span><i class="fa fa-bookmark"></i></span><span><b>5204</b><br><small>Coupons &amp; Deals for you</small></span></li>
            <li><span><i class="fa fa-users"></i></span><span><b>5636</b><br><small>Subscribed Users</small></span></li>
            <li><span><i class="fa fa-check-circle"></i></span><span><b>100%</b><br><small>Verified</small></span></li>
        </ul>
    <?php
}

add_action('xl_footer_stats','xl_footer_stats_callback');

function xl_footer_cats_callback(){
    $site_url = esc_url(home_url('/'));
    ?>

    <div class="xl-footer-cats">
        <div class="row">
            <ul>
                <h6 class="heading">CouponMachi</h6>
                <li><a href="<?php echo $site_url.'about'; ?>" title="About Us">About Us</a></li>
                <li><a href="<?php echo $site_url.'privacy-policy'; ?>" title="Privacy Policy" rel="nofollow">Privacy</a></li>
                <li><a href="<?php echo $site_url.'terms-and-conditions'; ?>" title="Terms & Conditions" rel="nofollow">Terms of Use</a></li>
                <li><a href="<?php echo $site_url.'sitemap_index.xml'; ?>" title="Sitemap">Sitemap</a></li>
            </ul>
            <ul>
                <h6 class="heading">Stores</h6>
                <li><a href="<?php echo $site_url.'paytm'; ?>" title="Paytm Offers">Paytm</a></li>
                <li><a href="<?php echo $site_url.'ebay'; ?>" title="Flipkart Offers">Flipkart</a></li>
                <li><a href="<?php echo $site_url.'amazon'; ?>" title="Amazon Offers">Amazon</a></li>
                <li><a href="<?php echo $site_url.'makemytrip'; ?>" title="MakeMyTrip Offers">MakeMyTrip</a></li>
                <li><a href="<?php echo $site_url.'snapdeal'; ?>" title="Snapdeal Offers">Snapdeal</a></li>
                <li class='view-all'><a href="<?php echo $site_url.'stores'; ?>" title="All Stores">All Stores</a></li>
            </ul>
            <ul>
                <h6 class="heading">Popular Coupons</h6>
                <li><a href="<?php echo $site_url.'recharge-coupons'; ?>" title="Recharge Offers">Recharge</a></li>
                <li><a href="<?php echo $site_url.'food-dining-coupons'; ?>" title="Food Offers">Food</a></li>
                <li><a href="<?php echo $site_url.'travel-coupons'; ?>" title="Travel Offers">Travel</a></li>
                <li><a href="<?php echo $site_url.'fashion-coupons'; ?>" title="Fashion Offers">Fashion</a></li>
                <li><a href="<?php echo $site_url.'groceries-coupons'; ?>" title="Groceries Offers">Groceries</a></li>
            </ul>
            <ul>
                <h6 class="heading">Popular Categories</h6>
                <li><a href="<?php echo $site_url.'electronics-coupons'; ?>" title="Electronics Offers">Electronics</a></li>
                <li><a href="<?php echo $site_url.'appliances-coupons'; ?>" title="Appliances Offers">Appliances</a></li>
                <li><a href="<?php echo $site_url.'flowers-gifts-jewellery-coupons'; ?>" title="Flowers & Gifts Offers">Flowers & Gifts</a></li>
                <li><a href="<?php echo $site_url.'beauty-health-coupons'; ?>" title="Health & Fitness Offers">Health & Fitness</a></li>
                <li><a href="<?php echo $site_url.'books-stationery-coupons'; ?>" title="Books & Stationery Offers">Books & Stationery</a></li>
                <li class='view-all'><a href="<?php echo $site_url.'categories'; ?>" title="View All Categories">All Categories</a></li>
            </ul>
            <ul>
                <h6 class="heading">Offer Filtes</h6>
                <li><a href="<?php echo $site_url.'promocodes'; ?>" title="All Store Promocodes">All Store Promocodes</a></li>
                <li><a href="<?php echo $site_url.'offer_tag/cashback'; ?>" title="Cashback Offers">Cashback Offers</a></li>
                <li><a href="<?php echo $site_url.'offer_tag/flat'; ?>" title="Flat Offers">Flat Offers</a></li>
                <li><a href="<?php echo $site_url.'expiring-offers'; ?>" title="Latest Offers">Expiring Offers</a></li>
                <li><a href="<?php echo $site_url.'flipkart_deal_of_the_day'; ?>" title="Flipkart Deal of the Day">Flipkart Deal of the Day</a></li>
             </ul>
         </div>
     </div>

    <?php
}

add_action('xl_footer_cats','xl_footer_cats_callback');

/* function xl_offer_of_the_day_callback(){?>
    <div class="buzz-container">
        <div class="buzz-it-up notification" id="buzz_smile" data-notifcount="2">
            <a href="https://buzz.grabon.in?utm_source=homepage" target="_blank" class=""><b><i class="gie-bellsmile"></i></b><span><b>BUZZ ME! </b><small>One Click To Happiness </small></span></a>
        </div>
        <div class="recent-buzz">
            <a href="http://goo.gl/FyQPN1" target="_blank" class="buzz-item buzz-1" data-buzzid="1"><b><i class="gie-bellsmile"></i></b><span><b>Just Recharge For FREE</b><small>Get Upto 100% Cashback On Recharge @ Paytm, Freecharge &amp; More</small></span></a>
            <a href="http://goo.gl/xKRgdE" target="_blank" class="buzz-item buzz-2" data-buzzid="2"><b><i class="gie-bellsmile"></i></b><span><b>Flat Rs 10000 Cashback On Apple Products</b><small>Shop For iPhones, iPads, Mac Books &amp; More @ Paytm</small></span></a>
        </div>
    </div>
}<?php

add_action('xl_offer_of_the_day','xl_offer_of_the_day_callback'); */

/* adding google analytics */

if(!is_localhost()){
    add_action('wp_footer', 'add_google_analytics');
}

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



/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function gp_remove_cpt_slug( $post_link, $post, $leavename ) {

    if ( 'store' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }

    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

    return $post_link;
}
add_filter( 'post_type_link', 'gp_remove_cpt_slug', 10, 3 );

function gp_parse_request_trick( $query ) {

    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;

    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'page', 'store' ) );
    }
}
add_action( 'pre_get_posts', 'gp_parse_request_trick' );

//Remove unnecessary meta tags from WordPress header
remove_action( 'wp_head', 'wp_generator' ) ;
remove_action( 'wp_head', 'wlwmanifest_link' ) ;
remove_action( 'wp_head', 'rsd_link' ) ;

// Hide the non-essential WordPress RSS Feeds
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

//Disable WordPress Login Hints
function no_wordpress_errors(){
  return 'GET OFF MY LAWN !! RIGHT NOW !!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );

//logged in for 1 month
add_filter( 'auth_cookie_expiration', 'stay_logged_in_for_1_year' );
function stay_logged_in_for_1_year( $expire ) {
  return 2592000; // 1 month in seconds
}

//promocodes search ajax

add_action('wp_ajax_promocodes_search', 'xl_promocodes_search');
add_action('wp_ajax_nopriv_promocodes_search', 'xl_promocodes_search');

//ajax call which trigger when person is searching, will output all choices at once and saved it in transients
function xl_promocodes_search(){

    $post_id = $_POST['store_id'];

    $args = array(
        'post_type'     => 'offer',
        'posts_per_page'=> -1,
        'post_status'   => 'publish',
        'meta_key'      => 'offer_expire',
        'orderby'       => 'meta_value_num',
        'paged'         => 1,
        'order'         => 'ASC',
        'meta_query'    => array(
            'relation' => 'AND',
            array(
                'key' => 'offer_store',
                'value' => $post_id,
                'compare' => '='
            ),
            array(
                'key' => 'offer_start',
                'value' => current_time( 'timestamp' ),
                'compare' => '<='
            ),
            array(
                'key' => 'offer_expire',
                'value' => current_time( 'timestamp' ),
                'compare' => '>='
            ),
            array(
                'key' => 'deal_status',
                'value' => 'has_items',
                'compare' => '='
            ),
        )
    );

    $transient_args = array(
        'post_type'     => 'offer',
        'posts_per_page'=> -1,
        'post_status'   => 'publish',
        'meta_key'      => 'offer_expire',
        'orderby'       => 'meta_value_num',
        'paged'         => 1,
        'order'         => 'ASC',
        'meta_query'    => array(
            'relation' => 'AND',
            array(
                'key' => 'offer_store',
                'value' => $post_id,
                'compare' => '='
            ),
        )
    );

    $transient_namespace = xl_transient_namespace();

    $transient_key = $transient_namespace .md5( serialize($transient_args) );

    if(is_localhost()){
        delete_transient( $transient_key );
    }

    if ( false === ( $offers = get_transient( $transient_key ) ) ) {
        $offers = new WP_Query( $args );
        set_transient( $transient_key, $offers, $category_page_transient_lifetime );
    }

    $store_data = array();
    while( $offers->have_posts() ){
        $offers->the_post();
        $offer_store = new stdClass();
        $store_id = get_the_ID();
        $offer_store->store_id = $store_id;
        $offer_store->aff_url = get_post_meta($store_id,'coupon_link',true);
        $offer_store->code = get_post_meta($store_id,'coupon_code',true);
        $offer_store->type = get_post_meta($store_id,'offer_type',true);
        $offer_store->title = get_the_title();
        $offer_store->url = get_the_permalink();
        $offer_store->description = get_the_content();
        $offer_categories = get_the_terms( $store_id, 'offer_cat' );
        $offer_store->cat = array();
        if(!empty($offer_categories)){
            foreach ($offer_categories as $key => $cat) {
                $offer_store->cat[] = $cat->term_id;
            }
        }
        $store_data[] = $offer_store;
    }

    echo json_encode($store_data);
    die();
}


?>
