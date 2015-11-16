<?php

$api_url = 'http://powerthemes.club/update/';
/************************************************/

/*******************Child Theme******************
//Use this section to provide updates for a child theme
//If using on child theme be sure to prefix all functions properly to avoid 
//function exists errors
if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('stylesheet'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = get_theme_data( get_stylesheet_directory() . '/style.css');
    $theme_version = $theme_data['Version'];
}    
$theme_base = get_option('stylesheet');
**************************************************/
global $theme_base;

/***********************Parent Theme**************/
if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('template'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = get_theme_data( TEMPLATEPATH . '/style.css');
    $theme_version = $theme_data['Version'];
}
$theme_base = get_option('template');
global $wp_version;
$wp_version = get_bloginfo( 'version' );
/**************************************************/


add_filter('pre_set_site_transient_update_themes', 'powerthemes_check_for_update');


function powerthemes_check_for_update( $checked_data ) {
	global $wp_version, $theme_version, $theme_base, $api_url;
	$request = array(
		'slug' => $theme_base,
		'version' => $theme_version 
	);
	// Start checking for an update
	$send_for_check = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => md5( get_bloginfo('url') ),
			'username' => get_option( $theme_base.'_envato_username' ),
			'api' => get_option( $theme_base.'_envato_api' ),
			'purchase_code' => get_option( $theme_base.'_envato_purchase_code' ),
			'url' => get_bloginfo('url')
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);
	$raw_response = wp_remote_post( $api_url, $send_for_check );
	if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ){
		$response = unserialize($raw_response['body']);
	}

	// Feed the update data into WP updater
	if ( !empty( $response ) ){
		$checked_data->response[$theme_base] = $response;
	}

	return $checked_data;
}

// Take over the Theme info screen on WP multisite
add_filter('themes_api', 'powerthemes_my_theme_api_call', 10, 3);

function powerthemes_my_theme_api_call( $def, $action, $args ) {
	global $wp_version, $theme_base, $api_url, $theme_version, $api_url;
	
	if ($args->slug != $theme_base)
		return false;
	
	// Get the current version

	$args->version = $theme_version;
	$request_string = array(
			'body' => array(
				'action' => $action, 
				'request' => serialize($args),
				'api-key' => md5(get_bloginfo('url'))
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
	$request = wp_remote_post( $api_url, $request_string );

	if (is_wp_error($request)) {
		$res = new WP_Error( 'themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message() );
	} else {
		$res = unserialize( $request['body'] );
		
		if ($res === false){
			$res = new WP_Error( 'themes_api_failed', __('An unknown error occurred'), $request['body'] );
		}
	}
	
	return $res;
}

if (is_admin()){
	$current = get_transient('update_themes');

	add_action( 'admin_menu', 'powerthemes_update_page' );
	/* add link to the sidebar */
	function powerthemes_update_page() {
		add_menu_page(__( 'PowerThemes Updates', 'couponxl' ), __( 'PowerThemes Updates', 'couponxl' ), 'manage_options', 'powerthemes-updates', 'powerthemes_update_settings', 'dashicons-update');
	}	

	/* Show welcome screen and upload form */
	function powerthemes_update_settings(){
		global $wp_version, $theme_version, $api_url, $theme_base;
		$message = '';
		if( isset( $_GET['deactivate'] ) ){		
			$request = array(
				'slug' => $theme_base,
				'version' => $theme_version 
			);

			$send_for_deactivation = array(
				'body' => array(
					'action' => 'theme_deactivate', 
					'request' => serialize($request),
					'api-key' => md5( get_bloginfo('url') ),
					'username' => get_option( $theme_base.'_envato_username' ),
					'api' => get_option( $theme_base.'_envato_api' ),
					'purchase_code' => get_option( $theme_base.'_envato_purchase_code' ),
					'url' => get_bloginfo('url')
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);
			$raw_response = wp_remote_post( $api_url, $send_for_deactivation );
			if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ){
				delete_option( $theme_base.'_envato_username' );
				delete_option( $theme_base.'_envato_api' );
				delete_option( $theme_base.'_envato_purchase_code' );
				$message = '<div id="message" class="updated notice"><p>Account <strong>disconnected</strong>.</p></div>';
			}
			else{
				$message = '<div id="message" class="error notice"><p>Account failed to <strong>disconnect</strong>.</p></div>';
			}
		}
		else if( isset( $_GET['save'] ) ){
			$envato_username = isset( $_POST['envato_username'] ) ? $_POST['envato_username'] : '';
			$envato_api = isset( $_POST['envato_api'] ) ? $_POST['envato_api']  : '';
			$purchase_code = isset( $_POST['purchase_code'] ) ? $_POST['purchase_code'] : '';

			$request = array(
				'slug' => $theme_base,
				'version' => $theme_version 
			);

			$check_credentials = array(
				'body' => array(
					'action' => 'check_credentials', 
					'request' => serialize($request),
					'api-key' => md5( get_bloginfo('url') ),
					'username' => $envato_username,
					'api' => $envato_api,
					'purchase_code' => $purchase_code,
					'url' => get_bloginfo('url')
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);
			$raw_response = wp_remote_post( $api_url, $check_credentials );
			if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ){
				$response = unserialize($raw_response['body']);
				if( $response['status'] == '1' ){
					update_option( $theme_base.'_envato_username', esc_sql( $_POST['envato_username'] ) );
					update_option( $theme_base.'_envato_api', esc_sql( $_POST['envato_api'] ) );
					update_option( $theme_base.'_envato_purchase_code', esc_sql( $_POST['purchase_code'] ) );

					$message = '<div id="message" class="updated notice"><p>Account <strong>connected</strong>.</p></div>';
				}
				else{
					$message = '<div id="message" class="error notice"><p>'.$response['message'].'</p></div>';
				}
			}
		}

		$envato_username = get_option( $theme_base.'_envato_username' );
		$envato_api = get_option( $theme_base.'_envato_api' );
		$purchase_code = get_option( $theme_base.'_envato_purchase_code' );

		echo '<div class="wrap">
        		<h2>'.__( 'Theme Updates' ).'</h2>
    			<div class="narrow">
    				<p>'.__( 'Input your data to receive information about theme updates', 'couponxl' ).'.</p>
				</div>';
		echo $message;
		if( empty( $envato_username ) && empty( $envato_api ) && empty( $purchase_code ) ){

			echo '<form method="post" action="'.esc_url( add_query_arg( array( 'save' => '1' ), "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) ).'">
						<table class="form-table">
							<tbody>
								<tr class="form-field form-required">
									<th scope="row"><label for="envato_username">'.__( 'Username', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
									<td><input name="envato_username" type="text" id="envato_username" value="'.esc_attr( $envato_username ).'" aria-required="true"></td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row"><label for="envato_api">'.__( 'API', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
									<td><input name="envato_api" type="text" id="envato_api" value="'.esc_attr( $envato_api ).'"></td>
								</tr>
								<tr class="form-field form-required">
									<th scope="row"><label for="purchase_code">'.__( 'Purchase Code', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
									<td><input name="purchase_code" type="text" id="purchase_code" value="'.esc_attr( $purchase_code ).'"></td>
								</tr>
							</tbody>
						</table>
						<p><input type="submit" value="'.esc_attr__( 'Connect', 'couponxl' ).'" class="button button-primary"></p>
					</form>';
		}
		else{

			echo '<table class="form-table">
					<tbody>
						<tr class="form-field form-required">
							<th scope="row"><label for="envato_username">'.__( 'Username', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
							<td>'.esc_attr( $envato_username ).'</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="envato_api">'.__( 'API', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
							<td>'.esc_attr( $envato_api ).'</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="purchase_code">'.__( 'Purchase Code', 'couponxl' ).' <span class="description">('.__( 'required', 'couponxl' ).')</span></label></th>
							<td>'.esc_attr( $purchase_code ).'</td>
						</tr>
					</tbody>
				</table>
				<p><a href="'.esc_url( add_query_arg( array( 'deactivate' => '1' ), "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) ).'" class="button button-primary">'.esc_attr__( 'Disconnect', 'couponxl' ).'</a></p>';
		}
		echo '</div>';
	}	
}
?>