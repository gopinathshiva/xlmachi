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
$store_link = get_post_meta( $post_id, 'store_link', true );
global $categories_data_transient_lifetime;
?>

<section>
    <div class="container">
        <div class="row">
            <?php echo do_shortcode('[featured_stores title="Featured Stores" text="" target="_self" btn_text="" link=""
            option1="237,235,229,226,224,218,196,233"
            option2="237,229,224,218,198,235,226,82"
            option3="196,93,176,192,85,161,188,218"
            option4="202,235,233,231,222,194,184,139"
            option5="220,210,143,135,117,131,147,208"]
            [/featured_stores]'); ?>
            <div class="col-md-3">

                <?php do_action('xl_offer_type'); ?>
                <?php do_action('xl_offer_cat'); ?>
                <?php
                    $advertisement_1_id = '';
                    $advertisement_2_id = '';
                    do_action('xl_advertisement',$advertisement_1_id);
                    do_action('xl_advertisement',$advertisement_2_id);
                ?>
                <?php
                    if ( is_active_sidebar( 'store-sidebar-1' ) ){
                        dynamic_sidebar( 'store-sidebar-1' );
                    }
                ?>
            </div>

            <div class="col-md-9" id='xl-store-start'>
                <?php do_action('xl_filter_text') ?>
                <?php
                //$cur_page = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1; //get curent page
                $cur_page = 1; //get curent page
                //$offers_per_page = couponxl_get_option( 'offers_per_page' );
                $offers_per_page = -1;

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
                            'value' => $post_id,
                            'compare' => '='
                        ),
                    )
                );


                // if( !empty( $offer_type ) ){
                //     $args['meta_query'][] = array(
                //         'key' => 'offer_type',
                //         'value' => $offer_type,
                //         'compare' => '='
                //     );
                // }

                $transient_namespace = xl_transient_namespace();

                $transient_key = $transient_namespace .md5( serialize($transient_args) );

                if(is_localhost()){
                    delete_transient( $transient_key );
                }

                if ( false === ( $offers = get_transient( $transient_key ) ) ) {
                    $offers = new WP_Query( $args );
                    set_transient( $transient_key, $offers, $category_page_transient_lifetime );
                }

                // $page_links_total =  $offers->max_num_pages;
                // $pagination_args = array(
                //     'end_size' => 2,
                //     'mid_size' => 2,
                //     'format' => '?page=%#%',
                //     'total' => $page_links_total,
                //     'current' => $cur_page,
                //     'prev_next' => false,
                //     'type' => 'array'
                // );

                // if( !empty( $offer_type ) ){
                //    //$pagination_args['format'] = !get_option( 'permalink_structure' ) ? '?page=%#%' : 'paged/%#%';
                // }
                // $page_links = paginate_links( $pagination_args );

                // $pagination = couponxl_format_pagination( $page_links );
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
                            $xl_offer_tag_slug = '';
                            $xl_offer_cat = get_the_terms( $xl_post_id, 'offer_cat' );
                            $xl_offer_tag = get_the_terms( $xl_post_id, 'offer_tag' );
                            for ($i = 0; $i < count($xl_offer_cat); ++$i) {
                                $xl_offer_cat_id.=$xl_offer_cat[$i]->term_taxonomy_id.',';
                            }
                            for ($i = 0; $i < count($xl_offer_tag); ++$i) {
                                $xl_offer_tag_slug.=$xl_offer_tag[$i]->slug.',';
                            }
                            unset($i);
                            $xl_offer_cat_id =  rtrim($xl_offer_cat_id, ",");
                            $xl_offer_tag_slug =  rtrim($xl_offer_tag_slug, ",");
                            $xl_store_id = get_post_meta( $xl_post_id, 'offer_store', true );
                            $xl_offer_type =  get_post_meta( $xl_post_id, 'offer_type', true );
                            ?>
                            <div data-xltype="<?php echo $xl_offer_type; ?>" data-xlstore="<?php echo $xl_store_id ?>" data-xlcategory="<?php echo $xl_offer_cat_id ?>" data-xltag="<?php echo $xl_offer_tag_slug; ?>" class="col-sm-<?php echo esc_attr( $col ) ?> xl-offer-item">
                                <?php include( locate_template( 'includes/offers/offers.php' ) ); ?>
                            </div>
                            <?php
                        }?>
                        <script type="text/javascript">
                            window.isXlStorePage = true;
                        </script>
                        <!-- <?php if( !empty( $pagination ) ): ?>
                            <div class="col-sm-<?php echo esc_attr( $col ) ?> masonry-item">
                                <ul class="pagination">
                                   <?php echo $pagination; ?>
                                </ul>
                            </div>
                        <?php endif; ?>   -->
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
                    $store_bottom_description = get_post_meta( $post_id, 'store_bottom', true );
                    if(!empty($store_bottom_description) && !wp_is_mobile()){ ?>
                        <div class="white-block store-bottom-description">
                            <div class="white-block-content">
                                <?php echo $store_bottom_description; ?>
                            </div>
                        </div>
                    <?php }
                ?>
            </div>

        </div>
    </div>
</section>

<?php
get_footer();
?>
