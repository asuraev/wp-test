<?php



/**
 * Load info.
 */
if ( is_admin() ) {
    require get_template_directory().'/lib/info/class.info.php';
    require get_template_directory().'/lib/info/info.php';
}

require get_template_directory().'/inc/widgets.php';
require get_template_directory().'/inc/post-types.php';
require get_template_directory().'/inc/recently-visited.php';

// Remove posts from the menu 
function post_remove() { 
   remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_remove'); 


// Co-Authors plugin support
add_filter('coauthors_supported_post_types' , function($post_types){
  $post_types[] = 'music-release';
  return $post_types;
});

add_filter('coauthors_plus_edit_authors', function($can_set_authors){
  $user_roles = (array) wp_get_current_user()->roles;
  if (in_array('author', $user_roles)) {
    return true;
  }
  return $can_set_authors;
});

/**
 * release filters from GET params
 * 
 * @return array
 */
function getFilters() {
  $filter = [];
  foreach ($_GET as $key => $value) {
    switch ($key) {
      case 'genre_exact':   $filter['genre']    = $value; break;
      case 'style_exact':   $filter['style']    = $value; break;
      case 'format_exact':  $filter['format']   = $value; break;
      case 'country_exact': $filter['country']  = $value; break;
      case 'decade':        $filter['decade']   = $value; break;
    }
  }
  return $filter;
}

/**
 * Get taxonomy terms as string
 * 
 * @param int $postID
 * @param string $taxonomy
 * 
 * @return array
 */
function termsList($postID, $taxonomy) {
  $terms = wp_get_post_terms($postID, $taxonomy);
  return implode(', ', array_map(function($term){ return $term->name;}, $terms));
}


function register_my_menu() {
  register_nav_menu('primary', __( 'Header Menu' ));
}
add_action( 'init', 'register_my_menu' );

function add_login_logout_register_menu( $items, $args ) {
	if ( $args->theme_location != 'primary' ) {
		return $items;
	}
	if ( is_user_logged_in() ) {
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item"><a title="'.__( 'Log Out' ).'" class="menu-link" href="'.esc_url(wp_logout_url('')).'">'.__( 'Log Out' ).'</a></li>';
	} else {
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item"><a title="'.__( 'Log In' ).'" class="menu-link" href="'.esc_url(wp_login_url('')).'">'.__( 'Log In' ).'</a></li>';
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item"><a title="'.__( 'Sign Up' ).'" class="menu-link" href="'.esc_url(wp_registration_url()).'">'.__( 'Sign Up' ).'</a></li>';//wp_register('<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item">', '</li>', false);//'' . __( 'Sign Up' ) . '';
	}
	return $items;
}

add_filter( 'wp_nav_menu_items', 'add_login_logout_register_menu', 199, 2 );
