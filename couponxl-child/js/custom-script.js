var updateOfferCount;
jQuery(document).ready(function($){

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

	//on click of checkbox in to filter offer category
	$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').off('click').on('click',function(event){
		var scrollStart = $("body").offset().top + 150;
		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 600);			
	 	xl_filterOffers();				
	});

	//on click of radio button to filter offer type
	$('.xl-offer-type-filter input.xl-offer-type-filter-radio').off('change').on('change',function(event){			
		xl_filterOffers();
	});

	//on click of radio button to filter offer type
	$('.xl-offer-store-filter input.xl-offer-store-filter-checkbox').off('click').on('click',function(event){					
		xl_filterOffers();		
	});

	function xl_filterOffers(){
		$('.xl-offer-filter-not-found').hide();

		var scrollStart = $("body").offset().top + 150;
		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 600);
	 	
		var offerType = $('.xl-offer-type-filter input.xl-offer-type-filter-radio:checked').val();
		$('.xl-offer-item').hide();

		//offer type filter always checked
		
		if($('.xl-offer-store-filter').length){
					
				$('.xl-offer-item').removeClass('xl-filtered-type xl-filtered-cat xl-filtered-store');

				//offer type filter 
				if(offerType=="all"){
					$('.xl-offer-item').addClass("xl-filtered-type");
				}else{
					$('div[data-xltype='+offerType+']').addClass("xl-filtered-type");
				}

				//offer category filter 	
				if($('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length){
					$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').each(function() {	       	
				       	var categoryId = $(this).val();
				       	$('.xl-offer-item.xl-filtered-type[data-xlcategory*='+categoryId+']').addClass("xl-filtered-cat");			       					
			     	});
				}else{
					$('.xl-offer-item.xl-filtered-type').addClass("xl-filtered-cat");
				}	

				//offer store filter 	
				if($('.xl-offer-store-filter input.xl-offer-store-filter-checkbox:checked').length){
					$('.xl-offer-store-filter input.xl-offer-store-filter-checkbox:checked').each(function(){
						$('.xl-offer-item.xl-filtered-type.xl-filtered-cat[data-xlstore='+$(this).val()+']').addClass('xl-filtered-store');
					});	
				}else{
					$('.xl-offer-item.xl-filtered-type.xl-filtered-cat').addClass('xl-filtered-store');
				}

				$('.xl-offer-item.xl-filtered-type.xl-filtered-cat.xl-filtered-store').fadeIn('slow');
		     	
		}else{
			//offer category filter not checked
			if(!$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length){
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

		if(!$('.xl-offer-item:visible').length){
			$('.xl-offer-filter-not-found').fadeIn('slow');
		}
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
		var dealCount = $('div[data-xltype=deal]').length;
		$('#xl-offer-type-deal-count').text('('+dealCount+')');
		var couponCount = $('div[data-xltype=coupon]').length;
		$('#xl-offer-type-coupon-count').text('('+couponCount+')');
		var allCount = dealCount+couponCount;
		$('#xl-offer-type-all-count').text('('+allCount+')');

		$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').each(function(){			
			var catId = $(this).val();
			var catCount = $('div[data-xlcategory*='+catId+']').length;			
			if(!catCount){
				$('li.xl-cat-'+catId).addClass('xl-no-offer').hide();
			}else{
				$('li.xl-cat-'+catId+' .count').text('('+catCount+')');	
			}			
		});

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

});