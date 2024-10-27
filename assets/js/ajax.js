(function($) {
	$(document).ready(function(){
		
		
		$(document).on('click','a.feature',function(){
			postId = $(this).closest('tr').attr('id').replace('post-','');
			if($(this).find('span.fa-star').length){
				//make unfeature list
				data = {action:'add_feature_list',feature : 'false',id : postId}
				$(this).find('span').removeClass('fa-star').addClass('fa-star-o');
			}else{
				//make feature list
				data = {action:'add_feature_list',feature : 'true',id : postId}
				$(this).find('span').removeClass('fa-star-o').addClass('fa-star');
			}
			$.fn.ajax_action(data)
		})
		
		$.fn.ajax_action = function(postData){
			$('.wrap #message').remove()
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'adlisting_ajax',
					adlAjaxData: postData,
					security: WP_ADL.security
				},
				beforeComplete: function(){
					$('.wrap h1').after('<div id="message" class="updated notice notice-warning is-dismissible">Loading...</div>')
				},
				success: function(response){
					$('.wrap #message').remove()
					if(response.success === true){
						console.log(response.data);
						$('.wrap h1').after('<div id="message" class="updated notice notice-success is-dismissible"><p>'+response.data+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>')
					}else{
						console.log(response.data);
						$('.wrap h1').after('<div id="message" class="updated notice notice-success is-dismissible"><p>'+response.data+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>')
					}					
				},
				error: function(error){
					console.log(error);
					$('.wrap h1').after('<div id="message" class="updated notice notice-error is-dismissible"><p>'+error.data+'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>')
				}
			})
		}
	})
})(jQuery);
