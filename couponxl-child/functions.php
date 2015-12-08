<?php

add_action('wp_ajax_search_offer', 'xl_search_offer');
add_action('wp_ajax_nopriv_search_offer', 'xl_search_offer');

//ajax call which trigger when person is searching, will output all choices at once and saved it in transients
function xl_search_offer(){
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
        set_transient( 'couponxl_offer_categories_and_stores', $offer_categories, 24 * HOUR_IN_SECONDS );         
    }

    echo $offer_categories;    
    die();
}


function new_excerpt_length($length) {
	return 15;
}
add_filter('excerpt_length', 'new_excerpt_length');

function adding_custom_scripts() {    
	wp_register_script('custom-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/custom-script.js','',null, true);
    wp_register_script('preloader-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/image-preloader.js','',null, true);

	wp_enqueue_script('custom-script');
    //wp_enqueue_script('preloader-script');
}
add_action( 'wp_enqueue_scripts', 'adding_custom_scripts' ); 

add_action('wp_head','add_image_preloader_inline_script');

//for to add placeholder image before loading image
function add_image_preloader_inline_script() {
    echo '<script type="text/javascript">
            jQuery(function(){
                jQuery(".home-page-body  img").imgPreload();
            });
    </script>';
}

//to add search box in nav bar
add_filter('wp_nav_menu_items','add_search_box', 10, 2);
function add_search_box($items, $args) {

        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();

        $searchform = '<form method="get" action="http://couponmachi.com/search-page/" class="clearfix xl-search-form"> 
                            <i class="fa fa-search icon-margin" ></i>
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

        $dropdown = '<div class="dropdown"> <a href="javascript:void(0);" data-toggle="dropdown" class="btn-block btn-grey" aria-expanded="false">Categories <i class="caret go-smooth"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="'.$home_url.'offer_cat/bus" title="Bus Coupons">Bus </a></li>
                            <li><a href="'.$home_url.'offer_cat/mobile-recharge" title="Recharge Coupons">Recharge </a></li>
                            <li><a href="'.$home_url.'offer_cat/footwear" title="Footwear Coupons">Footwear </a></li>
                            <li><a href="'.$home_url.'offer_cat/clothing" title="Clothing Coupons">Clothing </a></li>
                            <li><a href="'.$home_url.'categories/" title="All Categories">View All </a></li>
                        </ul>
                    </div>';

        $items .= '<li class="col-md-12 col-xs-12 xl-search-form-container">' . $searchform . '</li>';

        $items .= '<li class="col-md-12 col-xs-12 xl-dropdown-container">' . $dropdown . '</li>';
        

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
	$xl_offer_cats = couponxl_get_organized( 'offer_cat' ); ?>

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
            </ul>
        </div>
    </div>
    <?php
}

add_action('xl_offer_store','xl_offer_store_fn');

function xl_offer_store_fn(){
    $args = array(
        'post_type' => 'store',        
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'asc'
    );  

    $stores = new WP_Query( $args );

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
    $expiry_date = get_post_meta($id,'offer_expire',true);
    $start_date = get_post_meta($id,'offer_start',true);
    $offer_in_slider = get_post_meta($id,'offer_in_slider',true);

    if($offer_type == 'Promotion' || $offer_type == 'Coupon'){
        if($offer_type == 'Promotion'){
            $offer_type = 'deal';
        }else if($offer_type == 'Coupon'){
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

    $timestamp = strtotime($start_date);
    update_post_meta($id, 'offer_start', $timestamp);

    if(empty($offer_in_slider)){
        update_post_meta($id, 'offer_in_slider', 'no');
    }

    update_post_meta($id, 'deal_status', 'has_items');
    update_post_meta($id,'offer_initial_payment','paid');
    debug_to_console('calling expirationdate_update_post_meta function');
    //to set post expiry date while importing via wp all import
    xl_set_expiration($id,$expiry_date);

}

//using post-expirator plugin function
//CAUTION this function won't work when post expirator is deactivated
function xl_set_expiration($id, $date) {

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
            <div class='offer-used-count'><i class='fa fa-shopping-cart icon-margin'></i><?php echo(rand(2,50)); ?> Uses Today</div>
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
            }else if($tag_value == 'off'){
                $offer_unit = $tag_value;                
            }else if($tag_value == 'percent'){
                $offer_unit = $tag_value;                
            }
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