<?php





// SHORTCODE FOR Displaying CATEGORIES
function sws_display_childpages_func($atts) {

    $atts = shortcode_atts( array(
        'parent' => 'our-ministry',
		'list_class' => 'sws-childpages',
		'show' => 'children',
    ), $atts, 'childpages' );

    $parent_id = false;
    if ( $atts['parent'] ) {
        $parent = get_page_by_path( $atts['parent'] ); 
        if ( $parent ) {
            $parent_id = $parent->ID;
        }
    } else { // if no parent passed, then show children of current page
        $parent_id = get_the_ID();
    }

    $result = '';
    if ( ! $parent_id ) {  // don't waste time getting pages, if we couldn't get parent page
         return $result;
    }

    $childpages = wp_list_pages( array(
        'sort_column' => 'menu_order',
        'title_li' => '',
        'child_of' => $parent_id,
        'echo' => 0
    ) );

    if ( $childpages ) {
        $result = '<ul>' . $childpages . '</ul>';
    }

    return $result;

}

// register shortcode
add_shortcode('sws_display_childpages', 'sws_display_childpages_func'); 






?>