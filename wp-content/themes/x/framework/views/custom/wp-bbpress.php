<?php

// bbPress output for Custom

get_header();

?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
        <?php x_get_view( 'global', '_content', 'bbpress' ); ?>
      <?php endwhile; ?>

    </div>

    <?php get_sidebar(); ?>

  </div>

<?php get_footer(); ?>
