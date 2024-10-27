<?php
/**
 * @wordpress-plugin
 * Plugin Name: Ad Listing
 * Plugin URI: http://www.opencodetreat.com/ad-listing.zip
 * Description: An Advertisement Listing toolkit that helps you Advertise anything, Beautifully.
 * Version: 1.0.1
 * Author: Sunil Kumar Mutaka
 * Author URI: http://www.opencodetreat.com
 * Text Domain: ad-listing
 * Requires at least: 4.0
 * Tested up to: 4.1
 *
 */
 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'AdListing' ) ) :
final class AdListing
{
	public $version = '1.0.1';
		
	public function __construct(){
		$this->includes();
		$this->init();
	}
	
	protected function includes(){
		require ( plugin_dir_path(__FILE__)."includes/class-adl-post-type.php" );
		require ( plugin_dir_path(__FILE__)."includes/admin/class-adl-admin-post-type.php" );
		require ( plugin_dir_path(__FILE__)."includes/admin/class-adl-admin-quick-edit.php" );
		require ( plugin_dir_path(__FILE__).'includes/class-adl-install.php' );
		require ( plugin_dir_path(__FILE__).'includes/class-adl-shortcode.php' );
	}
	
	
	public function admin_enqueue_scripts() {
		global $pagenow,$typenow;
		
		if( ($pagenow=="post.php" || $pagenow=="post-new.php" || $pagenow=="edit.php") && $typenow=="listing" ) {
			wp_enqueue_style( "font-awesome.min",plugins_url( "assets/css/font-awesome.min.css", __FILE__ ) );
			wp_enqueue_style( "listing-stylesheet",plugins_url( "assets/css/stylesheet.css", __FILE__ ) );
		}
		
		if( ($pagenow=="post.php" || $pagenow=="post-new.php") && $typenow=="listing" ) {
			wp_enqueue_script( "listing-autocomplete",plugins_url( "assets/js/autocomplete-javascript.js", __FILE__ ), array( "jquery",'jquery-ui-autocomplete' ) , "21062016" , true);
			wp_enqueue_script( "listing-javascript",plugins_url( "assets/js/javascript.js", __FILE__ ), array( "jquery" ) , "11052016" , true);
		}
		
		if( $pagenow=="edit.php"  && $typenow=="listing")
		{
			wp_enqueue_script( "listing-ajax",plugins_url( "assets/js/ajax.js", __FILE__ ), array( "jquery" ) , "20062016" , true);
			wp_localize_script(
				"listing-ajax",
				'WP_ADL',
				array(
					'security'=>wp_create_nonce('wp-adlisting-ajax'),
					'success'=>true,
					'failure'=>false
			));
		}
	}
	public function template_enqueue_scripts(){
		wp_enqueue_style( "bootstrap.min",plugins_url( "templates/css/bootstrap.min.css", __FILE__ ) );
		wp_enqueue_style( "ad-listing-css",plugins_url( "templates/css/stylesheet.css", __FILE__ ) );
		wp_enqueue_script( "ad-listing",plugins_url( "templates/js/javascript.js", __FILE__ ),'' , "30062016" , true);
		wp_enqueue_script( "bootstrap.min",plugins_url( "templates/js/bootstrap.min.js", __FILE__ ),'' , "30062016" , true);
	}
	
	public function init(){
		add_action( "admin_enqueue_scripts",array(__CLASS__,"admin_enqueue_scripts"),5);
		add_action( "wp_enqueue_scripts",array(__CLASS__,"template_enqueue_scripts"),5);
		
		add_filter( "template_include",array(__CLASS__,"templates"),5 );
		register_activation_hook( __FILE__, array( 'ADL_Install', 'install' ) );		
	}
	public function templates(){
	
		//active template included if page is not listing type
		if(get_query_var('post_type')!=='listing'){
			return get_page_template();
		}
		
		//listing template included if user custom template exist
		if(is_archive() || is_search()){
			if(file_exists(get_stylesheet_directory().'/archive-ad.php')){
				return get_stylesheet_directory().'/archive-ad.php';
			}else{
				return plugin_dir_path(__FILE__).'templates/archive-ad.php';
			}
		}
		
		if(is_single()){
			if(file_exists(get_stylesheet_directory().'/single-ad.php')){
				return get_stylesheet_directory().'/single-ad.php';
			}else{
				return plugin_dir_path(__FILE__).'templates/single-ad.php';
			}
		}
	}
}
endif;

function ADL(){
	return $AdListing = new AdListing;
}
$GLOBALS['adlisting'] = ADL();

