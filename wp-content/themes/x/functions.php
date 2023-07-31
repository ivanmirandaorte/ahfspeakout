<?php

// =============================================================================
// FUNCTIONS.PHP
// -----------------------------------------------------------------------------
// Theme functions for X.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Autoloader
//   02. Bootstrap Theme
// =============================================================================

if ( file_exists( get_template_directory() . '/dev.php' ) ) {
  require_once( get_template_directory() . '/dev.php' );
}

// Bootstrap Theme
// =============================================================================

require_once( __DIR__ . '/framework/classes/Theme.php' );
require_once( __DIR__ . '/framework/classes/Util/IocContainer.php' );

\Themeco\Theme\Theme::instantiate(
  get_template_directory(),
  get_template_directory_uri()
);



function x_bootstrap() {
  return \Themeco\Theme\Theme::instance();
}

\Themeco\Theme\Theme::instance()->boot([
  // Global Services
  'preinit' => [
    '\Themeco\Theme\Services\ViewRouter',
    '\Themeco\Theme\Services\Enqueue'
  ]
],[

  // Main Includes

  'preinit' => [
    'functions/i18n',
    'functions/setup',
    'functions/plugins/cornerstone',

    // Plugin Integrations
    [ class_exists( 'acf_pro' ), 'functions/plugins/acf-pro' ],
    [ class_exists( 'Convert_Plug' ), 'functions/plugins/convertplug' ],
    [ class_exists( 'Envira_Gallery' ), 'functions/plugins/envira-gallery' ],
    [ class_exists( 'Essential_Grid' ), 'functions/plugins/essential-grid' ],
    [ class_exists( 'LFB_Core' ), 'functions/plugins/estimation-form' ],
    [ class_exists( 'WPLeadInAdmin' ) || class_exists( 'LeadinAdmin' ), 'functions/plugins/hubspot'],
    [ class_exists( 'LS_Sliders' ), 'functions/plugins/layerslider' ],
    [ class_exists( 'MEC' ), 'functions/plugins/modern-events-calendar' ],
    [ class_exists( 'RevSlider' ), 'functions/plugins/revolution-slider' ],
    [ class_exists( 'Soliloquy' ), 'functions/plugins/soliloquy'],
    [ class_exists( 'UberMenu' ), 'functions/plugins/ubermenu']
  ]
]);

// Allow SVG Files 
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '6.2.2' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );
