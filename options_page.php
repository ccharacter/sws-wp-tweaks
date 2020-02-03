<?php 
// OPTIONS PAGE CODE
function sws_wp_tweaks_register_settings() {

	 // register a new setting for "wporg" page
	 register_setting( 'sws_wp_tweaks', 'sws_wp_tweaks_options' );
	 
	 // register a new section in the "wporg" page
	 add_settings_section(
	 'sws_wp_tweaks_section_developers',
	 __( 'The Matrix has you.', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_section_developers_cb',
	 'sws_wp_tweaks'
	 );
	 
	 // register a new field in the "wporg_section_developers" section, inside the "wporg" page
	 add_settings_field(
	 'sws_wp_tweaks_field_pill', // as of WP 4.6 this value is used only internally
	 // use $args' label_for to populate the id inside the callback
	 __( 'Pill', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_pill_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'sws_wp_tweaks_field_pill',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

}
add_action( 'admin_init', 'sws_wp_tweaks_register_settings' );


// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wporg_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
 <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function sws_wp_tweaks_field_pill_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sws_wp_tweaks_options' );
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['sws_wp_tweaks_custom_data'] ); ?>"
 name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="red" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'red pill', 'sws_wp_tweaks' ); ?>
 </option>
 <option value="blue" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'blue pill', 'sws_wp_tweaks' ); ?>
 </option>
 </select>
 <p class="description">
 <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'sws_wp_tweaks' ); ?>
 </p>
 <p class="description">
 <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'sws_wp_tweaks' ); ?>
 </p>
 <?php
}



function sws_wp_tweaks_options_page()
{

	 // check user capabilities
	 if ( ! current_user_can( 'manage_options' ) ) {
	 return;
	 }
	 
	 // add error/update messages
	 
	 // check if the user have submitted the settings
	 // wordpress will add the "settings-updated" $_GET parameter to the url
	 if ( isset( $_GET['settings-updated'] ) ) {
	 // add settings saved message with the class of "updated"
	 add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
	 }
	 
	 // show error/update messages
	 settings_errors( 'wporg_messages' );
	 ?>
	 <div class="wrap">
	 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	 <form action="options.php" method="post">
	 <?php
	 // output security fields for the registered setting "wporg"
	 settings_fields( 'wporg' );
	 // output setting sections and their fields
	 // (sections are registered for "wporg", each field is registered to a specific section)
	 do_settings_sections( 'wporg' );
	 // output save settings button
	 submit_button( 'Save Settings' );
	 ?>
	 </form>
	 </div>
	 <?php







}


?>