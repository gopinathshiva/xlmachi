<?php
/*==================
 SINGLE BLOG POST
==================*/

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
                <?php
                $cur_page = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1; //get curent page
                $offers_per_page = couponxl_get_option( 'offers_per_page' );

                $args = array(
                    'post_type'     => 'offer',
                    'posts_per_page'=> $offers_per_page,
                    'post_status'   => 'publish',
                    'meta_key'      => 'offer_expire',
                    'orderby'       => 'meta_value_num',
                    'paged'         => $cur_page,
                    'order'         => 'ASC',
                    'meta_query'    => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'offer_store',
                            'value' => get_the_ID(),
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


                if( !empty( $offer_type ) ){
                    $args['meta_query'][] = array(
                        'key' => 'offer_type',
                        'value' => $offer_type,
                        'compare' => '='
                    );
                }

                $offers = new WP_Query( $args );

                $page_links_total =  $offers->max_num_pages;
                $pagination_args = array(
                    'end_size' => 2,
                    'mid_size' => 2,
                    'format' => '?page=%#%',
                    'total' => $page_links_total,
                    'current' => $cur_page, 
                    'prev_next' => false,
                    'type' => 'array'
                );

                if( !empty( $offer_type ) ){
                   //$pagination_args['format'] = !get_option( 'permalink_structure' ) ? '?page=%#%' : 'paged/%#%';
                }
                $page_links = paginate_links( $pagination_args );

                $pagination = couponxl_format_pagination( $page_links );
                if( $offers->have_posts() ){                    
                    $col = 4;							/* CUSTOMISATION DONE HERE FROM 6 TO 4 */
                    if( $offer_view == 'list' ){
                        $col = 12;
                    }
                    ?>
                    <div class="row masonry">                        
                        <?php
                        while( $offers->have_posts() ){
                            $offers->the_post();                            
                            $xl_post_id = get_the_ID();
                            $xl_offer_cat_id = '';
                            $xl_offer_cat = get_the_terms( $xl_post_id, 'offer_cat' );
                            for ($i = 0; $i < count($xl_offer_cat); ++$i) {
                                $xl_offer_cat_id.=$xl_offer_cat[$i]->term_taxonomy_id.',';                                
                            }
                            unset($i);                         
                            $xl_offer_cat_id =  rtrim($xl_offer_cat_id, ",");                            
                            $xl_store_id = get_post_meta( $xl_post_id, 'offer_store', true );
                            $xl_offer_type =  get_post_meta( $xl_post_id, 'offer_type', true );                            
                            ?>
                            <div data-xltype="<?php echo $xl_offer_type ?>" data-xlstore="<?php echo $xl_store_id ?>" data-xlcategory="<?php echo $xl_offer_cat_id ?>" class="col-sm-<?php echo esc_attr( $col ) ?> xl-offer-item">
                                <?php include( locate_template( 'includes/offers/offers.php' ) ); ?>
                            </div>
                            <?php
                        }?>
                        <script type="text/javascript">                        
                            jQuery(document).ready(function($){
                                setTimeout(function(){
                                    //updateOfferCount();
                                    var dealCount = $('div[data-xltype=deal]:visible').length;
                                    $('#xl-offer-type-deal-count').text('('+dealCount+')');
                                    var couponCount = $('div[data-xltype=coupon]:visible').length;
                                    $('#xl-offer-type-coupon-count').text('('+couponCount+')');
                                    var allCount = dealCount+couponCount;
                                    $('#xl-offer-type-all-count').text('('+allCount+')');
                                    
                                    //hiding categories with no offers                                    
                                    $('.xl-offer-cat-result li').each(function(k,v){
                                        var input = $(this).find('input')[0];
                                        var id = ($(input).attr('id'));
                                        if(!($('div[data-xlcategory='+id+']:visible').length)){
                                            $('div[data-xlcategory='+id+']').hide();
                                        }
                                    });

                                },1000);
                            });
                        </script>                        
                        <?php if( !empty( $pagination ) ): ?>
                            <div class="col-sm-<?php echo esc_attr( $col ) ?> masonry-item">
                                <ul class="pagination">
                                   <?php echo $pagination; ?>
                                </ul>
                            </div>
                        <?php endif; ?>                        
                    </div>
                    <!-- CUSTOMISATION DONE HERE -->
                    <div class="white-block xl-offer-filter-not-found">
                        <div class="white-block-content">
                            <p class="nothing-found">Currently there is no offer available for the Selected Filter !!!</p>
                        </div>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div class="white-block">
                        <div class="white-block-content">
                            <p class="nothing-found"><?php echo couponxl_get_option( 'store_no_offers_message' ); ?></p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>