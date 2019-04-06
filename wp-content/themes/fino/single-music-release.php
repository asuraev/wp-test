<?php
 get_header(); 
?>
<div class="blog sp-80">
  <div class="container">
    <?php 
       if(have_posts()) : 
    ?>
    <?php while(have_posts()) : the_post(); ?>
      <div class="row pb-20">
        <div class="col-sm-1">
          <?php if(has_post_thumbnail()) :?>
            <?php the_post_thumbnail('thumbnail');?>
          <?php else: ?>
          <div class="thumb-placeholder">
            <i class="fa fa-music"></i>
          </div>
          <?php endif;?>
        </div>
        <div class="col-sm-11">
          <h2><a href="<?= get_author_posts_url($post->post_author)?>"><?php the_author()?></a> - <?= the_title();?></h2>
        </div>
      </div>
      <dl>
        <dt><?php _e('Format', 'fino')?></dt>
        <dd><?= termsList($post->ID, 'format');?></dd>
        <dt><?php _e('Genre', 'fino')?></dt>
        <dd><?= termsList($post->ID, 'genre');?></dd>
        <dt><?php _e('Style', 'fino')?></dt>
        <dd><?= termsList($post->ID, 'style');?></dd>
        <dt><?php _e('Country', 'fino')?></dt>
        <dd><?= termsList($post->ID, 'country');?></dd>
        <dt><?php _e('Released', 'fino')?></dt>
        <dd><?= get_post_meta($post->ID, 'year', true)?></dd>
      </dl>
      <div class="pb-20">
        <?php the_content(); ?>
      </div>
      <h3><?php _e('Tracklist', 'fino')?></h3>
      <table>
        <thead>
          <tr>
            <th><?php _e('Title', 'fino')?></th>
            <th><?php _e('Duration', 'fino')?></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $args = [
              'post_type' => 'track',
              'posts_per_page' => -1,
              'post_parent' => $post->ID
            ];
            $tracks = get_posts($args);
          ?>
          <?php foreach ($tracks as $track) :?>
          <tr>
            <td><?= $track->post_title?></td>
            <td><?= get_post_meta($track->ID, 'duration', true)?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    <?php endwhile; ?>
    <?php else : 
      get_template_part( 'content-parts/content', 'none' );
    endif; ?>
    <?= do_shortcode('[recent_viewed]')?>
  </div>
</div>
    
<?php get_footer(); ?>