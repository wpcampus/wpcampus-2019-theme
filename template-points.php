<?php

// Template Name: WPCampus 2019: Sponsors

/**
 * @TODO:
 * - setup redirect like for feedback and watch, where redirects if no info.
 */

function wpc_2019_filter_feedback_page_title( $page_title ) {

	// Are we in a particular room?
	$room_slug = get_query_var( 'room' );
	if ( ! empty( $room_slug ) ) {

		// Get the location post for the room.
		$room_post = wpcampus_network()->get_post_by_name( $room_slug, 'locations' );

		if ( ! empty( $room_post->ID ) && 'locations' == $room_post->post_type && 'publish' == $room_post->post_status ) {

			// Get the room title.
			$title = get_the_title( $room_post->ID );
			if ( ! empty( $title ) ) {
				return sprintf( __( 'Watch %s', 'wpcampus-2019' ), $title );
			}
		}
	}

	return $page_title;
}
//add_filter( 'wpcampus_page_title', 'wpc_2019_filter_feedback_page_title' );

function wpc_2019_filter_sponsor_content( $content ) {

	if ( ! is_page( 'sponsor' ) ) {
		return $content;
	}

	$sponsor_id = get_query_var( 'sponsor' );

	if ( ! $sponsor_id ) {
		// TODO: error? redirect?
	}

	$sponsor = get_post( $sponsor_id );

	if ( ! $sponsor ) {
		// TODO: error? redirect?
	}

	$content = "<p><strong>Sponsor:</strong> " . get_the_title( $sponsor_id ) . '</p>' . $content;

	return $content;
}
add_filter( 'the_content', 'wpc_2019_filter_sponsor_content' );

get_template_part( 'index' );
