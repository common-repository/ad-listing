<?php
/*
** Template Name : Three Column 
**
*/
	get_header();
	global $post;
	?>
    	<div class="row ad-post">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="ad-title">
                	<h1><?php echo the_title(); ?></h1>
                </div>
                <div class="row">
                	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    	<div class="image-gallery">
                    	<?php
							$images ='';
			
							$featureImgId = get_post_thumbnail_id(get_the_ID());
							$images .= wp_get_attachment_image($featureImgId,'medium');
							
							$imageList = json_decode(get_post_meta(get_the_ID(),'listing_gallery',true),true);
	
							if($imageList!==NULL){
								foreach($imageList as $image){
									$image = (object) $image;
									if(get_post_thumbnail_id(get_the_ID()) != $image->id){
										$images .= wp_get_attachment_image($image->id,'medium');
									}
								}
							}
							echo $images;
						?>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                    	<div class="ad-description">
							<?php echo the_content();?>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="specification">
                	<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    	<label for="contact-no">Contact No</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                    	<?php if(!empty(get_post_meta(get_the_ID(),'listing_contact',true))){?>
                    	<label for="contact-no"><?php echo get_post_meta(get_the_ID(),'listing_contact',true); ?></label>
                        <?php }?>
                    </div>
                    
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    	<label for="email">Email</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
                    	<?php if(!empty(get_post_meta(get_the_ID(),'listing_email',true))){?>
                    	<label for="contact-no"><?php echo get_post_meta(get_the_ID(),'listing_email',true); ?></label>
                        <?php }?>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    	<label for="contact-no">Postal Address</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12">
                    	<?php if(!empty(get_post_meta(get_the_ID(),'listing_address',true))){?>
                    	<label for="contact-no"><?php echo get_post_meta(get_the_ID(),'listing_address',true); ?></label>
                        <?php }?>
                    </div>
                </div>
                </div>
                                
            </div>
        </div>
    <?php
	get_footer();
?>
