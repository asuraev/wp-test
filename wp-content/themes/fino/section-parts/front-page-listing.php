<?php 
  global $wp_query;

  if(isset($_REQUEST['posts_per_page'])) {
    $posts_per_page = $_REQUEST['posts_per_page'];
  }
  else {
    $posts_per_page = 2;
  }
  
  if(isset($_REQUEST['sort'])) {
    $sort_string = $_REQUEST['sort'];
    $sort_query = explode(',', $sort_string);
    switch ($sort_query[0]) {
      case 'date_added':
        $orderby = 'date';
      break;
      case 'title':
        $orderby = 'title';
      break;
      case 'year':
        $orderby = 'meta_value_num';
        $metakey = 'year';
      break;
      default : $sort = false;
    }
    $orderway = 'asc' == $sort_query[1] ? 'asc' : 'desc';
  }
  else {
    $sort_string = '';
    $orderby = 'none';
    $orderway = 'desc';
    $metakey = '';
  }
  

  $query_args = [
    'post_type' => 'music-release',
    'posts_per_page' => $posts_per_page,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
    'orderby' => $orderby,
    'order' => $orderway,
    'meta_key' => $metakey,
    'tax_query' => [],
    'meta_query' => [],
  ];
  
  $filters = getFilters();
  foreach ($filters as $filter_key => $filter) {
    if (is_array($filter)) {
      foreach ($filter as $slug) {
        $query_args['tax_query'][] = [
          'taxonomy' => $filter_key, 
          'field' => 'slug',
          'terms' => $slug
        ];
      }
    }
    else {
      $query_args['meta_query'][] = [
          'key'     => 'year',
          'value'   => [$filter, $filter + 9],
          'type'    => 'numeric',
          'compare' => 'BETWEEN',
      ];
    }
  }
  
  $releases = new WP_Query($query_args);
  
  $wp_query = NULL;
  $wp_query = $releases;
?>
<?php if($releases->have_posts()) : ?>
  <form name="frm" class="pb-30" method="get" action="">
    <label for="posts_per_page"><?php _e('Per page:', 'fino')?></label>
    <select onchange="document.frm.submit()" name="posts_per_page" id="posts_per_page">
      <option value="1" <?= (1 == $posts_per_page ? 'selected="selected"' : '') ?>>1</option>
      <option value="2" <?= (2 == $posts_per_page ? 'selected="selected"' : '') ?>>2</option>
      <option value="10" <?= (10 == $posts_per_page ? 'selected="selected"' : '') ?>>10</option>
    </select>
    <label for="sort"><?php _e('Sort', 'fino')?></label>
    <select onchange="document.frm.submit()" name="sort" id="sort">
      <option value="">--</option>
      <option value="date_added,desc" <?= ("date_added,desc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Latest Additions', 'fino')?></option>
      <option value="date_added,asc" <?= ("date_added,asc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Old Additions', 'fino')?></option>
      <option value="title,asc" <?= ("title,asc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Title, A-Z', 'fino')?></option>
      <option value="title,desc" <?= ("title,desc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Title, Z-A', 'fino')?></option>
      <option value="year,desc" <?= ("year,desc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Year, Newest First', 'fino')?></option>
      <option value="year,asc" <?= ("year,asc" == $sort_string ? 'selected="selected"' : '') ?>><?php _e('Year, Oldest First', 'fino')?></option>    
    </select>
  </form>
  <table>
    <tbody>
<?php while($releases->have_posts()) : $releases->the_post(); ?>
  <tr class="release">
    <td class="release-cover">
      <a href="<?= get_permalink()?>">
      <?php if(has_post_thumbnail()) :?>
        <?php the_post_thumbnail('thumbnail');?>
      <?php else: ?>
        <div class="thumb-placeholder">
          <i class="fa fa-music"></i>
        </div>
      <?php endif;?>
      </a>
    </td>
    <td class="release-body">
      <p>
        <a href="<?= get_permalink()?>"><?php the_title();?></a> - <a href="<?= get_author_posts_url(get_the_author_meta('ID'))?>"><?php the_author()?></a>
      </p>
      <p>
        <?php if (!empty(wp_get_post_terms(get_the_ID(), 'format'))) :?>
          <span class="format"><?= wp_get_post_terms(get_the_ID(), 'format')[0]->name?></span>
        <?php endif;?>
          <span class="genre"><?=trim(termsList(get_the_ID(), 'genre').', '.termsList(get_the_ID(), 'style'), ', ')?></span>
      </p>
      <p>
        <?= trim(termsList(get_the_ID(), 'country').', '.get_post_meta(get_the_ID(), 'year', true), ', ')?>
      </p>
    </td>
  </tr>
<?php endwhile; ?>
  </tbody>
</table>

<?php else : 
  get_template_part( 'content-parts/content', 'none' );
endif;?>
<div class="fino-pagination">
  <?php the_posts_pagination(
    array(
      'prev_text' => esc_html__('&lt;','fino'),
      'next_text' => esc_html__('&gt;','fino')
    )
  ); ?>
</div>