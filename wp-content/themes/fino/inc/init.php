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
  
  register_taxonomy(  
    'genre',
    'music-release',
    [  
      'hierarchical'  => false,  
      'label'         => 'Genre',
      'query_var'     => true,
      'meta_box_cb'   => 'post_categories_meta_box',
      'rewrite' => [
        'slug' => 'genre',
        'with_front' => false,
      ]
    ] 
  );
  
  register_taxonomy(  
    'style',
    'music-release',
    [  
      'hierarchical'  => false,  
      'label'         => 'Style',
      'query_var'     => true,
      'meta_box_cb'   => 'post_categories_meta_box',
      'rewrite' => [
        'slug' => 'style',
        'with_front' => false,
      ]
    ] 
  );
  
  register_taxonomy(  
    'format',
    'music-release',
    [  
      'hierarchical'  => false,  
      'label'         => 'Format',
      'query_var'     => true,
      'meta_box_cb'   => 'post_categories_meta_box',
      'rewrite' => [
        'slug' => 'format',
        'with_front' => false,
      ]
    ] 
  ); 
  
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
  add_meta_box('music-release', 'Release Options', 'release_attributes_meta_box', 'music-release');
}
add_action( 'add_meta_boxes', 'my_add_meta_boxes' );

function release_attributes_meta_box($post) {
  $tracklist = get_children([
    'post_parent' => $post->ID,
    'post_type'   => 'track', 
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'    => 'title'
  ]);
  ?>
  <p>
		<label><?=__( 'Country:', 'fino' )?></label><br />
    <input type="text" name="country" value="<?= get_post_meta($post->ID, 'country', true) ?>" />
	</p>
  <p>
		<label><?=__( 'Release Date:', 'fino' )?></label><br />
    <input type="number" min="1900" max="2099" step="1" name="year" value="<?= get_post_meta($post->ID, 'year', true) ?>" />
	</p>
  <?php if (!empty($tracklist)) : ?>
    <label><?=__( 'Tracklist:', 'fino' )?></label><br />
    <table class="widefat fixed">
      <thead>
        <tr>
          <th><?=__( 'Title', 'fino' )?></th>
          <th><?=__( 'Duration', 'fino' )?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tracklist as $track) : ?>
        <tr>
          <td><?= $track->post_title?></td>
          <td><?= get_post_meta($track->ID, 'duration', true)?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  <?php endif;
}


function track_attributes_meta_box( $post ) {
	$post_type_object = get_post_type_object( $post->post_type );
  $releases = wp_dropdown_pages(['post_type' => 'music-release', 'authors' => (!is_admin() ? [get_current_user_id()] : ''), 'selected' => $post->post_parent, 'name' => 'parent_id', 'sort_column'=> 'menu_order, post_title', 'echo' => 0 ]);
  ?>
  <p>
		<label><?=__( 'Track Length:', 'fino' )?></label><br />
    <input type="text" pattern="\d?\d?\d:[0-5]\d" name="duration" value="<?= get_post_meta($post->ID, 'duration', true) ?>" />
    <span>Time format mm:ss</span>
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
    if ('track' == $post->post_type && !empty( $_POST['duration']) && preg_match('/^\d?\d?\d:[0-5]\d$/',  $_POST['duration'])) {
      update_post_meta($post->ID, 'duration', $_POST['duration']);
    }
    if ('music-release' == $post->post_type) {
      if (!empty($_POST['country'])) {
        update_post_meta($post->ID, 'country', $_POST['country']);
      }
      if (!empty($_POST['year']) && (int)$_POST['year'] >= 1900 && (int)$_POST['year'] <= 2099) {
        update_post_meta($post->ID, 'year', (int)$_POST['year']);
      }
    }
  }
    
}
add_action( 'save_post', 'save_custom_fields' );

// Remove posts from the menu 
function post_remove() { 
   remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_remove'); 

