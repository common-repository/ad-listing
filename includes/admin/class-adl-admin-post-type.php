<?php



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



class ADL_Admin_Post_types {

	public static function init() {

		add_action("add_meta_boxes",array( __CLASS__, 'meta_boxs' ),5);

		add_action("save_post",array(__CLASS__,"save_listing"));

		add_action('wp_ajax_adlisting_ajax',array(__CLASS__,'add_feature'));

	}

	public static function meta_boxs() {

		

		add_meta_box(

			"listing_detail_meta",

			__('Listing Detail','adlisting'),

			array(__CLASS__,"listing_detail_meta_box_view_callback"),

			"listing",

			"normal",

			"high"

		);

		

		add_meta_box(

			"listing_gallery_meta",

			__('Listing Gallery','adlisting'),

			array(__CLASS__,"listing_gallery_meta_box_view_callback"),

			"listing",

			"side",

			"low"

		);

		

	}

	

	

	public static function listing_gallery_meta_box_view_callback($post) {

		wp_nonce_field( 'add_image_listing_gallery', 'listing_gallery_nonce' ); 

		$listing_stored_meta = get_post_meta($post->ID);

		$images = json_decode(stripslashes($listing_stored_meta["listing_gallery"][0]));
		$html_content ='<div id="listing-gallery-container">';
			$listing_gallery = array();
            if(is_array($images))
            {
                foreach($images as $image){
					$listing_gallery[] = array("id"=>$image->id,"url"=>(string)$image->url);
                        $html_content .='<div class="image"><img src="<?php echo $image->url; ?>" /><a href="#" class="btn" id="<?php echo $image->id;?>">&times;</a></div>';
                }
                    $html_content .='<div class="clear"></div>';
				$listing_gallery = stripslashes(json_encode($listing_gallery));
            }
			else
			{
				$listing_gallery = "";
			}
		$html_content .='</div>
    <input type="hidden" name="listing_gallery" id="listing-gallery" value="'.$listing_gallery.'"  />
	<p class="hide-if-no-js"><a href="#" id="image-uploader">Set Listing images</a></p>';
	return $html_content;
	}

	

	

	public static function listing_detail_meta_box_view_callback($post) {

		wp_nonce_field("add_listing_detail","listing_detail_meta_nonce" );

		$listing_stored_meta = get_post_meta($post->ID);		

		$users = get_users('role=advertiser');

		$html ='		
        	<table style="width:100%;">
            	<tr>
                	<td><label>Advertiser</label></td>
                    <td>';
                    if( !empty( $listing_stored_meta["listing_user"] ) )

					{

						$listing_user = esc_attr( $listing_stored_meta["listing_user"][0] );

						$userObj = get_user_by('id',$listing_user);

						$display_name = $userObj->display_name;

					}else{

						$listing_user='';

						$display_name='';

					}                    	

                    $html .='<select name="listing_user" id="listing-user" style="display:none;">
                        	<option value="">Select Advertiser</option>';
								foreach($users as $user)
								{
									if($listing_user==$user->ID){
                                    	$html .='<option value="<?php echo $user->ID; ?>" selected="selected"><?php echo $user->display_name; ?></option>';
									}else{
                                    	$html .='<option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>';
									}
								}
                   $html .='</select>
                        <input type="text" name="listing_user_name" class="autocomplete-user" value="<?php echo $display_name;?>" required />
                    </td>
                </tr>
            	<tr>
                	<td><label>Contact No</label></td>
                    <td><input type="tel" name="listing_contact" id="listing-contact" value="';
					
					if( !empty( $listing_stored_meta["listing_contact"] ) ) $html .=esc_attr( $listing_stored_meta["listing_contact"][0] );
					$html .='" /></td>
                    <td><label>Email Address</label></td>
                    <td><input type="email" name="listing_email" id="listing-email" value="';
					if( !empty( $listing_stored_meta["listing_email"] ) ) $html .=esc_attr( $listing_stored_meta["listing_email"][0] );
					$html .='" /></td>
                </tr>
                <tr>
                	<td><label>Postal Address</label></td>
                    <td colspan="3"><input type="text" name="listing_address" id="listing-address" value="';
					if( !empty( $listing_stored_meta["listing_address"] ) ) $html .= esc_attr( $listing_stored_meta["listing_address"][0] );
					$html .='" /></td>                  
                </tr>
                <tr>
                	<td colspan="4"><label>Shot Description</label></td>                                        
                </tr>                
                <tr>
                	<td colspan="4"><textarea name="listing_shot_desc" id="listing-shot-desc" rows="7">';
					if( !empty( $listing_stored_meta["listing_shot_desc"] ) ) $html .= esc_attr( $listing_stored_meta["listing_shot_desc"][0] );
					$html .='</textarea></td>
                </tr>
            </table>
            <input type="hidden" name="listing_feature" value="';
			$listing_feature = $listing_stored_meta["listing_feature"];
			if( !empty( $listing_feature ) ){
				$listing_feature = esc_attr( $listing_feature[0]);
				if(!empty($listing_feature))
				{ $html .= $listing_feature;}
				else{$html .= 'Unfeatured';}
			}else{ $html .= 'Unfeatured';}
			$html .='" />
            <input type="hidden" name="listing_metadata" value="';
			$listing_metadata = str_replace(" ","+",$post->post_title).'+'.str_replace(" ","+",$post->post_content);
			$html .=$listing_metadata;
			$html .='" />';
			return $html;
	}
	

	

	public static function add_feature(){

		//wp_send_json_error('you suck');

		if(!check_ajax_referer('wp-adlisting-ajax','security')){

			return wp_send_json_error('invalid nonce');

		}

		if(!current_user_can( 'manage_options' )){

			return wp_send_json_error('You don\'t have Auth... ');

		}

		$data = $_POST["adlAjaxData"];

		if(is_array($data)){

		

			if($data['action']=="add_feature_list")

			{				

				if($data['feature']=='true'){$feature_status='Featured';}else{$feature_status='Unfeatured';}

				update_post_meta( $data['id'],'listing_feature',sanitize_text_field( $feature_status ) );

				wp_send_json_success('Listing ID :'.$data['id'].' is now '.$feature_status);

			}

		}

	} 

	

	public static function save_listing( $post_id ) {

		global $post;

		$is_autosave = wp_is_post_autosave( $post_id );

		$is_revision = wp_is_post_revision( $post_id );

		$listing_detail_nonce = ( isset( $_POST["listing_detail_meta_nonce"] ) && wp_verify_nonce( $_POST["listing_detail_meta_nonce"],"add_listing_detail" ) ) ? 'true' : 'false';

		$listing_gallery_nonce = ( isset( $_POST["listing_gallery_meta_nonce"] ) && wp_verify_nonce( $_POST["listing_gallery_meta_nonce"],'add_image_listing_gallery' ) ) ? 'true' : 'false';

		//exist script depending on  save status



		if($is_autosave || $is_revision || !$listing_detail_nonce || !$listing_gallery_nonce) {

			return;

		}		

		

		//repeat how many you have meta tag

		if( isset( $_POST["listing_user"] ) ) {

			update_post_meta( $post_id,'listing_user',sanitize_text_field( $_POST["listing_user"] ) );

		}

		if( isset( $_POST["listing_contact"] ) ) {

			update_post_meta( $post_id,'listing_contact',sanitize_text_field( $_POST["listing_contact"] ) );

		}

		if( isset( $_POST["listing_email"] ) ) {

			update_post_meta( $post_id,'listing_email',sanitize_text_field( $_POST["listing_email"] ) );

		}

		if( isset( $_POST["listing_address"] ) ) {

			update_post_meta( $post_id,'listing_address',sanitize_text_field( $_POST["listing_address"] ) );

		}

		if( isset( $_POST["listing_gallery"] ) ) {

			$images = json_decode( stripslashes( $_POST[ 'listing_gallery' ] ) );

			$selected_images = array();

			if(is_array($images)){				

				foreach($images as $image)

				{

					$selected_images[] = array("id"=>$image->id,"url"=>$image->url);

				}

				$images=json_encode($selected_images);

			}else{$images='';}	

			

					

			update_post_meta( $post_id,'listing_gallery', $images );

		}

		if( isset( $_POST["listing_shot_desc"] ) ) {

			update_post_meta( $post_id,'listing_shot_desc',sanitize_text_field( $_POST["listing_shot_desc"] ) );

		}

		if( isset( $_POST["listing_feature"] ) ) {

			update_post_meta( $post_id,'listing_feature',sanitize_text_field( $_POST["listing_feature"] ) );

		}

		if( isset( $_POST["listing_metadata"] ) ) {

			update_post_meta( $post_id,'listing_metadata',sanitize_text_field( $_POST["listing_metadata"] ) );

		}

		

			

	}		

}

ADL_Admin_Post_types::init();



?>