<?php

// Template Name: WPCampus 2019: Watch

function wpc_2019_enable_watch_videos() {
	if ( function_exists( 'wpcampus_network_enable' ) ) {
		wpcampus_network_enable( 'videos' );
	}
}
//add_action( 'get_header', 'wpc_2019_enable_watch_videos' );

function wpc_2019_print_sponsor_pantheon() {
	?>
	<div class="wpc-sponsors sponsors-president">
		<div class="wpc-sponsor">
			<div class="wpc-sponsor-logo">
				<h3 id="pantheon"><a href="https://pantheon.io/"><img class="alignnone wp-image-555 size-medium" src="/wp-content/uploads/sites/11/2019/05/Pantheon.png" alt="Pantheon" srcset="/wp-content/uploads/sites/11/2019/05/Pantheon.png 800w, https://2019.wpcampus.org/wp-content/uploads/sites/11/2019/05/Pantheon-768x244.png 768w, https://2019.wpcampus.org/wp-content/uploads/sites/11/2019/05/Pantheon-1200x381.png 1200w" sizes="(max-width: 800px) 100vw, 800px"></a></h3>
			</div>
			<div class="wpc-sponsor-text"><a href="https://pantheon.io/">Pantheon</a> is a website operations platform for Drupal and WordPress, running more than 200,000 sites in the cloud and serving over 10 billion pageviews a month. Pantheon’s multitenant, container-based platform enables educational institutions to manage all of their websites from a single dashboard. Customers include Arizona State University, Cornell, Harvard, Yale, and Penn. For more information, visit <a href="https://pantheon.io/edu">pantheon.io/edu</a>.</div>
		</div>
	</div>
	<?php
}
//add_action( 'wpc_add_after_content', 'wpc_2019_print_sponsor_pantheon' );

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

get_template_part( 'index' );
