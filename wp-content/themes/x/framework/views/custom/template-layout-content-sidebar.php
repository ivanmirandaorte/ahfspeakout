<?php

// Custom TEMPLATE-LAYOUT-CONTENT-SIDEBAR.PHP
// -----------------------------------------------------------------------------
// Content left, sidebar right page output for Integrity.

get_header();

?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
        <?php x_get_view( 'custom', 'content', 'page' ); ?>
        <?php x_get_view( 'global', '_comments-template' ); ?>
      <?php endwhile; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>
