var updateOfferCount;

jQuery(document).ready(function($){

	var isTouchDevice = (window.innerWidth <= 768 ) ? true:false;
	var stickyNavigationHeight = $('.navigation.sticky-nav').height();

	//function to call for smooth scrolling on click of sidebar menu
	$(".xl-sidemenu a").click(function() {
		if ($(this).hasAttr("data-scroll-id")) {
			var offsetToAdd = (stickyNavigationHeight + 5);
			var id = $(this).attr('data-scroll-id');

		    $('html, body').animate({
		        scrollTop: ($("#xl-home-offer-"+id).offset().top - offsetToAdd)
		    }, 350);
		}
	});

	//on click of left arrow in sidemenu
	$(".xl-sidemenu .xl-sidemenu-left").off('click').on('click',function(){
		$('.xl-sidemenu').addClass('push-left');
	});

	//onclick of right arrow in sidemenu
	$(".xl-sidemenu .xl-sidemenu-right").off('click').on('click',function(){
		$('.xl-sidemenu').removeClass('push-left');
	});

	//function to call when user focus/exit on search box
	$('.xl-search-input').off('focus blur').on('focus blur',function(e){
		if(e.type=='blur'){
			$('.xl-search-result,.xl-search-description').slideUp('fast');
			$('.navbar-nav>li.dropdown').show();
		}else{
			updateSearchUI(this);
			if(isTouchDevice){
				$('.navbar-nav>li.dropdown').hide();
			}
		}
	});

	function updateSearchUI(input){
		if(!$(input).val()){
			$('.xl-search-result').slideUp('fast');
			$('.xl-search-description').slideDown('fast');
			return false;
		}else{
			$('.xl-search-description').slideUp('fast');
			$('.xl-search-result').slideDown('fast');
			return true;
		}
	}

	//to block triggering blur event on clicking any of search results
	$('.xl-search-form-container ul').off('mousedown').on('mousedown',function(evt) {
		evt.preventDefault();
	});

	var keyupTimeout;
	$( document ).on( 'keyup', '.xl-search-input', function() {

		if(!updateSearchUI(this)){
			return false;
		}

		if(keyupTimeout){
			clearTimeout(keyupTimeout);
		}
		$('.xl-search-form-container .search-loader').show();
		keyupTimeout = setTimeout(function(){
			var searchText = $(this).val();
			var parentElement = $(this).next()[0];

			$('.xl-search-result').slideUp('fast');

			if(sessionStorage.getItem('xl_offer_result')){
				updateSearchResults(sessionStorage.getItem('xl_offer_result'),$(this).val(),parentElement);
				return;
			}

			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					action : 'search_offer'
				},
				success : function( response ) {
					sessionStorage.setItem('xl_offer_result',response);
					updateSearchResults(response,searchText,parentElement);
				}
			});
		}.bind(this),300);

		function updateSearchResults(results,searchText,parentElement){
			$('.xl-search-form-container .search-loader').hide();
			$(parentElement).empty();
			results = JSON.parse(results);
			var resultCounter = 0;
			for(var i = 0 ;i < results.length; i++){
				if(results[i].offer_name.toLowerCase().indexOf(searchText.toLowerCase())>=0){
					if(results[i] && results[i].offer_slug){
						var str = results[i].offer_slug;
						results[i].offer_slug = str.replace('/store','');
					}
					$(parentElement).append("<a href="+results[i].offer_slug+"><li class='xl-search-result-item'><span>" + results[i].offer_name + "</span></li></a>");
					resultCounter++;
					if(resultCounter>=8){
						break;
					}
				}
			}
			if(!resultCounter){
				$(parentElement).append("<a href='javascript:void(0)'><li class='xl-search-result-item'><span>No Results Found</span></li></a>");
			}
			$(parentElement).slideDown('fast');
		}

	});

	// function xl_getText(text){
	// 	if(text.indexOf('+')<0){
	// 		return text.replace('-','+');
	// 	}else{
	// 		return text.replace('+','-');
	// 	}
	// }

	// //on click of coupon-info / deal - info button
	// $('.white-block-content a.read-info').on('click',function(){
	// 	var replaceText = xl_getText($(this).text());
	// 	$(this).closest('ul').next().slideToggle( "slow" );
	// 	$(this).text(replaceText);
	// });

	//on click of comment show/hide block
	$('.xl-comment-show-block h2').on('click',function(){
		var replaceText = xl_getText($(this).text());
		$(this).closest('.xl-comment-show-block').next().slideToggle( "slow" );
		$(this).text(replaceText);
	});

	function updateFilterTextContainer(){
		if($('#xl_filter_text_items li').length){
			$('#xl_filter_text_container').fadeIn('fast');
		}else{
			$('#xl_filter_text_container').fadeOut('fast');
		}
	}

	//on click of checkbox to filter offer category
	$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').off('click').on('click',function(event){
	 	xl_filterOffers();
	 	var option = $(this).attr('data-xlcategory');
	 	if($(this).prop('checked')){
	 		var id="xl_filter_text_"+option;
	 		var element = '<li class="xl_filter_text_item" id='+id+'>'+$(this).attr('data-option')+', </li>'
	 		$('#xl_filter_text_items').append(element);
	 	}else{
	 		$('#xl_filter_text_items #xl_filter_text_'+option)[0].remove();
	 	}
	 	updateFilterTextContainer();
	 	event.stopImmediatePropagation();
	 	//updateOfferTypeCount();
	});

	//on click of radio button to filter offer type
	$('.xl-offer-type-filter input.xl-offer-type-filter-radio').off('change').on('change',function(event){
		xl_filterOffers();
		event.stopImmediatePropagation();
		//updateOfferCategoryCount();
	});

	//on click of checkbox to filter offer store
	$('.xl-offer-store-filter input.xl-offer-store-filter-checkbox').off('click').on('click',function(event){
		xl_filterOffers();
		event.stopImmediatePropagation();
		//updateOfferTypeCount();
		//updateOfferCategoryCount();
	});

	function xl_filterOffers(){

		var scrollStart;
		if($('.single-store').length){
			scrollStart = $("body").offset().top + 330;
		}else{
			scrollStart = $("body").offset().top + 150;
		}

		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 350);

		$('.xl-offer-type-filter-radio,.xl-offer-cat-filter-checkbox,.xl-offer-store-filter-checkbox').prop('disabled',true);
		$('.xl-offer-filter-not-found').hide();

		$('.xl-offer-item').removeClass('xl-filtered-type xl-filtered-cat xl-filtered-store');

		var offerType = $('.xl-offer-type-filter input.xl-offer-type-filter-radio:checked').val();

		var offerCatCheckedCount = $('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length;
		var offerStoreCheckedCount = $('.xl-offer-store-filter input.xl-offer-store-filter-checkbox:checked').length;

		$('.xl-offer-item').hide();

		if($('.xl-offer-store-filter').length){

			//offer type filter
			if(offerType=="all"){
				$('.xl-offer-item').addClass("xl-filtered-type");
			}else if(offerType=="cashback"){
				$('div[data-xltag='+offerType+']').addClass("xl-filtered-type");
			}else{
				$('div[data-xltype='+offerType+']').addClass("xl-filtered-type");
			}

			//offer category filter
			if(offerCatCheckedCount){
				$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').each(function() {
			       	var categoryId = $(this).val();
			       	$('.xl-offer-item.xl-filtered-type[data-xlcategory*='+categoryId+']').addClass("xl-filtered-cat");
		     	});
			}else{
				$('.xl-offer-item.xl-filtered-type').addClass("xl-filtered-cat");
			}

			//offer store filter
			if(offerStoreCheckedCount){
				$('.xl-offer-store-filter input.xl-offer-store-filter-checkbox:checked').each(function(){
					$('.xl-offer-item.xl-filtered-type.xl-filtered-cat[data-xlstore='+$(this).val()+']').addClass('xl-filtered-store');
				});
			}else{
				$('.xl-offer-item.xl-filtered-type.xl-filtered-cat').addClass('xl-filtered-store');
			}

			$('.xl-offer-item.xl-filtered-type.xl-filtered-cat.xl-filtered-store').fadeIn('slow');

		}else{
			//offer category filter not checked
			if(!offerCatCheckedCount){
				if(offerType=="all"){
					$('.xl-offer-item').fadeIn("slow");
				}else if(offerType=="cashback"){
					$('div[data-xltag='+offerType+']').fadeIn('slow');
				}else{
					$('div[data-xltype='+offerType+']').fadeIn('slow');
				}
			}
			//offer category filter checked
			else{
				$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').each(function() {
			       	var categoryId = $(this).val();
			       	if(offerType=='all'){
			       		$('div[data-xlcategory*='+categoryId+']').fadeIn("slow");
			       	}else if(offerType=="cashback"){
						$('div[data-xltag='+offerType+'][data-xlcategory*='+categoryId+']').fadeIn('slow');
					}else{
			       		$('div[data-xltype='+offerType+'][data-xlcategory*='+categoryId+']').fadeIn("slow");
			       	}
		     	});
			}
		}

		if(!$('.xl-offer-item:visible').length){
			$('.xl-offer-filter-not-found').fadeIn('slow');
		}

		setTimeout(function(){
			$('.xl-offer-type-filter-radio,.xl-offer-cat-filter-checkbox,.xl-offer-store-filter-checkbox').prop('disabled',false);
		},1000);
	}

	//input search text to filter categories
	$('.xl-offer-cat-filter .xl-offer-cat-search').off('keyup').on('keyup',function(e){
		var value = $(this).val().toLowerCase();
		if(!value){
			$('.xl-offer-cat-result li:not(.xl-no-offer)').show();
			return;
		}
		$('.xl-offer-cat-result li:not(.xl-no-offer)').each(function(){
			if($(this).text().toLowerCase().indexOf(value)>=0){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	});

	//input search text to filter stores
	$('.xl-offer-store-filter .xl-offer-store-search').off('keyup').on('keyup',function(e){
		var value = $(this).val().toLowerCase();
		if(!value){
			$('.xl-offer-store-result li:not(.xl-no-offer)').show();
			return;
		}
		$('.xl-offer-store-result li:not(.xl-no-offer)').each(function(){
			if($(this).text().toLowerCase().indexOf(value)>=0){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	});

	updateOfferCount = function(){

		updateOfferTypeCount();
		updateOfferCategoryCount();
		updateOfferStoreCount();

	}

	function updateOfferTypeCount(){
		var dealCount = $('div[data-xltype=deal]:visible').length;
		$('#xl-offer-type-deal-count').text('('+dealCount+')');
		var couponCount = $('div[data-xltype=coupon]:visible').length;
		$('#xl-offer-type-coupon-count').text('('+couponCount+')');
		var cashbackCount = $('div[data-xltag=cashback]:visible').length;
		$('#xl-offer-type-cashback-count').text('('+cashbackCount+')');

		var allCount = dealCount+couponCount;
		$('#xl-offer-type-all-count').text('('+allCount+')');
	}

	function updateOfferCategoryCount(){
		$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').each(function(){
			var catId = $(this).val();
			var catCount = $('div[data-xlcategory*='+catId+']:visible').length;
			if(!catCount){
				$('li.xl-cat-'+catId).addClass('xl-no-offer').hide();
			}
		});
	}

	function updateOfferStoreCount(){
		if($('.xl-offer-store-filter').length){
			$('.xl-offer-store-filter input.xl-offer-store-filter-checkbox').each(function(){
				var storeId = $(this).val();
				var storeCount = $('div[data-xlstore*='+storeId+']').length;
				if(!storeCount){
					$('li.xl-store-'+storeId).addClass('xl-no-offer').hide();
				}
			});
		}
	}

	if(window.isXlSearchPage || window.isXlStorePage){
		updateOfferTypeCount();
	}

	//hiding stores with no offers
	if($('.xl-offer-store-result li').length){
		$('.xl-offer-store-result li').each(function(k,v){
            var input = $(this).find('input')[0];
            var id = ($(input).val());
            if(!($('div[data-xlstore*='+id+']:visible').length)){
                $(this).remove();
            }
        });
	}

	//hiding categories with no offers
	if($('.xl-offer-cat-result li').length){
		$('.xl-offer-cat-result li').each(function(k,v){
            var input = $(this).find('input')[0];
            var id = ($(input).val());
            if(!($('div[data-xlcategory*='+id+']:visible').length)){
                $(this).remove();
            }
        });
	}

	//scroll to top script
	$(window).scroll(function () {
        if ($(this).scrollTop() > 600) {
            $('.xl-scrollup').fadeIn();
        } else {
            $('.xl-scrollup').fadeOut();
        }
    });

    $('.xl-scrollup').click(function () {

        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

	$('.offer-box').hover(function(){
		$(this).find('.visit-store').addClass('active');
		$(this).find('.show-offer-detail').addClass('active');
	},function(){
		$(this).find('.visit-store').removeClass('active');
		$(this).find('.show-offer-detail').removeClass('active');
	});

    //sidemenu script
    $('.xl-sidemenu li').hover(function () {
    	$(this).find('span').fadeIn('fast');
    },function(){
    	$(this).find('span').fadeOut('fast');
    });

	//promocodes page
	if($('.page-template-page_tpl_promocode').length){

		if(isTouchDevice){
			$("html, body").animate({
	            scrollTop:30
	        }, 600);
		}else{
			$("html, body").animate({
	            scrollTop: 130
	        }, 600);
		}
	}


    //fixed position widget scroll on offer and store page
    if(window.isXlSearchPage && !isTouchDevice){

    	var fixmeTop = $('.xl-search-page-widget').offset().top;
    	var offerTypeFilterHeight = $('.xl-offer-type-filter').height();

    	$(window).scroll(function() {                  // assign scroll event listener

		    var currentScroll = $(window).scrollTop(); // get current position
		    if (currentScroll >= fixmeTop) {           // apply position: fixed if you
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(stickyNavigationHeight+5)+'px',
		            'z-index':'initial'
		        });
		        $('.xl-offer-store-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(stickyNavigationHeight+offerTypeFilterHeight+25)+'px',
		            'z-index':'initial'
		        });
		    } else {                                   // apply position: static
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'static'
		        });
		        $('.xl-offer-store-filter').css({                      // scroll to that element or below it
		            position: 'static'
		        });
		    }

		});

    }else if(window.isXlStorePage && !isTouchDevice){

    	var fixmeTop = $('.xl-store-detail').offset().top;       // get initial position of the element
    	var offerTypeFilterHeight = $('.xl-offer-type-filter').height();

    	$(window).scroll(function() {                  // assign scroll event listener

		    var currentScroll = $(window).scrollTop(); // get current position
		    if (currentScroll >= fixmeTop) {           // apply position: fixed if you
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(stickyNavigationHeight+5)+'px',
		            'z-index':'initial'
		        });
		        $('.xl-offer-cat-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(stickyNavigationHeight+offerTypeFilterHeight+25)+'px',
		            'z-index':'initial'
		        });
		    } else {                                   // apply position: static
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'static'
		        });
		        $('.xl-offer-cat-filter').css({                      // scroll to that element or below it
		            position: 'static'
		        });
		    }

		});
    }

    //added for carousel effect in featured store page in home page

    if($('.featured-stores.carousel ul').length){
    	var firstval = 0,isCarouselPaused = false, carouselTimeout;

		function Carousel() {
		    firstval += 2;
		    var parent = $('.featured-stores.carousel ul.stores-image-container')[0];
		    parent.style.left = "-" + firstval + "px";
		    if (!(firstval % 130)) {
		        carouselTimeout = setTimeout(Carousel, 3000);
		        firstval = 0;
		        var firstChild = parent.firstElementChild;
		        parent.appendChild(firstChild);
		        parent.style.left= 0;
		        return;
		    }
		    if(!isCarouselPaused){
		    	carouselTimeout = setTimeout(Carousel, 20);
		    }
		}
		Carousel();

		$('.featured-stores.carousel').hover(function(){
			isCarouselPaused = true;
		},function(){
			clearTimeout(carouselTimeout);
			isCarouselPaused = false;
			Carousel();
		});
    }

	if($('.single-store .featured-stores-tabs').length){
		$('.single-store .featured-stores-tabs').off('click').on('click','li',function () {
			var totalStores = $('.single-store .stores-image-container li');
			$(totalStores).fadeOut();
			var requiredStoreIds = $(this).attr('data-store-ids');
			requiredStoreIds = requiredStoreIds.split(',');
			for(var i=0;i<totalStores.length;i++){
				if(requiredStoreIds.indexOf(totalStores[i].id)>=0){
					requiredStoreIds.splice(requiredStoreIds.indexOf(totalStores[i].id),1);
					$(totalStores[i]).fadeIn();
				}
				if(!requiredStoreIds.length){
					break;
				}
			}
		});
	}

	//auto scroll on loading store page
	if($('.single-store').length && window.innerWidth >= 768){

		$('html, body').animate({
		    scrollTop: $("body").offset().top + 145
	 	}, 350);
	}

	//promocodes select button click
	if($('#promocodes-filter-store').length){
		$('#promocodes-filter-store').on('change',function () {
			if($(this).val()==-1){
				$('.promocode-table-container table tr:not(".promocode-tbl-header")').show();
			}else{
				onPromocodesStoreChange($(this).val());
			}
		});

		$('#promocodes-filter-category').on('change',function () {
			var id = $(this).val();
			$('.promocode-table-container table tr:not(".promocode-tbl-header")').hide();
			$('.promocode-table-container table tr:not(".promocode-tbl-header")').each(function(k,v){
			   var cat = $(v).attr('data-offer-cat');
			       if(cat.indexOf(id)>=0){
			         $(v).show();
			       }
			});
		});

		initPromocodePage();

		function initPromocodePage(){
			$('#promocodes-filter-store option#235').prop('selected',true);
			onPromocodesStoreChange('235');
		}

		function onPromocodesStoreChange(value){
			var cat_filters = [];
			$('.page-template-page_tpl_promocode .promocode-no-offers').hide();
			$('.page-template-page_tpl_promocode .promocode-loading').show();
			$('.promocode-table-container table tr:not(".promocode-tbl-header")').remove();
			$.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					action : 'promocodes_search',
					store_id: value
				},
				success : function( offers ) {
					$('.page-template-page_tpl_promocode .promocode-loading').hide();
					if(!offers){
						return;
					}
					offers = JSON.parse(offers);
					if(!offers.length){
						$('.page-template-page_tpl_promocode .promocode-no-offers').show();
						return;
					}
					$('.page-template-page_tpl_promocode .promocode-no-offers').hide();
					var html = '';
					for(var offer in offers){
						var cat = offers[offer].cat;
						if(cat.length){
							for(var i in cat){
								if(cat_filters.indexOf(cat[i])<0){
									cat_filters.push(cat[i]);
								}
							}
						}
						cat = cat.join(',');
						html += '<tr class="'+offers[offer].type+'" data-offer-cat="'+cat+'">';
						html += '<td>'+offers[offer].title+'</td>';
						if(!offers[offer].code){
							offers[offer].code = 'Deal';
						}
						html += '<td><span class="promo-code">'+offers[offer].code+'</span></td>';
						html += '<td><a target="_blank" class="offer-btn" href="'+offers[offer].url+'">Click Here</a></td>';
						html += '<td>Active</td>';
						html += '</tr>';
					}
					console.log(cat_filters);
					$('.promocode-table-container table').append(html);
					$('#promocodes-filter-category option').each(function (k,v) {
						if(cat_filters.indexOf(+v.id)<0 && v.id!="-1"){
							$(v).hide();
						}else{
							$(v).show();
						}
					});
					$('#promocodes-filter-category option').first().prop('selected',true);
				}
			});
		}
	}

	//offer menu to show only on devices less than 768px
	$('.filter-offer-menu').off('click').on('click',function () {
		if($('.filter-offer-container').hasClass('active')){
			//close filter
			$('.filter-offer-menu a').text('Offer Filter');
			$('.filter-offer-menu a').append('<i class="fa fa-chevron-down coupon-type"></i>');
			$('.filter-offer-container').removeClass('active');
			$('#xl-store-start').removeClass('opacity-zero');
			$('html, body').animate({
		        scrollTop: 0
		    }, 350);
		}else{
			//open filter
			$('.filter-offer-menu a').text('Click here to close filter');
			$('.filter-offer-menu a').append('<i class="fa fa-chevron-up coupon-type"></i>');
			$('.filter-offer-container').addClass('active');
			$('#xl-store-start').addClass('opacity-zero');
		}
	});

});
