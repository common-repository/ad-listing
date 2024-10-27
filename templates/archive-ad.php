<?php
/*
** Template Name : Three Column 
**
*/
	get_header();		
	
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$args = array(
			'post_type'		=>'listing',
			'post_status' 	=>'publish',
			'no_found_rows' => true,
			'posts_per_page'=> 12,
			'posts_per_page'=>$atts['count'],
			'paged'			=>$paged,
		);
		?>
        <?php
		$customQry = new WP_Query($args);
		if($customQry->have_posts()):?>
					<div class="row ad-container">                    
                    <?php	
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
		
					?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 each-box">
						<div class="box">
							<a href="<?php echo esc_url(get_permalink()); ?>">
								<div class="img"><?php echo $images; ?></div>
							</a>
							<div class="title">
								<h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html__(get_the_title()); ?></a></h3>
							</div>
							<div class="short-desc"><?php echo $listing_shot_desc; ?></div>
						</div>
					</div>
                    <?php
				endwhile;			
				?></div><?php			
			else:
					?><div class="alert alert-warning">You Dont have any Ads right now...</div><?php			
		endif;
		wp_reset_postdata();		
		if($customQry->max_num_pages > 1 && is_page()){
				?>
				<div class="row ad-pagination">
					<div class="col-lg-12">
						<nav class="prev-next-posts">
							<div class="nav-previous">
							<?php echo get_next_posts_link(__('<span class="meta-nav">&larr;</span>Previous'),$customQry->max_num_pages).'</div><div class="next-posts-link">'.get_previous_posts_link(__('<span class="meta-nav">&rarr;</span>Next'));
							?></div>
                        </nav>
                    </div>
                </div><?php
		}		
	get_footer();	
?>
