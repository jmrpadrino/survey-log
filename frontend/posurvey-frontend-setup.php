<?php

function po_frontend_scripts(){
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'show-survey') ) {
		wp_register_style( 'po_survey_frontend_css', POSURVEY_PLUGIN_URI . '/frontend/css/po-survey-frontend-style.css', false, NULL );
		wp_enqueue_style( 'po_survey_frontend_css' );

		wp_register_script( 'po_survey_frontend_js' , POSURVEY_PLUGIN_URI . '/frontend/js/po-survey-frontend-scripts.js', array('jquery'), NULL, true);
		wp_enqueue_script( 'po_survey_frontend_js' );
		wp_localize_script( 
			'po_survey_frontend_js', 
			'po_strings', 
			array( 
				'ajax_url' 			=> admin_url( 'admin-ajax.php' ),
				'po_before_send' 	=> _x('Sending data', 'po_survey'), 
				'po_success' 		=> _x('Survey sent', 'po_survey'), 
				'po_error' 			=> _x('There was an error, please try again later.', 'po_survey'), 
			) 
		);
	}
}
add_action('wp_enqueue_scripts', 'po_frontend_scripts');

function po_add_quote(){

	parse_str($_POST['survey'], $survey);
	parse_str($_POST['values'], $survey_values);
	parse_str($_POST['userData'], $survey_user_data);

	//mostrar($survey);
	//mostrar($survey_values);
	//mostrar($survey_user_data);

	$survey_data = get_term_by('slug', $survey['survey_slug'], 'po-survey-group');

	//mostrar($survey_data);

	ob_start();
?>
<div class="survey-info-placeholder">
	<h2>User Survey Application</h2>
	<h3>User data</h3>
	<p><strong>User Name:</strong> <?= $survey_user_data['first_name'] ?> <?= $survey_user_data['last_name'] ?></p>
	<p><strong>User Phone:</strong> <a href="tel:<?= $survey_user_data['phone'] ?>"><?= $survey_user_data['phone'] ?></a></p>
	<p><strong>User email:</strong> <a href="mailto:<?= $survey_user_data['email'] ?>"><?= $survey_user_data['email'] ?></a></p>
	<h3>Survey Data</h3>
	<p><strong>Survey name:</strong> <?= $survey_data->name ?></p>
	<p><strong>Survey Items</strong></p>
	<ul>
		<?php 
		foreach($survey_values as $index => $value){ 
			$question = get_post($index);
		?>
		<li><strong><?= $question->post_title ?></strong><br /><?= $value ?></li>
		<?php } ?>
	</ul>
</div>
<?php
	$html_content = ob_get_contents();
	ob_end_clean();
	echo $html_content;

	/** ADD QUOTE **/
	$pedido = array(
		'post_title' => '@'. date('Y-m-d h:i:s') . ' SG ' . $survey_data->name,
		'post_status' => 'publish',
		'post_type' => 'pos_apply',
		/*
		'meta_input' => array(
			$prefix . 'quote_ID' => $pedidoTemporal['quote'],
			$prefix . 'quote_status' => $pedidoTemporal['go-request'],
			$prefix . 'quote_ship' => $pedidoTemporal['ship'],
			$prefix . 'quote_departure' => $pedidoTemporal['departure'],
			$prefix . 'quote_promo' => $pedidoTemporal['promo'],
			$prefix . 'quote_duration' => $pedidoTemporal['duration'],
			$prefix . 'quote_adults' => $pedidoTemporal['adults'],
			$prefix . 'quote_children' => $pedidoTemporal['children'],
			$prefix . 'quote_cabins' => $pedidoTemporal['cabins-selected'],
			$prefix . 'quote_traveler' => $pedidoTemporal['traveler'],
			$prefix . 'quote_extras' => $pedidoTemporal['services'],
			$prefix . 'quote_billing_country' => $pedidoTemporal['billing-country'],
			$prefix . 'quote_billing_address' => $pedidoTemporal['billing-address'],
			$prefix . 'quote_billing_city' => $pedidoTemporal['billing-city'],
			$prefix . 'quote_billing_state' => $pedidoTemporal['billing-state'],
			$prefix . 'quote_billing_zipcode' => $pedidoTemporal['billing-zipcode'],
		)
		*/
	);
	wp_insert_post( $pedido );


	/** SEND MAIL **/
	$emailto = 'jmrpadrino@gmail.com';
	$subjectAlt = _x('Survey Application', 'op_survey');
	$headers = array(
		//'Content-Type: text/html; charset=UTF-8' . "\r\n",
		'From: Palacios Online <info@palacios-online.de>' . "\r\n",
	);

	add_filter( 'wp_mail_content_type', 'op_set_html_mail_content_type' );

	try{
		$mailtoUser = wp_mail($emailto, $subjectAlt, $html_content, $headersAlt);
		echo $mailtoUser;
	} catch (Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n"; 
	}


	remove_filter( 'wp_mail_content_type', 'op_set_html_mail_content_type' );
	wp_die();
}

add_action('wp_ajax_nopriv_po_add_quote','po_add_quote');
add_action('wp_ajax_po_add_quote','po_add_quote');

function op_set_html_mail_content_type(){
	return 'text/html';
}

function goquitongSaveStatus( $post_id ){
	if( !isset( $_POST['gquote_status_nonce'] ) || !wp_verify_nonce( $_POST['gquote_status_nonce'],'gquote_register_meta_boxes_nonce') ) 
		return;
	if ( !current_user_can( 'edit_post', $post_id ))
		return;

	update_post_meta($post_id, 'quote_status',  sanitize_text_field($_POST['quote_status']));

}
add_action('save_post', 'goquitongSaveStatus');

function gquote_register_meta_boxes() {
	add_meta_box( 'goquitong-quote-data', '<span class="dashicons dashicons-cart"></span> ' . __( 'Quote Info', 'textdomain' ), 'gquote_register_meta_boxes_callback', 'gquote' );
}
add_action( 'add_meta_boxes', 'gquote_register_meta_boxes' );

function mostrar($arr){
	echo '<pre>';
	var_dump($arr);
	echo '</pre>';
}