<?php 

function sws_tweaks_get_default_options() {
    $options = array(
        'show_server_name' => 'off',
		'fix_its_fontpath' => 'off',
		'fix_pw_reset_msg' => 'on',
		'disable_newUser_notice' => 'on',
		'email_banning' => 'off',
		'delete_never_logged_in' => 'off',
		'disable_pwChange_notice' => 'on',
		'disable_xmlrpc' => 'on',
		'screen_grav_forms' => 'off',
		'screen_form_ids' => '',
		'login_logo' => 'FILE.png'
    );
    return $options;
}

function sws_tweaks_options_init() {
    $my_options = get_option( 'sws_wp_tweaks_options' );
 
    // Are our options saved in the DB?
    if ( false === $my_options ) {
        // If not, we'll save our default options
        $my_options = sws_tweaks_get_default_options();
        add_option( 'sws_wp_tweaks_options', $my_options );
    }
}
 
// Initialize Theme options
add_action( 'after_setup_theme', 'sws_tweaks_options_init' );


// ENQUEUE UPLOADER JS
function sws_tweaks_uploader_enqueue() {
	if( empty( $_GET['page'] ) || "sws_wp_tweaks" !== $_GET['page'] ) { return; }
	wp_enqueue_media();
	wp_register_script('media-uploader', plugins_url('inc/upload.js' , __FILE__ ), array('jquery'));
	wp_enqueue_script('media-uploader');
}
add_action('admin_enqueue_scripts', 'sws_tweaks_uploader_enqueue');


// ADD TO PLUGIN ENTRY
function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="' . esc_url( get_admin_url(null, 'options-general.php?page=sws_wp_tweaks') ) . '">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );

// ADD TO SETTINGS
function sws_wp_tweaks_register_options_page() {
  add_options_page('Sharon\'s WordPress Tweaks', 'SWS WP Tweaks', 'manage_options', 'sws_wp_tweaks', 'sws_wp_tweaks_options_page_html');
}
add_action('admin_menu', 'sws_wp_tweaks_register_options_page');



// OPTIONS PAGE CODE
function sws_wp_tweaks_register_settings() {

	 // register a new setting 
	 register_setting( 'sws_wp_tweaks', 'sws_wp_tweaks_options' );
	 
	 // register a new section 
	 add_settings_section(
	 'sws_wp_tweaks_section_developers',
	 __( 'Customize your options:', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_section_developers_cb',
	 'sws_wp_tweaks'
	 );

	 // register a new field 
	 add_settings_field(
	 'show_server_name', 
	 __( 'Show server name*', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'show_server_name',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );
	 
	 // register a new field 
	 add_settings_field(
	 'fix_its_fontpath', 
	 __( 'iThemes Security Font Path Bug', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'fix_its_fontpath',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

	 // register a new field 
	 add_settings_field(
	 'fix_pw_reset_msg', 
	 __( 'Fix Password Reset link', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'fix_pw_reset_msg',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

	 // register a new field 
	 add_settings_field(
	 'disable_newUser_notice', 
	 __( 'Disable notifications of new users', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'disable_newUser_notice',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

	 // register a new field 
	 add_settings_field(
	 'email_banning', 
	 __( 'Enable email ban lists', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'email_banning',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

	 // register a new field 
	 add_settings_field(
	 'delete_never_logged_in', 
	 __( 'Remove users who have not logged in<br />within 60 days of registration**', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'delete_never_logged_in',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );	 
	 
	 // register a new field in 
	 add_settings_field(
	 'disable_pwChange_notice', 
	 __( 'Disable notifications of password changes', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'disable_pwChange_notice',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );
	 
	 
	 // register a new field 
	 add_settings_field(
	 'disable_xmlrpc', 
	 __( 'Disable XML-RPC', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'disable_xmlrpc',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );
	 
	 
	 // register a new field 
	 add_settings_field(
	 'screen_grav_forms', 
	 __( 'Gravity Form Screener', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_main_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'screen_grav_forms',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );
	 	 
	 // register a new field 
	 add_settings_field(
	 'screen_form_ids', 
	 __( 'Form ID(s) ~ separate with comma', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_field_txt_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'screen_form_ids',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );

	 // register a new field 
	 add_settings_field(
	 'login_logo', 
	 __( 'Choose logo that appears on login page', 'sws_wp_tweaks' ),
	 'sws_wp_tweaks_fileupload_cb',
	 'sws_wp_tweaks',
	 'sws_wp_tweaks_section_developers',
	 [
	 'label_for' => 'login_logo',
	 'class' => 'sws_wp_tweaks_row',
	 'sws_wp_tweaks_custom_data' => 'custom',
	 ]
	 );
	 

}
add_action( 'admin_init', 'sws_wp_tweaks_register_settings' );

/*function sws_tweaks_settings_header_text() {
    ?>
        <p><?php _e( 'Manage Logo Options for WpTuts Theme.', 'sws_wp_tweaks' ); ?></p>
    <?php
}
 
function sws_tweaks_setting_logo() {
    $wptuts_options = get_option( 'sws_wp_tweaks_options' );
    ?>
        <input type="text" id="logo_url" name="sws_wp_tweaks_options[logo]" value="<?php echo esc_url( $wptuts_options['logo'] ); ?>" />
        <input id="upload_logo_button" type="button" class="button-primary" value="<?php _e( 'Upload Logo', 'sws_wp_tweaks' ); ?>" />
        <span class="description"><?php _e('Upload an image for the banner.', 'sws_wp_tweaks' ); ?></span>
    <?php
}*/


// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function sws_wp_tweaks_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( '* Appends near-translucent <span> to content of Home & About pages', 'sws_wp_tweaks' ); ?><br />** Requires <em>Simple Login Log</em></p>
 <?php
}

function sws_tweaks_handle_file_upload($option)
{
  if(!empty($_FILES["upload_image_button"]["tmp_name"]))
  {
    $urls = wp_handle_upload($_FILES["upload_image_button"], array('test_form' => FALSE));
    $temp = $urls["url"];
    return $temp;  
  }
 
  return $option;
}


 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function sws_wp_tweaks_field_main_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'sws_wp_tweaks_options' );
 // output the field
 ?>
 <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
 data-custom="<?php echo esc_attr( $args['sws_wp_tweaks_custom_data'] ); ?>"
 name="sws_wp_tweaks_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
 >
 <option value="on" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'on', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'ON', 'sws_wp_tweaks' ); ?>
 </option>
 <option value="off" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'off', false ) ) : ( '' ); ?>>
 <?php esc_html_e( 'OFF', 'sws_wp_tweaks' ); ?>
 </option>
 </select>
 <?php
}

function sws_wp_tweaks_fileupload_cb( $args ) {
	$options = get_option('sws_wp_tweaks_options');
	if (isset($options[$args['label_for']])) { $myVal=$options[$args['label_for']]; } else { $myVal=""; }
 ?><input id="<?php echo esc_attr( $args['label_for'] ); ?>" type="text" name="sws_wp_tweaks_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo  $myVal; ?>" data-custom="<?php echo esc_attr( $args['sws_wp_tweaks_custom_data'] ); ?>"/>
	<input id="upload_image_button" type="button" class="button-primary" value="CHOOSE LOGO" />
    <div class="sws-img-preview" style="background-image: url('<?php echo esc_url( $myVal ); ?>');">
    </div> <?php
}
function sws_wp_tweaks_field_txt_cb( $args ) {
	//error_log(print_r($args,true),0);
	$options = get_option('sws_wp_tweaks_options');
	if (isset($options[$args['label_for']])) { $myVal=$options[$args['label_for']]; } else { $myVal=""; }
	//error_log(print_r($options,true),0);
	// output the field
 ?>
 <input type='text' name="sws_wp_tweaks_options[<?php echo esc_attr( $args['label_for'] ); ?>]"  id="<?php echo esc_attr( $args['label_for'] ); ?>" value='<?php echo  $myVal; ?>'  data-custom="<?php echo esc_attr( $args['sws_wp_tweaks_custom_data'] ); ?>"><?php
}

function sws_wp_tweaks_options_page_html()
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
	 add_settings_error( 'sws_wp_tweaks_messages', 'sws_wp_tweaks_message', __( 'Settings Saved', 'sws_wp_tweaks' ), 'updated' );
	 }
	 
	 // show error/update messages
	 settings_errors( 'sws_wp_tweaks_messages' );
	 ?>
	 <style>
	 .sws_wp_tweaks_row td { 
		padding: 8px !important;
	 }
	 .sws_wp_tweaks_row th {
		 width: 40%;
		 text-align: right;
		 padding: 8px 25px 8px 25px !important;
	 }
	 .sws-img-preview { 
		height: 200px; 
		width: 200px; 
		background-size: contain;
		position: absolute;
		right:10%;
		margin-top:-200px;
	 }
	 </style>
	 <div class="wrap">
	 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	 <form action="options.php" method="post" enctype="multipart/form-data">
	 <?php
	 // output security fields for the registered setting "wporg"
	 settings_fields( 'sws_wp_tweaks' );
	 // output setting sections and their fields
	 // (sections are registered for "wporg", each field is registered to a specific section)
	 do_settings_sections( 'sws_wp_tweaks' );
	 // output save settings button
	 submit_button( 'Save Settings' );
	 ?>
	 </form>
	 </div>
	 <?php
}
?>