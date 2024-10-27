<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ADL_Install {

	public static function init(){
		add_action('init',array( __CLASS__, 'install' ), 1 );
	}
	public static function install() {
		self::create_roles();
		self::create_listing_page();
	}

	public static function create_roles() {
		global $wp_roles;
		if ( ! class_exists( 'WP_Roles' ) ) {return;}
		if ( ! isset( $wp_roles ) ) {$wp_roles = new WP_Roles();}

		// Customer role
		add_role( 'advertiser', __( 'Advertiser', 'adlisting' ), array(
			'read' 						=> true,
			'edit_posts' 				=> false,
			'delete_posts' 				=> false
		) );
	}
	
	
	public static function create_listing_page(){
		$pages = array(
			'classifieds' => array(
				'name'    => _x( 'classifieds', 'Page slug', 'adlisting' ),
				'title'   => _x( 'Classifieds', 'Page title', 'adlisting' ),
				'content' => '[listing]'
				)
			);
		foreach($pages as $key=>$page){
			self::create_pages( esc_sql( $page['name'] ), 'listing' . $key . '_page_id', $page['title'], $page['content'], ! empty( $page['parent'] ) ? wc_get_page_id( $page['parent'] ) : '' );
		}
	}
	protected static function create_pages($slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0){
		global $wpdb;
		
		$option_value = get_option( $option );
		if ( $option_value > 0 && get_post( $option_value ) ) {
			return -1;
		}
		
		if ( strlen( $page_content ) > 0 ) {
			// Search for an existing page with the specified page content (typically a shortcode)
			$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		} else {
			// Search for an existing page with the specified page slug
			$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type='page' AND post_name = %s LIMIT 1;", $slug ) );
		}
		
		
		if ( $page_found ) {
			if ( ! $option_value ) {
				update_option( $option, $page_found );
			}	
			return $page_found;
		}
		
		$page_data = array(
			'post_status'       => 'publish',
			'post_type'         => 'page',
			'post_author'       => 1,
			'post_name'         => $slug,
			'post_title'        => $page_title,
			'post_content'      => $page_content,
			'post_parent'       => $post_parent,
			'comment_status'    => 'closed'
		);
		$page_id = wp_insert_post( $page_data );
	
		if ( $option ) {
			update_option( $option, $page_id );
		}	
		return $page_id;
	}
}

ADL_Install::init();
