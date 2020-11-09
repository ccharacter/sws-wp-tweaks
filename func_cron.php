<?php


// SET UP CRON JOBS
add_action('wp','sws_tweaks_cron_activation');

//hook that function onto our scheduled event:
add_action ('sws_tweaks_cron','sws_tweaks_cron_daily_function');

register_activation_hook( __FILE__, 'sws_tweaks_cron_activation' );
function sws_tweaks_cron_activation() {
	sws_tweaks_cron_deactivation();
    if ( ! wp_next_scheduled( 'sws_tweaks_cron' ) ) {
        wp_schedule_event( strtotime("03:00:00"), 'daily', 'sws_tweaks_cron' );
    }
}

// CLEAN UP CRON JOB IF DEACTIVATED
//register_deactivation_hook( __FILE__, 'sws_tweaks_cron_deactivation' );

// SET UP CRON SCHEDULE
add_filter( 'cron_schedules', 'sws_add_cron_interval' );
function sws_add_cron_interval( $schedules ) {
    $schedules['everyminute'] = array(
            'interval'  => 60, // time in seconds
            'display'   => 'Every Minute'
    );
    return $schedules;
}


function sws_tweaks_cron_deactivation() {
    wp_clear_scheduled_hook( 'sws_tweaks_cron' );
}


function sws_tweaks_cron_daily_function() { 
	global $optVals;
	//error_log("RUNNING CRON JOB",0);
	// OFF BY DEFAULT 
	if ((isset($optVals['delete_never_logged_in'])) && ($optVals['delete_never_logged_in']=="on")) {
		// REMOVE USERS WHO HAVE NOT LOGGED IN WITHIN 60 DAYS OF REGISTRATION
		//error_log("not logged in",0);
		sws_ck_logged();
	}

	// OFF BY DEFAULT 
	if ((isset($optVals['email_banning'])) && ($optVals['email_banning']=="on")) {
		// REMOVE ANY EXISTING BANNED 
		//error_log("old banned",0);
		sws_tweaks_ck_old_banned();
	}
}

?>