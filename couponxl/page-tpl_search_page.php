<?php
/*
	Template Name: Search Page
*/
get_header();
the_post();
require_once( locate_template( 'includes/title.php' ) );
$permalink = couponxl_get_permalink_by_tpl( 'page-tpl_search_page' );

$search_sidebar_location = couponxl_get_option( 'search_sidebar_location' );
?>
<section>
    <div class="container">
        <div class="row">

            <?php if( $search_sidebar_location == 'left' ): ?>
                <?php require_once( locate_template( 'includes/search-sidebar.php' ) ) ?>
            <?php endif; ?>



            <div class="col-md-9">

                <?php do_action('xl_filter_text') ?>
                <?php 
                // $show_search_slider = couponxl_get_option( 'show_search_slider' );
                // if( $show_search_slider == 'yes' ){
                //     include( locate_template( 'includes/featured-slider.php' ) );
                // }

                //include( locate_template( 'includes/filter-bar.php' ) );
                ?>

            	<?php
                    // $cur_page = 1;
                    // if( get_query_var( 'paged' ) ){
                    //     $cur_page = get_query_var( 'paged' );
                    // }
                    // else if( get_query_var( 'page' ) ){
                    //     $cur_page = get_query_var( 'page' );
                    // }
            		$args = array(
            			'post_status' => 'publish',
            			'posts_per_page' => couponxl_get_option( 'offers_per_page' ),
            			'post_type'	=> 'offer',
            			'paged' => $cur_page,
                        //'paged' => $cur_page,
            			'orderby' => 'meta_value_num',
            			'meta_key' => 'offer_expire',
            			'order' => 'ASC',
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
            			),
            			'tax_query' => array(
            				'relation' => 'AND'
            			)
            		);

                    // if( !empty( $offer_sort ) ){
                    //     $temp = explode( '-', $offer_sort );
                    //     if( $temp[0] == 'date' ){
                    //         unset( $args['meta_key'] );
                    //         $args['orderby'] = $temp[0];
                    //         $args['order'] = $temp[1];
                    //     }
                    //     else{
                    //         if( $temp[0] == 'rate' ){
                    //             $temp[0] = 'couponxl_average_rate';
                    //         }
                    //         $args['meta_key'] = $temp[0];
                    //         $args['order'] = $temp[1];
                    //     }
                    // }                    

            		if( !empty( $offer_type ) ){
                        if( !empty( $offer_type ) || $offer_type == 'deal' ){
                            $args['meta_query'][] = array(
                                'key' => 'deal_status',
                                'value' => 'has_items',
                                'compare' => '='
                            );
                        }
            			$args['meta_query'][] = array(
            				'key' => 'offer_type',
            				'value' => $offer_type,
            				'compare' => '='
            			);
            		}

                    // if( !empty( $offer_store ) ){
                    //     $args['meta_query'][] = array(
                    //         'key' => 'offer_store',
                    //         'value' => $offer_store,
                    //         'compare' => '=',
                    //     );
                    // }

            		if( !empty( $offer_cat ) ){
            			$args['tax_query'][] = array(
            				'taxonomy' => 'offer_cat',
            				'field'	=> 'slug',
            				'terms' => $offer_cat,
            			);
            		}                    

            		if( !empty( $offer_tag ) ){
            			$args['tax_query'][] = array(
            				'taxonomy' => 'offer_tag',
            				'field'	=> 'slug',
            				'terms' => $offer_tag,
            			);
            		}
            		// if( !empty( $location ) ){
            		// 	$args['tax_query'][] = array(
            		// 		'taxonomy' => 'location',
            		// 		'field'	=> 'slug',
            		// 		'terms' => $location,
            		// 	);
            		// }

                    if( !empty( $keyword ) ){
                        $args['s'] = urldecode( $keyword );
                    }
                    

                    $transient_args = $args;

                    foreach ($transient_args as $index => $data) {                        
                        if ($index == 'meta_query') {
                            unset($transient_args[$index]);
                        }
                    }

                    $transient_namespace = xl_transient_namespace();

                    $transient_key = $transient_namespace .md5( serialize($transient_args) );                       

                    if ( false === ( $offers = get_transient( $transient_key ) ) ) {
                        $offers = new WP_Query( $args );
                        set_transient( $transient_key, $offers, 8 * HOUR_IN_SECONDS );                                            
                    }                   
            		
					// $page_links_total =  $offers->max_num_pages;
     //                $pagination_args = array(
     //                    'prev_next' => true,
     //                    'end_size' => 2,
     //                    'mid_size' => 2,
     //                    'total' => $page_links_total,
     //                    'current' => $cur_page, 
     //                    'prev_next' => false,
     //                    'type' => 'array'
     //                );
     //                if( is_front_page() ){
     //                    $pagination_args['base'] = '%_%';
     //                    $pagination_args['format'] = '?page=%#%';
     //                }
					// $page_links = paginate_links( $pagination_args );

					// $pagination = couponxl_format_pagination( $page_links );            		

            		if( $offers->have_posts() ){
                        $col = is_active_sidebar( 'sidebar-search' ) ? '4' : '4'; /* CUSTOMISATION DONE changed from 6 to 4 to show 3 offers per row */
                        if( $offer_view == 'list' ){
                            $col = 12;
                        }
            			?>
                        <!-- CUSTOMISATION DONE HERE -->
                        <div class="white-block xl-offer-filter-not-found">
                            <div class="white-block-content">
                                <p class="nothing-found">Currently there is no offer available for the Selected Filter !!!</p>
                            </div>
                        </div>
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
	            				<div data-xltype="<?php echo $xl_offer_type ?>" data-xlstore="<?php echo $xl_store_id ?>" data-xlcategory="<?php echo $xl_offer_cat_id ?>" class="offer-view-<?php echo $offer_view; ?> col-sm-<?php echo esc_attr( $col ) ?> xl-offer-item">
	            					<?php include( locate_template( 'includes/offers/offers.php' ) ); ?>
	            				</div>
	            				<?php
	            			}?>
                            <script type="text/javascript">  
                                window.isXlSearchPage = true;
                            </script>  	            			
                            <!-- <?php if( !empty( $pagination ) ): ?>
	            			    <div class="col-sm-<?php echo esc_attr( $col ) ?> masonry-item">
                                    <ul class="pagination">
    	            				   <?php echo $pagination; ?>
                                    </ul>
	            			    </div>
                            <?php endif; ?> -->
            			</div>
            			<?php
            		}
                    else{
                        ?>
                        <div class="white-block">
                            <div class="white-block-content">
                                <p class="nothing-found"><?php echo couponxl_get_option( 'search_no_offers_message' ); ?></p>
                            </div>
                        </div>
                        <?php
                    }
            	?>
            </div>

            <?php if( $search_sidebar_location == 'right' ): ?>
                <?php require_once( locate_template( 'includes/search-sidebar.php' ) ) ?>
            <?php endif; ?>            
            
        </div>
    </div>
</section>
<?php
get_footer();
?>