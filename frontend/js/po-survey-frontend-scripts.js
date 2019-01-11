console.log('App started!');
var poSlides = jQuery('.po-slide');
var barWidth = 100 / poSlides.length;
jQuery(document).ready( function(){
	redoProgressBar(barWidth);
})
jQuery('.stage-item-label').click( function() {
	var parentContainer = jQuery(this).parents('.po-slide');
	var nextIndex = parentContainer.data('nextslide');
	var nextContainer = jQuery('*[data-slide="'+nextIndex+'"]');
	parentContainer.hide();
	nextContainer.toggle('fast', 'linear');
	redoProgressBar(barWidth * nextIndex);
});
function redoProgressBar(barWidth){
	jQuery('.bar').css({width: barWidth + '%'});
}

function surveyRestart(){
    jQuery('.po-slide').hide();
    jQuery('.po-slide[data-slide=1]').show();
}
function poSayThanks(){
    jQuery('div[data-nextslide="last"]').html(po_strings.thankyoutext)
}

/* AJAX call sending info */
jQuery('#send-survey').click( function (e){
	e.preventDefault();
	var surveySlug = jQuery('input[name=survey_slug]');
	var surveyItems = jQuery('input[type=radio]');
	var surveyUserData = jQuery('#po_user_data input');
	var poForm = jQuery('#po_survey_form');
	jQuery.ajax({
		type    : 'post',
		url     : po_strings.ajax_url,
		data: {
			action : 'po_add_quote',
			//values : poForm.serialize(),
			survey : surveySlug.serialize(),
			values : surveyItems.serialize(),
			userData : surveyUserData.serialize()
		},
		beforeSend : function(){
			console.log(po_strings.po_before_send, surveyItems, surveyUserData);
		},
		success    : function( response ){
			console.log(po_strings.po_success, response);
            //surveyRestart();
            poSayThanks();
		},
		error      : function( response ){
			console.log(po_strings.po_error);
		}
	})
})
