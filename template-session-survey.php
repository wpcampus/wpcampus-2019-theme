<?php

// Template Name: WPCampus 2019: Session Feedback

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

function wpc_2019_filter_feedback_content( $content ) {
	return $content;
}
//add_filter( 'the_content', 'wpc_2019_filter_feedback_content' );

get_template_part( 'index' );
