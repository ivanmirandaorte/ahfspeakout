<?php

/**
 * Custom stack single default
 */

$fullwidth = get_post_meta( get_the_ID(), '_x_post_layout', true );

get_header();

?>

  <div class="x-container max width offset">
    <div class="<?php x_main_content_class(); ?>" role="main">

      <?php while ( have_posts() ) : the_post(); ?>
        <?php x_get_view( 'custom', 'content', get_post_format() ); ?>
      <?php endwhile; ?>

    </div>

    <?php if ( $fullwidth != 'on' ) : ?>
      <?php get_sidebar(); ?>
    <?php endif; ?>

  </div>

<?php get_footer(); ?>

