jQuery(document).ready(function($){
	$('#upload_image_button').click(function() {
	    formfield = $('#term_meta[category_featured_image]').attr('name');
	    tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
	    return false;
	});

	window.send_to_editor = function(html) {
	    imgurl = $('img',html).attr('src');
	    $('#category_featured_image').val(imgurl);
	    tb_remove();
	}
});
