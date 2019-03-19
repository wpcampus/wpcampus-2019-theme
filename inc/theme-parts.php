<?php

/**
 * Print menu items, used for our main menu.
 */
function wpcampus_2018_print_menu( $menu_id, $menu_label ) {

	$menu = wp_nav_menu( array(
		'echo'           => false,
		'theme_location' => 'primary' . $menu_id,
		'container'      => null,
		'items_wrap'     => '<ul>%3$s</ul>'
	));

	if ( ! empty( $menu ) ) {
		?>
		<nav class="wpc-menu wpc-menu-<?php echo $menu_id; ?>" aria-label="<?php esc_attr_e( $menu_label ); ?>">
			<?php echo $menu; ?>
		</nav>
		<?php
	}
}

function wpcampus_2018_print_header() {

	$wpcampus_dir = trailingslashit( get_stylesheet_directory_uri() );

	?>
	<div class="wpc-container" role="navigation">
		<div class="wpc-logo">
			<a href="/">
				<?php

				if ( is_front_page() ) :
					?>
					<h1 class="for-screen-reader"><?php printf( __( '%1$s 2018 Conference: Where %2$s Meets Higher Education', 'wpcampus-2018' ), 'WPCampus', 'WordPress' ); ?></h1>
					<?php
				endif;

				?>
				<img alt="<?php printf( esc_attr__( 'The %1$s 2018 conference, where %2$s meets higher education, will take place July 12-14, 2018 in St. Louis, Missouri.', 'wpcampus-2018' ), 'WPCampus', 'WordPress' ); ?>" src="<?php echo $wpcampus_dir; ?>assets/images/wpcampus-2018-logo.png">
			</a>
		</div>
		<button class="wpc-toggle-menu" data-toggle="wpc-header" aria-label="<?php _e( 'Toggle menu', 'wpcampus-2018' ); ?>">
			<div class="wpc-toggle-bar"></div>
			<div class="wpc-open-menu-label"><?php _e( 'View menu', 'wpcampus-2018' ); ?></div>
		</button>
		<?php

		wpcampus_2018_print_menu( 1, __( 'First part of primary menu', 'wpcampus-2018' ) );
		wpcampus_2018_print_menu( 2, __( 'Second part of primary menu', 'wpcampus-2018' ) );

		if ( function_exists( 'wpcampus_print_social_media_icons' ) ) {
			wpcampus_print_social_media_icons();
		}

		?>
	</div>
	<?php
}
add_action( 'wpc_add_to_header', 'wpcampus_2018_print_header', 10 );

/**
 * Add header action button(s).
 *
 * TO DO:
 * - Update so message changes as it gets closer
 * and hides/changes after deadline ends.
 */
function wpcampus_2018_print_header_action() {

	//$deadline = wpcampus_2018_get_call_speaker_deadline();
	//$deadline_format = 'F j, Y';

	//if ( $deadline ) :
		?>
		<div id="wpc-header-actions" role="complementary">
			<button class="wpc-header-action wpc-subscribe-open" title="<?php printf( esc_attr__( 'Subscribe to the %s newsletter', 'wpcampus-2018' ), "WPCampus" ); ?>"><?php printf( __( '%1$sJoin the %2$s mailing list%3$s to receive email updates about the WPCampus community and conferences.', 'wpcampus-2018' ), '<span class="underline">', 'WPCampus', '</span>' ); ?></button>
		</div>
		<?php
	//endif;
}
add_action( 'wpc_add_before_body', 'wpcampus_2018_print_header_action', 1 );

function wpcampus_2018_filter_breadcrumbs( $crumbs ) {

	$is_feedback = is_page( 'feedback' );

	if ( is_singular( 'schedule' ) || is_page( 'speakers' ) || $is_feedback ) {

		$new_crumbs = array();

		foreach( $crumbs as $key => $crumb ) {

			if ( ! is_numeric( $key ) ) {
				$new_crumbs[ $key ] = $crumb;
			} else {
				$new_crumbs[] = $crumb;
			}

			// Add schedule items after home.
			if ( 'home' === $key ) {

				$new_crumbs[] = array(
					'url'   => '/schedule/',
					'label' => __( 'Schedule', 'wpcampus-2018' ),
				);

				// Add session page.
				if ( $is_feedback ) {

					// Get the post.
					$session = get_query_var( 'session' );

					// Get post object.
					if ( is_numeric( $session ) ) {
						$session_post = get_post( $session );
					} else {
						$session_post = wpcampus_network()->get_post_by_name( $session, 'schedule' );
					}

					// Make sure its a valid session.
					if ( ! empty( $session_post->ID ) ) {

						// Make sure its a session.
						$event_type = get_post_meta( $session_post->ID, 'event_type', true );
						if ( 'session' == $event_type ) {

							$new_crumbs[] = array(
								'url'   => get_permalink( $session_post->ID ),
								'label' => get_the_title( $session_post->ID ),
							);
						}
					}
				}
			}
		}

		// Change feedback label.
		if ( $is_feedback ) {
			$new_crumbs['current']['label'] = __( 'Feedback', 'wpcampus-2018' );
		}

		return $new_crumbs;
	}

	// If only 2 crumbs, remove the crumbs.
	/*if ( count( $crumbs ) < 3 ) {
		$crumbs = array();
	}*/

	return $crumbs;
}
add_filter( 'wpcampus_breadcrumbs', 'wpcampus_2018_filter_breadcrumbs' );
