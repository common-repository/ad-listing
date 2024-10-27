<?php
	$postHtml = '<div class="row ad-search-form">';
		$postHtml .='<h2>'.$atts['title'].'</h2>';
		$postHtml .= '<form method="post" action="'.esc_url( $_SERVER['REQUEST_URI'] ).'" enctype="multipart/form-data">';
		
		if($atts['location']){
			$options =array(
				'taxonomy' => 'listing-location',
				'name' => 'listing_location',
				'display_name' => 'Location',
			);
			$select = self::get_taxonomy($options);	
			$postHtml .='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><fieldset class="form-group">'.$select.'
</fieldset></div>';
		}
		if($atts['category']){
			$options =array(
				'taxonomy' => 'listing-category',
				'name' => 'listing_category',
				'display_name' => 'Category',
			);
			$select = self::get_taxonomy($options);	
			$postHtml .='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><fieldset class="form-group">'.$select.'</fieldset></div>';
		}
		$postHtml .='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><fieldset class="form-group">			
<label for="categoty"><input type="checkbox" name="listing_featured" /> Feature</label>	
</fieldset></div>';
		$postHtml .='<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12"><fieldset class="form-group">			
		<input type="text" name="search" class="form-control" placeholder="Search here" />	
		</fieldset></div>';
		
		$postHtml .='<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><input type="submit" name="listing_search" value="Search" class="btn btn-default" /></div>';
			
        $postHtml .='</form>
</div>' ;
		?>   
        