<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ADL_Admin_Quick_Edit {
	public static function init() {		
		add_filter( 'manage_listing_posts_columns' , array(__CLASS__ ,'add_column' ));
		add_action( 'manage_listing_posts_custom_column' ,array( __CLASS__ ,'custom_column_values' ), 10, 2 );
	}
	

	public static function custom_column_values( $column, $post_id ) {
		$listing_stored_meta = get_post_meta($post_id);
		switch ( $column ) {
			case 'thumbnail':
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				if ( $post_thumbnail_id ) {
					$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );				
					echo '<a href="'.admin_url('post.php?post='.$post_id.'&action=edit').'" class="feature"><img src="' . $post_thumbnail_img[0] . '" width="40" /></a>';
				}
				else
				{
					echo '<a href="'.admin_url('post.php?post='.$post_id.'&action=edit').'" class="feature"><img src="' .plugins_url('adlisting').'/assets/images/placeholder.png' . '" width="40" /></a>';
				}
			break;
			case 'feature':
				if( !empty( $listing_stored_meta["listing_feature"] ) ) $listing_feature = esc_attr( $listing_stored_meta["listing_feature"][0] );
				if($listing_feature=='Featured'){
					echo '<a href="#feature" class="feature" title="Yes"><span class="fa fa-star"></span></a>';
				}else{
					echo '<a href="#feature" class="feature" title="No"><span class="fa fa-star-o"></span></a>';
				}
			break;
		}
	}
	
	public static function add_column( $columns ) {
		
		$new_columns = array( 
							'cb' =>	$columns['cb'],
							'thumbnail' => __( '<span class="fa fa-picture-o"></span> Thumb', 'adlisting' ),
							'title' => $columns['title'],
							'taxonomy-listing-location' =>	$columns['taxonomy-listing-location'],
							'taxonomy-listing-category' =>	$columns['taxonomy-listing-category'],
							'comments' =>	$columns['comments'],
							'feature' => __('<span class="fa fa-star" title="Feature"></span>','adlisting'),
						);
		unset($columns['cb']);
		unset($columns['taxonomy-listing-location']);
		unset($columns['taxonomy-listing-category']);
		unset($columns['title']);
		unset($columns['comments']);
    	return array_merge( $new_columns, $columns);
	}
	
		
}
ADL_Admin_Quick_Edit::init();

?>