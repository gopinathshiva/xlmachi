<?php
/*
    Template Name: Store Page
*/

get_header();
the_post();
get_template_part( 'includes/title' );
$post_id = get_the_ID();
$offer_type = get_query_var( $couponxl_slugs['offer_type'], '' );
$theme_usage = couponxl_get_option( 'theme_usage' );
//$theme_usage = 'cool';
$store_link = get_post_meta( get_the_ID(), 'store_link', true );
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="white-block">
                    
                    <?php if( has_post_thumbnail() ): ?>
                        <div class="shop-logo">
                            <?php if( !empty( $store_link ) ): ?>
                                <a href="<?php echo esc_url( add_query_arg( array( 'rs' => get_the_ID() ), get_permalink() ) ) ?>" target="_blank">
                            <?php endif; ?>
                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-responsive' ) ); ?>
                            <?php if( !empty( $store_link ) ): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <ul class="list-unstyled list-inline store-social-networks">
                        <?php
                        $store_facebook = get_post_meta( $post_id, 'store_facebook', true );
                        if( !empty( $store_facebook ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_facebook ) ?>" target="_blank" class="share">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <?php
                        }
                        $store_twitter = get_post_meta( $post_id, 'store_twitter', true );
                        if( !empty( $store_twitter ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_twitter ) ?>" target="_blank" class="share">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <?php
                        }                            
                        $store_google = get_post_meta( $post_id, 'store_google', true );
                        if( !empty( $store_google ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_google ) ?>" target="_blank" class="share">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </li>
                            <?php
                        }                            
                        ?>
                    </ul>

                    <div class="white-block-content">
                        <?php 
                        $show_breadcrumbs = couponxl_get_option( 'show_breadcrumbs' );
                        if( $show_breadcrumbs == 'yes' ){
                            ?>
                            <!-- <h1 class="size-h5"><?php the_title(); ?></h1> -->
                            <?php
                        }
                        else{
                            ?>
                            <h5><?php the_title(); ?></h5>
                            <?php
                        }
                        ?>
                        <?php 
                        $content = get_the_content();
                        if( !empty( $content ) ):
                        ?>
                            <div class="read-more">
                                <div class="read-more-content">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            <a href="javascript:;" class="read-more-toggle" data-close="<?php esc_attr_e( 'Close', 'couponxl' ) ?>"><?php _e( 'Read More', 'couponxl' ) ?></a>
                        <?php
                        endif;
                        ?>
                    </div>

                    <!-- <div class="white-block-content shop-offer-filter">
                        <?php
                        if( $theme_usage == 'all' || $theme_usage == 'deals' ){
                            $deals = couponxl_count_post_type( 
                                'offer', 
                                array( 
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
                                            'key' => 'deal_status',
                                            'value' => 'has_items',
                                            'compare' => '='
                                        ),
                                        array(
                                            'key' => 'offer_store',
                                            'value' => get_the_ID(),
                                            'compare' => '='
                                        ),                                    
                                        array(
                                            'key' => 'offer_type',
                                            'value' => 'deal',
                                            'compare' => '='
                                        )
                                    ) 
                                ) 
                            );
                        }
                        else{
                            $deals  = 0;
                        }
                        
                        if( $theme_usage == 'all' || $theme_usage == 'coupons' ){
                            $coupons = couponxl_count_post_type( 
                                'offer', 
                                array( 
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
                                            'key' => 'deal_status',
                                            'value' => 'has_items',
                                            'compare' => '='
                                        ),
                                        array(
                                            'key' => 'offer_store',
                                            'value' => get_the_ID(),
                                            'compare' => '='
                                        ),                                    
                                        array(
                                            'key' => 'offer_type',
                                            'value' => 'coupon',
                                            'compare' => '='
                                        )
                                    ) 
                                ) 
                            );
                        }
                        else{
                            $coupons  = 0;
                        }
                        ?>  
                        <?php if( $theme_usage == 'all'): ?>
                            <a href="<?php the_permalink() ?>" class="<?php echo empty( $offer_type ) ? 'active' : '' ?>"><?php _e( 'All ', 'couponxl' ) ?>(<?php echo $deals + $coupons; ?>)</a>
                        <?php endif; ?>
                        <?php if( $theme_usage == 'deals' || $theme_usage == 'all' ): ?>
                            <a href="<?php echo couponxl_append_query_string( '', array( 'offer_type' => 'deal' ), array( 'store' ) ); ?>" class="<?php echo $offer_type == 'deal' ? 'active' : '' ?>"><?php _e( 'Deals ', 'couponxl' ) ?>(<?php echo $deals; ?>)</a>
                        <?php endif; ?>
                        <?php if( $theme_usage == 'coupons' || $theme_usage == 'all' ): ?>
                            <a href="<?php echo couponxl_append_query_string( '', array( 'offer_type' => 'coupon' ), array( 'store' ) ); ?>" class="<?php echo $offer_type == 'coupon' ? 'active' : '' ?>"><?php _e( 'Coupons ', 'couponxl' ) ?>(<?php echo $coupons; ?>)</a>
                        <?php endif; ?>
                    </div> -->

                </div>

                <?php do_action('xl_offer_type') ?>
                <?php do_action('xl_offer_cat') ?>     
                

                <?php 
                    if ( is_active_sidebar( 'store-sidebar-1' ) ){
                        dynamic_sidebar( 'store-sidebar-1' );
                    }
                ?>
            </div>

            <div class="col-md-9" id='xl-store-start'>
                <?php do_action('xl_filter_text') ?>
                <?php the_content(); ?>
                    <!-- CUSTOMISATION DONE HERE -->
                    <div class="white-block xl-offer-filter-not-found">
                        <div class="white-block-content">
                            <p class="nothing-found">Currently there is no offer available for the Selected Filter !!!</p>
                        </div>
                    </div>                                
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>