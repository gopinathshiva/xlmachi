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
                height: 90px;
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

        $transient_args = array(
            'post__in'=>$slider_posts,
            'post_type'=>'offer',
            'order' => 'post__in',
            'posts_per_page'=>4
        );

        $transient_namespace = xl_transient_namespace();
        $transient_key = $transient_namespace .md5( serialize($transient_args) );
        if ( false === ( $html = get_transient( $transient_key ) ) ) {
            query_posts($transient_args);
            if ( have_posts() ) {
                $html = '';
                while ( have_posts() ) : the_post();
                    $post_id = get_the_ID();
                    $img_url = get_the_post_thumbnail( $post_id, array(140,90) );
                    $title = get_the_title($post_id);
                    $url = get_post_meta( $post_id, 'coupon_link', true );
                    $html .= '<div data-offer-title="<?php the_title(); ?>" class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
                        <a title="'.$title.'" href="'.$url.'" target="_blank" rel="nofollow">
                            '.$img_url.'
                        </a>
                    </div>';
                endwhile;
                set_transient( $transient_key, $html, 1*DAY_IN_SECONDS );
                echo $html;
            }
        }else{
            echo $html;
        }
     ?>
  </div>
