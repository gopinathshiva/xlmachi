<?php

add_action('wp_ajax_search_offer', 'xl_search_offer');
add_action('wp_ajax_nopriv_search_offer', 'xl_search_offer');

function xl_search_offer(){        
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

    echo json_encode($post_data, JSON_PRETTY_PRINT);  
    die();
}


function new_excerpt_length($length) {
	return 15;
}
add_filter('excerpt_length', 'new_excerpt_length');

function adding_custom_scripts() {    
	wp_register_script('custom-script', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/custom-script.js','',null, true);
	wp_enqueue_script('custom-script');
}
add_action( 'wp_enqueue_scripts', 'adding_custom_scripts' ); 

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
                                <li><span class="left-description">Categories</span><span class="right-description">Recharge, Mobiles & Tablets</span></li>                                
                                </ul>
                            </div>
                        </form>';

        $items .= '<li class="xl-search-form-container">' . $searchform . '</li>';

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
                if(empty($cat->children)){?>                    
                            <li class="xl-cat-<?php echo $cat->term_taxonomy_id ?>"><input type="checkbox" data-xlcategory="<?php echo $cat->term_taxonomy_id ?>"  class="xl-offer-cat-filter-checkbox"  id="xl_<?php echo $cat->slug; ?>" data-option="<?php echo $cat->name; ?>" name="store_offer_cat" value="<?php echo $cat->term_taxonomy_id; ?>"><label for="xl_<?php echo $cat->slug; ?>">&nbsp<?php echo $cat->name; ?> <span class="count"></span></label></li>
                <?php }else{?>
                    <?php foreach( $cat->children as $key => $child ){ ?>                        
                            <li class="xl-cat-<?php echo $child->term_taxonomy_id ?>"><input type="checkbox" data-xlcategory="<?php echo $child->term_taxonomy_id ?>" class="xl-offer-cat-filter-checkbox" id="xl_<?php echo $child->slug; ?>" data-option="<?php echo $cat->name; ?>" name="store_offer_cat" value="<?php echo $child->term_taxonomy_id; ?>"><label for="xl_<?php echo $child->slug; ?>">&nbsp<?php echo $child->name; ?> <span class="count"></span></label></li>
                    <?php } 
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

    $timestamp = strtotime($expiry_date. '+1 day');
    update_post_meta($id, 'offer_expire', $timestamp);

    $timestamp = strtotime($start_date);
    update_post_meta($id, 'offer_start', $timestamp);

    if(empty($offer_in_slider)){
        update_post_meta($id, 'offer_in_slider', 'no');
    }

    update_post_meta($id, 'deal_status', 'has_items');
    update_post_meta($id,'offer_initial_payment','paid');

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