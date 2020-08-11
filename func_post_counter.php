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
	
	function sws_wp_tweaks_enqueue_script2() {   
		wp_enqueue_style( 'swsPostCountStyles', plugin_dir_url(__FILE__).'inc/sws_tweaks_post_count_style.css');
		wp_enqueue_script( 'swsPostCountScript', plugin_dir_url( __FILE__ ) . 'inc/sws_tweaks_post_count_script.js',array( 'jquery' ) );
	}
	add_action('wp_enqueue_scripts', 'sws_wp_tweaks_enqueue_script2');

	// SHORTCODE FOR MOST POPULAR POSTS 
	function sws_top_posts_grid_func($atts) {
		
		global $wpdb;
		
		$a=shortcode_atts(array(
		  'display_count' => 6,
		  'title' => "Most Popular Articles",
		  'grid_width' => 2,
		  'max_img_w' => "400px",
		  'max_img_h' => "400px",
		  'parent_div_class' => '',
		  'title_class' => 'font--tertiary--1 theme--primary-text-color pad-double--top pad-half--btm',
		  'heading_class' =>'media-block__title block__title',
		  'img_class'=> '',
		  'default_img_url'=> '/aiQu9o/uploads/2018/07/favicon-denim.png',
		  'excerpt_length'=>'25'
		), $atts);
		$display_count=$a['display_count']; 
		// NOTE TO SELF: SHORTCODE_ATTS DOESN'T LIKE UPPERCASE!!!!

		ob_start();
		$post_counter=0; $grid_counter=0;
		
		$args = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key'=>'sws_post_views_count',
				'value'=>0,
				'type'=>'numeric',
				'compare'=> '>'
			)
		),
		'orderby'=>'meta_value',
		'order'=>'DESC',
		'posts_per_page'=> '-1'
		);

		$popularpost = new WP_Query( $args ); //error_log($popularpost->request);
		
		if ( $popularpost->have_posts() ) :
		
			echo "<div class=\"sws-tweaks-tpost ".$a['parent_div_class']."\">";
			echo "<h2 class=\"".$a['title_class']."\">".$a['title']."</h3>";
		
			while ( $popularpost->have_posts() ) : $popularpost->the_post();
				if (($post_counter<$display_count) && (!(get_field('hide_me')) || (get_field('hide_me')!="Yes"))) { // NOT HIDDEN
					
					//$thisPostID=$popularpost->ID; error_log($thisPostID,0);
					if ($grid_counter==0) { 
						if ($post_counter>1) { echo "</div>"; }
						echo "<div class=\"sws-tweaks-tpost-row\">";
					}
					
					if (get_the_post_thumbnail_url()) { 
						$featured_img = get_the_post_thumbnail_url(); 
						$featured_img_alt = get_post_meta(get_post_thumbnail_id(),'_wp_attachment_image_alt',true);
					} else { $featured_img=$a['default_img_url']; $featured_img_alt="Site Logo"; }
					
					echo "<div class=\"sws-tweaks-tpost-column\">";
					echo "<a href=\"".get_the_permalink()."\"><div class=\"sws-tweaks-tpost-img-div\" id='sws-tweaks-tpost-$post_counter' ".$a['img_class']."\" style=\"background: url($featured_img);\"><span class='sws-alt-txt'>$featured_img_alt</span></div></a>";
					echo "<h3 class=\"sws-tweaks-tpost-heading ".$a['heading_class']."\"><a href=\"".get_the_permalink()."\">".get_the_title()."</a></h3>";
					echo "<p>".wp_trim_words(get_the_excerpt(),$a['excerpt_length'],'...')."</p>";
					echo "</div>";
					
					$post_counter++; 
					$grid_counter++;
					if ($grid_counter==$a['grid_width']) { $grid_counter=0; }
					
				} 
			endwhile;
			
			echo "</div></div>";	
		endif;

		return ob_get_clean();
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