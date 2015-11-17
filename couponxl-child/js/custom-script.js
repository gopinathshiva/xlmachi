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
	$('.xl-store-cat-filter input.xl-store-cat-filter-checkbox').off('click').on('click',function(event){
		var scrollStart = $("#xl-store-start").offset().top - 100;
		$('html, body').animate({
		    scrollTop: scrollStart
	 	}, 600);				
		$('.single-store .store-item').hide();
		if(!$('.xl-store-cat-filter input.xl-store-cat-filter-checkbox:checked').length){
			$('.single-store .store-item').fadeIn("slow");
		}else{
			$('.xl-store-cat-filter input.xl-store-cat-filter-checkbox:checked').each(function() {	       	
		       	var categoryId = $(this).val();
				$('.single-store div[data-xlcategory*='+categoryId+']').fadeIn("slow");
	     	});	
		}			
	});

	//input search text to filter categories in single store page
	$('.xl-store-cat-filter .xl-store-cat-search').off('keyup').on('keyup',function(e){
		var value = $(this).val().toLowerCase();
		if(!value){
			$('.xl-store-cat-result li').show();
			return;
		}		
		$('.xl-store-cat-result li').each(function(){
			if($(this).text().toLowerCase().indexOf(value)>=0){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
	});

});