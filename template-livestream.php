<?php

// Template Name: WPCampus 2019: Livestream

// Make sure the schedule knows to load.
conference_schedule()->load_schedule();

/**
 * Filter the <body> class to add the watch room slug.
 */
function wpc_2019_filter_watch_body_class( $class ) {

	// Are we in a particular room?
	$room_slug = get_query_var( 'room' );

	//if ( in_array( $room_slug, array( 1, 2, 3, 'auditorium' ) ) ) {
	$class[] = 'page-watch-room';

	if ( ! empty( $room_slug ) ) {
		$class[] = "page-watch-{$room_slug}";
	}

	return $class;
}
add_action( 'body_class', 'wpc_2019_filter_watch_body_class' );

function wpc_2019_filter_page_title( $page_title ) {

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
add_filter( 'wpcampus_page_title', 'wpc_2019_filter_page_title' );

// Add livestream URLs to the page
function wpc_2019_add_livestream_template( $content ) {

	//$has_started = true; //has_wpcampus_2019_started();
	//$in_progress = true; //is_wpcampus_2019_in_progress();
	//$is_over = false; //is_wpcampus_2019_over();

	// Are we in a particular room?
	/*$room_slug = get_query_var( 'room' );
	if ( empty( $room_slug ) ) {
		$room_post = false;
	} else {

		// Get the location post for the room.
		$room_post = wpcampus_network()->get_post_by_name( $room_slug, 'locations' );
	}*/

	ob_start();

	?>
	<div class="wpc-watch-container">
		<div class="wpc-livestream-sponsors">
			<div class="wpc-livestream-sponsor"><a href="https://campuspress.com/"><img class="aligncenter size-medium wp-image-709" src="/wp-content/uploads/sites/8/2019/04/CampusPress-logo.png" alt="CampusPress" /></a></div>
			<div class="wpc-livestream-sponsor"><a href="https://pantheon.io/"><img class="aligncenter size-medium wp-image-709" src="/wp-content/uploads/sites/8/2019/04/Pantheon-logo.png" alt="Pantheon" /></a></div>
		</div>
		<?php

		/*if ( ! $is_over ) :
			?>
			<a class="button royal-blue bigger expand" href="/wp-content/themes/wpcampus-2019-theme/assets/files/wpcampus-2019.ics">Add to your calendar</a>
			<?php
		endif;*/

		/*if ( ! $has_started ) :
			?>
			<div class="panel light-royal-blue center">If you can’t join us in St. Louis, gather with other WordPress users on your campus and plan a viewing party.
				<strong>Create your own WPCampus experience!</strong></div>
			<?php
		endif;

		if ( ! $has_started ) :
			?>
			<div class="panel">
				<p>All sessions from this year's event will be recorded and live streamed for free, sponsored by the amazing teams at <a href="https://campuspress.com/">CampusPress</a> and <a href="https://pantheon.io/">Pantheon</a>.</p>
				<p>Return to this page during the event to watch, and follow along with, the event live from WPCampus 2019 in Portland, Oregon. <em>Workshops will not be streamed.</em></p>
			</div>
			<?php
		endif;*/

		// Add the template.
		/*if ( ! empty( $room_post->ID ) ) :

			$room_title = get_the_title( $room_post->ID );

			$crowdcast_id = get_post_meta( $room_post->ID, 'crowdcast_id', true );

			if ( ! empty( $crowdcast_id ) ) :

				// Get the crowdcast embed source.
				$crowdcast_embed_src = 'https://www.crowdcast.io/e/' . $crowdcast_id;

				// Add crowdcast query args.
				$crowdcast_embed_src = add_query_arg( array( 'navlinks' => 'false', 'embed' => 'true' ), $crowdcast_embed_src );

				*//*
				 * @TODO:
				 * - Add event title.
				 * - Be able to refresh embed view.
				 *//*

				?>
				<div class="panel light-royal-blue center"><a href="http://docs.crowdcast.io/faq/what-are-the-compatible-devices" target="_blank">View crowdcast's list of compatible devices</a> to make sure your browser is able to view the event.</div>
				<div id="wpc-crowdcast">
					<iframe title="<?php printf( __( 'Join crowdcast stream for WPCampus 2019 Room %d', 'wpcampus-2019' ), $room_title ); ?>" width="100%" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true" src="<?php echo $crowdcast_embed_src; ?>" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allow="microphone; camera;"></iframe>
				</div>
				<?php
			endif;
		endif;*/

		//if ( ! $is_over ) :
			?>
			<p>The recordings from WPCampus 2019 are being processed and will be online as soon as possible.</p>
			<div class="panel">
				<p><strong>Share your experience:</strong> Follow along, and share your own experience, on social media using <a href="https://twitter.com/search?q=wpcampus&amp;src=typd">our #WPCampus hashtag</a>.</p>
				<p><strong>If you're in our Slack channel:</strong> <a href="https://wordcampus.slack.com/messages/CAY1FV6DQ">#attendees-wpc2019</a> is the channel for attendees in person to chat. <a href="https://wordcampus.slack.com/messages/CBCJH9HPS">#discuss-wpc2019</a> is the channel for folks in attendance, or watching via livestream, to discuss sessions.</p>
				<p><strong>If you're NOT in our Slack channel:</strong> <a href="https://wpcampus.org/get-involved/">Join the WPCampus Slack channel</a></p>
			</div>
			<?php
		//endif;

		// Print the schedule.
		/*$shortcode = '[print_conference_schedule';

		if ( empty( $room_post->ID ) ) {
			$shortcode .= ' watch="1"';
		}

		$shortcode .= ']';

		$schedule = do_shortcode( $shortcode ); //location="640"
		if ( ! empty( $schedule ) ) :
			?>
			<div id="wpc-crowdcast-schedule">
				<?php *//*<h2 id="wpc-next-header"><?php _e( 'Up Next', 'wpcampus-online' ); ?></h2>*//* ?>
				<?php echo $schedule; ?>
			</div>
			<?php
		endif;*/

		?>
	</div>
	<?php

	/*$pre_content = '<div class="wpc-watch-container">
		<div class="panel center"><strong>This conference is a free event.</strong><br>WPCampus Online uses <a href="https://www.crowdcast.io/" target="_blank" rel="noopener">crowdcast</a> to stream live, virtual sessions. <a href="http://docs.crowdcast.io/faq/what-are-the-compatible-devices">View crowdcast\'s list of compatible devices</a>. To join the conference, simply visit one of our two streaming rooms: <a href="/watch/1/">Room 1</a> and <a href="/watch/2/">Room 2</a>. If you want to chat about the event, <a href="https://wpcampus.org/get-involved/">join us in Slack</a>. If you want to interact on social media, use <a href="https://twitter.com/search?q=wpcampus">the #WPCampus hashtag</a>.</div>
	</div>';*//*

	$pre_content = wpc_online_get_watch_pre_message();*/

	return $content . ob_get_clean();
}
add_filter( 'the_content', 'wpc_2019_add_livestream_template' );

get_template_part( 'index' );












function wpc_online_begin_watch_container() {
	echo '<div class="wpc-watch-container">';
}
//add_action( 'wpc_add_before_page_title', 'wpc_online_begin_watch_container' );

function wpc_online_end_watch_container() {

	// Are we in a particular room?
	/*$room_slug = get_query_var( 'room' );
	if ( in_array( $room_slug, array( 1, 2 ) ) ) {

		$other_room = ( 1 == $room_slug ? 2 : 1 );
		echo '<a id="wpc-watch-other-room" class="button" href="/watch/' . $other_room . '/">' . sprintf( __( 'Join Room %d', 'wpcampus-online' ), $other_room ) . '</a>';

	}*/

	// Need to close .wpc-watch-container
	echo '</div>';

}
//add_action( 'wpc_add_after_page_title', 'wpc_online_end_watch_container' );

function wpc_online_print_watch_pre_message() {
	?>
	<div class="wpc-watch-container">
		<p><strong>WPCampus 2019 has come to an end.</strong> If you want to chat about the event, <a href="https://wpcampus.org/get-involved/">join us in Slack</a> in our #wpconline channel. If you want to interact on social media, use <a href="https://twitter.com/search?q=wpcampus">the #WPCampus hashtag</a>.</p>
		<div class="panel light-royal-blue center"><strong><a href="/thank-you/">Thank you</a></strong> to all of our wonderful volunteers, speakers, and attendees for their time and beautiful brains.</div>
	</div>
	<?php
}
