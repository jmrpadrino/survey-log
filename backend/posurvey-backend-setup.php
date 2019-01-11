<?php 

add_action('admin_enqueue_scripts', 'po_custom_scripts');

function po_custom_scripts(){
	global $post;
	wp_register_style( 'po_survey_admin_css', POSURVEY_PLUGIN_URI . '/backend/css/po-survey-admin-style.css', false, NULL );
	wp_enqueue_style( 'po_survey_admin_css' );
	if (
		$post->post_type == 'posurvey' || 
		$_GET['post_type'] == 'posurvey'
	){
		wp_register_style( 'po_survey_icons_css', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css', false, NULL );
		wp_enqueue_style( 'po_survey_icons_css' );
		wp_enqueue_script( 'po_survey_admin_js', POSURVEY_PLUGIN_URI . '/backend/js/po-survey-admin-scripts.js', array('jquery'), NULL, true );
		wp_enqueue_media ();
		wp_register_script( 'po_ajax_script' , NULL);
		wp_localize_script( 
			'po_ajax_script', 
			'po_strings', 
			array( 
				'po_before_send' 	=> _x('Sending data', 'po_survey'), 
				'po_success' 		=> _x('Survey reordered', 'po_survey'), 
				'po_error' 			=> _x('There was an error, please try again later.', 'po_survey'), 
			) 
		);
		wp_enqueue_script( 'po_ajax_script' );

	}
}


function op_columns_head($defaults){
	if ( $_GET['post_type'] == 'posurvey' ){
		$defaults['stageorder'] = _x('Stage Order','po_survey');
		$defaults['stageitems'] = _x('Stage Items','po_survey');
	}
    if ( $_GET['post_type'] == 'pos_apply' ){
		$defaults['applicationstatus'] = _x('Status','po_survey');
	}
	return $defaults;
}

function op_columns_content($column_name, $post_ID){


	if ( $column_name == 'stageorder'){
		echo get_post_field('menu_order', $post_ID);
	}

	if ( $column_name == 'stageitems'){
		$stage_metas = get_post_meta($post_ID, '_po_survey_stage_meta', true);
		echo count($stage_metas) . ' items';
	}

    if ( $column_name == 'applicationstatus'){
        $statues = array(
            _x('On Hold', 'op_survey'),
            _x('In Progress', 'op_survey'),
            _x('Ready', 'op_survey'),
            _x('Pending', 'op_survey'),
            _x('Closed', 'op_survey')
        );
		$stage_metas = get_post_meta($post_ID, '_po_survey_status', true);
		echo '<span class="status-tag status-'.$stage_metas.'">'.$statues[$stage_metas].'</span>';
	}

}

add_filter('manage_posts_columns', 'op_columns_head');
add_action('manage_posts_custom_column', 'op_columns_content', 10, 2);



function survey_stage_ordering(){
	$po_terms = get_terms( array(
		'taxonomy' => 'po-survey-group',
		'hide_empty' => false,
	) );

    if($po_terms)
    {
?>
<div class="wrap">
	<h1><?php _e( 'Survey stage reordering', 'po_survey' ) ?></h1>
	<hr class="wp-header-end">
	<form method="get" action="<?php echo admin_url() ?>edit.php">
		<input type="hidden" name="post_type" value="posurvey">
		<input type="hidden" name="page" value="po-ordering-survey">
		<label for="survey_group"><?php _e( 'Survey stage groups', 'po_survey' )?></label>
		<?php 
		//			echo '<pre>';
		//			var_dump($po_terms);
		//			echo '</pre>';
		?>
		<select name="survey_group">
			<option><?php _e('Select group', 'po_survey') ?></option>
			<?php 
		foreach($po_terms as $po_term){
			echo '<option value="'. $po_term->term_id .'">' . $po_term->name . ' ('. $po_term->count .')</option>';
		}
			?>
		</select>
		<button type="submit" class="button button-primary"><?php _e( 'Get stages', 'po_survey' ) ?></button>
		<span class="po-message-placeholder"><?php _e('Drag and drop item of the list and then click on the button below.', 'po_survey') ?></span>
	</form>
	<hr />
	<?php
				if(isset( $_GET['survey_group'] ) && !empty( $_GET['survey_group'] )){
					$args = array(
						'post_type' => 'posurvey',
						'posts_per_page' => -1,
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'po-survey-group',
								'terms' => array( $_GET['survey_group'] )
							)
						),
						'orderby' => 'menu_order',
						'order' => 'ASC'
					);
					$stages = get_posts( $args );
	?>
	<ul id="po-make-clonable">
		<?php foreach($stages as $stage){ ?> 
		<?php
			$items = count(get_post_meta($stage->ID, '_po_survey_stage_meta', true));
		?>
		<li class="stage-item" data-postid="<?php echo $stage->ID ?>"><i class="fas fa-ellipsis-v"></i> <?php echo $stage->post_title ?> - <?php echo $items .' '. esc_html('items', 'po_survey') ?> -<a href="<?php echo get_edit_post_link($stage->ID) ?>"><?php _e('Edit', 'po_survey') ?></a></li>
		<?php } ?>
	</ul>
	<hr />
	<button id="po_reorder_btn" class="button button-primary" type="button"><?php _e('Set Stage order', 'po_survey') ?></button>
	<?php } ?>
</div>
<?php
    }
}
function po_survey_admin_menu(){
	$args = array(
        'post_type' => 'pos_apply',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_po_survey_status',
                'value' => 0,
                'compare' => 'LIKE'
            )
        )
    );
    $quotes = get_posts($args);
	
	add_submenu_page( 
		'edit.php?post_type=posurvey',
		__( 'Survey stage ordering', 'po_survey' ), 
		__( 'Survey stage ordering', 'po_survey' ), 
		'manage_options', 
		'po-ordering-survey',
		'survey_stage_ordering'
	);
	$menu_title = sprintf( __( 'Applications %s', 'po_survey' ), ' <span class="gquote-not">' . count($quotes) . '</span>'); 
	add_submenu_page( 'edit.php?post_type=posurvey', $menu_title, $menu_title, 'manage_options', 'edit.php?post_type=pos_apply');
}
add_action( 'admin_menu', 'po_survey_admin_menu' );

/* AJAX calls */
function po_reorder_survey(){
//	echo '<pre>';
//	var_dump($_POST['newOrderArray']);
//	echo '</pre>';
	global $wpdb;
	
	foreach ($_POST['newOrderArray'] as $posttoorder){ 
		
		$wpdb->update( 
			$wpdb->posts, 
			array( 
				'menu_order' => $posttoorder['menuorder'],	// string
			), 
			array( 'ID' => $posttoorder['post_id'] )
		);
		
	}
	wp_die();
}
add_action('wp_ajax_po_reorder_survey','po_reorder_survey');
