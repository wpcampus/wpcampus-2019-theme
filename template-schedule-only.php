<?php

// Template Name: WPCampus 2018: Schedule Only

/**
 * Only have the schedule shortcode for the content.
 */
function wpc_2018_content_schedule_only( $content ) {
	return '[print_conference_schedule]';
}
add_filter( 'the_content', 'wpc_2018_content_schedule_only' );

get_template_part( 'template-content-only' );
