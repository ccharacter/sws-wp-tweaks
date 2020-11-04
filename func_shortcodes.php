<?php


function sws_override_sidebar_func() {
	global $post;
	global $active_sidebar;
	global $entry_hide_sidebar;
	global $theme_hide_sidebar;
	
	echo $active_sidebar."|".$entry_hide_sidebar."|".$theme_hide_sidebar;
	
	
}
// register shortcode
add_shortcode('sws_override_sidebar', 'sws_override_sidebar_func'); 



// SHORTCODE FOR Displaying CHILD/SIBLING PAGES
function sws_display_childpages_func($atts) {

    $atts = shortcode_atts( array(
        'parent' => 'our-ministry',
		'list_class' => 'sws-childpages',
		'sub_class' => 'sws-childpages-sub',
		'depth' => 1,
		'show' => 'siblings',
    ), $atts, 'childpages' );

    $parent_id = false;
    if ( $atts['parent'] ) {
        $parent = get_page_by_path( $atts['parent'] ); 
        if ( $parent ) {
            $parent_id = $parent->ID;
        }
    } else { // if no parent passed, then show siblings of current page
        if ($atts['show']=="siblings") {
			$parent_id=wp_get_post_parent_id();	
		} else { 
			$parent_id = get_the_ID();
		}
	}

    $result = '';
    if ( ! $parent_id ) {  // don't waste time getting pages, if we couldn't get parent page
         return $result;
    }

    $childpages = wp_list_pages( array(
        'sort_column' => 'post_title',
        'title_li' => '',
        'child_of' => $parent_id,
		'depth' => $atts['depth'],
        'echo' => 0
    ) );

    if ( $childpages ) {
        $result = '<ul class="'.$atts['list_class'].'">' . $childpages . '</ul>';
    }
	$result=str_replace("class=\"children\"","class='".$a['sub_class']."'",$result);

    return $result;

}

// register shortcode
add_shortcode('sws_display_childpages', 'sws_display_childpages_func'); 






?>