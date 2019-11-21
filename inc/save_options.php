<?php

function sws_tweaks_init() {
 // register a new setting for "sws-tweaks" page
 register_setting( 'sws-tweaks', 'sws-tweaks_options' );
 
 // register a new section in the "sws-tweaks" page
 add_settings_section(
 'sws-tweaks_section_developers',
 __( 'The Matrix has you.', 'sws-tweaks' ),
 'sws-tweaks_section_developers_cb',
 'sws-tweaks'
 );
 
 // register a new field in the "wporg_section_developers" section, inside the "wporg" page
 add_settings_field(
 'sws-tweaks_field_pill', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'Pill', 'sws-tweaks' ),
 'sws-tweaks_field_pill_cb',
 'sws-tweaks',
 'sws-tweaks_section_developers',
 [
 'label_for' => 'sws-tweaks_field_pill',
 'class' => 'sws-tweaks_row',
 'sws-tweaks_custom_data' => 'custom',
 ]
 );
}
 
/**
 * register our wporg_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'sws_tweaks_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function sws_tweaks_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'sws-tweaks' ); ?></p>
 <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function sws_tweaks_field_pill_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sws-tweaks_options' );
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['sws-tweaks_custom_data_custom_data'] ); ?>"
 name="sws-tweaks_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'red pill', 'sws-tweaks' ); ?>
 </option>
 <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'blue pill', 'sws-tweaks' ); ?>
 </option>
 </select>
 <p class="description">
 <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'sws-tweaks' ); ?>
 </p>
 <p class="description">
 <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'sws-tweaks' ); ?>
 </p>
 <?php
}
 
/**
 * top level menu
 */
function wporg_options_page() {
 // add top level menu page
 add_menu_page(
 'WPOrg',
 'WPOrg Options',
 'manage_options',
 'wporg',
 'wporg_options_page_html'
 );
}
 
/**
 * register our wporg_options_page to the admin_menu action hook
 */
// add_action( 'admin_menu', 'wporg_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function sws_tweaks_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'sws-tweaks_messages', 'sws-tweaks_message', __( 'Settings Saved', 'sws-tweaks' ), 'updated' );
 }
 
 // show error/update messages
 settings_errors( 'sws-tweaks_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "wporg"
 settings_fields( 'sws-tweaks' );
 // output setting sections and their fields
 // (sections are registered for "wporg", each field is registered to a specific section)
 do_settings_sections( 'sws-tweaks' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}