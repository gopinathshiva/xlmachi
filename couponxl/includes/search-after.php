<?php
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