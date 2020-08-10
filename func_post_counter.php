<?php

// TO ACTIVAGE, PASTE THIS CODE INSIDE SINGLE POST LOOP
// sws_tweaks_set_post_views(get_the_ID());

// ON BY DEFAULT
if ((!(isset($optVals['post_counter']))) || ($optVals['post_counter']=="on")) {
	//error_log("counter on!",0);
	
	function sws_tweaks_track_post_views ($post_id) {
		//error_log("TRACK",0);
		//if ( !is_single() ) return;
		if ( empty ( $post_id) ) {
			global $post;
			$post_id = $post->ID;    
		}
		sws_tweaks_set_post_views($post_id);
	}
	add_action( 'wp_head', 'sws_tweaks_track_post_views');


	function sws_tweaks_set_post_views($postID) {
		//error_log("SET",0);
		$count_key = 'sws_post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			$count = 0;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
		}else{
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
	//To keep the count accurate, lets get rid of prefetching
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

	function sws_tweaks_get_post_views($postID){
		$count_key = 'sws_post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '0');
			return "0 View";
		}
		return $count.' Views';
	}
	
	
	// SHORTCODE FOR MOST POPULAR POSTS 
	function sws_top_posts_grid_func($atts) {
		$a=shortcode_atts(array(
		  'display_count' => 6,
		  'test' => 'foobar'
		), $atts);
		$display_count=$a['display_count']; // NOTE TO SELF: SHORTCODE_ATTS DOESN'T LIKE UPPERCASE!!!!
		ob_start();
		$counter=0;
		$popularpost = new WP_Query( array( 'posts_per_page' => 4, 'meta_key' => 'sws_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );
		while ( $popularpost->have_posts() ) : $popularpost->the_post();
			$this_post_count=get_field('sws_post_views_count');
			if ($this_post_count) { // HAS a count
				if (($counter<$display_count) && (!$this_post_count==0)) { // greater than zero
					echo get_the_title();
					$counter++;
				} 
			}
		endwhile;
	
		ob_end_clean();
	}

	// register shortcode
	add_shortcode('sws_top_posts_grid', 'sws_top_posts_grid_func'); 

	
	
}

// SAMPLE TO SORT BY VIEW COUNT
/*

$popularpost = new WP_Query( array( 'posts_per_page' => 4, 'meta_key' => 'wpb_post_views_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );
while ( $popularpost->have_posts() ) : $popularpost->the_post();
 
the_title();
 
endwhile;

*/







?>