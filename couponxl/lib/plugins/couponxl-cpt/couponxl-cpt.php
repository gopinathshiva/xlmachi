<?php
/*
Plugin Name: Coupon XL Custom Post Types
Plugin URI: http://demo.powerthemes.club/themes/couponxl/
Description: Coupon XL custom post types and taxonomies
Version: 1.0
Author: pebas
Author URI: http://themeforest.net/user/pebas/
License: GNU General Public License version 3.0
*/

if( !function_exists( 'couponxl_post_types_and_taxonomies' ) ){
	function couponxl_post_types_and_taxonomies(){
		$offer_args = array(
			'labels' => array(
				'name' => __( 'Offers', 'couponxl' ),
				'singular_name' => __( 'Offer', 'couponxl' )
			),
			'public' => true,
			'menu_icon' => 'dashicons-megaphone',
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'author',
				'excerpt',
				'comments'
			)
		);
		if( class_exists('ReduxFramework') && function_exists('couponxl_get_option') ){
			$trans_offer = couponxl_get_option( 'trans_offer' );
			if( !empty( $trans_offer ) ){
				$offer_args['rewrite'] = array( 'slug' => $trans_offer );
			}
		}
		register_post_type( 'offer', $offer_args );	
		
		register_taxonomy( 'location', array( 'offer' ), array(
			'label' => __( 'Location', 'couponxl' ),
			'hierarchical' => true,
			'labels' => array(
				'name' 							=> __( 'Location', 'couponxl' ),
				'singular_name' 				=> __( 'Location', 'couponxl' ),
				'menu_name' 					=> __( 'Location', 'couponxl' ),
				'all_items'						=> __( 'All Locations', 'couponxl' ),
				'edit_item'						=> __( 'Edit Location', 'couponxl' ),
				'view_item'						=> __( 'View Location', 'couponxl' ),
				'update_item'					=> __( 'Update Location', 'couponxl' ),
				'add_new_item'					=> __( 'Add New Location', 'couponxl' ),
				'new_item_name'					=> __( 'New Location Name', 'couponxl' ),
				'parent_item'					=> __( 'Parent Location', 'couponxl' ),
				'parent_item_colon'				=> __( 'Parent Location:', 'couponxl' ),
				'search_items'					=> __( 'Search Locations', 'couponxl' ),
				'popular_items'					=> __( 'Popular Locations', 'couponxl' ),
				'separate_items_with_commas'	=> __( 'Separate locations with commas', 'couponxl' ),
				'add_or_remove_items'			=> __( 'Add or remove locations', 'couponxl' ),
				'choose_from_most_used'			=> __( 'Choose from the most used locations', 'couponxl' ),
				'not_found'						=> __( 'No locations found', 'couponxl' ),
			)
		
		) );
		

		register_taxonomy( 'offer_cat', array( 'offer' ), array(
			'label' => __( 'Offer Categories', 'couponxl' ),
			'hierarchical' => true,
			'labels' => array(
				'name' 							=> __( 'Offer Categories', 'couponxl' ),
				'singular_name' 				=> __( 'Offer Category', 'couponxl' ),
				'menu_name' 					=> __( 'Offer Category', 'couponxl' ),
				'all_items'						=> __( 'All Offer Categories', 'couponxl' ),
				'edit_item'						=> __( 'Edit Offer Category', 'couponxl' ),
				'view_item'						=> __( 'View Offer Category', 'couponxl' ),
				'update_item'					=> __( 'Update Offer Category', 'couponxl' ),
				'add_new_item'					=> __( 'Add New Offer Category', 'couponxl' ),
				'new_item_name'					=> __( 'New Offer Category Name', 'couponxl' ),
				'parent_item'					=> __( 'Parent Offer Category', 'couponxl' ),
				'parent_item_colon'				=> __( 'Parent Offer Category:', 'couponxl' ),
				'search_items'					=> __( 'Search Offer Categories', 'couponxl' ),
				'popular_items'					=> __( 'Popular Offer Categories', 'couponxl' ),
				'separate_items_with_commas'	=> __( 'Separate offer categories with commas', 'couponxl' ),
				'add_or_remove_items'			=> __( 'Add or remove offer categories', 'couponxl' ),
				'choose_from_most_used'			=> __( 'Choose from the most used offer categories', 'couponxl' ),
				'not_found'						=> __( 'No offer categories found', 'couponxl' ),
			)
		
		) );

		register_taxonomy( 'offer_tag', array( 'offer' ), array(
			'label' => __( 'Offer Tags', 'couponxl' ),
			'hierarchical' => false,
			'labels' => array(
				'name' 							=> __( 'Offer Tags', 'couponxl' ),
				'singular_name' 				=> __( 'Offer Tag', 'couponxl' ),
				'menu_name' 					=> __( 'Offer Tag', 'couponxl' ),
				'all_items'						=> __( 'All Offer Tags', 'couponxl' ),
				'edit_item'						=> __( 'Edit Offer Tag', 'couponxl' ),
				'view_item'						=> __( 'View Offer Tag', 'couponxl' ),
				'update_item'					=> __( 'Update Offer Tag', 'couponxl' ),
				'add_new_item'					=> __( 'Add New Offer Tag', 'couponxl' ),
				'new_item_name'					=> __( 'New Offer Tag Name', 'couponxl' ),
				'parent_item'					=> __( 'Parent Offer Tag', 'couponxl' ),
				'parent_item_colon'				=> __( 'Parent Offer Tag:', 'couponxl' ),
				'search_items'					=> __( 'Search Offer Tags', 'couponxl' ),
				'popular_items'					=> __( 'Popular Offer Tags', 'couponxl' ),
				'separate_items_with_commas'	=> __( 'Separate offer tags with commas', 'couponxl' ),
				'add_or_remove_items'			=> __( 'Add or remove offer tags', 'couponxl' ),
				'choose_from_most_used'			=> __( 'Choose from the most used offer tags', 'couponxl' ),
				'not_found'						=> __( 'No offer tags found', 'couponxl' ),
			)
		
		) );
		
		$store_args = array(
			'labels' => array(
				'name' => __( 'Stores', 'couponxl' ),
				'singular_name' => __( 'Store', 'couponxl' )
			),
			'public' => true,
			'menu_icon' => 'dashicons-store',
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			),
		);
		if( class_exists('ReduxFramework') && function_exists('couponxl_get_option') ){
			$trans_store = couponxl_get_option( 'trans_store' );
			if( !empty( $trans_store ) ){
				$store_args['rewrite'] = array( 'slug' => $trans_store );
			}
		}
		register_post_type( 'store', $store_args );	

		register_taxonomy( 'letter', array( 'store' ), array(
			'label' => __( 'Letters', 'couponxl' ),
			'hierarchical' => true,
			'labels' => array(
				'name' 							=> __( 'Letters', 'couponxl' ),
				'singular_name' 				=> __( 'Letter', 'couponxl' ),
				'menu_name' 					=> __( 'Letter', 'couponxl' ),
				'all_items'						=> __( 'All Letters', 'couponxl' ),
				'edit_item'						=> __( 'Edit Letter', 'couponxl' ),
				'view_item'						=> __( 'View Letter', 'couponxl' ),
				'update_item'					=> __( 'Update Letter', 'couponxl' ),
				'add_new_item'					=> __( 'Add New Letter', 'couponxl' ),
				'new_item_name'					=> __( 'New Letter Name', 'couponxl' ),
				'parent_item'					=> __( 'Parent Letter', 'couponxl' ),
				'parent_item_colon'				=> __( 'Parent Letter:', 'couponxl' ),
				'search_items'					=> __( 'Search Letters', 'couponxl' ),
				'popular_items'					=> __( 'Popular Letters', 'couponxl' ),
				'separate_items_with_commas'	=> __( 'Separate letters with commas', 'couponxl' ),
				'add_or_remove_items'			=> __( 'Add or remove letters', 'couponxl' ),
				'choose_from_most_used'			=> __( 'Choose from the most used letters', 'couponxl' ),
				'not_found'						=> __( 'No letters found', 'couponxl' ),
			)
		
		) );		
		
		register_post_type( 'voucher', array(
			'labels' => array(
				'name' => __( 'Vouchers', 'couponxl' ),
				'singular_name' => __( 'Voucher', 'couponxl' )
			),
			'public' => true,
			'menu_icon' => 'dashicons-tickets-alt',
			'has_archive' => false,
			'supports' => array(
				'title',
			),
			'capabilities' => array(
			    'publish_posts' => 'manage_options',
			    'edit_posts' => 'manage_options',
			    'edit_others_posts' => 'manage_options',
			    'delete_posts' => 'manage_options',
			    'delete_others_posts' => 'manage_options',
			    'read_private_posts' => 'manage_options',
			    'edit_post' => 'manage_options',
			    'delete_post' => 'manage_options',
			    'read_post' => 'manage_options',
			),			
		));

		if( class_exists( 'Seravo_Custom_Bulk_Action' ) ){
			$bulk_actions = new Seravo_Custom_Bulk_Action( array('post_type' => 'voucher') );

			$bulk_actions->register_bulk_action(array(
			    'menu_text' => __( 'Pay To Sellers', 'couponxl' ),
			    'admin_notice'=> __( 'Sellers are paid', 'couponxl' ),
			    'callback' => function( $post_ids ) {
			    	couponxl_pay_all_sellers( $post_ids, 'no' );
			    	return true;
				}
			));

			$bulk_actions->init();
		}
	
	}
}
add_action('init', 'couponxl_post_types_and_taxonomies', 0);

?>