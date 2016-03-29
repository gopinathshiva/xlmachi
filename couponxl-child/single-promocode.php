<?php
get_header();
the_post();
get_template_part( 'includes/title' );
?>

<style>
    .single-promocode .breadcrumb-section {
        margin-bottom: 0;
    }
    .single-promocode .row>h1{
        font-size: 30px;
        font-weight: bold;
        text-align: center;
    }
    .single-promocode .promocode-filter-container{
        padding: 10px 0;
    }
    .single-promocode .promocode-filter-container select{
        width: 40%;
    }
</style>
<?php
    $args = array(
        'post_type' => 'store',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    );

    $stores = new WP_Query( $args );

    $xl_offer_cats = couponxl_get_organized( 'offer_cat' );
?>
<section class="contact-page">
    <div class="container">
        <div class="row">
            <h1><?php the_title(); ?></h1>
            <div class="col-md-<?php echo is_active_sidebar( 'sidebar-right' ) ? '9' : '12' ?>">
                <div class="white-block">
                    <?php
                    if( has_post_thumbnail() ){
                        the_post_thumbnail( 'post-thumbnail' );
                    }
                    ?>

                    <div class="white-block-content">
                        <div class="page-content clearfix">
                            <?php the_content() ?>
                        </div>
                        <div class="promocode-filter-container">
                            <div class='col-md-6'>
                                <label>Filter by Store:&nbsp;</label>
                                <select> <?php
                                    while ( $stores->have_posts() ) : $stores->the_post();
                                        echo '<option value="'.$post->ID.'">';
                                    	the_title();
                                    	echo '</option>';
                                    endwhile; ?>
                                </select>
                            </div>
                            <div class='col-md-6'>
                                <label>Filter by Category:&nbsp;</label>
                                <select>
                                <?php
                                    foreach( $xl_offer_cats as $key => $cat){
                                        echo '<option value="'.$cat->term_taxonomy_id.'">';
                                    	echo $cat->name;
                                    	echo '</option>';
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <br/>
                        <div class="promocode-table-container">
                            <table>
                                <tr>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Offer Page</th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td>Code</td>
                                    <td><a href="javascript:void(0)">Offer Page</a></td>
                                    <td>Status</td>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td>Code</td>
                                    <td><a href="javascript:void(0)">Offer Page</a></td>
                                    <td>Status</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <?php get_sidebar('right') ?>

        </div>
    </div>
</section>
<?php get_footer(); ?>
