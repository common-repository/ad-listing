<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ADL_Shortcode {
	public static function init() {
		add_shortcode('ad-listing',array(__CLASS__,'adlisting'));
		add_shortcode('search-box',array(__CLASS__,'search_box'));
	}	
	public static function adlisting($atts,$content = null){
		$atts = shortcode_atts(
			array(
				"title"		=>"Ad Listing",
				'count'		=>12,
				'pagination'=>false,
				'featured'  =>false,						
			),$atts
		);
		
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$args = array(
			'post_type'		=>'listing',
			'post_status' 	=>'publish',
			'no_found_rows' =>$atts['pagination'],
			'posts_per_page'=>$atts['count'],
			'paged'			=>$paged,
		);
		
		if($atts['featured']=='on'){
			$args['meta_query'] = array(
				'key'=> 'listing_feature',
				'value' => 'Featured',
				'compare' => '=',
			);
		}elseif($atts['featured']=='off'){
			$args['meta_query'] = array(
				'key'=> 'listing_feature',
				'value' => 'Unfeatured',
				'compare' => '=',
			);
		}
		
		//search box filter
		if(isset($_POST["listing_search"])){
			
			$tax_query = array();
			if($_POST["listing_location"]>0){
				$tax = array(
					'taxonomy' => 'listing-location',
					'field'    => 'term_id',
					'terms'	   => array($_POST['listing_location']),
				);
				array_push($tax_query,$tax);				
			}
			
			if($_POST["listing_category"]>0){
				if(!empty($tax_query)){
					$tax_query['relation'] = 'AND';
				}				
				$tax = array(
					'taxonomy' => 'listing-category',
					'field'    => 'term_id',
					'terms'	   => array($_POST['listing_category']),
				);
				array_push($tax_query,$tax);				
			}
			if(!empty($tax_query)){
				$args['tax_query'] = $tax_query;
			}
			
			//featured or unfeature
			$meta_query = array();
			if(isset($_POST["listing_featured"])){
				//$meta_query['relation'] = 'AND';
				$meta = array(
					'key'=> 'listing_feature',
					'value' => 'Featured',
					'compare' => '=',
				);
				array_push($meta_query,$meta);
			}
			if(!empty($_POST["search"])){				
				if(!empty($meta_query)){
					$meta_query['relation'] = 'AND';
				}				
				$meta = array(
					'key'=> 'listing_metadata',
					'value' => str_replace(" ","+",$_POST["search"]),
					'compare' => 'LIKE',
				);
				array_push($meta_query,$meta);
			}
			if(!empty($meta_query)){
				$args['meta_query'] = $meta_query;
			}
		}
		
		$customQry = new WP_Query($args);
		$postHtml="";
		if($customQry->have_posts()):
				$postHtml = '<div class="row ad-container">';	
				while($customQry->have_posts()):$customQry->the_post();
					global $post;					
					$images ='';			
					
					$featureImgId = get_post_thumbnail_id(get_the_ID());
					$images .= wp_get_attachment_image($featureImgId,'medium');
								
					$imageList = json_decode(get_post_meta(get_the_ID(),'listing_gallery',true),true);
					$listing_shot_desc = get_post_meta(get_the_ID(),'listing_shot_desc',true);					
					
					if($imageList!==NULL){
						foreach($imageList as $image){
							$image = (object) $image;
							if(get_post_thumbnail_id(get_the_ID()) != $image->id){
								$images .= wp_get_attachment_image($image->id,'medium');
							}
						}
					}
		
					$postHtml .='
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 each-box">
						<div class="box">
							<a href="'.esc_url(get_permalink()).'">
								<div class="img">'.$images.'</div>
							</a>
							<div class="title">
								<h3><a href="'.esc_url(get_permalink()).'">'.esc_html__(get_the_title()).'</a></h3>
							</div>
							<div class="short-desc">'.$listing_shot_desc.'</div>
						</div>
					</div>';
				endwhile;			
				$postHtml .='</div>';			
			else:
				$postHtml ='<div class="alert alert-warning">You Dont have any Ads right now...</div>';			
		endif;
		wp_reset_postdata();		
		if($customQry->max_num_pages > 1 && is_page()){
			$postHtml .='
				<div class="row ad-pagination">
					<div class="col-lg-12">
						<nav class="prev-next-posts">
							<div class="nav-previous">'.
							get_next_posts_link(__('<span class="meta-nav">&larr;</span>Previous'),$customQry->max_num_pages).'</div><div class="next-posts-link">'.get_previous_posts_link(__('<span class="meta-nav">&rarr;</span>Next')).'</div></nav></div></div>';
		}
		
						
		return $postHtml;				
	}
	public function search_box($atts,$content = null){
	$postHtml = '';
		$atts = shortcode_atts(
			array(
				'title'		=>'Advance Search',
				'location'	=>true,
				'category'  =>true,
				'feature'	=>true,				
			),$atts
		);	
		include(plugin_dir_path(__FILE__).'../templates/search-box-ad.php');				
		return $postHtml;
	}
	public function get_taxonomy($options){
		$args = array('hide_empty' => false, 'hierarchical' => true, 'parent' => 0); 
		$terms = get_terms($options['taxonomy'], $args);	
		$html = '';
		$html .= '<select name="' . $options['name'] . '"' . 'class="form-control">';
			if(isset($_POST[$options['name']]) && $_POST[$options['name']]==0){
				$html .= '<option value="0" selected>All '.$options['display_name'].'</option>';
			}else{
				$html .= '<option value="0" selected>All '.$options['display_name'].'</option>';
			}
			foreach ( $terms as $term ) {
				//level one
				if(isset($_POST[$options['name']]) && $_POST[$options['name']]==$term->term_id){
					$html .= '<option value="' . $term->term_id . '" selected>' . $term->name . '</option>';
				}else{
					$html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
				}
				
	
				$args = array(
					'hide_empty'    => false, 
					'hierarchical'  => true, 
					'parent'        => $term->term_id
				); 
				$childterms = get_terms($options['taxonomy'], $args);
	
				foreach ( $childterms as $childterm ) {
					//level two
					if(isset($_POST[$options['name']]) && $_POST[$options['name']]==$childterm->term_id){
						$html .= '<option value="' . $childterm->term_id . '" selected>' . $term->name . ' > ' . $childterm->name . '</option>';
					}else{
						$html .= '<option value="' . $childterm->term_id . '">' . $term->name . ' > ' . $childterm->name . '</option>';
					}
					$args = array('hide_empty' => false, 'hierarchical'  => true, 'parent' => $childterm->term_id); 
					$granchildterms = get_terms($options['taxonomy'], $args);
	
					foreach ( $granchildterms as $granchild ) {
						//level three
						if(isset($_POST[$options['name']]) && $_POST[$options['name']]==$granchild->term_id){
							$html .= '<option value="' . $granchild->term_id . '" selected>' . $term->name . ' > ' . $childterm->name . ' > ' . $granchild->name . '</option>';
						}else{
							$html .= '<option value="' . $granchild->term_id . '">' . $term->name . ' > ' . $childterm->name . ' > ' . $granchild->name . '</option>';
						}
					}
				}
			}
		$html .=  "</select>";
		return $html;
	}
}
ADL_Shortcode::init();

?>