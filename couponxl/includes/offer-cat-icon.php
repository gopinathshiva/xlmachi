<?php

/* Custom Meta For Taxonomies */

/* CUSTOMISATION DONE HERE /*
/* loading required script and styles for media uploader */

add_action('admin_init','load_my_custom_script');
function load_my_custom_script() {
	global $pagenow, $typenow;
	if (empty($typenow) && !empty($_GET['post'])) {
		$post = get_post($_GET['post']);
		$typenow = $post->post_type;
	}
	if (is_admin() && $typenow=='offer' && $pagenow=='edit-tags.php') {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

		wp_register_script('custom-script-cat-featured-img', esc_url( home_url('/') ).'wp-content/themes/couponxl-child/js/admin-custom-script.js','',null, true);
		wp_enqueue_script('custom-script-cat-featured-img');
	}
}

/* Adding New **/
/* icon meta */
function couponxl_category_icon_add() {
	echo '
	<div class="form-field">
		<label for="term_meta[category_icon]">'.__( 'Icon:', 'pippin' ).'</label>
		<select name="term_meta[category_icon]" id="term_meta[category_icon]"> 
			'.couponxl_icons_list( '' ).'
		</select>
		<p class="description">'.__( 'Select icon for the code category','pippin' ).'</p>
	</div>
	<div class="form-field">
		<label for="term_meta[category_featured_image]">'.__( 'Featured Image:', 'pippin' ).'</label>
		<input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="" />
		<input id="upload_image_button" type="button" value="Upload Image" />
		<p class="description">'.__( 'Select featured image for the code category','pippin' ).'</p>
	</div>
	';
}
add_action( 'offer_cat_add_form_fields', 'couponxl_category_icon_add', 10, 2 );

/* Editing */
function couponxl_category_icon_edit( $term ) {
	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
	
	$value = !empty( $term_meta['category_icon'] ) ? $term_meta['category_icon'] : '';
	$feature_category_image = !empty( $term_meta['category_featured_image'] ) ? $term_meta['category_featured_image'] : '';
	?>
	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="term_meta[category_icon]"><?php _e( 'Icon', 'couponxl' ); ?></label></th>
				<td>
					<select name="term_meta[category_icon]" id="term_meta[category_icon]"> 
						<?php echo couponxl_icons_list( $value ); ?>
					</select>
				<p class="description"><?php _e( 'Select icon for the code category', 'couponxl' ); ?></p></td>
			</tr>
		</tbody>
	</table>

	<table class="form-table">
		<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label for="term_meta[category_featured_image]"><?php _e( 'Featured Image', 'couponxl' ); ?></label></th>
				<td>
					<input id="category_featured_image" type="text" size="36" name="term_meta[category_featured_image]" value="<?php echo $feature_category_image; ?>" />
					<input id="upload_image_button" type="button" value="Upload Image" />
				<p class="description"><?php _e( 'Select featured image for the code category', 'couponxl' ); ?></p></td>
			</tr>
		</tbody>
	</table>
	<?php
}
add_action( 'offer_cat_edit_form_fields', 'couponxl_category_icon_edit', 10, 2 );

/* Save It */
function couponxl_category_icon_save( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_offer_cat', 'couponxl_category_icon_save', 10, 2 );  
add_action( 'create_offer_cat', 'couponxl_category_icon_save', 10, 2 );

/* Delete meta */
function couponxl_category_icon_delete( $term_id ) {
	delete_option( "taxonomy_$term_id" );
}  
add_action( 'delete_offer_cat', 'couponxl_category_icon_delete', 10, 2 );

/* Add icon column */
function couponxl_category_column( $columns ) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name', 'couponxl'),
		'description' => __('Description', 'couponxl'),
        'slug' => __( 'Slug', 'couponxl' ),
        'posts' => __( 'Codes', 'couponxl' ),
		'icon' => __( 'Icon', 'couponxl' )
        );
    return $new_columns;
}
add_filter("manage_edit-offer_cat_columns", 'couponxl_category_column'); 

function couponxl_populate_category_column( $out, $column_name, $label_id ){
    switch ( $column_name ) {
        case 'icon': 
			$term_meta = get_option( "taxonomy_$label_id" );
			$value = !empty( $term_meta['category_icon'] ) ? $term_meta['category_icon'] : '';

            $out .= '<div style="width: 20px; height: 20px;"><span class="fa fa-'.$value.'"></span></div>';
            break;
 
        default:
            break;
    }
    return $out; 
}

add_filter("manage_offer_cat_custom_column", 'couponxl_populate_category_column', 10, 3);
?>