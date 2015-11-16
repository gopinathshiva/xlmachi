<div class="white-block">
    <div class="white-block-title no-border">
        <?php if( !empty( $icon ) ): ?>
            <i class="fa fa-<?php echo esc_attr( $icon ); ?>"></i>
        <?php endif; ?>
        <h2><?php echo $title ?></h2>
        <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?>">
            <?php echo $small_title; ?>
            <i class="fa fa-arrow-circle-o-right"></i>
        </a>
    </div>
</div>

<?php
if( !empty( $items ) ){
    $blogs = new WP_Query(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $blogs_number,
        'post__in' => $items,
        'orderby' => $blogs_orderby,
        'order' => $blogs_order,
        'ignore_sticky_posts' => true
    ));
    $counter = 0;
    if( $blogs->have_posts() ){
        ?>
        <div class="row">
        <?php                    
        while( $blogs->have_posts() ){
            if( $counter == $blogs_per_row ){
                echo '</div><div class="row">';
                $counter = 0;
            }
            $counter++;
            $blogs->the_post();
            $cpxl_col_class = 12/$blogs_per_row;
            $cpxl_col_class = "col-sm-".$cpxl_col_class;
            ?>
            <div class="<?php echo $cpxl_col_class ?>">
                <?php include( locate_template( 'includes/blog-latest.php' ) ); ?>
            </div>
            <?php
        }
        ?>
        </div>
        <?php
        wp_reset_query();
    }    
}
?>