<?php
 ?>
 <div class="row">
     <style>
        .offer-slider-post{
            height: 110px;
            padding: 10px;
        }
        .offer-slider-post img{
            width: 100%;
            height: 100%;
        }

        @media screen and (min-width:1200px){
            .offer-slider-post{
                height: 131px;
            }
        }
        @media screen and (max-width:991px){
            .offer-slider-post img{
                height: auto;
            }
        }
        @media screen and (max-width:767px){
            .offer-slider-post{
                padding: 10px 20px;
                min-height: 90px;
                height: auto;
            }
            .offer-slider-post img{
                box-shadow: initial;
            }
        }
     </style>
     <?php
        query_posts(array(
            'post__in'=>$slider_posts,
            'post_type'=>'offer',
            'order' => 'post__in',
            'posts_per_page'=>4
        ));
        if ( have_posts() ) {
            while ( have_posts() ) : the_post();
                $post_id = get_the_ID();
                $img_url = get_the_post_thumbnail( $post_id, array(140,90) );
                $url = get_the_permalink($post_id);
                ?>
                <div data-offer-title="<?php the_title(); ?>" class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
                    <a href="<?php echo $url; ?>" target="_blank" rel="nofollow">
                        <!-- <img src="<?php echo $img_url; ?>"> -->
                        <?php echo $img_url; ?>
                    </a>
                </div>
                <?php
            endwhile;
        }
     ?>
  </div>
