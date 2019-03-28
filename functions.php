<?php

$includes_path = STYLESHEETPATH . '/inc/';
require_once $includes_path . 'filters.php';
require_once $includes_path . 'theme-parts.php';

/**
 * Setup the theme:
 *
 * - Load the textdomain.
 * - Register the navigation menus.
 */
function wpcampus_2019_setup_theme() {

	// Load the textdomain.
	load_theme_textdomain( 'wpcampus-2019', get_stylesheet_directory() . '/languages' );

	// Register the nav menus.
	register_nav_menus( array(
		'primary1' => __( 'Primary Menu 1', 'wpcampus-2019' ),
		'primary2' => __( 'Primary Menu 2', 'wpcampus-2019' ),
	));
}
add_action( 'after_setup_theme', 'wpcampus_2019_setup_theme', 10 );

/**
 * Modify theme components.
 *
 * Runs in "wp" action since this is first
 * hook available after WP object is setup
 * and we can use conditional tags.
 */
function wpcampus_2019_setup_theme_parts() {

	// Don't print MailChimp signup on the application.
	if ( is_page( 'call-for-speakers/application' ) ) {
		remove_action( 'wpc_add_after_content', 'wpcampus_print_mailchimp_signup' );
	}

	// Remove breadcrumbs on livestream page.
	if ( is_page_template( 'template-livestream.php' ) ) {
		remove_action( 'wpc_add_before_content', 'wpcampus_parent_print_breadcrumbs', 15 );
	}
}
add_action( 'wp', 'wpcampus_2019_setup_theme_parts', 10 );

/**
 * Make sure the Open Sans
 * font weights we need are added.
 *
 * They're loaded in the parent theme.
 *
 * TODO: What fonts do we need?
 */
function wpcampus_2019_load_open_sans_weights( $weights ) {
	return array_merge( $weights, array( 300, 400, 600 ) );
}
add_filter( 'wpcampus_open_sans_font_weights', 'wpcampus_2019_load_open_sans_weights' );

/**
 * Setup/enqueue styles and scripts for theme.
 *
 * TODO: Setup
 */
function wpcampus_2019_enqueue_theme() {

	// Set the directories.
	$wpcampus_dir     = trailingslashit( get_stylesheet_directory_uri() );
	$wpcampus_dir_css = $wpcampus_dir . 'assets/build/css/';
	$wpcampus_dir_js  = $wpcampus_dir . 'assets/build/js/';

	// Enqueue the base styles and script.
	wp_enqueue_style( 'wpcampus-2019', $wpcampus_dir_css . 'styles.min.css', array( 'wpcampus-parent' ), null );
	//wp_enqueue_script( 'wpcampus-2019', $wpcampus_dir_js . 'wpc-2019.min.js', array( 'jquery' ), null );

	// For the livestream page
	//if ( is_page_template( 'template-livestream.php' ) && is_wpcampus_2019_in_progress() ) {

}
add_action( 'wp_enqueue_scripts', 'wpcampus_2019_enqueue_theme', 10 );

function wpcampus_2019_get_current_time() {
	$timezone = get_option( 'timezone_string' ) ?: 'UTC';
	$time = 'now'; //'2019-07-13 16:40:00';
	return new DateTime( $time, new DateTimeZone( $timezone ) );
}

/**
 * Get the call for speaker deadline.
 */
function wpcampus_2019_get_call_speaker_deadline() {
	$deadline = get_option( 'wpc_2019_call_for_speakers_deadline' );
	if ( ! empty( $deadline ) && false !== strtotime( $deadline ) ) {
		$timezone = get_option( 'timezone_string' ) ?: 'UTC';
		return new DateTime( $deadline, new DateTimeZone( $timezone ) );
	}
	return false;
}

/**
 * Get the call for speaker deadline.
 */
function is_wpcampus_2019_in_progress() {
	if ( has_wpcampus_2019_started() && ! is_wpcampus_2019_over() ) {
		return true;
	}
	return false;
}

/**
 * Get the call for speaker deadline.
 */
function has_wpcampus_2019_started() {
	$first_day = get_option( 'wpc_2019_first_day' );
	if ( ! empty( $first_day ) && false !== strtotime( $first_day ) ) {

		$timezone = get_option( 'timezone_string' ) ?: 'UTC';
		$timezone = new DateTimeZone( $timezone );

		$first_day = new DateTime( $first_day, $timezone );

		$now = wpcampus_2019_get_current_time();

		if ( $now >= $first_day ) {
			return true;
		}
	}
	return false;
}

/**
 * Get the call for speaker deadline.
 */
function is_wpcampus_2019_over() {
	$last_day = get_option( 'wpc_2019_last_day' );
	if ( ! empty( $last_day ) && false !== strtotime( $last_day ) ) {

		$timezone = get_option( 'timezone_string' ) ?: 'UTC';
		$timezone = new DateTimeZone( $timezone );

		$last_day = new DateTime( $last_day, $timezone );

		$now = wpcampus_2019_get_current_time();

		if ( $now > $last_day ) {
			return true;
		}
	}
	return false;
}

/**
 * Add custom query vars.
 */
function wpcampus_2019_add_query_vars( $vars ) {
	$vars[] = 'room';
	$vars[] = 'session';
	return $vars;
}
add_filter( 'query_vars', 'wpcampus_2019_add_query_vars' );

/**
 * Make sure the watch page is valid.
 */
function wpcampus_2019_watch_redirect() {

	// Only for the watch page.
	if ( ! is_page( 'watch' ) ) {
		return;
	}

	// Get room ID.
	$room = get_query_var( 'room' );

	// Main watch page.
	if ( empty( $room ) ) {
		return;
	}

	$watch_path = "/watch/";

	// Is a location watch page?
	$location = wpcampus_network()->get_post_by_name( $room, 'locations' );
	if ( ! empty( $location->ID ) && 'locations' == $location->post_type && 'publish' == $location->post_status ) {

		// Determine if a watch page.
		$is_watch_page = get_post_meta( $location->ID, 'is_wpc_watch_page', true );

		// If valid, stay.
		if ( $is_watch_page ) {
			return;
		}

		// Otherwise, go to main watch page.
		wp_safe_redirect( $watch_path, 301 );
		exit;
	}

	// If this far, check if we have a session slug.
	$session = wpcampus_network()->get_post_by_name( $room, 'schedule' );

	// If not a valid session, go to main watch page.
	if ( empty( $session->ID ) || 'schedule' != $session->post_type || 'publish' != $session->post_status ) {
		wp_safe_redirect( $watch_path, 301 );
		exit;
	}

	// Get the session's location watch page.
	$location_id = get_post_meta( $session->ID, 'conf_sch_event_location', true );
	if ( $location_id > 0 ) {

		// Go to watch page for the location.
		$location_post_name = get_post_field( 'post_name', $location_id );
		if ( ! empty( $location_post_name ) ) {
			wp_safe_redirect( $watch_path . $location_post_name, 301 );
			exit;
		}
	}

	// Go to watch page by default.
	wp_safe_redirect( $watch_path, 301 );
	exit;
}
add_action( 'wp', 'wpcampus_2019_watch_redirect' );

/**
 * Add rewrite rules.
 */
function wpcampus_2019_add_rewrite_rules() {

	// For full/only schedule.
	add_rewrite_rule( '^schedule\/full\/?', 'index.php?pagename=schedule&wpc_template=schedule-only', 'top' );
	add_rewrite_rule( '^schedule\/only\/?', 'index.php?pagename=schedule&wpc_template=schedule-only', 'top' );

	// For watch pages.
	add_rewrite_rule( '^watch\/([^\/]+)\/?', 'index.php?pagename=watch&room=$matches[1]', 'top' );

	// For session feedback.
	add_rewrite_rule( '^feedback\/confirmation\/?', 'index.php?pagename=feedback/confirmation', 'top');
	add_rewrite_rule( '^feedback\/([^\/]+)\/?', 'index.php?pagename=feedback&session=$matches[1]', 'top');

}
add_action( 'init', 'wpcampus_2019_add_rewrite_rules' );

/**
 * Filter the livestream URL.
 */
function wpcampus_2019_filter_livestream_url( $livestream_url, $post ) {

	// Only for certain event types.
	$event_type = get_post_meta( $post->ID, 'event_type', true );
	if ( ! in_array( $event_type, array( 'session', 'lightning-talk' ) ) ) {
		return $livestream_url;
	}

	// Watch page is watch/{location post name}.
	/*$location_id = get_post_meta( $post->ID, 'conf_sch_event_location', true );
	if ( $location_id > 0 ) {

		$location_post_name = get_post_field( 'post_name', $location_id );
		if ( ! empty( $location_post_name ) ) {
			return "/watch/{$location_post_name}/";
		}
	}*/

	// Watch page is watch/{session post name}.
	$session_post_name = get_post_field( 'post_name', $post->ID );
	if ( ! empty( $session_post_name ) ) {
		return "/watch/{$session_post_name}/";
	}

	return '';
}
add_filter( 'conf_sch_livestream_url', 'wpcampus_2019_filter_livestream_url', 100, 2 );

/**
 * Filter the feedback URL.
 *
 * @access  public
 * @param   $feedback_url - string - the default feedback URL.
 * @param   $post - object - the post information.
 * @return  string - the filtered feedback URL.
 */
function wpcampus_2019_filter_feedback_url( $feedback_url, $post ) {

	// Only for certain event types.
	$event_type = get_post_meta( $post->ID, 'event_type', true );
	if ( ! in_array( $event_type, array( 'session', 'lightning-talk' ) ) ) {
		return $feedback_url;
	}

	// Survey page is watch/{session post name}.
	$session_post_name = get_post_field( 'post_name', $post->ID );
	if ( ! empty( $session_post_name ) ) {
		return "/feedback/{$session_post_name}/";
	}

	return '';
}
add_filter( 'conf_sch_feedback_url', 'wpcampus_2019_filter_feedback_url', 100, 2 );

/**
 *
 */
function wpcampus_2019_get_login_form( $args = array() ) {

	$defaults = array(
		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'form_id' => 'loginform',
		'label_username' => __( 'Username or Email Address' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in' => __( 'Log In' ),
		'id_username' => 'user_login',
		'id_password' => 'user_pass',
		'id_remember' => 'rememberme',
		'id_submit' => 'wp-submit',
		'remember' => true,
		'value_username' => '',
		'value_remember' => false,
		'wpc_ajax' => true,
	);

	$args = wp_parse_args( $args, $defaults );

	// Make sure we don't echo.
	$args['echo'] = false;

	return '<div class="wpcampus-login-form wpcampus-login-ajax">' . wp_login_form( $args ) . '</div>';
}

function wpcampus_2019_print_login_form( $args = array() ) {
	echo wpcampus_2019_get_login_form( $args );
}
