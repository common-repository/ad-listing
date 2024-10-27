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
		
	})
})(jQuery);
