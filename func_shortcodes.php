<?php

function sws_list_by_cat_func($atts)   {
	
	$a=shortcode_atts(array(
	  'num_posts' => -1,
	  'category' => "uncategorized",
	  'post_type' => 'post',
	  'format' => 'list',
	  ), $atts);
	
    $args = array( 'posts_per_page' => $a['num_posts'], 'category_name' => $a['category']);                  
    $myQuery = new WP_Query( $args );
	$content="<ul>";

    while($myQuery->have_posts()) : 
        $myQuery->the_post();
        $link = get_permalink();
        $title = get_the_title();
        $date = get_the_date();                              
	

    switch($a['format']) {
		case("list"):
			$content.="<li><a href='.$link.' target="_top">'.$title.' ('.$date. ')</a></li>";
			break;
		default:
		$content .= '<div class="sws-list-posts">';
        $content .= '<h3><a href='.$link.' target="_top">'.$title.' / '.$date. '</a></h3>';
        $content .= '<p class="excerpt">' .get_the_excerpt(). '</p>';
        $content .= '</div>';
			break;
	}
	if ($a['format']=="list") { $content.="</ul>"; }
	
	endwhile;

	return $content;
}

add_shortcode('sws_list_by_cat', 'sws_list_by_cat_func' );

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
        $result .= '<style>.current_page_item a { font-weight: bold; text-decoration: none !important; }
		.'.$atts['sub_class'].' > li::before { font-family: "Font Awesome 5 Free"; font-size: 120%; font-weight: 900; content: "\f0da"; padding-right: 1rem; }
		</style>
		<ul class="'.$atts['list_class'].'">' . $childpages . '</ul>';
    }
	
	$myClass='class="'.$atts['sub_class'].'"';
	$result=str_replace("class='children'",$myClass,$result);
	
    return $result;

}

// register shortcode
add_shortcode('sws_display_childpages', 'sws_display_childpages_func'); 






?>