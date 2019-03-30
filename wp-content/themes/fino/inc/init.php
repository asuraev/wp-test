<?php



/**
 * Load info.
 */
if ( is_admin() ) {
    require get_template_directory().'/lib/info/class.info.php';
    require get_template_directory().'/lib/info/info.php';
}


// Register Custom Post Types
function register_music_post_types() {
  $labels = array(
    'name'                  => _x( 'Music Releases', 'Post Type General Name', 'fino' ),
    'singular_name'         => _x( 'Music Release', 'Post Type Singular Name', 'fino' ),
    'menu_name'             => __( 'Music Releases', 'fino' ),
    'name_admin_bar'        => __( 'Music Releases', 'fino' )
  );
  $args = array(
    'label'                 => __( 'Music Release', 'fino' ),
    'labels'                => $labels,
    'hierarchical'          => true,
    'public'                => true,
    'menu_icon'             => 'dashicons-playlist-audio'
  );
  register_post_type( 'music-release', $args );
  register_taxonomy_for_object_type( 'post_tag', 'your_post' );
  register_taxonomy_for_object_type( 'post_tag', 'your_post' );
  $labels = array(
    'name'                  => _x( 'Tracks', 'Post Type General Name', 'fino' ),
    'singular_name'         => _x( 'Track', 'Post Type Singular Name', 'fino' ),
    'menu_name'             => __( 'Tracks', 'fino' ),
    'name_admin_bar'        => __( 'Tracks', 'fino' )
  );
  $args = array(
    'label'                 => __( 'Tracks', 'fino' ),
    'labels'                => $labels,
    'hierarchical'          => false,
    'public'                => true,
    'menu_icon'             => 'dashicons-format-audio',
    'supports'              => ['title']
	);
	register_post_type( 'track', $args );
}
add_action( 'init', 'register_music_post_types' );

// Add custom fields
function my_add_meta_boxes() {
	add_meta_box('track-album', 'Track Options', 'track_attributes_meta_box', 'track');
}
add_action( 'add_meta_boxes', 'my_add_meta_boxes' );

function track_attributes_meta_box( $post ) {
	$post_type_object = get_post_type_object( $post->post_type );
  $releases = wp_dropdown_pages(['post_type' => 'music-release', 'authors' => [get_current_user_id()], 'selected' => $post->post_parent, 'name' => 'parent_id', 'sort_column'=> 'menu_order, post_title', 'echo' => 0 ]);
  ?>
  <p>
		<label><?=__( 'Track Length:', 'fino' )?></label><br />
		<input type="text" name="duration" value="<?= get_post_meta($post->ID, 'duration', true) ?>" />
	</p>
  <?php if (!empty($releases)) :?>
    <p>
      <label><?=__( 'Release:', 'fino' )?></label><br />
      <?= $releases?>
    </p>
  <?php endif;?>
  <?php
}

function save_custom_fields(){
  global $post;
 
  if ($post) {
    update_post_meta($post->ID, 'duration', $_POST['duration']);
  }
}
add_action( 'save_post', 'save_custom_fields' );

// Remove posts from the menu 
function post_remove() { 
   remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_remove'); 

