<?php

// Template Name: WPCampus 2019: Schedule Only

/**
 * Only have the schedule shortcode for the content.
 */
function wpc_2019_content_schedule_only( $content ) {
	return '[print_conference_schedule]';
}
add_filter( 'the_content', 'wpc_2019_content_schedule_only' );

get_template_part( 'template-content-only' );
