<?php

// NOTE TO SELF: in iThemes Security->WordPress Tweaks, make sure login errors are showing

// LOGIN FUNCTIONS
// OFF BY DEFAULT
if ((isset($optVals['login_logo'])) && (strlen($optVals['login_logo'])>0))  {
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
}


function sws_tweaks_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'sws_tweaks_login_logo_url' );

function sws_tweaks_login_head() {
remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'sws_tweaks_login_head');

function sws_tweaks_ck_old_banned () {
	$extensions="https://docs.google.com/spreadsheets/d/e/2PACX-1vTMtU2xcD85-JGUC7YSyqA8gJSRE0S2jchGwx7pTEBM0Ctbwdtyfy6K0SGWc_3OxX7CRjeNyXYllAtQ/pub?output=csv";

	$keywords="https://docs.google.com/spreadsheets/d/e/2PACX-1vTbMPp5ITCS8-jUzN4bECUu5st9BmQ-9mZEXrqQpW3O0tcHKrNbvAk_-0l5ecoqgHV3Wka3uwnFegkG/pub?output=csv"; // LOGIN BANNING

	$extArr=sws_tweaks_csvToArray($extensions,',',"N");
	$keyArr=sws_tweaks_csvToArray($keywords,',',"N");
	
	$args=array("role"=>"member");
	$members = get_users();
	foreach ($members as $user) { 
		$user_email=$user->user_email; $thisID=$user->ID;
		list( $email_user, $email_domain ) = explode( '@', $user_email );
		list($email_domain, $email_extension) = explode(".",$email_domain);
		
		foreach ($extArr as $key=>$test) { 
			if ($email_extension==$test) { //error_log($user_email,0);
				error_log("DELETING: $user_email",0); 
				if (!(wp_delete_user($thisID))) { error_log("Could not delete: $user_email",0); }
			}
		}
		 

		foreach ($keyArr as $key=>$test) { 
			if (!(strpos($user_email,$test)===false)) { 
				error_log("DELETING: $user_email",0); 
				if (!(wp_delete_user($thisID))) { error_log("Could not delete: $user_email",0); }
			}
		} 

	}
}

// Create sws_removed_users table
function sws_removed_users_table() {
  global $wpdb;

  $sws_tweaks_db = 1.0;

  $table_name = $wpdb->prefix . 'sws_removed_users';

  $charset_collate = $wpdb->get_charset_collate();

  $sql = "
    CREATE TABLE IF NOT EXISTS $table_name (
      `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `user_status` int(11) NOT NULL DEFAULT 0,
      `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
      `spam` tinyint(2) NOT NULL DEFAULT 0,
      `deleted` tinyint(2) NOT NULL DEFAULT 0,
      PRIMARY KEY (`ID`)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );
    add_option( 'sws_tweaks_db', $sws_tweaks_db);
}

function record_removed_user($row){
	global $wpdb;
	$table_name = $wpdb->prefix . 'sws_removed_users';
	$id=$row['ID'];
	error_log(print_r($row,true),0);
	$query = "INSERT INTO $table_name select * from {$wpdb->prefix}.users where `ID`=$id";
	error_log($query,0);

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
  //dbDelta( $query );
}

function sws_ck_logged() {
	sws_removed_users_table();
	
	global $wpdb;
	$tableName=$wpdb->prefix."simple_login_log"; //error_log($tableName,0);
	$pref=$wpdb->prefix;
	$today=date("Y-m-d", strtotime("-60 days"));
	if($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
		$query="SELECT `ID`,`user_registered` FROM {$wpdb->prefix}users where `ID` not in (select uid from $tableName) and `user_registered`<'$today'"; //error_log($query,0);
		$delArr=$wpdb->get_results($query, ARRAY_A);
		//error_log(print_r($delArr,true),0);
		foreach ($delArr as $row) { 
			$thisID=$row['ID']; 
			if (!(user_can($thisID,'publish_posts'))) { error_log("DELETING: $thisID",0);
			// Insert into removed_users
			record_removed_user($row);


				//	if (!(wp_delete_user($thisID))) { error_log("Could not delete: $thisID",0); }
			}
		}
	} else { 
		error_log("Simple Login Log does not exist.",0); 
	}
}



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
		if ($email_extension==$test) { error_log($test,0);
		$errors->add( 'email_error', __( '<strong>ERROR</strong>: Domain not allowed.', 'my_domain' ) );
		$valid=0;
		}
	} 

	foreach ($keyArr as $key=>$test) { 
		if (!(strpos($user_email,$test)===false)) { error_log($test,0); 
			$errors->add( 'email_error', __( '<strong>ERROR</strong>: Email address not allowed.', 'my_domain' ) );
			$valid=0;
			break;
		}
	} 
		
	// Disable registration for testing purposes
	// $errors->add( 'email_error', __( '<strong>ERROR</strong>: Test error.', 'my_domain' ) );
	return $errors;
}

?>