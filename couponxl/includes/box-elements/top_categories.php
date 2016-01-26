<div class="row xl-coupon-listing">
    <div class="white-block" >
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
    <!-- <div class="row">      
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
        </div>
    </div> -->
    <div class="row" >
      <div class="category_slider">
        <ul>
          <li ><a href="http://couponmachi.com/recharge-offers"  ><i class="fa fa-flash icon-margin"></i><h6 class="pop-cat-title">Recharge</h6></a></li>
          <li ><a href="http://couponmachi.com/travel-offers"  ><i class="fa fa-bus icon-margin"></i><h6 class="pop-cat-title">Travel</h6></a></li>
          <li ><a href="http://couponmachi.com/food-dining-offers"  ><i class="fa fa-cutlery icon-margin"></i><h6 class="pop-cat-title">Food</h6></a></li>
          <li ><a href="http://couponmachi.com/groceries-offers"  ><i class="fa fa-shopping-cart icon-margin"></i><h6 class="pop-cat-title">Groceries</h6></a></li>
          <li ><a href="http://couponmachi.com/mobiles-tablets-offers"  ><i class="fa fa-mobile icon-margin"></i><h6 class="pop-cat-title">Mobile/Tablet</h6></a></li>
          <!-- <li ><a   ><i class="fa fa-mobile icon-margin"></i><h6 class="pop-cat-title">Mobile/Tablet</h6></a></li> -->
          <li ><a href="http://couponmachi.com/fashion-offers"  ><i class="fa fa-female icon-margin"></i><h6 class="pop-cat-title">Fashion</h6></a></li>
          <li ><a href="http://couponmachi.com/search/offer_cat/hotel/"  ><i class="fa fa-bed icon-margin"></i><h6 class="pop-cat-title">Hotel</h6></a></li>
        </ul>
    </div>
</div>
</div>