<div class="white-block offer-box coupon-box <?php echo esc_attr( $offer_view ) ?> <?php echo $col == '12' ? 'clearfix' : '' ?>">
	<div class="white-block-media <?php echo $col == '12' ? 'col-sm-4 no-padding' : '' ?>">
		<?php do_action('offer_top_info'); ?>
		<div class="embed-responsive embed-responsive-16by9">
			<?php
			global $xl_coupon_link;
			$xl_post_id = get_the_ID();
			$store_id = get_post_meta( $xl_post_id, 'offer_store', true );			
			$xl_offer_cat = get_the_terms( $xl_post_id, 'offer_cat' );
			$xl_affiliate_link = get_post_meta( get_the_ID(), 'coupon_link', true );
			$xl_coupon_link = esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) );
			$xl_coupon_link = rtrim($xl_coupon_link,"/");
			$xl_coupon_link .= '?coupon_id='.$xl_post_id;
			?>
			<a class="show-code" target="_blank" data-affiliate="<?php echo $xl_affiliate_link; ?>" href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>">
			<?php 
			couponxl_store_logo( $store_id );
			?>			
			</a>
		</div>
		<?php get_template_part( 'includes/share' ); ?>
		<?php 		
		if( !isset( $is_shortcode ) ){
			$is_shortcode = false;
		}
		echo couponxl_coupon_button( '', $is_shortcode ); 
		?>
	</div>
	<!-- href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>" -->

	<div style="<?php echo $coupons_per_row == '4'? 'padding:10px':'' ?>" class="white-block-content <?php echo $col == '12' ? 'col-sm-8' : '' ?>">
		<ul class="list-unstyled list-inline top-meta">
			<li>
				<?php echo couponxl_get_ratings() ?>
			</li>
			<li>
				<?php
				$offer_expire = get_post_meta( $xl_post_id, 'offer_expire', true );
				echo couponxl_remaining_time( $offer_expire, 'left-red' );
				?>
			</li>
		</ul>		
		<h3>
			<a class='custom show-code' target="_blank" data-affiliate="<?php echo $xl_affiliate_link; ?>" href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<!-- <div class="read-info-container">
			<a class="read-info" href="javascript:void(0)">Coupon Info (+)</a>
			<div class="read-info-description"><?php the_excerpt(); ?></div>
		</div> -->
		<!-- CUSTOMISATION DONE HERE -->
		<?php do_action('offer_other_info'); ?>
		<ul class="list-unstyled list-inline bottom-meta">
			<li>
				<i class="fa fa-dot-circle-o icon-margin"></i>
				<?php echo '<a href="'.get_permalink( $store_id ).'">'.get_the_title( $store_id ).'</a>'; ?>
			</li>
			
			<?php	       
			$xl_coupon_content = get_the_content();        
	        if( !empty($xl_coupon_content) ):
	        ?>
				<li class="coupon-info-container"> 					 					
					<a class="read-info" href="javascript:void(0)">Coupon Info (+)</a>										
				</li>				
			<?php endif; ?>
			<?php
			$coupon_type = get_post_meta( get_the_ID(), 'coupon_type', true );
			if( $coupon_type == 'printable' ){
				?>
				<li>
					<i class="fa fa-map-marker icon-margin"></i> 
					<?php echo couponxl_taxonomy( 'location', 1 ) ?>
				</li>				
				<?php
			}
			?>
		</ul>
		<div class="read-info-description"><?php echo the_excerpt(); ?></div>
	</div>
</div>