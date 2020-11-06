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
	global $post;
    $atts = shortcode_atts( array(
        'parent_id' => 0,
		'list_class' => 'sws-childpages',
		'sub_class' => 'sws-childpages-sub',
		'depth' => 1,
		'show' => 'siblings',
		'exclude' => "",
		'title' => 'PARENT',
		'title_class'=> 'c-block__heading-title u-theme--color--darker',
    ), $atts, 'childpages' );

	$show=$atts['show'];

    if ( $atts['parent_id']==0 ) {
        $parent_id = wp_get_post_parent_id($post); 
		if (($parent_id==0) || (!$parent_id)) { $parent_id = get_the_ID(); }
	} else {
		$parent_id=$atts['parent_id'];
    }
	
    if (!$show=="siblings") {
		$parent_id = get_the_ID();
	}
	
	$result = '';

	if (!$atts['title']=="") {
		if ($atts['title']=="PARENT") { $myTitle=get_the_title($parent_id); } else { $myTitle=$atts['title']; }
		$result="<h3 class=\"".$atts['title_class']."\">$myTitle</h3>"; 
	}
	
    $childpages = wp_list_pages( array(
        'sort_column' => 'post_title',
        'title_li' => '',
		'exclude' => $atts['exclude'],
        'child_of' => $parent_id,
		'depth' => $atts['depth'],
        'echo' => 0
    ) );

    if ( $childpages ) {
        $result .= '<ul class="'.$atts['list_class'].'">' . $childpages . '</ul>';
    }
	$result=str_replace("class=\"children\"","class='".$atts['sub_class']."'",$result);

    return $result;

}

// register shortcode
add_shortcode('sws_display_childpages', 'sws_display_childpages_func'); 






?>