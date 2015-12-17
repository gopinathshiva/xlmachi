<?php

function get_xl_ajax_category_args(){
	$args = array(
        'post_type' => 'offer',
        'post_status' => 'publish',
        'posts_per_page' => $offers_number,
        'orderby' => 'meta_value_num',
        'meta_key' => 'offer_expire',
        'order' => 'ASC',
        'tax_query' => array(
            'relation' => 'AND'
        ),
        'meta_query' => array(
            'relation' => 'AND',
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
                'key' => 'offer_type',
                'value' => 'coupon',
                'compare' => '='
            )
        )
    );

    return $args;
}

function xl_run_ajax_category_loop($args,$offers_per_row){
	$coupons = new WP_Query( $args );
    $counter = 0;
	if( $coupons->have_posts() ){
	    ?>
	    <div class="row">
	    <?php                    
	    while( $coupons->have_posts() ){
	        if( $counter == $offers_per_row ){
	            echo '</div><div class="row">';
	            $counter = 0;
	        }
	        $counter++;
	        $coupons->the_post();
	        $cpxl_col_class = 12/$offers_per_row;
	        $cpxl_col_class= "col-sm-".$cpxl_col_class;
	        ?>
	        <div class="<?php echo $cpxl_col_class ?>">
	            <?php include( locate_template( 'includes/offers/offers.php' ) ); ?>
	        </div>
	        <?php
	    }
	    ?>
	    </div>
	    <?php
	    wp_reset_query();
	    return $coupons;
	}	
}

?>
<ul class="couponxl-ajax-category-filter-menu nav nav-tabs"> 
	<?php if(!empty($custom_category_title)) { ?>
		<li data-xlcategoryid='0' class="active"><a href="javascript:void(0);" class="home-tab" data-toggle="tab" data-target="#hometab0" aria-expanded="false"><?php echo $custom_category_title; ?></a></li> 
	<?php
		} 
		foreach ($ajax_categories as $ajax_category) {
			$ajax_category = get_term_by( 'slug', $ajax_category, 'offer_cat');			
	?>	
	<li data-xlcategoryid="<?php echo $ajax_category->term_id; ?>"><a href="javascript:void(0);" data-toggle="tab" class="home-tab" data-target="#hometab1"><?php echo $ajax_category->name;; ?></a></li> 
	<?php } ?>
</ul>
<div id="couponxl-ajax-category-filter" class="couponxl-ajax-category-filter-container">
	<div id="couponxl-custom-category-filter-result">
		<?php 
			$args = get_xl_ajax_category_args();
	    	xl_run_ajax_category_loop($args,$offers_per_row);
		?>
	</div>
</div>

<?php

add_action('wp_ajax_xl_ajax_category_filter', 'xl_ajax_category_filter');
add_action('wp_ajax_xl_ajax_category_filter', 'xl_ajax_category_filter');

function xl_ajax_category_filter(){
	$transient_name = 'xl_ajax_category_'.$_POST['cat_id'];
	if ( false === ( $offers = get_transient( $transient_name ) ) ) {
		$args = get_xl_ajax_category_args();
		if( !empty( $_POST['cat_id'] ) ){
	        $args['tax_query'][] = array(
	            'taxonomy' => 'offer_cat',
	            'field' => 'slug',
	            'terms' => array( $_POST['cat_id'] ),
	            'operator' => 'IN'
	        );
	    }	    
	    $coupons = xl_run_ajax_category_loop($args,$offers_per_row);
	    set_transient( $transient_name, $coupons, 8 * HOUR_IN_SECONDS );    
	}
}

?>