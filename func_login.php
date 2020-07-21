<?php


// LOGIN FUNCTIONS
function sws_tweaks_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url('/cc-dev/wp-content/uploads/2015/03/favicon.png');
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
	list( $email_user, $email_domain ) = explode( '@', $user_email );
	$banArr=array();
	
	$ext=array();
	
	$valid=1;
	
	
	while ($valid==1) {
		if ( in_array( $email_domain,$banArr) ) {
			$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );
			$valid=0;
		} 
	
		foreach ($searchArr as $tmp) { 
					if (!(strpos($email_domain,$tmp)===false)) { 
						$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );
						$valid=0;
						break;
					}
		} 
		
		$tmp2=substr($email_domain,0,strpos($email_domain,"."));
		if (strlen($tmp2)>0) { 
		
			if (ctype_digit($tmp2)) { 
				$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );	
				$valid=0;
				break;			
			}		
		}
		
		foreach ($ext as $var) {
			if (strpos($email_domain,".".$var)) {
				$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );	
				$valid=0;
				break;			
			}			
		}

		$valid=0;
	}

	return $errors;
}

// add_filter( 'registration_errors', 'disable_email_domain', 10, 3 );





?>