<?php
//print_r($top_categories);
?><div class="row xl-coupon-listing">
    <div class="white-block">
        <div class="white-block-title no-border">
            <?php if( !empty( $icon ) ): ?>
                <i class="fa fa-<?php echo esc_attr( $icon ); ?>"></i>
            <?php endif; ?>
            <h2><?php echo $title ?></h2>
            <?php                 
            if( empty( $small_title_link ) ){
                $small_title_link = esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_type' => 'deal' ), array() ) );
            }else{
                $small_title_link = esc_url( home_url('/') ).$small_title_link;
            }                
            ?>        
            <a href="<?php echo $small_title_link ?>">
                <?php echo $small_title; ?>
                <i class="fa fa-arrow-circle-o-right"></i>
            </a>
        </div>
    </div>    
    <div class="row">      
        <div class="categories category-boxes">
              <?php foreach ($top_categories as $category) { ?>
                <div class="<?php echo $categories_per_row == 4?'col-sm-3':'col-sm-4'; ?>">
                    <a href="<?php echo esc_url( couponxl_append_query_string( couponxl_get_permalink_by_tpl( 'page-tpl_search_page' ), array( 'offer_cat' => $category ), array() ) ); ?>">
                      <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/8a774c1b171d36a633ab34da6411711b/cover-570x550.jpg?975324})">
                          <div class="content">
                              <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/8a774c1b171d36a633ab34da6411711b/icon-200x200.jpg?647287">
                              <?php 
                                  $category = explode( "-", $category );
                                  $category = implode(" ",$category);                                
                               ?>
                              <p class="pop-cat-title"><?php echo $category; ?></p>
                          </div>
                      </div>
                  </a>
                </div>              
              <?php } ?>
              <!-- <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/mobiles-and-tablets?active_tab=online&amp;ref=home_tc_2_4">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/6739ff00f8be5b1f4492e3e8a7006156/cover-570x550.jpg?19177})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/6739ff00f8be5b1f4492e3e8a7006156/icon-200x200.jpg?539950">
                            <p class="pop-cat-title">Mobiles &amp; Tablets</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/fashion?active_tab=online&amp;ref=home_tc_3_1">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/fd002aa03f01a72f938ddcb5ad9fcea5/cover-570x550.jpg?748246})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/fd002aa03f01a72f938ddcb5ad9fcea5/icon-200x200.jpg?963934">
                            <p class="pop-cat-title">Fashion</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/food-and-dining?active_tab=online&amp;ref=home_tc_4_3">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/e63dffce67a8e8cfc20789ca11f597ab/cover-570x550.jpg?762801})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/e63dffce67a8e8cfc20789ca11f597ab/icon-200x200.jpg?247486">
                            <p class="pop-cat-title">Food &amp; Dining</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/computers-laptops-and-gaming?active_tab=online&amp;ref=home_tc_5_5">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/8c0ff3d08b067cc01b06611c036b0c69/cover-570x550.jpg?589278})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/8c0ff3d08b067cc01b06611c036b0c69/icon-200x200.jpg?867341">
                            <p class="pop-cat-title">Computers, Laptops &amp; Gaming</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/home-furnishing-and-decor?active_tab=online&amp;ref=home_tc_6_9">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/09e26611e04a43b2e2561615496895d4/cover-570x550.jpg?728106})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/09e26611e04a43b2e2561615496895d4/icon-200x200.jpg?261697">
                            <p class="pop-cat-title">Home Furnishing &amp; Decor</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/travel?active_tab=online&amp;ref=home_tc_7_2">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/2401a77287b1e93ec44f4ff5f93aba00/cover-570x550.jpg?198893})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/2401a77287b1e93ec44f4ff5f93aba00/icon-200x200.jpg?220165">
                            <p class="pop-cat-title">Travel</p>
                        </div>
                    </div>
                </a>
              </div>
              <div class="col-sm-3">
                  <a href="http://www.coupondunia.in/category/beauty-and-health?active_tab=online&amp;ref=home_tc_8_13">
                    <div class="category-box category-bg-default" style="background-image: url(http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/fd8e622434e9041a4bdee2344ef1a9dc/cover-570x550.jpg?374394})">
                        <div class="content">
                            <img class="cat-icon" src="http://cdn01-s3.coupondunia.in/sitespecific/in/coupon-categories/fd8e622434e9041a4bdee2344ef1a9dc/icon-200x200.jpg?666423">
                            <p class="pop-cat-title">Beauty &amp; Health</p>
                        </div>
                    </div>
                </a>
              </div> -->
        </div>
    </div>
</div>