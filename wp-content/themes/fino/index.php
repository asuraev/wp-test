<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Fino
 */
 get_header(); 
?>

 <div class="bg-w sp-80">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
              <?php get_template_part( 'section-parts/front-page-listing' ); ?>
            </div>
            <div class="col-md-3">
                    <aside class="sidebar">
                        <?php get_sidebar(); ?>
                    </aside>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>