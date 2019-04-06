<?php
class RecentViewed {
  public static function visit()
  {
    if (is_singular('music-release')) {
      self::cookieSave('recent_releases', get_the_ID());
    }
    if (is_author()) {
      $author = get_queried_object();
      self::cookieSave('recent_authors', $author->ID);
    }
  }
  
  private static function cookieSave($key, $id) 
  {
    $rv_entity = isset($_COOKIE[$key]) ? json_decode($_COOKIE[$key], true) : null;
    if (isset($rv_entity)) {
      // Remove current post in the cookie
      $rv_entity = array_diff($rv_entity, [$id]);
      // update cookie with current post
      array_unshift($rv_entity, $id);
    } else {
      $rv_entity = [$id];
    }
    setcookie($key, json_encode($rv_entity), time() + (DAY_IN_SECONDS * 31), COOKIEPATH, COOKIE_DOMAIN);
  }
  
  public static function showCarousel()
  {
    if (is_singular('music-release')) {
      // Check cookie existence
      $rv_cookie_posts = isset($_COOKIE['recent_releases']) ? json_decode($_COOKIE['recent_releases'], true) : null;
      if (isset($rv_cookie_posts)) {
        // Remove current post
        $rv_cookie_posts = array_diff($rv_cookie_posts, [get_the_ID()]);			
        if (count($rv_cookie_posts) > 0):?>
          <h3 class="pb-20"><?php _e('Recent viewed releases', 'fino')?></h3>
          <div class="owl-carousel owl-theme">
          <?php
          // Loop through posts in the cookie array
          foreach ($rv_cookie_posts as $postId) {
            if (absint($postId) == 0) {
              continue;
            }
            $rv_post = get_post(absint($postId)); // Get the post
            if (isset($rv_post) && $rv_post->post_type == 'music-release') {
              $permalink = esc_url(get_permalink( $rv_post->ID ));
              ?>
              <div class="item recentviewed text-center">
                <a href="<?php echo $permalink; ?>">
                  <?php if (has_post_thumbnail($rv_post->ID)) : ?>
                    <?= get_the_post_thumbnail($rv_post->ID, 'thumbnail'); ?>
                  <?php else : ?>
                    <div class="thumb-placeholder">
                      <i class="fa fa-music"></i>
                    </div>
                  <?php endif; ?>
                  <?= apply_filters('the_title', $rv_post->post_title, $rv_post->ID );?>
                  <?= get_post_meta($rv_post->ID, 'year', true);?>
                </a>
              </div>
              <?php
            }
          }
          ?>
          </div>
        <?php endif;
      }
    }
    if (is_author()) {
      // Check cookie existence
      $rv_cookie_authors = isset($_COOKIE['recent_authors']) ? json_decode($_COOKIE['recent_authors'], true) : null;
      if (isset($rv_cookie_authors)) {
        // Remove current author
        $author = get_queried_object();
        $rv_cookie_authors = array_diff($rv_cookie_authors, [$author->ID]);			
        if (count($rv_cookie_authors) > 0):?>
          <h3 class="pb-20"><?php _e('Recent viewed authors', 'fino')?></h3>
          <div class="owl-carousel owl-theme text-center">
          <?php
          // Loop through authors in the cookie array
          foreach ($rv_cookie_authors as $authorId) {
            if (absint($authorId) == 0) {
              continue;
            }
            $rv_author = get_user_by('ID', absint($authorId)); // Get the authors
            if (isset($rv_author)) {
              $permalink = esc_url(get_author_posts_url($rv_author->ID));
              ?>
              <div class="item recentviewed">
                <a href="<?php echo $permalink; ?>">
                  <?= get_avatar($rv_author->ID, 80); ?>
                  <?= $rv_author->display_name; ?>
                </a>
              </div>
              <?php
            }
          }
          ?>
          </div>
        <?php endif;
      }
    }
    ?>
<script type="text/javascript">
  jQuery('document').ready(function(){
    jQuery('.owl-carousel').owlCarousel({
      margin:10,
      nav:true,
      responsive:{
        0:{
          items:1
        },
        600:{
          items:3
        },
        1000:{
          items:5
        }
      }
    });
  });
</script>
    <?php
  }
}

add_action('template_redirect', ['RecentViewed', 'visit']);
add_shortcode('recent_viewed', ['RecentViewed', 'showCarousel']);
