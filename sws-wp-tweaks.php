<?php

/**
 * Plugin Name:       SWS WordPress Tweaks
 * Plugin URI:        https://ccharacter.com/custom-plugins/sws-wp-tweaks/
 * Description:       Various tweaks that I'll want on most or all of my WordPress sites
 * Version:           1.7
 * Requires at least: 5.2
 * Requires PHP:      5.5
 * Author:            Sharon Stromberg
 * Author URI:        https://ccharacter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sws-wp-tweaks
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path(__FILE__).'inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/ccharacter/sws-wp-tweaks/master/plugin.json',
	__FILE__,
	'sws-wp-tweaks'
);

// FOR NON-MULTI-SITES
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

// FIX BUG IN iThemes Security path
if ( ! function_exists( 'it_icon_font_admin_enueue_scripts' ) ) {
        function it_icon_font_admin_enueue_scripts() {
		        $url=plugins_url();

                if ( version_compare( $GLOBALS['wp_version'], '3.7.10', '>=' ) ) {
                        $dir = str_replace( '\\', '/', dirname( __FILE__ ) );

                        $content_dir = rtrim( str_replace( '\\', '/', WP_CONTENT_DIR ), '/' );
                        $abspath = rtrim( str_replace( '\\', '/', ABSPATH ), '/' );

                        if ( empty( $content_dir ) || ( 0 === strpos( $dir, $content_dir ) ) ) {
                                $url = WP_CONTENT_URL . str_replace( '\\', '/', preg_replace( '/^' . preg_quote( $content_dir, '/' ) . '/', '', $dir ) );
                        } else if ( empty( $abspath ) || ( 0 === strpos( $dir, $abspath ) ) ) {
                                $url = get_option( 'siteurl' ) . str_replace( '\\', '/', preg_replace( '/^' . preg_quote( $abspath, '/' ) . '/', '', $dir ) );
                        }

                        if ( empty( $url ) ) {
                                $dir = realpath( $dir );

                                if ( empty( $content_dir ) || ( 0 === strpos( $dir, $content_dir ) ) ) {
                                        $url = WP_CONTENT_URL . str_replace( '\\', '/', preg_replace( '/^' . preg_quote( $content_dir, '/' ) . '/', '', $dir ) );
                                } else if ( empty( $abspath ) || ( 0 === strpos( $dir, $abspath ) ) ) {
                                        $url = get_option( 'siteurl' ) . str_replace( '\\', '/', preg_replace( '/^' . preg_quote( $abspath, '/' ) . '/', '', $dir ) );
                                }
                        }

                        if ( is_ssl() ) {
                                $url = preg_replace( '|^http://|', 'https://', $url );
                        } else {
                                $url = preg_replace( '|^https://|', 'http://', $url );
                        }


                        wp_enqueue_style( 'ithemes-icon-font', "$url/icon-fonts.css" );
                }
        }
        add_action( 'admin_enqueue_scripts', 'it_icon_font_admin_enueue_scripts' );
}


// FIX  BUG IN PASSWORD RESET MSG
add_filter("retrieve_password_message", "sws_custom_password_reset", 99, 4);

function sws_custom_password_reset($message, $key, $user_login, $user_data )    {
	$message = "Someone has requested a password reset for the following account:
" . sprintf(__('%s'), $user_data->user_email) . "
If this was a mistake, just ignore this email and nothing will happen.
To reset your password, visit the following address:
" . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

  	return $message;

}

// DISABLE NEW USER NOTIFICATIONS
if ( ! function_exists( 'wp_new_user_notification' ) ) :
	function wp_new_user_notification( $user_id ) {}
endif;


// DISABLE PASSWORD CHANGE NOTIFICATIONS
if ( !function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification() {}
}

// Disable use XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );


// ENQUEUE FONTAWESOME
function sws_enqueue_fontawesome(){
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
}
add_action('wp_enqueue_scripts','sws_enqueue_fontawesome');

?>
