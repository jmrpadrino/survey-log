jQuery( function() {
	var po_ul = jQuery('#po-make-clonable');
	console.log('App ready!')

	if (jQuery('.set_custom_images').length > 0) {
		if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			jQuery(document).on('click', '.set_custom_images', function(e) {
				e.preventDefault();
				var button = jQuery(this);
				var id = button.prev();
				wp.media.editor.send.attachment = function(props, attachment) {
					console.log(attachment);
					id.val(attachment.url);
				};
				wp.media.editor.open(button);
				return false;
			});
		}
	}

	po_ul.sortable({
		placeholder: "ui-state-highlight",
		delay: 150,
		stop: function(event, ui){
			refreshItemslist();
		}
	});
	po_ul.disableSelection();

	jQuery('#po_add_item').click( function(e){
		e.preventDefault();
		var totalItems = jQuery('.input-placeholder');
		var nextItem = totalItems.length + 1;	
		po_ul.find('.input-placeholder:last').clone(true).appendTo(po_ul);
		po_ul.find('.input-placeholder:last').attr('data-item', nextItem);
		var inputs = po_ul.find('.input-placeholder:last').find('input');
		refreshItemslist();
		jQuery.each(inputs, function(index, value){
			jQuery(this).attr('name', jQuery(this).attr('name').replace(/[1-9]/g, nextItem));
			jQuery(this).val('');
		});
		console.log(inputs);	
	})
	jQuery('.fa-trash-alt').click( function(){
		jQuery(this).parents('.input-placeholder').remove();
	})
	
		

} );

jQuery('#po_reorder_btn').click( function(e){
	e.preventDefault();
	
	var orderingList = jQuery('.stage-item');
	var newOrderArray = [];
	var i = 1;
	
	jQuery.each( orderingList, function(index, value){
		newOrderArray.push( {
			post_id		: jQuery(this).data('postid'), 
			menuorder	: i++, 
		})
	})
	
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data:{
			action: 'po_reorder_survey',
			newOrderArray: newOrderArray
		},
		beforeSend: function(){
			jQuery('.po-message-placeholder').html(po_strings.po_before_send);
		},
		success: function(response){
			jQuery('.po-message-placeholder')
				.html(po_strings.po_success + ' ' + response)
				.removeClass('error')
				.addClass('success');
		},
		error: function(response){
			jQuery('.po-message-placeholder')
				.html(po_strings.po_error + ' ' + response)
				.removeClass('success')
				.addClass('error');
		}
	})
	// hacer un AJAX
});

function refreshItemslist(){
	var totalItems = jQuery('.input-placeholder');
	var itemStart = 1;
	jQuery.each( totalItems, function(index, value){
		jQuery(this).attr('data-item', itemStart);
		var inputs = jQuery(this).find('input');
		jQuery.each(inputs, function(index, value){
			jQuery(this).attr('name', jQuery(this).attr('name').replace(/[1-9]/g, itemStart));
		});
		itemStart++;
		console.log(value);
	})
}