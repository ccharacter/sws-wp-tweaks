<?php

// NOTE TO SELF: in iThemes Security->WordPress Tweaks, make sure login errors are showing

// LOGIN FUNCTIONS
function sws_tweaks_login_logo() { 
	$optVals = get_option( 'sws_wp_tweaks_options' );
	$logo_url=$optVals['login_logo'];
?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url('<?php echo $logo_url; ?>');
        height:200px !important;
        width:200px !important;
        background-size: contain;
        padding-bottom: -25px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'sws_tweaks_login_logo' );

function sws_tweaks_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'sws_tweaks_login_logo_url' );

function sws_tweaks_login_head() {
remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'sws_tweaks_login_head');




// BAN DOMAINS
function sws_tweaks_email_banning ( $errors, $sanitized_user_login, $user_email ) {

	$extensions="https://docs.google.com/spreadsheets/d/e/2PACX-1vTMtU2xcD85-JGUC7YSyqA8gJSRE0S2jchGwx7pTEBM0Ctbwdtyfy6K0SGWc_3OxX7CRjeNyXYllAtQ/pub?output=csv";

	$keywords="https://docs.google.com/spreadsheets/d/e/2PACX-1vTbMPp5ITCS8-jUzN4bECUu5st9BmQ-9mZEXrqQpW3O0tcHKrNbvAk_-0l5ecoqgHV3Wka3uwnFegkG/pub?output=csv"; // LOGIN BANNING

	$extArr=sws_tweaks_csvToArray($extensions,',',"N");
	$keyArr=sws_tweaks_csvToArray($keywords,',',"N");
	//error_log(print_r($extArr,true),0);
	
	list( $email_user, $email_domain ) = explode( '@', $user_email );
	list($email_domain, $email_extension) = explode(".",$email_domain);
	//error_log($user_email."|".$email_user."|".$email_domain."|".$email_extension,0);


	$valid=1;
	
	foreach ($extArr as $key=>$test) { 
		//error_log($key."|".$test,0); 
		if ($email_extension==$test) { //error_log($test,0);
		$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );
		$valid=0;
		}
	} 

	foreach ($keyArr as $key=>$test) { 
		if (!(strpos($user_email,$test)===false)) { //error_log($test,0); 
			$errors->add( 'email_error', __( '<strong>ERROR</strong>: Email address not allowed.', 'my_domain' ) );
			$valid=0;
			break;
		}
	} 
		
	// Disable registration for testing purposes
	// $errors->add( 'email_error', __( '<strong>ERROR</strong>: Test error.', 'my_domain' ) );
	return $errors;
}

add_filter( 'registration_errors', 'sws_tweaks_email_banning', 10, 3 );

?>