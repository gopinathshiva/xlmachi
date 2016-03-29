<div class="white-block featured-stores">
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
            $counter = 0;
            ?>
            <ul class="list-unstyled list-inline nav nav-tabs nav-justified" role="tablist">
                <li class=""><a href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-hand-right Top"></span> Top Online Stores</a></li>
                <li class=""><a href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-hand-right Top"></span> Top Online Stores</a></li>
                <li class=""><a href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-hand-right Top"></span> Top Online Stores</a></li>
                <li class=""><a href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-hand-right Top"></span> Top Online Stores</a></li>
                <li class=""><a href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-hand-right Top"></span> Top Online Stores</a></li>
            </ul>
            <?php
            if( $stores->have_posts() ){
                ?>
                <ul class="list-unstyled list-inline">
                    <?php
                    while( $stores->have_posts() ){
                        $stores->the_post();
                        ?>
                        <li>
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
