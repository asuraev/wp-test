<?php
  get_header();
?>
<div class="bg-w sp-80">
  <div class="container">
    <h1><?= $authordata->data->display_name; ?></h1>
    <p><?php the_author_meta( 'description', $authordata->ID ) ?></p>
    
    <div class="discography-tabs">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#panel1"><?php _e('Discography', 'fino')?></a></li>
        <li><a data-toggle="tab" href="#panel2"><?php _e('Tracks list', 'fino')?></a></li>
      </ul>

      <div class="tab-content">
        <div id="panel1" class="tab-pane fade in active">
          <?php $formats = get_terms('format', ['hide_empty' => true]); ?>
          <table>
            <tbody>
              <?php foreach ($formats as $format) :?>
                <?php 
                  $args = [
                    'post_type' => 'music-release',
                    'posts_per_page' => -1,
                    'author' => $authordata->ID,
                    'tax_query' => [
                      [
                        'taxonomy' => 'format', 
                        'field' => 'slug',
                        'terms' => $format->slug
                      ]
                    ],
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'meta_key' => 'year',
                  ];
                  $releases = get_posts($args);
                ?>
                <?php if (!empty($releases)) : ?>
                  <tr>
                    <td colspan="2" class="format">
                      <?= $format->name?>
                    </td>
                  </tr>
                  <?php foreach ($releases as $release) :?>
                    <tr>
                      <td>
                        <a href="<?php the_permalink($release)?>"><?= $release->post_title;?></a>
                      </td>
                      <td>
                        <?= get_post_meta($release->ID, 'year', true)?>
                      </td>
                    </tr>
                  <?php endforeach;?>
                <?php endif;?>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <div id="panel2" class="tab-pane fade">
          <?php 
            $args = [
              'post_type' => 'track',
              'posts_per_page' => -1,
              'author' => $authordata->ID,
            ];
            $tracks = get_posts($args);
          ?>
          <table>
            <thead>
              <tr>
                <th><?php _e('Composition title', 'fino')?></th>
                <th><?php _e('Year', 'fino')?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tracks as $track) :?>
              <tr>
                <td><?= $track->post_title?></td>
                <td><?= get_post_meta($track->post_parent, 'year', true)?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?= do_shortcode('[recent_viewed]')?>
  </div>
</div>

<?php get_footer(); ?>

