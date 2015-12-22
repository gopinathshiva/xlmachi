var updateOfferCount;
jQuery(document).ready(function($){

	var isTouchDevice = (window.innerWidth <= 760 ) ? true:false;
	var navigationHeight = $('.navigation').height();

	//function to call for smooth scrolling on click of sidebar menu
	$(".xl-sidemenu a").click(function() {
		if ($(this).hasAttr("data-scroll-id")) {
			var offsetToAdd = (navigationHeight + 5);
			var id = $(this).attr('data-scroll-id');
		    $('html, body').animate({
		        scrollTop: ($("#xl-home-offer-"+id).offset().top - offsetToAdd)
		    }, 2000);
		}
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
			$(parentElement).empty();
			results = JSON.parse(results);
			var resultCounter = 0;			
			for(var i = 0 ;i < results.length; i++){
				if(results[i].offer_name.toLowerCase().indexOf(searchText.toLowerCase())==0){
					$(parentElement).append("<a href="+results[i].offer_slug+"><li class='xl-search-result-item'><span>" + results[i].offer_name + "</span></li></a>");
					resultCounter++;
					if(resultCounter>=6){
						break;
					}
				}
			}
			$(parentElement).slideDown('fast');
		}

	});

	function xl_getText(text){		
		if(text.indexOf('+')<0){
			return text.replace('-','+');
		}else{
			return text.replace('+','-');
		}
	}

	//on click of coupon-info / deal - info button
	$('.white-block-content a.read-info').on('click',function(){		
		var replaceText = xl_getText($(this).text());
		$(this).closest('ul').next().slideToggle( "slow" );
		$(this).text(replaceText);
	});	

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
	 	var option = $(this).attr('data-option');
	 	option = option.toLowerCase();
 		option = option.replace(' ','_');
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
		$('.xl-offer-type-filter-radio,.xl-offer-cat-filter-checkbox,.xl-offer-store-filter-checkbox').prop('disabled',true);
		$('.xl-offer-filter-not-found').hide();

		$('.xl-offer-item').removeClass('xl-filtered-type xl-filtered-cat xl-filtered-store');

		var scrollStart = $("body").offset().top + 150;
		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 550);
	 	
		var offerType = $('.xl-offer-type-filter input.xl-offer-type-filter-radio:checked').val();
		$('.xl-offer-item').hide();

		var offerCatCheckedCount = $('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length;
		var offerStoreCheckedCount = $('.xl-offer-store-filter input.xl-offer-store-filter-checkbox:checked').length;

		// if(!offerCatCheckedCount && !offerStoreCheckedCount){
		// 	$('.xl-offer-item').fadeIn("slow");			
		// }else{
			//offer type filter always checked
		
			if($('.xl-offer-store-filter').length){
									
					//offer type filter 
					if(offerType=="all"){
						$('.xl-offer-item').addClass("xl-filtered-type");
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
				       	}else{
				       		$('div[data-xltype='+offerType+'][data-xlcategory*='+categoryId+']').fadeIn("slow");
				       	}				
			     	});	
				}
			}	
		//}					

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
		var allCount = dealCount+couponCount;
		$('#xl-offer-type-all-count').text('('+allCount+')');
	}

	function updateOfferCategoryCount(){
		$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').each(function(){			
			var catId = $(this).val();
			var catCount = $('div[data-xlcategory*='+catId+']:visible').length;			
			if(!catCount){
				$('li.xl-cat-'+catId).addClass('xl-no-offer').hide();
			}else{
				//$('li.xl-cat-'+catId+' .count').text('('+catCount+')');	
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
		var dealCount = $('div[data-xltype=deal]:visible').length;
        $('#xl-offer-type-deal-count').text('('+dealCount+')');
        var couponCount = $('div[data-xltype=coupon]:visible').length;
        $('#xl-offer-type-coupon-count').text('('+couponCount+')');
        var allCount = dealCount+couponCount;
        $('#xl-offer-type-all-count').text('('+allCount+')');                                        

        if(window.isXlSearchPage){
    		//hiding stores with no offers                                    
	        $('.xl-offer-store-result li').each(function(k,v){
	            var input = $(this).find('input')[0];
	            var id = ($(input).val());
	            if(!($('div[data-xlstore='+id+']:visible').length)){
	                $(this).remove();
	            }
	        });
        }        
        else if(window.isXlStorePage){
        	//hiding categories with no offers                                    
	        $('.xl-offer-cat-result li').each(function(k,v){
	            var input = $(this).find('input')[0];
	            var id = ($(input).val());
	            if(!($('div[data-xlcategory='+id+']:visible').length)){
	                $(this).remove();
	            }
	        }); 
        }        
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

    //sidemenu script
    $('.xl-sidemenu li').hover(function () {
    	$(this).find('span').fadeIn('fast');
    },function(){
    	$(this).find('span').fadeOut('fast');
    });
    
    //fixed position widget scroll on offer and store page
    if(window.isXlSearchPage && !isTouchDevice){

    	var fixmeTop = $('.xl-search-page-widget').offset().top;
    	var offerTypeFilterHeight = $('.xl-offer-type-filter').height();

    	$(window).scroll(function() {                  // assign scroll event listener

		    var currentScroll = $(window).scrollTop(); // get current position
		    console.log('currentScroll='+currentScroll+' fixmeTop='+fixmeTop);

		    if (currentScroll >= fixmeTop) {           // apply position: fixed if you
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(navigationHeight+10)+'px'
		        });
		        $('.xl-offer-store-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(navigationHeight+offerTypeFilterHeight+25)+'px'
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

    }else if(window.isXlStorePage && !isTouchDevice){
    	
    	var fixmeTop = $('.xl-store-detail').offset().top;       // get initial position of the element
    	var offerTypeFilterHeight = $('.xl-offer-type-filter').height();

    	$(window).scroll(function() {                  // assign scroll event listener

		    var currentScroll = $(window).scrollTop(); // get current position
		    console.log('currentScroll='+currentScroll+' fixmeTop='+fixmeTop);

		    if (currentScroll >= fixmeTop) {           // apply position: fixed if you
		        $('.xl-offer-type-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(navigationHeight+10)+'px'
		        });
		        $('.xl-offer-cat-filter').css({                      // scroll to that element or below it
		            position: 'fixed',
		            top:(navigationHeight+offerTypeFilterHeight+25)+'px'
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

});