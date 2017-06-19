<?php

function team_init() {
	register_post_type( 'team', array(
		'labels'            => array(
			'name'                => __( 'Team Members', 'teams' ),
			'singular_name'       => __( 'Team Member', 'teams' ),
			'all_items'           => __( 'All Team Members', 'teams' ),
			'new_item'            => __( 'New Team Member', 'teams' ),
			'add_new'             => __( 'Add New', 'teams' ),
			'add_new_item'        => __( 'Add New Team Member', 'teams' ),
			'edit_item'           => __( 'Edit Team Member', 'teams' ),
			'view_item'           => __( 'View Team Member', 'teams' ),
			'search_items'        => __( 'Search Team Members', 'teams' ),
			'not_found'           => __( 'No Team Members found', 'teams' ),
			'not_found_in_trash'  => __( 'No Team Members found in trash', 'teams' ),
			'parent_item_colon'   => __( 'Parent Team Member', 'teams' ),
			'menu_name'           => __( 'Team Members', 'teams' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor', 'revisions', 'thumbnail' ),
		'has_archive'       => true,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-groups',
		'show_in_rest'      => true,
		'rest_base'         => 'team',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'team_init' );

function team_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['team'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Team Member updated. <a target="_blank" href="%s">View Team Member</a>', 'teams'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'teams'),
		3 => __('Custom field deleted.', 'teams'),
		4 => __('Team Member updated.', 'teams'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Team Member restored to revision from %s', 'teams'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Team Member published. <a href="%s">View Team Member</a>', 'teams'), esc_url( $permalink ) ),
		7 => __('Team Member saved.', 'teams'),
		8 => sprintf( __('Team Member submitted. <a target="_blank" href="%s">Preview Team Member</a>', 'teams'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Team Member scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Team Member</a>', 'teams'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Team Member draft updated. <a target="_blank" href="%s">Preview Team Member</a>', 'teams'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'team_updated_messages' );

/**
 * Define the metabox and field configurations.
 */
function team_details_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'team_';

    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box( array(
        'id'            => 'team_metabox',
        'title'         => __( 'Team Details', 'teams' ),
        'object_types'  => array( 'team', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Position', 'teams' ),
        'desc'       => __( 'Official Title', 'teams' ),
        'id'         => $prefix . 'position',
        'type'       => 'text',
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Start Date', 'teams' ),
        'desc'       => __( 'First year on the team.', 'teams' ),
        'id'         => $prefix . 'start_date',
        'type'       => 'text',
    ) );
}
add_action( 'cmb2_admin_init', 'team_details_metabox' );

function team_title_placeholder( $placeholder ) {
	if ( 'team' === get_current_screen()->post_type ) {
		$placeholder = __( 'Member Name', 'teams' );
	}

	return $placeholder;
}
add_filter( 'enter_title_here', 'team_title_placeholder' );
