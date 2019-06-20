<?php 


add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles');
function salient_child_enqueue_styles() {
	
		$nectar_theme_version = nectar_get_theme_version();
		
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array('font-awesome'), $nectar_theme_version);

    if ( is_rtl() ) 
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
}

/******* Add Nectar JS ******/ 
/*
function ps19_scripts() {
	wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/nectar-slider.js', array() , false, true );
}
add_action( 'wp_enqueue_scripts', 'ps19_scripts', 99);
*/

/// Add excerpt for Pages ///
add_post_type_support( 'page', 'excerpt' );

// Issue with showing VC shortcodes in search results 
// Strip out Visual Composer specific shortcodes

add_filter('relevanssi_pre_excerpt_content', 'rlv_shortcode_blaster');
function rlv_shortcode_blaster($content) {
	$content = preg_replace('/\[.*?\]/s', '', $content);
//var_dump($content);
	return $content;
}


/******* Code for Relevanssi Limit Filters for post per page ******/ 
add_filter( 'query_vars', 'rlv_query_vars' );
function rlv_query_vars( $qv ) {
	$qv[] = 'posts_per_page';
	return $qv;
}

/******* Code for Relevanssi Limit Filters - Filters wont work without this ******/ 
function nectar_change_wp_search_size(){    
	require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/search.php';
}

 /******* Code for Custom Search function appear on search result page ******/ 
function custom_search_form_2( $form ) {
	$form = '<form method="get" id="main-search" action="'.esc_url(home_url( '/' )).'">';	
	$form .= '<input type="search" class="form-control" value="' . get_search_query() . '" name="s" id="s2" />';	
	$form .= '<button id="submitsearch2" type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>';
	$form .= '</form>';
	return $form;
}

?>
