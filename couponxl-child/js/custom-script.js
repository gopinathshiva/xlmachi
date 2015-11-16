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

	$('.xl-store-cat-filter input.xl-store-cat-filter-radio').on('click',function(event){		
		debugger
		$('.single-store .masonry .masonry-item').hide();
		var categoryId = $(this).attr('data-xlcategory');
		$('.single-store .masonry div[data-xlcategory*='+categoryId+']').fadeIn("slow");
	});

});