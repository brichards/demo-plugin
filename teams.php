<?php
/**
 * Plugin Name:     Team Profiles
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          Brian Richards
 * Author URI:      YOUR SITE HERE
 * Text Domain:     teams
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Teams
 */

include_once 'post-types/team.php';

function team_flush_permalinks() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'team_flush_permalinks' );
register_deactivation_hook( __FILE__, 'team_flush_permalinks' );

function team_details_output( $content ) {

	if ( 'team' !== get_post_type() ) {
		return $content;
	}

	$position = get_post_meta( get_the_ID(), 'team_position', true );
	$join_year = get_post_meta( get_the_ID(), 'team_start_date', true );

	$output = sprintf( __( '<p><strong>Position:</strong> %s</p>', 'teams' ), esc_html( $position ) );
	$output .= sprintf( __( '<p><strong>Start Date:</strong> %s</p>', 'teams' ), esc_html( $join_year ) );

	return $output . $content;
}
add_filter( 'the_content', 'team_details_output' );
