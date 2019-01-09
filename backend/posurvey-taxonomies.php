<?php

if ( ! function_exists( 'po_survey_group' ) ) {

// Register Custom Taxonomy
function po_survey_group() {

	$labels = array(
		'name'                       => _x( 'Stage Surveys', 'Taxonomy General Name', 'po_survey' ),
		'singular_name'              => _x( 'Stage Survey', 'Taxonomy Singular Name', 'po_survey' ),
		'menu_name'                  => __( 'Stage Surveys', 'po_survey' ),
		'all_items'                  => __( 'All Stage Surveys', 'po_survey' ),
		'parent_item'                => __( 'Parent Stage Survey', 'po_survey' ),
		'parent_item_colon'          => __( 'Parent Stage Survey:', 'po_survey' ),
		'new_item_name'              => __( 'New Stage Survey Name', 'po_survey' ),
		'add_new_item'               => __( 'Add New Stage Survey', 'po_survey' ),
		'edit_item'                  => __( 'Edit Stage Survey', 'po_survey' ),
		'update_item'                => __( 'Update Stage Survey', 'po_survey' ),
		'view_item'                  => __( 'View Stage Survey', 'po_survey' ),
		'separate_items_with_commas' => __( 'Separate Stage Surveys with commas', 'po_survey' ),
		'add_or_remove_items'        => __( 'Add or remove Stage Surveys', 'po_survey' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'po_survey' ),
		'popular_items'              => __( 'Popular Stage Surveys', 'po_survey' ),
		'search_items'               => __( 'Search Stage Surveys', 'po_survey' ),
		'not_found'                  => __( 'Not Found', 'po_survey' ),
		'no_terms'                   => __( 'No Stage Surveys', 'po_survey' ),
		'items_list'                 => __( 'Stage Surveys list', 'po_survey' ),
		'items_list_navigation'      => __( 'Stage Surveys list navigation', 'po_survey' ),
	);
	$rewrite = array(
		'slug'                       => 'survey',
		'with_front'                 => true,
		'hierarchical'               => true,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'query_var'                  => 'survey-group',
		'rewrite'                    => $rewrite,
		'show_in_rest'               => true,
		'rest_base'                  => 'survey-groups',
	);
	register_taxonomy( 'po-survey-group', array( 'posurvey' ), $args );

}
add_action( 'init', 'po_survey_group', 0 );

}