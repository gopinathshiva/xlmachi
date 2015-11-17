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

	//on click of checkbox in single-store page
	$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox').off('click').on('click',function(event){
		var scrollStart = $("body").offset().top + 150;
		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 600);			
	 	xl_filterOffers();	
		// $('.xl-offer-item').hide();
		// if(!$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length){
		// 	$('.xl-offer-item').fadeIn("slow");
		// }else{
		// 	$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').each(function() {	       	
		//        	var categoryId = $(this).val();
		// 		$('div[data-xlcategory*='+categoryId+']').fadeIn("slow");
	 //     	});	
		// }			
	});

	$('.xl-offer-type-filter input.xl-offer-type-filter-radio').off('change').on('change',function(event){			
		xl_filterOffers();
	});

	function xl_filterOffers(){
		var offerType = $('.xl-offer-type-filter input.xl-offer-type-filter-radio:checked').val();
		$('.xl-offer-item').hide();
		if(!$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').length){
			if(offerType=="all"){
				$('.xl-offer-item').fadeIn("slow");
			}else{
				$('div[data-xltype='+offerType+']').fadeIn('slow');
			}
		}else{
			$('.xl-offer-cat-filter input.xl-offer-cat-filter-checkbox:checked').each(function() {	       	
		       	var categoryId = $(this).val();
		       	if(offerType=='all'){
		       		$('div[data-xlcategory*='+categoryId+']').fadeIn("slow");	
		       	}else{
		       		$('div[data-xltype='+offerType+'][data-xlcategory*='+categoryId+']').fadeIn("slow");
		       	}				
	     	});	
		}

		if(!$('.xl-offer-item:visible').length){
			$('.xl-offer-cat-filter-not-found').show();
		}else{
			$('.xl-offer-cat-filter-not-found').hide();
		}
	}

	//input search text to filter categories in single store page
	$('.xl-offer-cat-filter .xl-offer-cat-search').off('keyup').on('keyup',function(e){
		var value = $(this).val().toLowerCase();
		if(!value){
			$('.xl-offer-cat-result li').show();
			return;
		}		
		$('.xl-offer-cat-result li').each(function(){
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
			debugger
			if(!catCount){
				$('li.xl-cat-'+catId).hide();
			}else{
				$('li.xl-cat-'+catId+' .count').text('('+catCount+')');	
			}			
		});
	}

});