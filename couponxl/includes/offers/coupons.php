<div class="white-block offer-box coupon-box <?php echo esc_attr( $offer_view ) ?> <?php echo $col == '12' ? 'clearfix' : '' ?>">
	<div class="white-block-media <?php echo $col == '12' ? 'col-sm-4 no-padding' : '' ?>">
		<?php do_action('offer_top_info'); ?>
		<div class="embed-responsive embed-responsive-16by9">
			<?php
			$xl_post_id = get_the_ID();
			$store_id = get_post_meta( $xl_post_id, 'offer_store', true );
			$xl_offer_cat = get_the_terms( $xl_post_id, 'offer_cat' );
			//$xl_affiliate_link = get_post_meta( $xl_post_id, 'coupon_link', true );
			$coupon_code = get_post_meta( $xl_post_id, 'coupon_code', true );
			?>
			<!-- <a class="show-code" target="_blank" data-affiliate="<?php echo $xl_affiliate_link; ?>" href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>"> -->
			<?php
			couponxl_store_logo( $store_id );
			?>
			<!-- </a> -->
		</div>
		<!-- <?php get_template_part( 'includes/share' ); ?> -->
		<?php
		if( !isset( $is_shortcode ) ){
			$is_shortcode = false;
		}
		?>
	</div>
	<!-- href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>" -->

	<div class="white-block-content <?php echo $col == '12' ? 'col-sm-8' : '' ?>">
		<!-- <ul class="list-unstyled list-inline top-meta">
			<li>
				<?php echo couponxl_get_ratings() ?>
			</li>
			<li>
				<?php
				$offer_expire = get_post_meta( $xl_post_id, 'offer_expire', true );
				echo couponxl_remaining_time( $offer_expire, 'left-red' );
				?>
			</li>
		</ul> -->
		<h3 style="max-height:100px;overflow:auto;">
			<a data-coupon="<?php echo $coupon_code; ?>" href="<?php the_permalink(); ?>" class='custom show-code' target="_blank" href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $xl_offer_cat[0]->slug ), array('all') ) ); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<div class='offer-btn-container'>
			<?php echo couponxl_coupon_button( '', $is_shortcode ); ?>
		</div>
		<!-- <p class='xl-permalink'><?php echo the_permalink(); ?></p> -->
		<!-- <div class="read-info-description"><?php echo the_content(); ?></div> -->
	</div>
	<p class="visit-store"><a href="<?php echo get_permalink( $store_id ); ?>" title="<?php echo get_the_title( $store_id ); ?>">Visit Store</a></p>
</div>
