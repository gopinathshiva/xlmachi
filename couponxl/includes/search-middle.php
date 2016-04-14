<div class="col-md-3 filter-offer-container">
    <?php
        do_action('xl_offer_type');
        do_action('xl_offer_cat');
        do_action('xl_offer_store');
        $advertisement_1_id = '397';
        $advertisement_2_id = '398';
        do_action('xl_advertisement',$advertisement_1_id);
        do_action('xl_advertisement',$advertisement_2_id);
    ?>
    <div class="xl-search-page-widget">
    <?php
        if ( is_active_sidebar( 'sidebar-search' ) ){
            dynamic_sidebar( 'sidebar-search' );
        }
    ?>
    </div>
</div>
