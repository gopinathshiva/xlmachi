<div class="white-block offer-box deal-box <?php echo esc_attr( $offer_view ) ?> <?php echo $col == '12' ? 'clearfix' : '' ?>">
	<div class="white-block-media  <?php echo $col == '12' ? 'col-sm-4 no-padding' : '' ?>">
		<?php do_action('offer_top_info'); ?>
		<div class="embed-responsive embed-responsive-16by9">
			<?php
			$xl_post_id = get_the_ID();

			if( has_post_thumbnail() ){?>
			<!-- CUSTOMISATION DONE HERE -->
			<a target="_blank" href="<?php the_permalink(); ?>" >
				<?php the_post_thumbnail( 'offer-box', array( 'class' => 'embed-responsive-item' ) ); ?>
				</a>
			<?php
			}else{
				$store_id = get_post_meta( $xl_post_id, 'offer_store', true );
				?>

				<a target="_blank" href="<?php the_permalink(); ?>" >
					<?php
					couponxl_store_logo( $store_id );
					?>
				</a>
				<?php
			}
			?>
		</div>
		<!-- <?php
			get_template_part( 'includes/share' );
		?> -->
	</div>

	<div class="white-block-content  <?php echo $col == '12' ? 'col-sm-8' : '' ?>">
		<!-- <ul class="list-unstyled list-inline top-meta">
			<li>
				<?php echo couponxl_get_ratings() ?>
			</li>
			<li>
				<?php
				$offer_expire = get_post_meta( get_the_ID(), 'offer_expire', true );
				echo couponxl_remaining_time( $offer_expire );
				?>
			</li>
		</ul> -->

		<h3 style="min-height:105px;overflow:auto;"><a target="_blank" href="<?php the_permalink(); ?>" class=""><?php the_title(); ?></a></h3>
		<div class="read-info-description"><?php echo $content; ?></div>
		<p class='xl-permalink'><?php echo the_permalink(); ?></p>
		<div class='offer-btn-container'>
			<a target="_blank" href="<?php the_permalink(); ?>" class="xl-activate-deal btn">ACTIVATE DEAL</a>
		</div>
	</div>
	<!-- hiding empty div when there is no price to be shown -->

	<!-- <?php
	 $xl_deal_price = couponxl_get_deal_price();
	 if( !empty($xl_deal_price) ){ ?>
	  <div class="white-block-footer  <?php echo $col == '12' ? 'col-sm-12' : '' ?>">
	    <div class="white-block-content">
		<?php echo couponxl_get_deal_price(); ?>
	    </div>
      </div>
	<?php } ?> -->
	<p class="visit-store"><a href="<?php echo get_permalink( $store_id ); ?>" title="<?php echo get_the_title( $store_id ); ?>">Visit Store</a></p>
</div>
