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

