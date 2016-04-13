<?php get_header();
the_post();
require_once( locate_template( 'includes/title.php' ) );
$permalink = couponxl_get_permalink_by_tpl( 'page-tpl_search_page' );

$search_sidebar_location = couponxl_get_option( 'search_sidebar_location' );
?>
<div class="filter-offer-menu">
    <a href="javascript:void(0);">Filter Offers<i class="fa fa-chevron-down coupon-type"></i></a>
</div>
<section class="search-template-page">
    <div class="container">
        <div class="row">

            <?php if( $search_sidebar_location == 'left' ): ?>
                <?php require_once( locate_template( 'includes/search-middle.php' ) ) ?>
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
