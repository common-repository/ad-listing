<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ADL_Post_types {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_taxonomy' ), 5 );
	}
	
	
	public static function register_post_types() {
		
		$labels = array(
							'name'               => __( 'Listings', 'adlisting' ),
							'singular_name'      => __( 'Listing', 'adlisting' ),
							'menu_name'          => __( 'Listings','adlisting' ),
							'add_name'           => __( 'Add Listing', 'adlisting' ),
							'add_new_item'       => __( 'Add New Listing', 'adlisting' ),
							'edit'               => __( 'Edit', 'adlisting' ),
							'edit_item'          => __( 'Edit Listing', 'adlisting' ),
							'new_item'           => __( 'New Listing', 'adlisting' ),
							'view'               => __( 'View Listing', 'adlisting' ),
							'view_item'          => __( 'View Listing', 'adlisting' ),
							'search_items'       => __( 'Search Listings', 'adlisting' ),
							'not_found'          => __( 'No Listings found', 'adlisting' ),
							'not_found_in_trash' => __( 'No Listings found in trash', 'adlisting' )
						);
		
		
		
		$args = array(
					'labels'              => $labels,
					'description'         => __( 'This is where you can add new Ads to your listing.', 'adlisting' ),
					'public'              => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'show_in_nav_menus'   => true,
					'show_ui'             => true,
					"show_in_menu" 			=> true,
					"show_in_admin_bar" 	=> true,
					"menu_position" 		=> 10,
					"menu_icon" 			=> "dashicons-megaphone",					
					"can_export" 			=> true,					
					"delete_with_user" 		=> false,
					'hierarchical'        => false,				
					'has_archive'         => true,
					'query_var'           => true,					
					'capability_type'     => 'post',					
					'map_meta_cap'        => true,				
					'rewrite'             => array("slug"=> "listing","width_font"=> true,"pages"=> true,"feeds"=> true),					
					'supports'            => array( 'title', 'editor', 'thumbnail', /*'excerpt', 'custom-fields',*/ 'comments', 'page-attributes', 'publicize', 'wpcom-markdown' )
				);
		register_post_type( 'listing',$args);
	}

	public static function register_taxonomy() {
		$singular = "Location";
		$plural = "Locations";		
		$labels = array(
			"name" => $singular,
			"singular_name" => $singular,
			"search_items" => "Search ".$plural,
			"popular_items" => "Popular ".$plural,
			"all_items" => "All ".$plural,
			"parent_item" => null,
			"parent_item_colon" =>null,
			"edit_item" => "Edit ".$singular,
			"update_item" => "Update ".$singular,
			"add_new_item" => "Add New ".$singular,
			"new_item_name" => "New ".$singular." Name",
			"seperate_items_with_commas" => "Seperate ".$plural." with commas",
			"add_or_remove_items" => "Add or Remove ".$plural,
			"choose_from_most_used" => "Choose from the most used ".$plural,
			"not_found" => "No ".$plural." found",
			"menu_name" => $plural,			
		);		 
		$args = array(
			"hierarchical" => true,
			"labels" => $labels,
			"show_ui" => true,
			"show_admin_column" => true,
			"update_count_callback" => "_update_post_term_count",
			"query_var" => true,
			"rewrite" => array( "slug" => "listing-location" ),
		);			
		register_taxonomy("listing-location","listing",$args);
		
		
		$singular = "Category";
		$plural = "Categories";		
		$labels = array(
			"name" => $singular,
			"singular_name" => $singular,
			"search_items" => "Search ".$plural,
			"popular_items" => "Popular ".$plural,
			"all_items" => "All ".$plural,
			"parent_item" => null,
			"parent_item_colon" =>null,
			"edit_item" => "Edit ".$singular,
			"update_item" => "Update ".$singular,
			"add_new_item" => "Add New ".$singular,
			"new_item_name" => "New ".$singular." Name",
			"seperate_items_with_commas" => "Seperate ".$plural." with commas",
			"add_or_remove_items" => "Add or Remove ".$plural,
			"choose_from_most_used" => "Choose from the most used ".$plural,
			"not_found" => "No ".$plural." found",
			"menu_name" => $plural,			
		);		 
		$args = array(
			"hierarchical" => true,
			"labels" => $labels,
			"show_ui" => true,
			"show_admin_column" => true,
			"update_count_callback" => "_update_post_term_count",
			"query_var" => true,
			"rewrite" => array( "slug" => "listing-category" ),
		);			
		register_taxonomy("listing-category","listing",$args);
	}
	
}
ADL_Post_types::init();

?>