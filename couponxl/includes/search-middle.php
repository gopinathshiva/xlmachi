<div class="col-md-3">
    <?php
    do_action('xl_offer_type');
    do_action('xl_offer_cat');
    do_action('xl_offer_store'); ?>
    <div class="xl-search-page-widget">
    <?php 
        if ( is_active_sidebar( 'sidebar-search' ) ){
            dynamic_sidebar( 'sidebar-search' );
        }
    ?>
    </div>
</div>