<?php
class Filter_Widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      'filter_widget',
      'Filter Widget',
      array('description' => 'Displays a Music release filter',)
    );
  }

  function widget ($args, $instance) {
    $filters = getFilters();
    
    $post_count_query_args = [
      'post_type' => 'music-release', 
      'posts_per_page' => -1,
      'tax_query' => [],
      'meta_query' => [],
    ];
    foreach ($filters as $filter_key => $filter) {
      if (is_array($filter)) {
        foreach ($filter as $slug) {
          $post_count_query_args['tax_query'][] = [
            'taxonomy' => $filter_key, 
            'field' => 'slug',
            'terms' => $slug
          ];
        }
      }
      else {
        $post_count_query_args['meta_query'][] = [
            'key'     => 'year',
            'value'   => [$filter, $filter + 9],
            'type'    => 'numeric',
            'compare' => 'BETWEEN',
        ];
      }
    }
    
    if (!empty($filters)) :?>
      <ul class="selected-filters list-unstyled">
      <?php foreach ($filters as $filter_key => $filter) : ?>
        <?php if (is_array($filter)):?>
          <?php foreach ($filter as $key => $filter_value) : ?>
            <?php 
              $filter_array = $filter;
              unset($filter_array[$key]);
            ?>
            <li><a href="<?= add_query_arg([$filter_key.'_exact' => $filter_array])?>"><?= get_term_by('slug', $filter_value, $filter_key)->name?><i class="fa fa-times pull-right"></i></a></li>
          <?php endforeach;?>
        <?php else:?>
          <li><a href="<?= remove_query_arg($filter_key)?>"><?= $filter?><i class="fa fa-times pull-right"></i></a></li>
        <?php endif;?>
      <?php endforeach;?>
      </ul>
    <?php endif;
    
    $genres = get_terms('genre');
    ?>
    <h3><?=__('Genre', 'fino')?></h3>
    <ul class="list-unstyled">
      <?php foreach ($genres as $genre): ?>
        <?php if (!isset($filters['genre']) || !in_array($genre->slug, $filters['genre']) ):?>
          <?php 
            $args = $post_count_query_args;
            $args['tax_query'][] = [
              'taxonomy' => 'genre', 
              'field' => 'slug',
              'terms' => $genre->slug
            ];
            $count = (new WP_Query($args))->post_count;
          ?>
          <?php if ($count):?>
            <li>
              <a href="<?=add_query_arg(['genre_exact[]' => $genre->slug])?>"><?= $genre->name?><small class="pull-right"><?= $count;?></small></a>
            </li>
          <?php endif;?>
        <?php endif;?>
      <?php endforeach;?>
    </ul>
    <?php
    
    $styles = get_terms('style');
    ?>
    <h3><?=__('Style', 'fino')?></h3>
    <ul class="list-unstyled">
      <?php foreach ($styles as $style): ?>
        <?php if (!isset($filters['style']) || !in_array($style->slug, $filters['style']) ):?>
          <?php 
            $args = $post_count_query_args;
            $args['tax_query'][] = [
              'taxonomy' => 'style', 
              'field' => 'slug',
              'terms' => $style->slug
            ];
            $count = (new WP_Query($args))->post_count;
          ?>
          <?php if ($count):?>
            <li>
              <a href="<?=add_query_arg(['style_exact[]' => $style->slug])?>"><?= $style->name?><small class="pull-right"><?= $count;?></small></a>
            </li>
          <?php endif;?>
        <?php endif;?>
      <?php endforeach;?>
    </ul>
    <?php
    
    $formats = get_terms('format');
    ?>
    <h3><?=__('Format', 'fino')?></h3>
    <ul class="list-unstyled">
      <?php foreach ($formats as $format): ?>
        <?php if (!isset($filters['format']) || !in_array($format->slug, $filters['format']) ):?>
          <?php 
            $args = $post_count_query_args;
            $args['tax_query'][] = [
              'taxonomy' => 'format', 
              'field' => 'slug',
              'terms' => $format->slug
            ];
            $count = (new WP_Query($args))->post_count;
          ?>
          <?php if ($count):?>
            <li>
              <a href="<?=add_query_arg(['format_exact[]' => $format->slug])?>"><?= $format->name?><small class="pull-right"><?= $count;?></small></a>
            </li>
          <?php endif;?>
        <?php endif;?>
      <?php endforeach;?>
    </ul>
    <?php
    
    $countries = get_terms('country');
    ?>
    <h3><?=__('Country', 'fino')?></h3>
    <ul class="list-unstyled">
      <?php foreach ($countries as $country): ?>
        <?php if (!isset($filters['country']) || !in_array($country->slug, $filters['country']) ):?>
          <?php 
            $args = $post_count_query_args;
            $args['tax_query'][] = [
              'taxonomy' => 'country', 
              'field' => 'slug',
              'terms' => $country->slug
            ];
            $count = (new WP_Query($args))->post_count;
          ?>
          <?php if ($count):?>
            <li>
              <a href="<?=add_query_arg(['country_exact[]' => $country->slug])?>"><?= $country->name?><small class="pull-right"><?= $count;?></small></a>
            </li>
          <?php endif;?>
        <?php endif;?>
      <?php endforeach;?>
    </ul>
    <?php
    
    $decades = array_reverse(range(1900, 2010, 10));
    ?>
    <h3><?=__('Decade', 'fino')?></h3>
    <ul class="list-unstyled">
      <?php foreach ($decades as $decade): ?>
        <?php if (!isset($filters['decade']) || $filters['decade'] != $decade) :?>
          <?php 
            $args = $post_count_query_args;
            $args['meta_query'][] = [
              'key'     => 'year',
              'value'   => [$decade, $decade + 9],
              'type'    => 'numeric',
              'compare' => 'BETWEEN',
            ];
            $count = (new WP_Query($args))->post_count;
          ?>
          <?php if ($count):?>
            <li>
              <a href="<?=add_query_arg(['decade' => $decade])?>"><?= $decade?><small class="pull-right"><?= (new WP_Query($args))->post_count;?></small></a>
            </li>
          <?php endif;?>
        <?php endif;?>
      <?php endforeach;?>
    </ul>
    <?php
  }
} 

function register_filter_widget() {
	register_widget('Filter_Widget');
}
add_action( 'widgets_init', 'register_filter_widget' );