<?php
/*==================
 SINGLE DEAL POST
==================*/

the_post();
$_offer_type = get_post_meta( get_the_ID(), 'offer_type', true );
$site_logo = couponxl_get_option( 'site_logo' );
$offer_store = get_post_meta( get_the_ID(), 'offer_store', true );
if($_offer_type== 'deal'){
	$aff_link = get_post_meta( get_the_ID(), 'deal_link', true );
}else{
	$aff_link = get_post_meta( get_the_ID(), 'coupon_link', true );
}
if(is_localhost()){
	$base_url = 'http://localhost/coupons/';
}else{
	$base_url = 'http://couponmachi.com/';
}
header( "refresh:.25;url=".$aff_link );
?>
<link rel="stylesheet" href="<?php echo $base_url; ?>wp-content/themes/couponxl-child/redirect-style.css">
<div class="store-redirector-parent">
    <div class="container">
        <div class="redirection-box">
            <div class="redirect-logo">
                <ul>
                    <li class="logo"><img src="<?php echo esc_url( $site_logo['url'] ); ?>"></li>
                    <li class="store-redirector">&nbsp;</li>
                    <li class="partner-logo"><?php couponxl_store_logo( $offer_store ); ?></li>
                </ul>
            </div>
            <div class="description-box centered">
                <div class="innerBorder">
                    <h4>You are on your way to our partners' web store</h4>
                    <p class="title-deal"><?php the_title(); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	setTimeout(function(){document.location.href = $this.data('affiliate');},250);
</script>
    <?php

?>