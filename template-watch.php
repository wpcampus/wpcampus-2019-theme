<?php

// Template Name: WPCampus 2019: Watch

function wpc_2019_enable_watch_videos() {
	if ( function_exists( 'wpcampus_network_enable' ) ) {
		wpcampus_network_enable( 'videos' );
	}
}
add_action( 'get_header', 'wpc_2019_enable_watch_videos' );

/**
 * Prints watch videos page.
 */
function wpc_2019_print_watch_videos_content( $content ) {
	if ( function_exists( 'wpcampus_print_watch_videos' ) ) {
		wpcampus_print_watch_videos( 'wpc-videos', array(
			'playlist'     => 'wpcampus-2019',
			'show_event'   => false,
			'show_filters' => false,
		));
	}
}
add_action( 'wpc_add_after_content', 'wpc_2019_print_watch_videos_content' );

get_template_part( 'index' );
