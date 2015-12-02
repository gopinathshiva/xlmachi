<?php
/*==================
 SINGLE BLOG POST FOR FLIPKART DAILY DEALS
==================*/

get_header();
//the_post();
get_template_part( 'includes/title' );
$post_id = 475;
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="white-block">                                    
                        <div class="shop-logo">
                            <img width="150" height="150" src="http://localhost/CouponMachi/wp-content/uploads/2015/11/Flipkart-store-logo-150x150.png" class="img-responsive wp-post-image" alt="Flipkart store logo">
                        </div>                    

                    <ul class="list-unstyled list-inline store-social-networks">
                        <?php
                        $store_facebook = get_post_meta( $post_id, 'store_facebook', true );
                        if( !empty( $store_facebook ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_facebook ) ?>" target="_blank" class="share">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <?php
                        }
                        $store_twitter = get_post_meta( $post_id, 'store_twitter', true );
                        if( !empty( $store_twitter ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_twitter ) ?>" target="_blank" class="share">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <?php
                        }                            
                        $store_google = get_post_meta( $post_id, 'store_google', true );
                        if( !empty( $store_google ) ){
                            ?>
                            <li>
                                <a href="<?php echo esc_url( $store_google ) ?>" target="_blank" class="share">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </li>
                            <?php
                        }                            
                        ?>
                    </ul>

                    <div class="white-block-content">
                        <?php 
                        $show_breadcrumbs = couponxl_get_option( 'show_breadcrumbs' );
                        if( $show_breadcrumbs == 'yes' ){
                            ?>
                            <!-- <h1 class="size-h5"><?php the_title(); ?></h1> -->
                            <?php
                        }
                        else{
                            ?>
                            <h5><?php the_title(); ?></h5>
                            <?php
                        }
                        ?>
                        <?php 
                        $content = get_the_content();
                        if( !empty( $content ) ):
                        ?>
                            <div class="read-more">
                                <div class="read-more-content">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            <a href="javascript:;" class="read-more-toggle" data-close="<?php esc_attr_e( 'Close', 'couponxl' ) ?>"><?php _e( 'Read More', 'couponxl' ) ?></a>
                        <?php
                        endif;
                        ?>
                    </div>                    
                </div>                    

                <?php 
                    if ( is_active_sidebar( 'store-sidebar-1' ) ){
                        dynamic_sidebar( 'store-sidebar-1' );
                    }
                ?>
            </div>

            <div class="col-md-9" id='xl-store-start'> 
            <!-- <div id='flipkart-search-box' style="margin-bottom: 10px;margin-left: 15px;">
            <input style="width: 70%;border-radius: 4px;outline: 0;border: 1px solid rgba(0, 0, 0, 0.3);padding-left: 10px;
    height: 35px;" type="text" class="xl-flipkart-search-input" value="" placeholder="Search Flipkart Offers" name="keyword">  
            <button id='flipkart-search' style="background: rgba(91, 15, 112, 0.29);height: 35px;border-radius: 5px;outline: 0;width: 29%;">Search</button>
            </div> -->
            <?php 

                if ( false === ( $response = get_transient( 'flipkart_daily_deals' ) ) ) {                   
                   $response = getDailyDeals();
                }   
            
                if(empty($response)){
                    echo '<h5>Sorry something went wrong ! Please try Reloading the page</h5>';
                }
                else{
                    $response = $response['dotdList'];
                    ?>
                    <div id='flipkart-offer-start'>
                    <?php                    
                    foreach ($response as $value) {                                          
            ?>

            <!-- start -->

            <div class="col-sm-4 xl-offer-item">
                <div class="white-block offer-box coupon-box grid ">
                    <div class="white-block-media ">
                        <div class="embed-responsive embed-responsive-16by9">
                                        <a href="<?php echo $value['url'] ?>">
                            <img style="top:65%;" width="200" height="200" src="<?php echo $value['imageUrls'][1]['url'] ?>" alt="flipkart store logo">           
                            </a>
                        </div>
                        <ul class="list-unstyled share-networks animation ">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $value['url'] ?>" class="share" target="_blank">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="http://twitter.com/intent/tweet?text=<?php echo $value['url'] ?>" class="share" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://plus.google.com/share?url=<?php echo $value['url'] ?>" class="share" target="_blank">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </li>
                        </ul>

                        <a href="javascript:;" class="share open-share">
                            <i class="fa fa-share-alt"></i>
                        </a>
                        <a href="<?php echo $value['url'] ?>" class="btn">ACTIVATE DEAL</a>
                   </div>

                    <div style="" class="white-block-content ">
                        <div class="list-unstyled list-inline top-meta">                                                        
                            <span class="red-meta">Availability: </span>                            
                            <span style="<?php echo ($value['availability']=='LIVE' ? 'color:green;':'') ?> font-weight:bold;"><?php echo $value['availability'] ?></span>
                        </div>

                        <h3>
                            <a href="<?php echo $value['url'] ?>">
                                <?php echo $value['title']; ?> - <?php echo $value['description']; ?> </a>
                        </h3>                        
                        <ul class="list-unstyled list-inline bottom-meta">
                            <li>
                                <i class="fa fa-dot-circle-o icon-margin"></i>
                                <a href="http://dl.flipkart.com/dl/?affid=couponmac">Flipkart</a> 
                              </li>                        
                        </ul>                        
                    </div>
                </div>                                          
            </div>
            <?php                
                }
               ?> </div><?php
            }
            ?>

            <!-- end -->



                    
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>