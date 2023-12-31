<?php

// =============================================================================
// FUNCTIONS/X/MIGRATION.PHP
// -----------------------------------------------------------------------------
// Handles theme migration.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Version Migration
//   02. Version Migration Notice
//   03. Theme Migration
//   04. Term Splitting Migration (WordPress 4.2 Breaking Change)
// =============================================================================

// Version Migration
// =============================================================================

function x_version_migration() {

  $prior = get_option( 'x_version', false );


  // Store the version on first install
  // ----------------------------------

  if ( false === $prior ) {
    update_option( 'x_version', X_VERSION, true );
    update_option( 'x_dismiss_update_notice', true );
    return;
  }


  if ( version_compare( $prior, X_VERSION, '<' ) ) {

    // If $prior is less than 2.2.0
    // ----------------------------

    if ( version_compare( $prior, '2.2.0', '<' ) ) {

      $mods = get_theme_mods();

      foreach( $mods as $key => $value ) {
        update_option( $key, $value );
      }

    }


    // If $prior is less than 3.1.0
    // ----------------------------

    if ( version_compare( $prior, '3.1.0', '<' ) ) {

      $stack      = get_option( 'x_stack' );
      $design     = ( $stack == 'integrity' ) ? '_' . get_option( 'x_integrity_design' ) : '';
      $stack_safe = ( $stack == 'icon' ) ? 'integrity' : $stack;

      $updated = array(
        'x_layout_site'               => get_option( 'x_' . $stack . '_layout_site' ),
        'x_layout_site_max_width'     => get_option( 'x_' . $stack . '_sizing_site_max_width' ),
        'x_layout_site_width'         => get_option( 'x_' . $stack . '_sizing_site_width' ),
        'x_layout_content'            => get_option( 'x_' . $stack . '_layout_content' ),
        'x_layout_content_width'      => get_option( 'x_' . $stack_safe . '_sizing_content_width' ),
        'x_layout_sidebar_width'      => get_option( 'x_icon_sidebar_width' ),
        'x_design_bg_color'           => get_option( 'x_' . $stack . $design . '_bg_color' ),
        'x_design_bg_image_pattern'   => get_option( 'x_' . $stack . $design . '_bg_image_pattern' ),
        'x_design_bg_image_full'      => get_option( 'x_' . $stack . $design . '_bg_image_full' ),
        'x_design_bg_image_full_fade' => get_option( 'x_' . $stack . $design . '_bg_image_full_fade' )
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }

    }


    // If $prior is less than 4.0.0
    // ----------------------------

    if ( version_compare( $prior, '4.0.0', '<' ) ) {

      $updated = array(
        'x_pre_v4' => true
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }

    }


    // If $prior is less than 4.0.4
    // ----------------------------

    if ( version_compare( $prior, '4.0.4', '<' ) ) {

      $stack            = get_option( 'x_stack' );
      $navbar_font_size = get_option( 'x_navbar_font_size', 12 );

      if ( $stack == 'integrity' ) {
        $link_spacing        = round( intval( $navbar_font_size ) * 1.429 );
        $link_letter_spacing = 2;
      } else if ( $stack == 'renew' ) {
        $link_spacing        = intval( $navbar_font_size );
        $link_letter_spacing = 1;
      } else if ( $stack == 'icon' ) {
        $link_spacing        = 5;
        $link_letter_spacing = 1;
      } else if ( $stack == 'ethos' ) {
        $link_spacing        = get_option( 'x_ethos_navbar_desktop_link_side_padding' );
        $link_letter_spacing = 1;
      }

      $updated = array(
        'x_navbar_adjust_links_top_spacing' => $link_spacing,
        'x_navbar_letter_spacing'           => $link_letter_spacing
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }

    }


    // If $prior is less than 4.2.0
    // ----------------------------

    if ( version_compare( $prior, '4.2.0', '<' ) ) {

      $stack        = get_option( 'x_stack' );
      $design       = get_option( 'x_integrity_design' );
      $h_base       = ( intval( get_option( 'x_body_font_size', 14 ) ) + intval( get_option( 'x_content_font_size', 14 ) ) ) / 2;
      $h1_font_size = $h_base * 4;
      $h2_font_size = $h_base * 2.857;
      $h3_font_size = $h_base * 2.285;
      $h4_font_size = $h_base * 1.714;
      $h5_font_size = $h_base * 1.5;
      $h6_font_size = $h_base * 1;

      if ( $stack == 'integrity' && $design == 'dark' ) {
        $logo_font_color     = '#ffffff';
        $headings_font_color = '#ffffff';
        $body_font_color     = '#666666';
      } else if ( $stack == 'renew' ) {
        $logo_font_color     = '#ffffff';
        $headings_font_color = '#2c3e50';
        $body_font_color     = '#28323f';
      } else if ( $stack == 'icon' ) {
        $logo_font_color     = '#566471';
        $headings_font_color = '#566471';
        $body_font_color     = '#566471';
      } else if ( $stack == 'ethos' ) {
        $logo_font_color     = '#ffffff';
        $headings_font_color = '#333333';
        $body_font_color     = '#7a7a7a';
      } else {
        $logo_font_color     = '#272727';
        $headings_font_color = '#272727';
        $body_font_color     = '#7a7a7a';
      }

      $logo_font_color                = ( get_option( 'x_logo_font_color_enable' ) == '1' ) ? get_option( 'x_logo_font_color' ) : $logo_font_color;
      $headings_font_color            = ( get_option( 'x_headings_font_color_enable' ) == '1' ) ? get_option( 'x_headings_font_color' ) : $headings_font_color;
      $body_font_color                = ( get_option( 'x_body_font_color_enable' ) == '1' ) ? get_option( 'x_body_font_color' ) : $body_font_color;
      $px_to_em_letter_spacing_logo   = round( intval( get_option( 'x_logo_letter_spacing', 1 ) ) / intval( get_option( 'x_logo_font_size', 54 ) ), 3 );
      $px_to_em_letter_spacing_navbar = round( intval( get_option( 'x_navbar_letter_spacing', 1 ) ) / intval( get_option( 'x_navbar_font_size', 12 ) ), 3 );
      $px_to_em_letter_spacing_h1     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h1_font_size, 3 );
      $px_to_em_letter_spacing_h2     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h2_font_size, 3 );
      $px_to_em_letter_spacing_h3     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h3_font_size, 3 );
      $px_to_em_letter_spacing_h4     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h4_font_size, 3 );
      $px_to_em_letter_spacing_h5     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h5_font_size, 3 );
      $px_to_em_letter_spacing_h6     = round( intval( get_option( 'x_headings_letter_spacing', 1 ) ) / $h6_font_size, 3 );

      $updated = array(
        'x_google_fonts_subsets'           => get_option( 'x_custom_font_subsets' ),
        'x_google_fonts_subset_cyrillic'   => get_option( 'x_custom_font_subset_cyrillic' ),
        'x_google_fonts_subset_greek'      => get_option( 'x_custom_font_subset_greek' ),
        'x_google_fonts_subset_vietnamese' => get_option( 'x_custom_font_subset_vietnamese' ),
        'x_logo_font_color'                => $logo_font_color,
        'x_logo_letter_spacing'            => $px_to_em_letter_spacing_logo,
        'x_navbar_letter_spacing'          => $px_to_em_letter_spacing_navbar,
        'x_headings_font_color'            => $headings_font_color,
        'x_h1_letter_spacing'              => $px_to_em_letter_spacing_h1,
        'x_h2_letter_spacing'              => $px_to_em_letter_spacing_h2,
        'x_h3_letter_spacing'              => $px_to_em_letter_spacing_h3,
        'x_h4_letter_spacing'              => $px_to_em_letter_spacing_h4,
        'x_h5_letter_spacing'              => $px_to_em_letter_spacing_h5,
        'x_h6_letter_spacing'              => $px_to_em_letter_spacing_h6,
        'x_body_font_color'                => $body_font_color
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }

    }


    // If $prior is less than 5.1.0
    // ----------------------------

    if ( version_compare( $prior, '5.1.0', '<' ) ) {

      $body_font_size        = intval( get_option( 'x_body_font_size', '14' ) );
      $content_font_size     = intval( get_option( 'x_content_font_size', '14' ) );
      $content_font_size_rem = round( $content_font_size / $body_font_size, 3 );

      $updated = array(
        'x_root_font_size_stepped_xs'  => get_option( 'x_root_font_size_stepped_xs', $body_font_size ),
        'x_root_font_size_stepped_sm'  => get_option( 'x_root_font_size_stepped_sm', $body_font_size ),
        'x_root_font_size_stepped_md'  => get_option( 'x_root_font_size_stepped_md', $body_font_size ),
        'x_root_font_size_stepped_lg'  => get_option( 'x_root_font_size_stepped_lg', $body_font_size ),
        'x_root_font_size_stepped_xl'  => get_option( 'x_root_font_size_stepped_xl', $body_font_size ),
        'x_root_font_size_scaling_min' => get_option( 'x_root_font_size_scaling_min', $body_font_size ),
        'x_root_font_size_scaling_max' => get_option( 'x_root_font_size_scaling_max', $body_font_size ),
        'x_content_font_size_rem'      => get_option( 'x_content_font_size_rem', $content_font_size_rem )
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }

    }

    if ( version_compare( $prior, '10.0.0', '<' ) ) {

      // Set changed keys to their currently stored or previous default value
      $updated = array(
        'x_layout_content'                 => get_option( 'x_layout_content', 'content-sidebar' ),
        'x_enable_font_manager'            => get_option( 'x_enable_font_manager', false ),
        'x_body_font_family_selection'     => get_option( 'x_body_font_family_selection', 'inherit' ),
        'x_body_font_weight_selection'     => get_option( 'x_body_font_weight_selection', 'inherit' ),
        'x_headings_font_family_selection' => get_option( 'x_headings_font_family_selection', 'inherit' ),
        'x_headings_font_weight_selection' => get_option( 'x_headings_font_weight_selection', 'inherit' ),
        'x_logo_font_family_selection'     => get_option( 'x_logo_font_family_selection', 'inherit' ),
        'x_logo_font_weight_selection'     => get_option( 'x_logo_font_weight_selection', 'inherit' ),
        'x_navbar_font_family_selection'   => get_option( 'x_navbar_font_family_selection', 'inherit' ),
        'x_navbar_font_weight_selection'   => get_option( 'x_navbar_font_weight_selection', 'inherit' )
      );

      foreach ( $updated as $key => $value ) {
        update_option( $key, $value );
      }
    }


    // Notes
    // -----
    // 01. Update stored version number.
    // 02. Turn on the version migration notice.
    // 03. Enable validation reminder.
    // 04. Bust caches.

    update_option( 'x_version', X_VERSION );        // 01
    delete_option( 'x_dismiss_update_notice' );     // 02
    delete_option( 'x_dismiss_validation_notice' ); // 03
    x_bust_google_fonts_cache();                    // 04

  }

}

add_action( 'init', 'x_version_migration' );



// Version Migration Notice
// =============================================================================

//
// 1. Output notice.
// 2. Dismiss notice.
//

function x_version_migration_notice() { // 1
  $releaseNotesURL = '//theme.co/blog/pro6-3-x10-3-cornerstone7-3';

  if ( false === get_option( 'x_dismiss_update_notice', false ) ) {

    tco_common()->admin_notice( array(
      'message' => sprintf(
        __( 'Congratulations, you&apos;ve successfully updated X! <strong><a href="%s" target="_blank">Release Notes</a></strong>. Make sure you also update Cornerstone!', '__x__' ),
        $releaseNotesURL
      ),
      'dismissible' => true,
      'ajax_dismiss' => 'x_dismiss_update_notice'
    ) );

  }

}

add_action( 'admin_notices', 'x_version_migration_notice' );


function x_version_migration_notice_dismiss() { // 2

  update_option( 'x_dismiss_update_notice', true );
  wp_send_json_success();

}

add_action( 'wp_ajax_x_dismiss_update_notice', 'x_version_migration_notice_dismiss' );



// Theme Migration
// =============================================================================

function x_theme_migration( $new_name, $new_theme ) {

  if ( $new_theme == 'X' || $new_theme->get( 'Template' ) == 'x' ) {
    return false;
  }

  include_once( ABSPATH . '/wp-admin/includes/plugin.php' );

  $plugins   = get_plugins();
  $x_plugins = array();

  foreach ( (array) $plugins as $plugin => $headers ) {
    if ( ! empty( $headers['X Plugin'] ) ) {
      $x_plugins[] = $plugin;
    }
  }

  deactivate_plugins( $x_plugins );

}

add_action( 'switch_theme', 'x_theme_migration', 10, 2 );



// Term Splitting Migration (WordPress 4.2 Breaking Change)
// =============================================================================

function x_split_shared_term_migration( $term_id, $new_term_id, $term_taxonomy_id, $taxonomy ) {

  //
  // Ethos filterable index categories.
  //

  if ( $taxonomy == 'category' ) {

    $setting = array_map( 'trim', explode( ',', get_option( 'x_ethos_filterable_index_categories' ) ) );

    foreach ( $setting as $index => $old_term ) {
      if ( $old_term == (string) $term_id ) {
        $setting[$index] = (string) $new_term_id;
      }
    }

    update_option( 'x_ethos_filterable_index_categories', implode( ', ', $setting ) );

  }


  //
  // Portfolio categories.
  //

  if ( $taxonomy == 'portfolio-category' ) {

    $post_ids = get_posts( array(
      'fields'       => 'ids',
      'meta_key'     =>  '_x_portfolio_category_filters',
      'meta_value'   => '',
      'meta_compare' => '!='
    ) );

    foreach ( $post_ids as $post_id ) {

      $post_terms = get_post_meta( $post_id, '_x_portfolio_category_filters', true );

      if ( is_array( $post_terms ) ) {
        foreach ( $post_terms as $index => $old_term ) {
          if ( $term_id == $old_term) {
            $post_terms[$index] = $new_term_id;
          }
        }
      }

      update_post_meta( $post_id, '_x_portfolio_category_filters', $post_terms );

    }
  }

}

add_action( 'split_shared_term', 'x_split_shared_term_migration' );


// Product Notice
// =============================================================================

function x_product_notice_info() {
  return [
    '9.1',
    sprintf(
      __( 'Take the Slider Element to the next level with <strong><a href="%s" target="_blank">Modern Sliders</a></strong> — our new course and expansion pack!', '__x__' ),
      '//theme.co/modern-sliders'
    )
  ];
}

function x_product_notice() {

  if (strpos(X_VERSION,'-') !== false) { // ignore on prerelease builds
    return;
  }

  $stored = get_option( 'x_dismiss_product_notice', false );

  if (false === $stored) {
    $stored = [];
  }

  list($version, $message) = x_product_notice_info();

  if ( ! isset($stored[$version])) {

    tco_common()->admin_notice( array(
      'message' => $message,
      'dismissible' => true,
      'ajax_dismiss' => 'x_dismiss_product_notice'
    ) );

  }

}

add_action( 'admin_notices', 'x_product_notice' );


function x_product_notice_dismiss() {

  $stored = get_option( 'x_dismiss_product_notice', false );

  if (false === $stored) {
    $stored = [];
  }

  list($version) = x_product_notice_info();
  $stored[$version] = true;

  update_option( 'x_dismiss_product_notice', $stored );
  wp_send_json_success();

}

add_action( 'wp_ajax_x_dismiss_product_notice', 'x_product_notice_dismiss' );
