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
    .single-promocode .promocode-table-container table{
        text-align: center;
    }
    .single-promocode .promocode-table-container table .promo-code{
        background: #fff;
        padding: 8px 15px;
    }
    .single-promocode .promocode-table-container table,.single-promocode .promocode-table-container table tr:nth-child(odd) {
        background: #e2f1ec;
    }
    .single-promocode .promocode-table-container th{
        background-color: rgb(43, 133, 206);
        color: #fff;
        border: none;
        text-align: center;
    }
    .single-promocode .promocode-table-container .offer-btn{
        padding: 8px 12px;
        background: #4CAF50;
        color: white;
        font-weight: 500;
    }
    @media screen and (max-width:650px) {
        .single-promocode .contact-page .white-block {
            padding: 0;
        }
    }
    @media screen and (max-width:650px) {
        .single-promocode .container,.single-promocode .white-block-content {
            padding: 0;
        }
        .single-promocode .promocode-table-container .offer-btn{
            padding: 6px 6px;
        }
        .single-promocode .promocode-table-container td{
            padding: 10px 5px;
        }
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
                                <col width="40%">
                                <col width="20%">
                                <col width="32%">
                                <col width="8%">
                                <tr>
                                    <th>Title</th>
                                    <th>Code</th>
                                    <th>Click Here</th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td><span class="promo-code">Code</span></td>
                                    <td ><a class="offer-btn" href="javascript:void(0)">Click Here</a></td>
                                    <td>Status</td>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td><span class="promo-code">Code</span></td>
                                    <td><a class="offer-btn" href="javascript:void(0)">Click Here</a></td>
                                    <td>Status</td>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td><span class="promo-code">Code</span></td>
                                    <td ><a class="offer-btn" href="javascript:void(0)">Click Here</a></td>
                                    <td>Status</td>
                                </tr>
                                <tr>
                                    <td>Post title here</td>
                                    <td><span class="promo-code">Code</span></td>
                                    <td><a class="offer-btn" href="javascript:void(0)">Click Here</a></td>
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
