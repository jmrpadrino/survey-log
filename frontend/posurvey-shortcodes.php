<?php

function po_show_survey($atts){
	
	global $inputs_array; 
	
	/* user data inputs array */
	$inputs_array = array(
		array(
			'id' 			=> 'first_name',
			'name' 			=> 'first_name', 
			'type' 			=> 'text',
			'placeholder'	=> _x('First Name', 'po_survey')
		),
		array(
			'id' 			=> 'last_name',
			'name' 			=> 'last_name', 
			'type' 			=> 'text',
			'placeholder'	=> _x('Last Name', 'po_survey')
		),
		array(
			'id' 			=> 'phone',
			'name' 			=> 'phone', 
			'type' 			=> 'text',
			'placeholder'	=> _x('Phone Number', 'po_survey')
		),
		array(
			'id' 			=> 'email',
			'name' 			=> 'email', 
			'type' 			=> 'email',
			'placeholder'	=> _x('E-Mail', 'po_survey')
		)
	);
	
	$inputs_array = apply_filters('po_survey_inputs', $inputs_array);

	$a = shortcode_atts( array(
		'group' => '',
		'show-box-title' => "true",
		'box-title' => esc_html('Survey', 'po_survey'),
		'theme' => 'default'
	), $atts );
	
	$string = '<div class="po-survey-placeholder">';
	
	if ( empty($a['group']) ){
		$string .= _x('In order to show this tool you need to specify the survey group on the shortcode "group" argument. Thanks!', 'po_survey');
	}else{
		
		$args = array(
			'post_type' => 'posurvey',
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'po-survey-group',
					'field' => 'slug',
					'terms' => array( $a['group'] )
				)
			),
			'order' => 'ASC',
			'orderby' => 'menu_order'
		);
		$survey = get_posts($args);
		
		if($survey){
			$string .= '<form id="po_survey_form" role="form">';
			
			$string .= '<input type="hidden" name="survey_slug" value="'.$a['group'].'">';
			
			$string .= '<div class="po-slider-container">';
			$string .= '<div class="progress-bar"><div class="bar"></div></div>';
			$counter = 1;
			foreach ($survey as $stage){
				
				$next = $counter + 1;
				
				
				// GET METAINFO OF THE STAGE
				
				$stage_child_items = get_post_meta($stage->ID, '_po_survey_stage_meta', true);
				
				$appearance = ($counter == 1) ? 'block' : 'none';
				$string .= '<div class="po-slide" style="display: '. $appearance .';" data-slide="'.$counter.'" data-nextslide="'. $next .'">';
				$string .= '<h2 class="po-stage-title">' . $stage->post_title . '</h2>';
				
				$string .= '<div class="po-stage-items-container">';
				
				if ( $stage_child_items ){
				
					foreach($stage_child_items as $stage_child_item){
						$string .= '<input id="stage-'.$counter.'-'.$stage_child_item['id'].'" class="po-radio-control" type="radio" name="'.$stage->ID.'" value="'.$stage_child_item['title'].'">';
						$string .= '<label for="stage-'.$counter.'-'.$stage_child_item['id'].'" class="stage-item-label">';
						$string .= '<div class="stage-item">';
						$string .= '<img src="'.$stage_child_item['image_url'].'" class="stage-item-thumbnail">';
						$string .= '<h3 class="stage-item-title">' . $stage_child_item['title'] . '</h3>';
						$string .= '</div>'; //stage-item
						$string .= '</label>';
					}
				}
				$string .= '</div>'; // po-stage-item-container of loop
				
				$string .= '</div>'; // po-slide
				$counter++;
			}
			
			$string .= '<div class="po-slide" style="display: '. $appearance .';" data-slide="'.$counter++.'" data-nextslide="last">';
			
			$string .= '<div id="po_user_data" class="last-slide-container">';
			
			$string .= '<div class="last-slide-container-half">';
			$string .= '<p>Chocolate brownie dessert gingerbread tiramisu bear claw chocolate bar.</p><p>Jelly beans jelly beans oat cake. Oat cake toffee tart candy pie soufflé jelly. Lemon drops fruitcake gummi bears jelly wafer oat cake halvah. Ice cream soufflé fruitcake chocolate tart.</p><p>Cotton candy donut brownie gummi bears cotton candy muffin bear claw jelly beans halvah.</p>';
			$string .= '</div>'; // last-slide-container-half
			
			$string .= '<div class="last-slide-container-half">';
			$string .= '<h3>';
			$string .= _x('Who should receive the offers?', 'po_survey');
			$string .= '</h3>';
			
			foreach ($inputs_array as $input){
				$string .= '<div class="survey-input-placeholder">';
				$string .= '<label>'.$input['placeholder'].'</label>';
				$string .= '<input id="'.$input['id'].'" name="'.$input['name'].'" type="'.$input['type'].'" placeholder="'.$input['placeholder'].'">';
				$string .= '</div>';
			} 
			
			$string .= '<button id="send-survey" class="survey-btn" type="submit">' . esc_html('Send information', 'po_survey') . '</button>';
			
			$string .= '</div>'; // last-slide-container-half
			
			$string .= '</div>'; // last-slide-container
			
			$string .= '</div>'; // po-stage last slide
			$string .= '</div>'; // po-slider-container
			$string .= '</form>';
		}
	}
	$string .= '</div>';
	return $string;
}
add_shortcode('show-survey', 'po_show_survey');