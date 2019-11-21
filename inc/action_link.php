<?php

/**
 * Add plugin action links.
 *
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param  array  $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */
function sws_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php' ) ) . '">' . __( 'Settings', 'sws-wp-tweaks' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sws_action_links' );

?>