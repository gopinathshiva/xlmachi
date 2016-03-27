<div class="white-block offer-box deal-box <?php echo esc_attr( $offer_view ) ?> <?php echo $col == '12' ? 'clearfix' : '' ?>">
	<div class="white-block-media  <?php echo $col == '12' ? 'col-sm-4 no-padding' : '' ?>">
		<?php do_action('offer_top_info'); ?>		
		<div class="embed-responsive embed-responsive-16by9">
			<?php
			$xl_post_id = get_the_ID();
			//$xl_offer_cat = get_the_terms( $xl_post_id, 'offer_cat' );
			//$xl_affiliate_link = get_post_meta( $xl_post_id, 'deal_link')[0];			
			
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
		<?php 
			get_template_part( 'includes/share' );
		?>	
		<!-- CUSTOMISATION DONE HERE -->		
		<!-- <a data-affiliate="<?php echo $xl_affiliate_link; ?>" class="xl-activate-deal btn" onclick="activateDeal(this,'<?php echo $xl_affiliate_link; ?>')">ACTIVATE DEAL</a>					 -->
		<a target="_blank" href="<?php the_permalink(); ?>" class="xl-activate-deal btn">ACTIVATE DEAL</a>
		<!-- <a href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'deal' => get_the_ID() ), array('all') ) ); ?>" class="btn"><?php _e( 'VIEW DEAL', 'couponxl' ) ?></a> -->
	</div>

	<div style="<?php echo $deals_per_row == '4'? 'padding:10px':'' ?>" class="white-block-content  <?php echo $col == '12' ? 'col-sm-8' : '' ?>">
		<ul class="list-unstyled list-inline top-meta">
			<li>
				<?php echo couponxl_get_ratings() ?>
			</li>
			<li>
				<?php
				$offer_expire = get_post_meta( get_the_ID(), 'offer_expire', true );
				echo couponxl_remaining_time( $offer_expire );
				?>
			</li>
		</ul>

		<h3 style="max-height:105px;overflow:auto;"><a target="_blank" href="<?php the_permalink(); ?>" class=""><?php the_title(); ?></a></h3>
		
		<?php do_action('offer_other_info'); ?>
		<ul class="list-unstyled list-inline bottom-meta">
			<li>
				<i class="fa fa-dot-circle-o icon-margin"></i> 
				<?php
					$store_id = get_post_meta( get_the_ID(), 'offer_store', true );
					echo '<a href="'.get_permalink( $store_id ).'">'.get_the_title( $store_id ).'</a>'; 
				?>			
			</li>
			<!-- MODIFICATION DONE -->
			<?php
	        //$deal_in_short = get_post_meta( get_the_ID(), 'deal_in_short', true );	
	        $content = get_the_content();	        
	        if( !empty($content) ):
	        ?>					
				<li class="deal-info-container">
					<a href="<?php the_permalink(); ?>" class="read-info" href="javascript:void(0)">Deal Info (+)</a>									
				</li>
			<?php endif; ?>
		</ul>
		<div class="read-info-description"><?php echo $content; ?></div>
		<p class='xl-permalink'><?php echo the_permalink(); ?></p>
	</div>
	
	<!-- hiding empty div when there is no price to be shown -->
	
	<?php
	 $xl_deal_price = couponxl_get_deal_price();
	 if( !empty($xl_deal_price) ){ ?>
	  <div class="white-block-footer  <?php echo $col == '12' ? 'col-sm-12' : '' ?>">
	    <div class="white-block-content">
		<?php echo couponxl_get_deal_price(); ?>
	    </div>
      </div>
	<?php } ?>
</div>