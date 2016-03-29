
<?php
    if(!$is_carousel){ ?>
        <div class="white-block featured-stores-carousel" style="margin-bottom:10px;">
            <ul class="featured-stores-tabs nav nav-tabs nav-justified">
                <li data-store-ids="<?php echo $option1; ?>" id='store-option1' class="active"><a href="javascript:void(0)" data-toggle="tab"><i class="fa fa-flash icon-margin"></i> Top Online Stores</a></li>
                <li data-store-ids="<?php echo $option2; ?>" id='store-option2' class=""><a href="javascript:void(0)" data-toggle="tab"><i class="fa fa-mobile icon-margin"></i> Mobiles &amp; Electronics </a></li>
                <li data-store-ids="<?php echo $option3; ?>" id='store-option3' class=""><a href="javascript:void(0)" data-toggle="tab"><i class="fa fa-female icon-margin"></i> Clothing &amp; Shoes </a></li>
                <li data-store-ids="<?php echo $option4; ?>" id='store-option4' class=""><a href="javascript:void(0)" data-toggle="tab"><i class="fa fa-bus icon-margin"></i> Recharge &amp; Travel </a></li>
                <li data-store-ids="<?php echo $option5; ?>" id='store-option5' class=""><a href="javascript:void(0)" data-toggle="tab"><i class="fa fa-cutlery icon-margin"></i> Food, Grocery &amp; Gifts </a></li>
            </ul>
        </div>
    <?php }
?>
<div class="white-block featured-stores <?php echo $is_carousel?'carousel':'no-carousel'; ?>">
    <div class="row">
        <div class="col-sm-12">
            <?php
            $args = array(
                'post_type' => 'store',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );

            if( !empty( $items ) ){
                $args['post__in'] = $items;
                $args['orderby'] = 'post__in';
            }
            else{
                $args['meta_query'] = array(
                    array(
                        'key' => 'store_featured',
                        'value' => 'yes',
                        'compare' => '='
                    ),
                );
            }
            $stores = new WP_Query( $args );
            if( $stores->have_posts() ){
                ?>
                <ul class="list-unstyled list-inline stores-image-container">
                    <?php
                    while( $stores->have_posts() ){
                        $stores->the_post();
                        ?>
                        <li id="<?php the_ID(); ?>">
                            <div class="store-logo">
                                <a href="<?php the_permalink() ?>">
                                    <?php couponxl_store_logo(); ?>
                                </a>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                wp_reset_query();
            }
            ?>
        </div>
    </div>
</div>
