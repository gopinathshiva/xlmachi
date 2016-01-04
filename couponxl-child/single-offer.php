<?php
/*==================
 SINGLE DEAL POST
==================*/

the_post();
$_offer_type = get_post_meta( get_the_ID(), 'offer_type', true );
$site_logo = couponxl_get_option( 'site_logo' );
$offer_store = get_post_meta( get_the_ID(), 'offer_store', true );
if($_offer_type == 'deal'){
	$aff_link = get_post_meta( get_the_ID(), 'deal_link', true );
}else{
	$aff_link = get_post_meta( get_the_ID(), 'coupon_link', true );
}
if(is_localhost()){
    $base_url = 'http://localhost/coupons/';
}else{
    $base_url = 'http://couponmachi.com/';
}
header( "refresh:.5;url=".$aff_link );
?>
<style>
body{
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
}

.store-redirector-parent{
    background: #FFF;
    width: 100%;
    padding-top: 0px;
}

.store-redirector-parent .container{
    width: 95%;
    margin-right: auto;
    margin-left: auto;
}

.store-redirector-parent .redirection-box{
    display: block;
    margin-bottom: 10px;
    width: 100%;
    padding-top: 60px;
}

.redirection-box .redirect-logo {
    overflow: hidden;
    margin-bottom: 60px;
    width: 100%;
    border-bottom: solid 1px #dfdfdf;
    padding-bottom: 60px;
    padding-top: 20px;
}

.redirection-box .redirect-logo ul li.logo {
    float: left;
    width: 25%;
    margin-left: 5%;
    margin-right: 5%;
    text-align: center;
    margin-top: 15px;
}

.redirection-box .redirect-logo ul li.store-redirector {
    float: left;
    width: 30%;
    height: 92px;
    background: url(http://localhost/coupons/wp-content/themes/couponxl-child/images/store_redirector.gif) no-repeat center center;
}

.redirection-box .redirect-logo ul li.partner-logo {
    float: right;
    width: 25%;
    margin-left: 5%;
    margin-right: 5%;
    text-align: center;
}

.redirection-box .redirect-logo img {
    max-width: 230px;
    max-height: 92px;
}

.description-box {
    margin: 0px 0px 20px;
    padding: 0px;
    background-color: #f9f9f9;
    border: dashed 1px #ccc;
    overflow: hidden;
}

.description-box.centered {
    text-align: center;
}

.description-box .innerBorder {
    border: solid 5px #fff;
    padding-top: 10px;
    padding-bottom: 0px;
    overflow: hidden;
    padding-left: 1%;
}

.description-box .innerBorder h4 {
    color: #fb7f25;
    font: 300 26px/30px 'Oswald', sans-serif;
    margin-bottom: 20px;
    margin-top: 10px;
    margin-right: 2%;
}

.description-box .innerBorder p.title-deal {
    font: 300 19px/30px 'Oswald', sans-serif;
    color: #373737;
    margin-bottom: 10px;
    margin-right: 2%;
}
</style>
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
    <?php

?>