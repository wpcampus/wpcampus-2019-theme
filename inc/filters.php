<?php

/**
 * Filter the nav menu item CSS.
 *
 * @param   $classes - array - The CSS classes that are applied to the menu item's `<li>` element.
 * @param   $item - WP_Post - The current menu item.
 * @param   $args- stdClass - An object of wp_nav_menu() arguments.
 * @param   $depth - int - Depth of menu item. Used for padding.
 * @return  array - the filtered classes array.
 */
function wpc_2018_filter_nav_menu_css_class( $classes, $item, $args, $depth ) {

	if ( 'Schedule' == $item->title ) {

		$current_schedule = is_singular( 'schedule' ) || is_page( 'feedback' ) || is_page( 'speakers' );

		if ( $current_schedule ) {
			$classes[] = 'current-menu-item';
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'wpc_2018_filter_nav_menu_css_class', 100, 4 );
