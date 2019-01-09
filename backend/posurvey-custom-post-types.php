<?php

if ( ! function_exists('po_survey_cpt') ) {

	// Register Custom Post Type
	function po_survey_cpt() {

		$labels = array(
			'name'                  => _x( 'Surveys Stages', 'Post Type General Name', 'po_survey' ),
			'singular_name'         => _x( 'Survey Stage', 'Post Type Singular Name', 'po_survey' ),
			'menu_name'             => __( 'Surveys', 'po_survey' ),
			'name_admin_bar'        => __( 'Survey Stage', 'po_survey' ),
			'archives'              => __( 'Survey Stage Archives', 'po_survey' ),
			'attributes'            => __( 'Survey Stage Attributes', 'po_survey' ),
			'parent_item_colon'     => __( 'Parent Survey Stage:', 'po_survey' ),
			'all_items'             => __( 'All Surveys Stages', 'po_survey' ),
			'add_new_item'          => __( 'Add New Survey Stage', 'po_survey' ),
			'add_new'               => __( 'Add New Stage', 'po_survey' ),
			'new_item'              => __( 'New Survey Stage', 'po_survey' ),
			'edit_item'             => __( 'Edit Survey Stage', 'po_survey' ),
			'update_item'           => __( 'Update Survey Stage', 'po_survey' ),
			'view_item'             => __( 'View Survey Stage', 'po_survey' ),
			'view_items'            => __( 'View Surveys Stages', 'po_survey' ),
			'search_items'          => __( 'Search Survey Stage', 'po_survey' ),
			'not_found'             => __( 'Not found', 'po_survey' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'po_survey' ),
			'featured_image'        => __( 'Featured Image', 'po_survey' ),
			'set_featured_image'    => __( 'Set featured image', 'po_survey' ),
			'remove_featured_image' => __( 'Remove featured image', 'po_survey' ),
			'use_featured_image'    => __( 'Use as featured image', 'po_survey' ),
			'insert_into_item'      => __( 'Insert into survey Stage', 'po_survey' ),
			'uploaded_to_this_item' => __( 'Uploaded to this survey Stage', 'po_survey' ),
			'items_list'            => __( 'Surveys Stages list', 'po_survey' ),
			'items_list_navigation' => __( 'Surveys Stages list navigation', 'po_survey' ),
			'filter_items_list'     => __( 'Filter Surveys Stages list', 'po_survey' ),
		);
		$rewrite = array(
			'slug'                  => 'survey',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Survey', 'po_survey' ),
			'description'           => __( 'Survey for Palacios Online', 'po_survey' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'page-attributes' ),
			'taxonomies'            => array( '' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon' 			=> POSURVEY_PLUGIN_URI . '/backend/img/po-survey-icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'query_var'             => 'survey',
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rest_base'             => 'posurvey',
		);
		
		register_post_type( 'posurvey', $args );
		
	}
	add_action( 'init', 'po_survey_cpt', 0 );

}

if ( ! function_exists('po_survey_applications') ) {

// Register Custom Post Type
function po_survey_applications() {

	$labels = array(
		'name'                  => _x( 'Survey applications', 'Post Type General Name', 'po_survey' ),
		'singular_name'         => _x( 'Survey application', 'Post Type Singular Name', 'po_survey' ),
		'menu_name'             => __( 'Survey applictions', 'po_survey' ),
		'name_admin_bar'        => __( 'Survey application', 'po_survey' ),
		'archives'              => __( 'Item Archives', 'po_survey' ),
		'attributes'            => __( 'Item Attributes', 'po_survey' ),
		'parent_item_colon'     => __( 'Parent Item:', 'po_survey' ),
		'all_items'             => __( 'Survey applications', 'po_survey' ),
		'add_new_item'          => __( 'Add New Item', 'po_survey' ),
		'add_new'               => __( 'Add New', 'po_survey' ),
		'new_item'              => __( 'New Item', 'po_survey' ),
		'edit_item'             => __( 'Edit Item', 'po_survey' ),
		'update_item'           => __( 'Update Item', 'po_survey' ),
		'view_item'             => __( 'View Item', 'po_survey' ),
		'view_items'            => __( 'View Items', 'po_survey' ),
		'search_items'          => __( 'Search Item', 'po_survey' ),
		'not_found'             => __( 'Not found', 'po_survey' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'po_survey' ),
		'featured_image'        => __( 'Featured Image', 'po_survey' ),
		'set_featured_image'    => __( 'Set featured image', 'po_survey' ),
		'remove_featured_image' => __( 'Remove featured image', 'po_survey' ),
		'use_featured_image'    => __( 'Use as featured image', 'po_survey' ),
		'insert_into_item'      => __( 'Insert into item', 'po_survey' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'po_survey' ),
		'items_list'            => __( 'Items list', 'po_survey' ),
		'items_list_navigation' => __( 'Items list navigation', 'po_survey' ),
		'filter_items_list'     => __( 'Filter items list', 'po_survey' ),
	);
	$capabilities = array(
		'edit_post'             => 'edit_post',
		'read_post'             => 'read_post',
		'delete_post'           => 'delete_post',
		'edit_posts'            => 'edit_posts',
		'edit_others_posts'     => 'edit_others_posts',
		'read_private_posts'    => 'read_private_posts',
	);
	$args = array(
		'label'                 => __( 'Survey application', 'po_survey' ),
		'description'           => __( 'Survey applications list', 'po_survey' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 100,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//'capabilities'          => $capabilities,
		'show_in_rest'          => true,
		'rest_base'             => 'survey_appplies',
	);
	register_post_type( 'pos_apply', $args );

}
add_action( 'init', 'po_survey_applications', 0 );

}