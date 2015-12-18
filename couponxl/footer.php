<?php get_sidebar('footer'); ?>

<?php
$footer_copyrights = couponxl_get_option( 'footer_copyrights' );
$footer_facebook = couponxl_get_option( 'footer_facebook' );
$footer_twitter = couponxl_get_option( 'footer_twitter' );
$footer_google = couponxl_get_option( 'footer_google' );

if(is_front_page() && !wp_is_mobile()){
	do_action('xl_side_menu');
}
if( !empty( $footer_copyrights ) || !empty( $footer_facebook ) || !empty( $footer_twitter ) || !empty( $footer_google ) ):
?>
	<section class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php
					$show_to_top = couponxl_get_option( 'show_to_top' );
					if( $show_to_top == 'yes' ):
					?>
						<div class="to-top">
							<a href="javascript:;">
								<i class="fa fa-angle-double-up"></i>
							</a>
						</div>
					<?php endif; ?>

					<div class="pull-left">
						<?php echo $footer_copyrights ?>
					</div>

					<?php 
					debug_to_console('calling scroll top action');
					do_action('xl_scroll_top'); ?>

					<div class="pull-right">
						<?php
							if( !empty( $footer_facebook ) ){
								?>
								<a href="<?php echo esc_url( $footer_facebook ) ?>" class="btn facebook" target="_blank"><i class="fa fa-facebook"></i></a>
								<?php
							}
							if( !empty( $footer_twitter ) ){
								?>
								<a href="<?php echo esc_url( $footer_twitter ) ?>" class="btn twitter" target="_blank"><i class="fa fa-twitter"></i></a>
								<?php
							}
							if( !empty( $footer_google ) ){
								?>
								<a href="<?php echo esc_url( $footer_google ) ?>" class="btn google" target="_blank"><i class="fa fa-google-plus"></i></a>
								<?php
							}						
						?>
					</div>

				</div>
			</div>
		</div>
	</section>
<?php
endif;
?>
<!-- modal -->
<div class="modal fade in" id="showCode" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content showCode-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="coupon_modal_content">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- .modal -->
<!-- modal -->
<div class="modal fade in" id="showPayment" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content showCode-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="payment-content">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in" id="payUAdditional" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content showCode-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="payu-content-modal">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- .modal -->
<?php
wp_footer();
?>
</body>
</html>