(function($) {
	$(document).ready(function(){
		
		//for user auto complete					   
		var users = [];
		$('select[name=listing_user] option').each(function(i,option){
			if($(this).val()>0){
				obj = {'value':$(this).val(),'label':$(this).text()}
				users.push(obj);
			}
		})
		$(".autocomplete-user" ).autocomplete({
			source: users,
			focus: function(event,ui){
				$(this).val(ui.item.label);
				return false;
			},
			select: function(event,ui){
				$(this).val(ui.item.label)
				$(this).parent().find('select').val(ui.item.value)
				return false;
			},
		}).data('ui-autocomplete')._renderItem = function(ul,item){
			return $('<li>')
			.append('<a>'+item.label+'</a>')
			.appendTo(ul)
		};
		//end here
		
		
		
		/*listing grid*/
		$(document).on('hover','a.feature',function(){
			$(this).find('span').removeClass('fa-star-o').addClass('fa-star');
		})
		$(document).on('mouseout','a.feature',function(){
			$(this).find('span').removeClass('fa-star').addClass('fa-star-o');
		})
		/*image gallery script*/
		var customUploader = wp.media({
			title: 'Add Image to Listing Gallery',
			button: {
				text: 'Add Gallery Image'
			},
			multiple: true
		});
		//opening wordpress media iframe
		$(document).on('click','a#image-uploader',function(e){
			e.preventDefault();
			customUploader.open();
			
		})
		//selecteding images on media iframe close event
		customUploader.on( 'select', function() {
			var attachments = customUploader.state().get('selection').toJSON();
			//console.log(attachments);
			
			selected = $.parseJSON($('input[name=listing_gallery]').val());
			if(selected==null){selected = [];}
			$.each(attachments,function(k,obj){
				valid = true;
				if(selected.length>0)
				{
					$.each(selected,function(i,obj1){
						if(obj1.id==obj.id){valid=false;}
					})				
				}
				if(valid){
					selectedObj = { id: obj.id, url: obj.url };				
					selected.push(selectedObj);
				}
			})
			html='';
			$.each(selected,function(k,obj){
				html +='<div class="image"><img src="'+obj.url+'" /><a href="#" class="btn" id="'+obj.id+'">&times;</a></div>';								 
			})
			html +='<div class="clear"></div>';
			$('#listing-gallery-container').html(html);
			$('input[name=listing_gallery]').val(JSON.stringify( selected ));
		});
		
		//removeing images from gallery container		
		$(document).on('click','#listing-gallery-container .image a.btn',function(e){
			e.preventDefault();
			removed =[];																	  
			selected = $.parseJSON($('input[name=listing_gallery]').val());			
			removeId = $(this).prop('id')
			$(this).closest('.image').remove();
			$.each(selected,function(k,obj){										 
				if(obj.id!=removeId)				
				{
					removedObj = { id: obj.id, url: obj.url };				
					removed.push(removedObj);
				}				
			})
			if(removed.length==0){removed='';}else{removed=JSON.stringify( removed );}
			$('input[name=listing_gallery]').val(removed);
		})	
	})
})(jQuery);
