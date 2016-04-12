<?php
 ?>
 <div class="row">
     <style>
        .offer-slider-post{
            height: 110px;
            background-repeat: no-repeat;
            background-size: contain;
            padding: 10px;
        }
        .offer-slider-post img{
            width: 100%;
            height: 100%;
            /*box-shadow: 0px 0px 10px -2px black;*/
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
            'order' => 'post__in'
        ));
        if ( have_posts() ) {
            while ( have_posts() ) : the_post();
                // $post_id = get_the_ID();
                // $permalink = get_post_meta($post_id,'coupon_link',true);
                ?>
                <div class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
                    <a class="sp_offer cpn_click_redir atf-ad-small " data-link="http://www.grabon.in/load/coupon/?go=23661" href="?go=23661" target="_blank" rel="nofollow">
                        <img src="http://cdn.lmitassets.com/gograbon/images/coupon/special/spcial-1460251319791-thumb.png">
                    </a>
                </div>
                <?php
            endwhile;
        }
     ?>

     <!-- <div class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
          <a class="sp_offer cpn_click_redir atf-ad-small " data-link="http://www.grabon.in/load/coupon/?go=15355" href="?go=15355" target="_blank" rel="nofollow">
              <img src="http://cdn.lmitassets.com/gograbon/images/coupon/special/spcial-1460375368027-thumb.png">
          </a>
      </div>
      <div class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
          <a class="sp_offer cpn_click_redir atf-ad-small no-margin" data-link="http://www.grabon.in/load/coupon/?go=15084" href="?go=15084" target="_blank" rel="nofollow">
              <img src="http://cdn.lmitassets.com/gograbon/images/coupon/special/spcial-1459749231058-thumb.png">
          </a>
      </div>
      <div class="col-md-6 col-sm-3 col-xs-6 offer-slider-post">
          <a class="sp_offer cpn_click_redir atf-ad-small no-margin" data-link="http://www.grabon.in/load/coupon/?go=15084" href="?go=15084" target="_blank" rel="nofollow">
              <img src="http://cdn.lmitassets.com/gograbon/images/coupon/special/spcial-1459749231058-thumb.png">
          </a>
      </div> -->
  </div>
