<?php
/*
	Template Name: Food Ordering Page
*/
    global $category_page_transient_lifetime;
                    require_once( locate_template( 'includes/search-before.php' ) );
                    $cur_page = 1;

                    $offer_cat = 'food-ordering,beverages,sweets-snacks';

            		$args = array(
            			'post_status' => 'publish',
            			'posts_per_page' => couponxl_get_option( 'offers_per_page' ),
            			'post_type'	=> 'offer',
            			'paged' => $cur_page,
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

            		// if( !empty( $offer_type ) ){
              //           if( !empty( $offer_type ) || $offer_type == 'deal' ){
              //               $args['meta_query'][] = array(
              //                   'key' => 'deal_status',
              //                   'value' => 'has_items',
              //                   'compare' => '='
              //               );
              //           }
            		// 	$args['meta_query'][] = array(
            		// 		'key' => 'offer_type',
            		// 		'value' => $offer_type,
            		// 		'compare' => '='
            		// 	);
            		// }

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
            				'terms' => explode( ",", $offer_cat ),
                            'operator' => 'IN'
            			);
            		}

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
                        set_transient( $transient_key, $offers, $category_page_transient_lifetime );
                    }

					require_once( locate_template( 'includes/search-after.php' ) );
?>
