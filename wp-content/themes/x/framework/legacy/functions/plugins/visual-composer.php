<?php

// =============================================================================
// FUNCTIONS/GLOBAL/PLUGINS/VISUAL-COMPOSER.PHP
// -----------------------------------------------------------------------------
// Plugin setup for theme compatibility.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Conditional Theme Integration
//   02. Add X Legacy Integration Checkbox
//   03. Provision Frontend Editor
//   04. Map Shortcodes
//   05. Update Existing Elements
//   06. Incremental ID Counter for Templates
//   07. VC Column Inner Template
//   08. VC Column Template
//   09. VC Row Inner Template
//   10. VC Row template
// =============================================================================

// Conditional Theme Integration
// =============================================================================

function x_visual_composer_integration_on() {
  return get_option( 'wpb_js_x_integration', false );
}

function x_visual_composer_set_as_theme() {
  if ( x_visual_composer_integration_on() ) {
    vc_set_as_theme( true );
  } else {
    add_action( 'admin_notices', 'x_visual_composer_hide_update_notice', -99 );
    vc_manager()->disableUpdater();
  }
}

add_action( 'vc_before_init', 'x_visual_composer_set_as_theme' );

function x_visual_composer_hide_update_notice() {
  remove_action( 'admin_notices', array( vc_license(), 'adminNoticeLicenseActivation' ) );
}

function x_visual_composer_filter_removals() {
  // Note to reviewer: This remove_filter call disabled VC automatic updates because we provide those
  // updates directly so the buyer doesn't need to purchase the plugin to get automatic updates.
  remove_filter( 'upgrader_pre_download', array( vc_updater(), 'preUpgradeFilter' ) );
}

add_action( 'vc_after_init', 'x_visual_composer_filter_removals' );



// Add X Legacy Integration Checkbox
// =============================================================================

//
// Add setting fields.
//

function x_visual_composer_add_setting_fields( $vc_settings ) {
  $vc_settings->addField( 'general', __( 'Legacy X Integration', '__x__' ), 'x_integration', 'sanitize_key', 'x_visual_composer_x_integration' );
}

function x_visual_composer_x_integration() {
  $checked = ( $checked = get_option( 'wpb_js_x_integration', false ) ) ? $checked : false; ?>

  <label>
    <input type="checkbox"<?php echo( $checked ? ' checked="checked";' : '' ) ?> value="1" id="wpb_js_x_integration" name="wpb_js_x_integration">
    <?php _e( 'Enable', '__x__' ); ?>
  </label>
  <br/>
  <p class="description indicator-hint">
    <?php _e( 'Activate legacy X integration. Keep this enabled if you built your site with Visual Composer and X shortcodes. This allows the theme to overwrite the column and row shortcodes. Turning this off will allow the plugin to operate natively without any changes from the theme.', '__x__' ); ?>
  </p>

  <?php
}

add_action( 'vc_settings_tab-general', 'x_visual_composer_add_setting_fields' );



// Provision Frontend Editor
// =============================================================================

if ( x_visual_composer_integration_on() ) :

  //
  // Optionally disables the frontend editing options from the WordPress admin
  // edit screen as well as the admin bar.
  //

  if ( function_exists( 'vc_disable_frontend' ) && get_option( 'wpb_js_x_disable_frontend_editor', false ) ) {
    vc_disable_frontend();
  }


  //
  // Helper function to check if frontend editing is currently active.
  //

  function x_visual_composer_is_front_end_editor() {
    return ( function_exists( 'vc_manager' ) && vc_manager()->mode() == 'page_editable' );
  }


  //
  // Run certain actions only when using front end editing.
  //

  function x_visual_composer_fee_configure() {

    if ( ! x_visual_composer_is_front_end_editor() ) {
      return;
    }

    add_action( 'wp_enqueue_scripts', 'x_visual_composer_fee_enqueue', 999 );
    add_action( 'x_legacy_css', 'x_visual_composer_fee_output_styles', 999 );

  }

  add_action( 'init', 'x_visual_composer_fee_configure' );


  //
  // Enqueue extra scripts while frontend editor is active.
  //

  function x_visual_composer_fee_enqueue() {

    wp_enqueue_script( 'vendor-google-maps' );
    wp_enqueue_script( 'mediaelement' );
    wp_enqueue_script( 'cs-ilightbox' );
    wp_enqueue_script( 'cs-flexslider' );

  }


  //
  // Output extra styles while frontend editor is active.
  //

  function x_visual_composer_fee_output_styles() {

    switch ( x_get_stack() ) {
      case 'integrity':
        $base_margin = '1.313em';
        break;
      case 'integrity':
        $base_margin = '1.5em';
        break;
      case 'integrity':
        $base_margin = '1.313em';
        break;
      case 'integrity':
        $base_margin = '2.15em';
        break;
    }

    ?>

    .vc_welcome .vc_buttons {
      margin-top: 0;
    }

    .x-column.vc {
      width: 100%;
      margin: 0;
    }

    .vc_vc_column {
      margin-right: 4%;
      padding: 0;
    }

    .vc_vc_column:last-of-type {
      margin-right: 0;
    }

    .vc_vc_column.vc_col-sm-12 { width: 100%;      }
    .vc_vc_column.vc_col-sm-11 { width: 91.33332%; }
    .vc_vc_column.vc_col-sm-10 { width: 82.66666%; }
    .vc_vc_column.vc_col-sm-9  { width: 74%;       }
    .vc_vc_column.vc_col-sm-8  { width: 65.33332%; }
    .vc_vc_column.vc_col-sm-7  { width: 56.66666%; }
    .vc_vc_column.vc_col-sm-6  { width: 48%;       }
    .vc_vc_column.vc_col-sm-5  { width: 39.33332%; }
    .vc_vc_column.vc_col-sm-4  { width: 30.66666%; }
    .vc_vc_column.vc_col-sm-3  { width: 22%;       }
    .vc_vc_column.vc_col-sm-2  { width: 13.33332%; }
    .vc_vc_column.vc_col-sm-1  { width: 4.666666%; }

    .x-content-band.vc.marginless-columns .x-container .vc_container-anchor {
      display: none;
    }

    .x-content-band.vc.marginless-columns .x-container .vc_vc_column {
      display: table-cell;
      margin-right: 0;
      float: none;
    }

    .x-content-band.vc.marginless-columns .x-container .vc_vc_column .x-column {
      display: block;
      width: 100%;
      margin-right: 0;
      float: none;
    }

    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-12 { width: 100%;      }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-11 { width: 91.66666%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-10 { width: 83.33333%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-9  { width: 75%;       }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-8  { width: 66.66666%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-7  { width: 58.33333%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-6  { width: 50%;       }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-5  { width: 41.66666%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-4  { width: 33.33333%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-3  { width: 25%;       }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-2  { width: 16.66666%; }
    .x-content-band.vc.marginless-columns .x-container .vc_vc_column.vc_col-sm-1  { width: 8.33333%;  }

    .vc_element {
      margin-bottom: <?php echo $base_margin; ?>;
    }

    .vc_element.vc_vc_row,
    .vc_element.vc_vc_column,
    .vc_element:last-of-type {
      margin-bottom: 0;
    }

    .hentry p:last-of-type,
    .hentry ul:last-of-type,
    .hentry ol:last-of-type {
      margin-bottom: 0;
    }

    @media (max-width: 767px) {
      .vc_vc_column[class*="vc_col"] {
        float: none;
        width: 100%;
        margin-right: 0;
      }

      .x-content-band.vc.marginless-columns .x-container .vc_vc_column[class*="vc_col"] {
        display: block;
        width: 100%;
      }
    }

    <?php

  }


  add_action( 'vc_backend_editor_enqueue_js_css', function() {
    ob_start(); ?>

    .vc_element-icon.x-shortcode { background: none!important; display: flex; align-items: center; justify-content: center; } .vc_element-icon.x-shortcode:after { content: 'X'; font-style: normal; color: black; }

    .x-content-element div.wpb_vc_param_value:not(.content) { display: none !important; }

    .x-content-element div.wpb_vc_param_value.content {
      margin: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .x-content-element div.wpb_vc_param_value.content p,
    .x-content-element div.wpb_vc_param_value.content ul,
    .x-content-element div.wpb_vc_param_value.content ol,
    .x-content-element div.wpb_vc_param_value.content div,
    .x-content-element div.wpb_vc_param_value.content span {
      margin: 3px 0 0;
      font-size: 11px;
      font-style: italic;
      line-height: 1;
      color: #999999;
    }

    .x-content-element div.wpb_vc_param_value.content iframe {
      margin: 3px 0 0;
      width: 100%;
    }

    <?php
    wp_add_inline_style( 'js_composer', ob_get_clean() );
  } );

endif;



// Map Shortcodes
// =============================================================================

if ( ! function_exists( 'x_visual_composer_map_shortcodes' ) && x_visual_composer_integration_on() ) {

  function x_visual_composer_map_shortcodes() {

    //
    // Variables.
    //

    $param_icon_value        = array( '- Select Option -', 'glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'gear', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'rotate-right', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center', 'align-right', 'align-justify', 'list', 'dedent', 'outdent', 'indent', 'video-camera', 'photo', 'image', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'edit', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'mail-forward', 'share', 'expand', 'compress', 'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'warning', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart-o', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'gears', 'cogs', 'comments', 'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'group', 'users', 'chain', 'link', 'cloud', 'flask', 'cut', 'scissors', 'copy', 'files-o', 'paperclip', 'save', 'floppy-o', 'square', 'navicon', 'reorder', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'unsorted', 'sort', 'sort-down', 'sort-desc', 'sort-up', 'sort-asc', 'envelope', 'linkedin', 'rotate-left', 'undo', 'legal', 'gavel', 'dashboard', 'tachometer', 'comment-o', 'comments-o', 'flash', 'bolt', 'sitemap', 'umbrella', 'paste', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile-phone', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'mail-reply', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code', 'mail-reply-all', 'reply-all', 'star-half-empty', 'star-half-full', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'unlink', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'toggle-down', 'caret-square-o-down', 'toggle-up', 'caret-square-o-up', 'toggle-right', 'caret-square-o-right', 'euro', 'eur', 'gbp', 'dollar', 'usd', 'rupee', 'inr', 'cny', 'rmb', 'yen', 'jpy', 'ruble', 'rouble', 'rub', 'won', 'krw', 'bitcoin', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gittip', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'toggle-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'turkish-lira', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'institution', 'bank', 'university', 'mortar-board', 'graduation-cap', 'yahoo', 'google', 'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper-square', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'automobile', 'car', 'cab', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-photo-o', 'file-picture-o', 'file-image-o', 'file-zip-o', 'file-archive-o', 'file-sound-o', 'file-audio-o', 'file-movie-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-bouy', 'life-saver', 'support', 'life-ring', 'circle-o-notch', 'ra', 'rebel', 'ge', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'wechat', 'weixin', 'send', 'paper-plane', 'send-o', 'paper-plane-o', 'history', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb' );
    $param_social_icon_value = array( '- Select Option -', 'thumb-up', 'thumb-down', 'rss', 'facebook', 'twitter', 'pinterest', 'github', 'path', 'linkedin', 'dribbble', 'stumble-upon', 'behance', 'reddit', 'google-plus', 'youtube', 'vimeo', 'flickr', 'slideshare', 'picassa', 'skype', 'steam', 'instagram', 'foursquare', 'delicious', 'chat', 'torso', 'tumblr', 'video-chat', 'digg', 'wordpress' );

    sort( $param_icon_value );
    sort( $param_social_icon_value );


    //
    // Horizontal rule.
    //

    vc_map(
      array(
        'base'        => 'line',
        'name'        => __( 'Line', '__x__' ),
        'weight'      => 980,
        'class'       => 'x-content-element x-content-element-line',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Structure', '__x__' ),
        'description' => __( 'Place a horizontal rule in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Gap.
    //

    vc_map(
      array(
        'base'        => 'gap',
        'name'        => __( 'Gap', '__x__' ),
        'weight'      => 970,
        'class'       => 'x-content-element x-content-element-gap',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Structure', '__x__' ),
        'description' => __( 'Insert a vertical gap in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'size',
            'heading'     => __( 'Size', '__x__' ),
            'description' => __( 'Enter in the size of your gap. Pixels, ems, and percentages are all valid units of measurement.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '1.313em'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
        )
      )
    );


    //
    // Clear.
    //

    vc_map(
      array(
        'base'        => 'clear',
        'name'        => __( 'Clear', '__x__' ),
        'weight'      => 960,
        'class'       => 'x-content-element x-content-element-clear',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Structure', '__x__' ),
        'description' => __( 'Clear floated elements in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Blockquote.
    //

    vc_map(
      array(
        'base'        => 'blockquote',
        'name'        => __( 'Blockquote', '__x__' ),
        'weight'      => 810,
        'class'       => 'x-content-element x-content-element-blockquote',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Include a blockquote in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'cite',
            'heading'     => __( 'Cite', '__x__' ),
            'description' => __( 'Cite the person you are quoting.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select the alignment of the blockquote.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Center'            => 'center',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Pullquote.
    //

    vc_map(
      array(
        'base'        => 'pullquote',
        'name'        => __( 'Pullquote', '__x__' ),
        'weight'      => 800,
        'class'       => 'x-content-element x-content-element-pullquote',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Include a pullquote in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'cite',
            'heading'     => __( 'Cite', '__x__' ),
            'description' => __( 'Cite the person you are quoting.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select the alignment of the pullquote.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
        )
      )
    );


    //
    // Alert.
    //

    vc_map(
      array(
        'base'        => 'alert',
        'name'        => __( 'Alert', '__x__' ),
        'weight'      => 650,
        'class'       => 'x-content-element x-content-element-alert',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Information', '__x__' ),
        'description' => __( 'Provide information to users with alerts', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'heading',
            'heading'     => __( 'Heading', '__x__' ),
            'description' => __( 'Enter the heading of your alert.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Type', '__x__' ),
            'description' => __( 'Select the alert style.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Success'           => 'success',
              'Info'              => 'info',
              'Warning'           => 'warning',
              'Danger'            => 'danger',
              'Muted'             => 'muted'
            )
          ),
          array(
            'param_name'  => 'close',
            'heading'     => __( 'Close', '__x__' ),
            'description' => __( 'Select to display the close button.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Map.
    //

    vc_map(
      array(
        'base'        => 'map',
        'name'        => __( 'Map', '__x__' ),
        'weight'      => 530,
        'class'       => 'x-content-element x-content-element-map',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Embed a map from a third-party provider', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Code (See Notes Below)', '__x__' ),
            'description' => __( 'Switch to the "text" editor and do not place anything else here other than your &lsaquo;iframe&rsaquo; or &lsaquo;embed&rsaquo; code.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'no_container',
            'heading'     => __( 'No Container', '__x__' ),
            'description' => __( 'Select to remove the container around the map.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
        )
      )
    );


    //
    // Google map.
    //

    vc_map(
      array(
        'base'            => 'google_map',
        'name'            => __( 'Google Map', '__x__' ),
        'weight'          => 530,
        'class'           => 'x-content-element x-content-element-google-map',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Media', '__x__' ),
        'description'     => __( 'Embed a customizable Google map', '__x__' ),
        'as_parent'       => array( 'only' => 'google_map_marker' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'lat',
            'heading'     => __( 'Latitude', '__x__' ),
            'description' => __( 'Enter in the center latitude of your map.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lng',
            'heading'     => __( 'Longitude', '__x__' ),
            'description' => __( 'Enter in the center longitude of your map.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'drag',
            'heading'     => __( 'Draggable', '__x__' ),
            'description' => __( 'Select to allow your users to drag the map view.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'zoom',
            'heading'     => __( 'Zoom Level', '__x__' ),
            'description' => __( 'Choose the initial zoom level of your map. This value should be between 1 and 18. 1 is fully zoomed out and 18 is right at street level.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'zoom_control',
            'heading'     => __( 'Zoom Control', '__x__' ),
            'description' => __( 'Select to activate the zoom control for the map.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'height',
            'heading'     => __( 'Height', '__x__' ),
            'description' => __( 'Choose an optional height for your map. If no height is selected, a responsive, proportional unit will be used. Any type of unit is acceptable (e.g. 450px, 30em, 40%, et cetera).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'hue',
            'heading'     => __( 'Custom Color', '__x__' ),
            'description' => __( 'Choose an optional custom color for your map.', '__x__' ),
            'type'        => 'colorpicker',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'no_container',
            'heading'     => __( 'No Container', '__x__' ),
            'description' => __( 'Select to remove the container around the map.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
        )
      )
    );


    //
    // Google map marker.
    //

    vc_map(
      array(
        'base'            => 'google_map_marker',
        'name'            => __( 'Google Map Marker', '__x__' ),
        'weight'          => 530,
        'class'           => 'x-content-element x-content-element-google-map-marker',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Media', '__x__' ),
        'description'     => __( 'Place a location marker on your Google map', '__x__' ),
        'as_child'        => array( 'only' => 'google_map' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'lat',
            'heading'     => __( 'Latitude', '__x__' ),
            'description' => __( 'Enter in the latitude of your marker.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lng',
            'heading'     => __( 'Longitude', '__x__' ),
            'description' => __( 'Enter in the longitude of your marker.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'info',
            'heading'     => __( 'Additional Information', '__x__' ),
            'description' => __( 'Optional description text to appear in a popup when your marker is clicked on.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'image',
            'heading'     => __( 'Custom Marker Image', '__x__' ),
            'description' => __( 'Utilize a custom marker image instead of the default provided by Google.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
        )
      )
    );


    //
    // Skill bar.
    //

    vc_map(
      array(
        'base'        => 'skill_bar',
        'name'        => __( 'Skill Bar', '__x__' ),
        'weight'      => 640,
        'class'       => 'x-content-element x-content-element-skill-bar',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Information', '__x__' ),
        'description' => __( 'Include an informational skill bar', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'heading',
            'heading'     => __( 'Heading', '__x__' ),
            'description' => __( 'Enter the heading of your skill bar.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'percent',
            'heading'     => __( 'Percent', '__x__' ),
            'description' => __( 'Enter the percentage of your skill and be sure to include the percentage sign (i.e. 90%).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'bar_text',
            'heading'     => __( 'Bar Text', '__x__' ),
            'description' => __( 'Enter in some alternate text in place of the percentage inside the skill bar.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Code.
    //

    vc_map(
      array(
        'base'        => 'code',
        'name'        => __( 'Code', '__x__' ),
        'weight'      => 740,
        'class'       => 'x-content-element x-content-element-code',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Add a block of example code to your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Buttons.
    //

    vc_map(
      array(
        'base'        => 'button',
        'name'        => __( 'Button', '__x__' ),
        'weight'      => 720,
        'class'       => 'x-content-element x-content-element-button',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Marketing', '__x__' ),
        'description' => __( 'Add a clickable button to your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'shape',
            'heading'     => __( 'Shape', '__x__' ),
            'description' => __( 'Select the button shape.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Square'            => 'square',
              'Rounded'           => 'rounded',
              'Pill'              => 'pill'
            )
          ),
          array(
            'param_name'  => 'size',
            'heading'     => __( 'Size', '__x__' ),
            'description' => __( 'Select the button size.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Mini'              => 'mini',
              'Small'             => 'small',
              'Standard'          => 'regular',
              'Large'             => 'large',
              'Extra Large'       => 'x-large',
              'Jumbo'             => 'jumbo'
            )
          ),
          array(
            'param_name'  => 'float',
            'heading'     => __( 'Float', '__x__' ),
            'description' => __( 'Optionally float the button.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Left'              => 'left',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'block',
            'heading'     => __( 'Block', '__x__' ),
            'description' => __( 'Select to make your button go fullwidth.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'circle',
            'heading'     => __( 'Marketing Circle', '__x__' ),
            'description' => __( 'Select to include a marketing circle around your button.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'icon_only',
            'heading'     => __( 'Icon Only', '__x__' ),
            'description' => __( 'Select if you are only using an icon in your button.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'href',
            'heading'     => __( 'Href', '__x__' ),
            'description' => __( 'Enter in the URL you want your button to link to.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter in the title attribute you want for your button (will also double as title for popover or tooltip if you have chosen to display one).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'target',
            'heading'     => __( 'Target', '__x__' ),
            'description' => __( 'Select to open your button link in a new window.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'blank'
            )
          ),
          array(
            'param_name'  => 'info',
            'heading'     => __( 'Info', '__x__' ),
            'description' => __( 'Select whether or not you want to add a popover or tooltip to your button.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Popover'           => 'popover',
              'Tooltip'           => 'tooltip'
            )
          ),
          array(
            'param_name'  => 'info_place',
            'heading'     => __( 'Info Placement', '__x__' ),
            'description' => __( 'Select where you want your popover or tooltip to appear.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Top'               => 'top',
              'Left'              => 'left',
              'Right'             => 'right',
              'Bottom'            => 'bottom'
            )
          ),
          array(
            'param_name'  => 'info_trigger',
            'heading'     => __( 'Info Trigger', '__x__' ),
            'description' => __( 'Select what actions you want to trigger the popover or tooltip.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Hover'             => 'hover',
              'Click'             => 'click',
              'Focus'             => 'focus'
            )
          ),
          array(
            'param_name'  => 'info_content',
            'heading'     => __( 'Info Content', '__x__' ),
            'description' => __( 'Extra content for the popover.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lightbox_thumb',
            'heading'     => __( 'Lightbox Thumbnail', '__x__' ),
            'description' => __( 'Use this option to select a thumbnail for your lightbox thumbnail navigation or to set an image if you are linking out to a video.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lightbox_video',
            'heading'     => __( 'Lightbox Video', '__x__' ),
            'description' => __( 'Select if you are linking to a video from this button in the lightbox.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'lightbox_caption',
            'heading'     => __( 'Lightbox Caption', '__x__' ),
            'description' => __( 'Lightbox caption text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Block grid.
    //

    vc_map(
      array(
        'base'            => 'block_grid',
        'name'            => __( 'Block Grid', '__x__' ),
        'weight'          => 880,
        'class'           => 'x-content-element x-content-element-block-grid',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a block grid container in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'block_grid_item' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Type', '__x__' ),
            'description' => __( 'Select how many block grid items you want per row.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Two'               => 'two-up',
              'Three'             => 'three-up',
              'Four'              => 'four-up'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Block grid item.
    //

    vc_map(
      array(
        'base'            => 'block_grid_item',
        'name'            => __( 'Block Grid Item', '__x__' ),
        'weight'          => 870,
        'class'           => 'x-content-element x-content-element-block-grid-item',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a block grid item in your block grid', '__x__' ),
        'as_child'        => array( 'only' => 'block_grid' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Images.
    //

    vc_map(
      array(
        'base'        => 'image',
        'name'        => __( 'Image', '__x__' ),
        'weight'      => 610,
        'class'       => 'x-content-element x-content-element-image',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Include an image in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( 'Select the image style.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Thumbnail'         => 'thumbnail',
              'Rounded'           => 'rounded',
              'Circle'            => 'circle'
            )
          ),
          array(
            'param_name'  => 'float',
            'heading'     => __( 'Float', '__x__' ),
            'description' => __( 'Optionally float the image.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Left'              => 'left',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'src',
            'heading'     => __( 'Src', '__x__' ),
            'description' => __( 'Enter your image.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'alt',
            'heading'     => __( 'Alt', '__x__' ),
            'description' => __( 'Enter in the alt text for your image.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'link',
            'heading'     => __( 'Link', '__x__' ),
            'description' => __( 'Select to wrap your image in an anchor tag.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'href',
            'heading'     => __( 'Href', '__x__' ),
            'description' => __( 'Enter in the URL you want your image to link to. If using this image for a lightbox, enter the URL of your media here (e.g. YouTube embed URL, et cetera). Leave this field blank if you want to link to the image uploaded to the "Src" for your lightbox.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter in the title attribute you want for your image (will also double as title for popover or tooltip if you have chosen to display one).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'target',
            'heading'     => __( 'Target', '__x__' ),
            'description' => __( 'Select to open your image link in a new window.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'blank'
            )
          ),
          array(
            'param_name'  => 'info',
            'heading'     => __( 'Info', '__x__' ),
            'description' => __( 'Select whether or not you want to add a popover or tooltip to your image.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Popover'           => 'popover',
              'Tooltip'           => 'tooltip'
            )
          ),
          array(
            'param_name'  => 'info_place',
            'heading'     => __( 'Info Placement', '__x__' ),
            'description' => __( 'Select where you want your popover or tooltip to appear.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Top'               => 'top',
              'Left'              => 'left',
              'Right'             => 'right',
              'Bottom'            => 'bottom'
            )
          ),
          array(
            'param_name'  => 'info_trigger',
            'heading'     => __( 'Info Trigger', '__x__' ),
            'description' => __( 'Select what actions you want to trigger the popover or tooltip.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Hover'             => 'hover',
              'Click'             => 'click',
              'Focus'             => 'focus'
            )
          ),
          array(
            'param_name'  => 'info_content',
            'heading'     => __( 'Info Content', '__x__' ),
            'description' => __( 'Extra content for the popover.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lightbox_thumb',
            'heading'     => __( 'Lightbox Thumbnail', '__x__' ),
            'description' => __( 'Use this option to select a different thumbnail for your lightbox thumbnail navigation or to set an image if you are linking out to a video. Will default to the "Src" image if nothing is set.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'lightbox_video',
            'heading'     => __( 'Lightbox Video', '__x__' ),
            'description' => __( 'Select if you are linking to a video from this image in the lightbox.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'lightbox_caption',
            'heading'     => __( 'Lightbox Caption', '__x__' ),
            'description' => __( 'Lightbox caption text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Icon list.
    //

    vc_map(
      array(
        'base'            => 'icon_list',
        'name'            => __( 'Icon List', '__x__' ),
        'weight'          => 780,
        'class'           => 'x-content-element x-content-element-icon-list',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Typography', '__x__' ),
        'description'     => __( 'Include an icon list in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'icon_list_item' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Icon list item.
    //

    vc_map(
      array(
        'base'            => 'icon_list_item',
        'name'            => __( 'Icon List Item', '__x__' ),
        'weight'          => 770,
        'class'           => 'x-content-element x-content-element-icon-list-item',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Typography', '__x__' ),
        'description'     => __( 'Include an icon list item in your icon list', '__x__' ),
        'as_child'        => array( 'only' => 'icon_list' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Type', '__x__' ),
            'description' => __( 'Select your icon.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => $param_icon_value
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Text columns.
    //

    vc_map(
      array(
        'base'        => 'columnize',
        'name'        => __( 'Columnize', '__x__' ),
        'weight'      => 860,
        'class'       => 'x-content-element x-content-element-columnize',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Content', '__x__' ),
        'description' => __( 'Split your text into multiple columns', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Video player.
    //

    vc_map(
      array(
        'base'        => 'x_video_player',
        'name'        => __( 'Video (Self Hosted)', '__x__' ),
        'weight'      => 570,
        'class'       => 'x-content-element x-content-element-x-video-player',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Include responsive video into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Aspect Ratio', '__x__' ),
            'description' => __( 'Select your aspect ratio.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              '16:9'              => '16:9',
              '5:3'               => '5:3',
              '5:4'               => '5:4',
              '4:3'               => '4:3',
              '3:2'               => '3:2'
            )
          ),
          array(
            'param_name'  => 'm4v',
            'heading'     => __( 'M4V', '__x__' ),
            'description' => __( 'Include and .m4v version of your video.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'ogv',
            'heading'     => __( 'OGV', '__x__' ),
            'description' => __( 'Include and .ogv version of your video for additional native browser support.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'poster',
            'heading'     => __( 'Poster Image', '__x__' ),
            'description' => __( 'Include a poster image for your self-hosted video.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'advanced_controls',
            'heading'     => __( 'Advanced Controls', '__x__' ),
            'description' => __( 'Select to enable advanced controls on your self-hosted video.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'hide_controls',
            'heading'     => __( 'Hide Controls', '__x__' ),
            'description' => __( 'Select to hide the controls on your self-hosted video.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'autoplay',
            'heading'     => __( 'Autoplay', '__x__' ),
            'description' => __( 'Select to automatically play your self-hosted video.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'no_container',
            'heading'     => __( 'No Container', '__x__' ),
            'description' => __( 'Select to remove the container around the video.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Video embed.
    //

    vc_map(
      array(
        'base'        => 'x_video_embed',
        'name'        => __( 'Video (Embedded)', '__x__' ),
        'weight'      => 560,
        'class'       => 'x-content-element x-content-element-x-video-embed',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Include responsive video into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Code (See Notes Below)', '__x__' ),
            'description' => __( 'Switch to the "text" editor and do not place anything else here other than your &lsaquo;iframe&rsaquo; or &lsaquo;embed&rsaquo; code.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Aspect Ratio', '__x__' ),
            'description' => __( 'Select your aspect ratio.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              '16:9'              => '16:9',
              '5:3'               => '5:3',
              '5:4'               => '5:4',
              '4:3'               => '4:3',
              '3:2'               => '3:2'
            )
          ),
          array(
            'param_name'  => 'no_container',
            'heading'     => __( 'No Container', '__x__' ),
            'description' => __( 'Select to remove the container around the video.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Accordion.
    //

    vc_map(
      array(
        'base'            => 'accordion',
        'name'            => __( 'Accordion', '__x__' ),
        'weight'          => 930,
        'class'           => 'x-content-element x-content-element-accordion',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include an accordion into your content', '__x__' ),
        'as_parent'       => array( 'only' => 'accordion_item' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Accordion item.
    //

    vc_map(
      array(
        'base'            => 'accordion_item',
        'name'            => __( 'Accordion Item', '__x__' ),
        'weight'          => 940,
        'class'           => 'x-content-element x-content-element-accordion-item',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include an accordion item in your accordion', '__x__' ),
        'as_child'        => array( 'only' => 'accordion' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'parent_id',
            'heading'     => __( 'Parent ID', '__x__' ),
            'description' => __( 'Optionally include an ID given to the parent accordion to only allow one toggle to be open at a time.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Include a title for your accordion item.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'open',
            'heading'     => __( 'Open', '__x__' ),
            'description' => __( 'Select for your accordion item to be open by default.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Tab nav.
    //

    vc_map(
      array(
        'base'            => 'tab_nav',
        'name'            => __( 'Tab Nav', '__x__' ),
        'weight'          => 920,
        'class'           => 'x-content-element x-content-element-tab-nav',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a tab nav into your content', '__x__' ),
        'as_parent'       => array( 'only' => 'tab_nav_item' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Tab Nav Items Per Row', '__x__' ),
            'description' => __( 'If your tab nav is on top, select how many tab nav items you want per row.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Two'               => 'two-up',
              'Three'             => 'three-up',
              'Four'              => 'four-up',
              'Five'              => 'five-up'
            )
          ),
          array(
            'param_name'  => 'float',
            'heading'     => __( 'Tab Nav Position', '__x__' ),
            'description' => __( 'Select the position of your tab nav.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'None'              => 'none',
              'Left'              => 'left',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Tab nav item.
    //

    vc_map(
      array(
        'base'            => 'tab_nav_item',
        'name'            => __( 'Tab Nav Item', '__x__' ),
        'weight'          => 910,
        'class'           => 'x-content-element x-content-element-tab-nav-item',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a tab nav item into your tab nav', '__x__' ),
        'as_child'        => array( 'only' => 'tab_nav' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Include a title for your tab nav item.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'active',
            'heading'     => __( 'Active', '__x__' ),
            'description' => __( 'Select to make this tab nav item active.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Tabs.
    //

    vc_map(
      array(
        'base'            => 'tabs',
        'name'            => __( 'Tabs', '__x__' ),
        'weight'          => 900,
        'class'           => 'x-content-element x-content-element-tabs',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a tabs container after your tab nav', '__x__' ),
        'as_parent'       => array( 'only' => 'tab' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Tab.
    //

    vc_map(
      array(
        'base'            => 'tab',
        'name'            => __( 'Tab', '__x__' ),
        'weight'          => 890,
        'class'           => 'x-content-element x-content-element-tab',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Include a tab into your tabs container', '__x__' ),
        'as_child'        => array( 'only' => 'tabs' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'active',
            'heading'     => __( 'Active', '__x__' ),
            'description' => __( 'Select to make this tab active.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Responsive visibility.
    //

    vc_map(
      array(
        'base'            => 'visibility',
        'name'            => __( 'Visibility', '__x__' ),
        'weight'          => 850,
        'class'           => 'x-content-element x-content-element-visibility',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Content', '__x__' ),
        'description'     => __( 'Alter content based on screen size', '__x__' ),
        'as_parent'       => array( 'only' => 'vc_row, line, gap, clear, highlight, container, blockquote, pullquote, alert, map, skill_bar, code, button, icon, block_grid, image, icon_list, info, columnize, x_video_player, x_video_embed, accordion, tab_nav, tabs, slider, protect, recent_posts, x_audio_player, x_audio_embed, pricing_table, callout, promo, lightbox, author, prompt, share, toc, custom_headline, social, feature_headline, responsive_text, search, text_output, rev_slider_vc, contact-form-7' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Visibility Type', '__x__' ),
            'description' => __( 'Select how you want to hide or show your content.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Hidden Phone'      => 'hidden-phone',
              'Hidden Tablet'     => 'hidden-tablet',
              'Hidden Desktop'    => 'hidden-desktop',
              'Visible Phone'     => 'visible-phone',
              'Visible Tablet'    => 'visible-tablet',
              'Visible Desktop'   => 'visible-desktop'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Slider.
    //

    vc_map(
      array(
        'base'            => 'slider',
        'name'            => __( 'Slider', '__x__' ),
        'weight'          => 590,
        'class'           => 'x-content-element x-content-element-slider',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Media', '__x__' ),
        'description'     => __( 'Include a slider in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'slide' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'animation',
            'heading'     => __( 'Animation', '__x__' ),
            'description' => __( 'Select your slider animation.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Slide'             => 'slide',
              'Fade'              => 'fade'
            )
          ),
          array(
            'param_name'  => 'slide_time',
            'heading'     => __( 'Slide Time', '__x__' ),
            'description' => __( 'The amount of time a slide will stay visible in milliseconds.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '5000'
          ),
          array(
            'param_name'  => 'slide_speed',
            'heading'     => __( 'Slide Speed', '__x__' ),
            'description' => __( 'The amount of time to transition between slides in milliseconds.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '650'
          ),
          array(
            'param_name'  => 'slideshow',
            'heading'     => __( 'Slideshow', '__x__' ),
            'description' => __( 'Select for your slides to advance automatically.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'random',
            'heading'     => __( 'Random', '__x__' ),
            'description' => __( 'Select to randomly display your slides each time the page loads.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'control_nav',
            'heading'     => __( 'Control Navigation', '__x__' ),
            'description' => __( 'Select to display the control navigation.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'prev_next_nav',
            'heading'     => __( 'Previous/Next Navigation', '__x__' ),
            'description' => __( 'Select to display the previous/next navigation.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'no_container',
            'heading'     => __( 'No Container', '__x__' ),
            'description' => __( 'Select to remove the container from your slider.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Slide.
    //

    vc_map(
      array(
        'base'            => 'slide',
        'name'            => __( 'Slide', '__x__' ),
        'weight'          => 600,
        'class'           => 'x-content-element x-content-element-slide',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Media', '__x__' ),
        'description'     => __( 'Include a slide into your slider', '__x__' ),
        'as_child'        => array( 'only' => 'slider' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Protected content.
    //

    vc_map(
      array(
        'base'        => 'protect',
        'name'        => __( 'Protect', '__x__' ),
        'weight'      => 840,
        'class'       => 'x-content-element x-content-element-protect',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Content', '__x__' ),
        'description' => __( 'Protect content from non logged in users', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Recent posts.
    //

    vc_map(
      array(
        'base'        => 'recent_posts',
        'name'        => __( 'Recent Posts', '__x__' ),
        'weight'      => 490,
        'class'       => 'x-content-element x-content-element-recent-posts',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Social', '__x__' ),
        'description' => __( 'Display your most recent posts', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Post Type', '__x__' ),
            'description' => __( 'Choose between standard posts or portfolio posts.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Posts'             => 'post',
              'Portfolio'         => 'portfolio'
            )
          ),
          array(
            'param_name'  => 'count',
            'heading'     => __( 'Post Count', '__x__' ),
            'description' => __( 'Select how many posts to display.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              '1'                 => '1',
              '2'                 => '2',
              '3'                 => '3',
              '4'                 => '4'
            )
          ),
          array(
            'param_name'  => 'offset',
            'heading'     => __( 'Offset', '__x__' ),
            'description' => __( 'Enter a number to offset initial starting post of your recent posts.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'category',
            'heading'     => __( 'Category', '__x__' ),
            'description' => __( 'To filter your posts by category, enter in the slug of your desired category. To filter by multiple categories, enter in your slugs separated by a comma.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'orientation',
            'heading'     => __( 'Orientation', '__x__' ),
            'description' => __( 'Select the orientation or your recent posts.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Horizontal'        => 'horizontal',
              'Vertical'          => 'vertical'
            )
          ),
          array(
            'param_name'  => 'no_image',
            'heading'     => __( 'Remove Featured Image', '__x__' ),
            'description' => __( 'Select to remove the featured image.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'fade',
            'heading'     => __( 'Fade Effect', '__x__' ),
            'description' => __( 'Select to activate the fade effect.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Audio player.
    //

    vc_map(
      array(
        'base'        => 'x_audio_player',
        'name'        => __( 'Audio (Self Hosted)', '__x__' ),
        'weight'      => 550,
        'class'       => 'x-content-element x-content-element-x-audio-player',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Place audio files into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'mp3',
            'heading'     => __( 'MP3', '__x__' ),
            'description' => __( 'Include and .mp3 version of your audio.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'oga',
            'heading'     => __( 'OGA', '__x__' ),
            'description' => __( 'Include and .oga version of your audio for additional native browser support.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'advanced_controls',
            'heading'     => __( 'Advanced Controls', '__x__' ),
            'description' => __( 'Select to enable advanced controls on your self-hosted audio.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Audio embed.
    //

    vc_map(
      array(
        'base'        => 'x_audio_embed',
        'name'        => __( 'Audio (Embedded)', '__x__' ),
        'weight'      => 540,
        'class'       => 'x-content-element x-content-element-x-audio-embed',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Place audio files into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Code (See Notes Below)', '__x__' ),
            'description' => __( 'Switch to the "text" editor and do not place anything else here other than your &lsaquo;iframe&rsaquo; or &lsaquo;embed&rsaquo; code.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Pricing table.
    //

    vc_map(
      array(
        'base'            => 'pricing_table',
        'name'            => __( 'Pricing Table', '__x__' ),
        'weight'          => 680,
        'class'           => 'x-content-element x-content-element-pricing-table',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Marketing', '__x__' ),
        'description'     => __( 'Include a pricing table in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'pricing_table_column' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'columns',
            'heading'     => __( 'Columns', '__x__' ),
            'description' => __( 'Select how many columns you want for your pricing table.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              '1'                 => '1',
              '2'                 => '2',
              '3'                 => '3',
              '4'                 => '4',
              '5'                 => '5'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Pricing table column.
    //

    vc_map(
      array(
        'base'            => 'pricing_table_column',
        'name'            => __( 'Pricing Table Column', '__x__' ),
        'weight'          => 670,
        'class'           => 'x-content-element x-content-element-pricing-table-column',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Marketing', '__x__' ),
        'description'     => __( 'Include a pricing table column', '__x__' ),
        'as_child'        => array( 'only' => 'pricing_table' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Include a title for your pricing column.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'featured',
            'heading'     => __( 'Featured', '__x__' ),
            'description' => __( 'Select to make this your featured offer.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'featured_sub',
            'heading'     => __( 'Featured Sub Heading', '__x__' ),
            'description' => __( 'Include a sub heading for your featured column.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'currency',
            'heading'     => __( 'Currency Symbol', '__x__' ),
            'description' => __( 'Enter in the currency symbol you want to use.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'price',
            'heading'     => __( 'Price', '__x__' ),
            'description' => __( 'Enter in the price for this pricing column.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'interval',
            'heading'     => __( 'Interval', '__x__' ),
            'description' => __( 'Enter in the time period that this pricing column is for.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 'Per Month'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Callout.
    //

    vc_map(
      array(
        'base'        => 'callout',
        'name'        => __( 'Callout', '__x__' ),
        'weight'      => 710,
        'class'       => 'x-content-element x-content-element-callout',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Marketing', '__x__' ),
        'description' => __( 'Include a marketing callout into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select the alignment for your callout.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Center'            => 'center',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter the title for your callout.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'message',
            'heading'     => __( 'Message', '__x__' ),
            'description' => __( 'Enter the message for your callout.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'button_text',
            'heading'     => __( 'Button Text', '__x__' ),
            'description' => __( 'Enter the text for your callout button.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'button_icon',
            'heading'     => __( 'Button Icon', '__x__' ),
            'description' => __( 'Optionally enter the button icon.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => $param_icon_value
          ),
          array(
            'param_name'  => 'circle',
            'heading'     => __( 'Marketing Circle', '__x__' ),
            'description' => __( 'Select to include a marketing circle around your button.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'href',
            'heading'     => __( 'Href', '__x__' ),
            'description' => __( 'Enter in the URL you want your callout button to link to.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'target',
            'heading'     => __( 'Target', '__x__' ),
            'description' => __( 'Select to open your callout link button in a new window.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'blank'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Promo.
    //

    vc_map(
      array(
        'base'        => 'promo',
        'name'        => __( 'Promo', '__x__' ),
        'weight'      => 700,
        'class'       => 'x-content-element x-content-element-promo',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Marketing', '__x__' ),
        'description' => __( 'Include a marketing promo in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'image',
            'heading'     => __( 'Promo Image', '__x__' ),
            'description' => __( 'Include an image for your promo element.', '__x__' ),
            'type'        => 'attach_image',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'alt',
            'heading'     => __( 'Alt', '__x__' ),
            'description' => __( 'Enter in the alt text for your promo image.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Responsive lightbox.
    //

    vc_map(
      array(
        'base'        => 'lightbox',
        'name'        => __( 'Responsive Lightbox', '__x__' ),
        'weight'      => 580,
        'class'       => 'x-content-element x-content-element-lightbox',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Media', '__x__' ),
        'description' => __( 'Display images in a responsive lightbox', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'selector',
            'heading'     => __( 'Selector', '__x__' ),
            'description' => __( 'Enter in the selector for your images (e.g. if your class is "lightbox-img" enter ".lightbox-img"). Set to ".x-img-link" to automatically setup a lightbox for all linked [image] shortcodes on your page.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '.x-img-link'
          ),
          array(
            'param_name'  => 'deeplink',
            'heading'     => __( 'Deeplink', '__x__' ),
            'description' => __( 'Select to activate deeplinking (creates unique link for each image).', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'opacity',
            'heading'     => __( 'Backdrop Opacity', '__x__' ),
            'description' => __( 'Enter in the opacity for the backdrop (valid inputs are numbers 0 to 1).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0.875'
          ),
          array(
            'param_name'  => 'prev_scale',
            'heading'     => __( 'Previous Item Scale', '__x__' ),
            'description' => __( 'Enter in the scale for the previous item (valid inputs are numbers 0 to 1).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0.75'
          ),
          array(
            'param_name'  => 'prev_opacity',
            'heading'     => __( 'Previous Item Opacity', '__x__' ),
            'description' => __( 'Enter in the opacity for the previous item (valid inputs are numbers 0 to 1).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0.75'
          ),
          array(
            'param_name'  => 'next_scale',
            'heading'     => __( 'Next Item Scale', '__x__' ),
            'description' => __( 'Enter in the scale for the next item (valid inputs are numbers 0 to 1).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0.75'
          ),
          array(
            'param_name'  => 'next_opacity',
            'heading'     => __( 'Next Item Opacity', '__x__' ),
            'description' => __( 'Enter in the opacity for the next item (valid inputs are numbers 0 to 1).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0.75'
          ),
          array(
            'param_name'  => 'orientation',
            'heading'     => __( 'Orientation', '__x__' ),
            'description' => __( 'Select the orientation of your lightbox.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Horizontal'        => 'horizontal',
              'Vertical'          => 'vertical'
            )
          ),
          array(
            'param_name'  => 'thumbnails',
            'heading'     => __( 'Thumbnails', '__x__' ),
            'description' => __( 'Select to activate thumbnail navigation.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          )
        )
      )
    );


    //
    // Post author.
    //

    vc_map(
      array(
        'base'        => 'author',
        'name'        => __( 'Author', '__x__' ),
        'weight'      => 510,
        'class'       => 'x-content-element x-content-element-author',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Social', '__x__' ),
        'description' => __( 'Include post author information', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter in a title for your author information.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 'About the Author'
          ),
          array(
            'param_name'  => 'author_id',
            'heading'     => __( 'Author ID', '__x__' ),
            'description' => __( 'By default the author of the post or page will be output by leaving this input blank. If you would like to output the information of another author, enter in their user ID here.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Prompt.
    //

    vc_map(
      array(
        'base'        => 'prompt',
        'name'        => __( 'Prompt', '__x__' ),
        'weight'      => 690,
        'class'       => 'x-content-element x-content-element-prompt',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Marketing', '__x__' ),
        'description' => __( 'Include a marketing prompt into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select the alignment of your prompt.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter the title for your prompt.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'message',
            'heading'     => __( 'Message', '__x__' ),
            'description' => __( 'Enter the message for your prompt.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'button_text',
            'heading'     => __( 'Button Text', '__x__' ),
            'description' => __( 'Enter the text for your prompt button.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'button_icon',
            'heading'     => __( 'Button Icon', '__x__' ),
            'description' => __( 'Optionally enter the button icon.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => $param_icon_value
          ),
          array(
            'param_name'  => 'circle',
            'heading'     => __( 'Marketing Circle', '__x__' ),
            'description' => __( 'Select to include a marketing circle around your button.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'href',
            'heading'     => __( 'Href', '__x__' ),
            'description' => __( 'Enter in the URL you want your prompt button to link to.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'target',
            'heading'     => __( 'Target', '__x__' ),
            'description' => __( 'Select to open your prompt button link in a new window.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'blank'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Entry share.
    //

    vc_map(
      array(
        'base'        => 'share',
        'name'        => __( 'Social Sharing', '__x__' ),
        'weight'      => 500,
        'class'       => 'x-content-element x-content-element-share',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Social', '__x__' ),
        'description' => __( 'Include social sharing into your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Enter in a title for your social links.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 'Share this Post'
          ),
          array(
            'param_name'  => 'facebook',
            'heading'     => __( 'Facebook', '__x__' ),
            'description' => __( 'Select to activate the Facebook sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'twitter',
            'heading'     => __( 'Twitter', '__x__' ),
            'description' => __( 'Select to activate the Twitter sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'linkedin',
            'heading'     => __( 'LinkedIn', '__x__' ),
            'description' => __( 'Select to activate the LinkedIn sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'pinterest',
            'heading'     => __( 'Pinterest', '__x__' ),
            'description' => __( 'Select to activate the Pinterest sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'reddit',
            'heading'     => __( 'Reddit', '__x__' ),
            'description' => __( 'Select to activate the Reddit sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'email',
            'heading'     => __( 'Email', '__x__' ),
            'description' => __( 'Select to activate the email sharing link.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Table of contents.
    //

    vc_map(
      array(
        'base'            => 'toc',
        'name'            => __( 'Table of Contents', '__x__' ),
        'weight'          => 630,
        'class'           => 'x-content-element x-content-element-toc',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Information', '__x__' ),
        'description'     => __( 'Include a table of contents in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'toc_item' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Set the title of the table of contents.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => 'Table of Contents'
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select the alignment of your table of contents.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Right'             => 'right',
              'Fullwidth'         => 'block'
            )
          ),
          array(
            'param_name'  => 'columns',
            'heading'     => __( 'Columns', '__x__' ),
            'description' => __( 'Select a column count for your links if you have chosen "Fullwidth" as your alignment.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              '1'                 => '1',
              '2'                 => '2',
              '3'                 => '3'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Table of contents item.
    //

    vc_map(
      array(
        'base'            => 'toc_item',
        'name'            => __( 'Table of Contents Item', '__x__' ),
        'weight'          => 620,
        'class'           => 'x-content-element x-content-element-toc-item',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Information', '__x__' ),
        'description'     => __( 'Include a table of contents item', '__x__' ),
        'as_child'        => array( 'only' => 'toc' ),
        'content_element' => true,
        'params'          => array(
          array(
            'param_name'  => 'title',
            'heading'     => __( 'Title', '__x__' ),
            'description' => __( 'Set the title of the table of contents item.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'page',
            'heading'     => __( 'Page', '__x__' ),
            'description' => __( 'Set the page of the table of contents item.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Custom headline.
    //

    vc_map(
      array(
        'base'        => 'custom_headline',
        'name'        => __( 'Custom Headline', '__x__' ),
        'weight'      => 830,
        'class'       => 'x-content-element x-content-element-custom-headline',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Include a custom headline in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select which way to align the custom headline.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Center'            => 'center',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'level',
            'heading'     => __( 'Heading Level', '__x__' ),
            'description' => __( 'Select which level to use for your heading (e.g. h2).', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'h1'                => 'h1',
              'h2'                => 'h2',
              'h3'                => 'h3',
              'h4'                => 'h4',
              'h5'                => 'h5',
              'h6'                => 'h6'
            )
          ),
          array(
            'param_name'  => 'looks_like',
            'heading'     => __( 'Looks Like', '__x__' ),
            'description' => __( 'Select which level your heading should look like (e.g. h3).', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'h1'                => 'h1',
              'h2'                => 'h2',
              'h3'                => 'h3',
              'h4'                => 'h4',
              'h5'                => 'h5',
              'h6'                => 'h6'
            )
          ),
          array(
            'param_name'  => 'accent',
            'heading'     => __( 'Accent', '__x__' ),
            'description' => __( 'Select to activate the heading accent.', '__x__' ),
            'type'        => 'checkbox',
            'holder'      => 'div',
            'value'       => array(
              '' => 'true'
            )
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Container.
    //

    vc_map(
      array(
        'base'            => 'container',
        'name'            => __( 'Container', '__x__' ),
        'weight'          => 990,
        'class'           => 'x-content-element x-content-element-container',
        'icon'            => 'x-shortcode',
        'category'        => __( 'Structure', '__x__' ),
        'description'     => __( 'Include a container in your content', '__x__' ),
        'as_parent'       => array( 'only' => 'vc_row, line, gap, clear, highlight, blockquote, pullquote, alert, map, skill_bar, code, button, icon, block_grid, image, icon_list, info, columnize, x_video_player, x_video_embed, accordion, tab_nav, tabs, visibility, slider, protect, recent_posts, x_audio_player, x_audio_embed, pricing_table, callout, promo, lightbox, author, prompt, share, toc, custom_headline, social, feature_headline, responsive_text, search, text_output, rev_slider_vc, contact-form-7' ),
        'content_element' => true,
        'js_view'         => 'VcColumnView',
        'params'          => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Feature headline.
    //

    vc_map(
      array(
        'base'        => 'feature_headline',
        'name'        => __( 'Feature Headline', '__x__' ),
        'weight'      => 820,
        'class'       => 'x-content-element x-content-element-feature-headline',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Include a feature headline in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          ),
          array(
            'param_name'  => 'type',
            'heading'     => __( 'Alignment', '__x__' ),
            'description' => __( 'Select which way to align the feature headline.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'Left'              => 'left',
              'Center'            => 'center',
              'Right'             => 'right'
            )
          ),
          array(
            'param_name'  => 'level',
            'heading'     => __( 'Heading Level', '__x__' ),
            'description' => __( 'Select which level to use for your heading (e.g. h2).', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'h1'                => 'h1',
              'h2'                => 'h2',
              'h3'                => 'h3',
              'h4'                => 'h4',
              'h5'                => 'h5',
              'h6'                => 'h6'
            )
          ),
          array(
            'param_name'  => 'looks_like',
            'heading'     => __( 'Looks Like', '__x__' ),
            'description' => __( 'Select which level your heading should look like (e.g. h3).', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => array(
              '- Select Option -' => '',
              'h1'                => 'h1',
              'h2'                => 'h2',
              'h3'                => 'h3',
              'h4'                => 'h4',
              'h5'                => 'h5',
              'h6'                => 'h6'
            )
          ),
          array(
            'param_name'  => 'icon',
            'heading'     => __( 'Icon', '__x__' ),
            'description' => __( 'Select the icon to use with your feature headline.', '__x__' ),
            'type'        => 'dropdown',
            'holder'      => 'div',
            'value'       => $param_icon_value
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Responsive text.
    //

    vc_map(
      array(
        'base'        => 'responsive_text',
        'name'        => __( 'Responsive Text', '__x__' ),
        'weight'      => 730,
        'class'       => 'x-content-element x-content-element-responsive-text',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Typography', '__x__' ),
        'description' => __( 'Include responsive text in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'selector',
            'heading'     => __( 'Selector', '__x__' ),
            'description' => __( 'Enter in the selector for your responsive text (e.g. if your class is "h-responsive" enter ".h-responsive").', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
          ),
          array(
            'param_name'  => 'compression',
            'heading'     => __( 'Compression', '__x__' ),
            'description' => __( 'Enter the compression for your responsive text (adjust up and down to desired level in small increments).', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '1.0'
          ),
          array(
            'param_name'  => 'min_size',
            'heading'     => __( 'Minimum Size', '__x__' ),
            'description' => __( 'Enter the minimum size of your responsive text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'max_size',
            'heading'     => __( 'Maximum Size', '__x__' ),
            'description' => __( 'Enter the maximum size of your responsive text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Search.
    //

    vc_map(
      array(
        'base'        => 'search',
        'name'        => __( 'Search', '__x__' ),
        'weight'      => 480,
        'class'       => 'x-content-element x-content-element-search',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Content', '__x__' ),
        'description' => __( 'Include a search field in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Counter.
    //

    vc_map(
      array(
        'base'        => 'counter',
        'name'        => __( 'Counter', '__x__' ),
        'weight'      => 470,
        'class'       => 'x-content-element x-content-element-counter',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Information', '__x__' ),
        'description' => __( 'Include an animated number counter in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'num_start',
            'heading'     => __( 'Starting Number', '__x__' ),
            'description' => __( 'Enter in the number that you would like your counter to start from.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '0'
          ),
          array(
            'param_name'  => 'num_end',
            'heading'     => __( 'Ending Number', '__x__' ),
            'description' => __( 'Enter int he number that you would like your counter to end at. This must be higher than your starting number.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '100'
          ),
          array(
            'param_name'  => 'num_speed',
            'heading'     => __( 'Counter Speed', '__x__' ),
            'description' => __( 'The amount of time to transition between numbers in milliseconds.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div',
            'value'       => '1500'
          ),
          array(
            'param_name'  => 'num_prefix',
            'heading'     => __( 'Number Prefix', '__x__' ),
            'description' => __( 'Prefix your number with a symbol or text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'num_suffix',
            'heading'     => __( 'Number Suffix', '__x__' ),
            'description' => __( 'Suffix your number with a symbol or text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'num_color',
            'heading'     => __( 'Number Color', '__x__' ),
            'description' => __( 'Select the color of your number.', '__x__' ),
            'type'        => 'colorpicker',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'text_above',
            'heading'     => __( 'Text Above', '__x__' ),
            'description' => __( 'Optionally include text above your number.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'text_below',
            'heading'     => __( 'Text Below', '__x__' ),
            'description' => __( 'Optionally include text below your number.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'text_color',
            'heading'     => __( 'Text Color', '__x__' ),
            'description' => __( 'Select the color of your text above and below the number if you have include any.', '__x__' ),
            'type'        => 'colorpicker',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'id',
            'heading'     => __( 'ID', '__x__' ),
            'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'class',
            'heading'     => __( 'Class', '__x__' ),
            'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          ),
          array(
            'param_name'  => 'style',
            'heading'     => __( 'Style', '__x__' ),
            'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
            'save_always' => true,
            'type'        => 'textfield',
            'holder'      => 'div'
          )
        )
      )
    );


    //
    // Text output.
    //

    vc_map(
      array(
        'base'        => 'text_output',
        'name'        => __( 'Text', '__x__' ),
        'weight'      => 940,
        'class'       => 'x-content-element x-content-element-text-output',
        'icon'        => 'x-shortcode',
        'category'    => __( 'Content', '__x__' ),
        'description' => __( 'Include a block of text in your content', '__x__' ),
        'params'      => array(
          array(
            'param_name'  => 'content',
            'heading'     => __( 'Text', '__x__' ),
            'description' => __( 'Enter your text.', '__x__' ),
            'save_always' => true,
            'type'        => 'textarea_html',
            'holder'      => 'div',
            'value'       => ''
          )
        )
      )
    );

  }

  add_action( 'vc_before_init', 'x_visual_composer_map_shortcodes' );


  //
  // Extend container class (parents).
  //

  class WPBakeryShortCode_Accordion extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Block_Grid extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Container extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Fade extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Icon_List extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Pricing_Table extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Slider extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Tab_Nav extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Tabs extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Toc extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Visibility extends WPBakeryShortCodesContainer { }
  class WPBakeryShortCode_Google_Map extends WPBakeryShortCodesContainer { }


  //
  // Extend shortcode class (children).
  //

  class WPBakeryShortCode_Accordion_Item extends WPBakeryShortCode { }
  class WPBakeryShortCode_Block_Grid_Item extends WPBakeryShortCode { }
  class WPBakeryShortCode_Icon_List_Item extends WPBakeryShortCode { }
  class WPBakeryShortCode_Pricing_Table_Column extends WPBakeryShortCode { }
  class WPBakeryShortCode_Slide extends WPBakeryShortCode { }
  class WPBakeryShortCode_Tab_Nav_Item extends WPBakeryShortCode { }
  class WPBakeryShortCode_Tab extends WPBakeryShortCode { }
  class WPBakeryShortCode_Toc_Item extends WPBakeryShortCode { }
  class WPBakeryShortCode_Google_Map_marker extends WPBakeryShortCode { }

}



// Update Existing Elements
// =============================================================================

if ( ! function_exists( 'x_visual_composer_update_existing_shortcodes' ) && x_visual_composer_integration_on() ) {

  function x_visual_composer_update_existing_shortcodes() {

    //
    // [vc_row]
    //

    vc_map_update( 'vc_row', array(
      'name'        => __( 'Content Band', '__x__' ),
      'weight'      => 1000,
      'class'       => 'x-content-element x-content-element-content-band',
      'icon'        => 'x-shortcode',
      'category'    => __( 'Structure', '__x__' ),
      'description' => __( 'Place and structure your shortcodes inside of a row', '__x__' )
    ) );

    vc_remove_param( 'vc_row', 'bg_color' );
    vc_remove_param( 'vc_row', 'font_color' );
    vc_remove_param( 'vc_row', 'padding' );
    vc_remove_param( 'vc_row', 'margin_bottom' );
    vc_remove_param( 'vc_row', 'bg_image' );
    vc_remove_param( 'vc_row', 'bg_image_repeat' );
    vc_remove_param( 'vc_row', 'el_class' );
    vc_remove_param( 'vc_row', 'css' );
    vc_remove_param( 'vc_row', 'full_width' );
    vc_remove_param( 'vc_row', 'full_height' );
    vc_remove_param( 'vc_row', 'content_placement' );
    vc_remove_param( 'vc_row', 'video_bg' );
    vc_remove_param( 'vc_row', 'video_bg_url' );
    vc_remove_param( 'vc_row', 'video_bg_parallax' );
    vc_remove_param( 'vc_row', 'parallax' );
    vc_remove_param( 'vc_row', 'parallax_image' );
    vc_remove_param( 'vc_row', 'gap' );
    vc_remove_param( 'vc_row', 'equal_height' );
    vc_remove_param( 'vc_row', 'columns_placement' );
    vc_remove_param( 'vc_row', 'el_id' );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'inner_container',
      'heading'     => __( 'Inner Container', '__x__' ),
      'description' => __( 'Select to insert a container inside of the content band. Use this instead of the [container] shortcode for greater flexibility and to contain multiple columns.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'no_margin',
      'heading'     => __( 'Remove Margin', '__x__' ),
      'description' => __( 'Select to remove the margin from the content band and stack them on top of each other.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'padding_top',
      'heading'     => __( 'Padding Top', '__x__' ),
      'description' => __( 'Set the top padding of the content band (leave blank to keep default).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '0px'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'padding_bottom',
      'heading'     => __( 'Padding Bottom', '__x__' ),
      'description' => __( 'Set the bottom padding of the content band (leave blank to keep default).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '0px'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'border',
      'heading'     => __( 'Border', '__x__' ),
      'description' => __( 'Select whether or not to display a border on your content band.', '__x__' ),
      'type'        => 'dropdown',
      'holder'      => 'div',
      'value'       => array(
        '- Select Option -' => '',
        'None'              => 'none',
        'Top'               => 'top',
        'Left'              => 'left',
        'Right'             => 'right',
        'Bottom'            => 'bottom',
        'Horizontal'        => 'horizontal',
        'Vertical'          => 'vertical',
        'All'               => 'all'
      )
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'bg_color',
      'heading'     => __( 'Background Color', '__x__' ),
      'description' => __( 'Select the background color of your content band (leave blank for "transparent").', '__x__' ),
      'type'        => 'colorpicker',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'bg_pattern',
      'heading'     => __( 'Background Pattern', '__x__' ),
      'description' => __( 'Upload a background pattern to your content band.', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'bg_image',
      'heading'     => __( 'Background Image', '__x__' ),
      'description' => __( 'Upload a background image to your content band (this will overwrite your Background Pattern).', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'parallax',
      'heading'     => __( 'Parallax', '__x__' ),
      'description' => __( 'Select to activate the parallax effect with background patterns and images.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'bg_video',
      'heading'     => __( 'Background Video', '__x__' ),
      'description' => __( 'Include the path to your background video (this will overwrite both your Background Pattern and Background Image).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'bg_video_poster',
      'heading'     => __( 'Background Video Poster', '__x__' ),
      'description' => __( 'Include a poster image for your background video on mobile devices.', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'marginless_columns',
      'heading'     => __( 'Marginless Columns', '__x__' ),
      'description' => __( 'Select to remove the spacing between columns.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'class',
      'heading'     => __( 'Class', '__x__' ),
      'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row', array(
      'param_name'  => 'style',
      'heading'     => __( 'Style', '__x__' ),
      'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );


    //
    // [vc_row_inner]
    //

    vc_map_update( 'vc_row_inner', array(
      'name'        => __( 'Content Band', '__x__' ),
      'weight'      => 1000,
      'class'       => 'x-content-element x-content-element-content-band',
      'icon'        => 'x-shortcode',
      'category'    => __( 'Structure', '__x__' ),
      'description' => __( 'Place and structure your shortcodes inside of a row', '__x__' )
    ) );

    vc_remove_param( 'vc_row_inner', 'font_color' );
    vc_remove_param( 'vc_row_inner', 'el_class' );
    vc_remove_param( 'vc_row_inner', 'css' );
    vc_remove_param( 'vc_row_inner', 'gap' );
    vc_remove_param( 'vc_row_inner', 'equal_height' );
    vc_remove_param( 'vc_row_inner', 'content_placement' );
    vc_remove_param( 'vc_row_inner', 'el_id' );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'inner_container',
      'heading'     => __( 'Inner Container', '__x__' ),
      'description' => __( 'Select to insert a container inside of the content band. Use this instead of the [container] shortcode for greater flexibility and to contain multiple columns.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'no_margin',
      'heading'     => __( 'Remove Margin', '__x__' ),
      'description' => __( 'Select to remove the margin from the content band and stack them on top of each other.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'padding_top',
      'heading'     => __( 'Padding Top', '__x__' ),
      'description' => __( 'Set the top padding of the content band (leave blank to keep default).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '0px'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'padding_bottom',
      'heading'     => __( 'Padding Bottom', '__x__' ),
      'description' => __( 'Set the bottom padding of the content band (leave blank to keep default).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '0px'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'border',
      'heading'     => __( 'Border', '__x__' ),
      'description' => __( 'Select whether or not to display a border on your content band.', '__x__' ),
      'type'        => 'dropdown',
      'holder'      => 'div',
      'value'       => array(
        '- Select Option -' => '',
        'None'              => 'none',
        'Top'               => 'top',
        'Left'              => 'left',
        'Right'             => 'right',
        'Bottom'            => 'bottom',
        'Horizontal'        => 'horizontal',
        'Vertical'          => 'vertical',
        'All'               => 'all'
      )
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'bg_color',
      'heading'     => __( 'Background Color', '__x__' ),
      'description' => __( 'Select the background color of your content band (leave blank for "transparent").', '__x__' ),
      'type'        => 'colorpicker',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'bg_pattern',
      'heading'     => __( 'Background Pattern', '__x__' ),
      'description' => __( 'Upload a background pattern to your content band.', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'bg_image',
      'heading'     => __( 'Background Image', '__x__' ),
      'description' => __( 'Upload a background image to your content band (this will overwrite your Background Pattern).', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'parallax',
      'heading'     => __( 'Parallax', '__x__' ),
      'description' => __( 'Select to activate the parallax effect with background patterns and images.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'bg_video',
      'heading'     => __( 'Background Video', '__x__' ),
      'description' => __( 'Include the path to your background video (this will overwrite both your Background Pattern and Background Image).', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'bg_video_poster',
      'heading'     => __( 'Background Video Poster', '__x__' ),
      'description' => __( 'Include a poster image for your background video on mobile devices.', '__x__' ),
      'type'        => 'attach_image',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'marginless_columns',
      'heading'     => __( 'Marginless Columns', '__x__' ),
      'description' => __( 'Select to remove the spacing between columns.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'class',
      'heading'     => __( 'Class', '__x__' ),
      'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_row_inner', array(
      'param_name'  => 'style',
      'heading'     => __( 'Style', '__x__' ),
      'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );


    //
    // [vc_column]
    //

    vc_remove_param( 'vc_column', 'width' );
    vc_remove_param( 'vc_column', 'offset' );
    vc_remove_param( 'vc_column', 'font_color' );
    vc_remove_param( 'vc_column', 'el_class' );
    vc_remove_param( 'vc_column', 'css' );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'fade',
      'heading'     => __( 'Fade Effect', '__x__' ),
      'description' => __( 'Select to activate the fade effect.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'fade_animation',
      'heading'     => __( 'Fade Animation', '__x__' ),
      'description' => __( 'Select the type of fade animation you want to use.', '__x__' ),
      'type'        => 'dropdown',
      'holder'      => 'div',
      'value'       => array(
        '- Select Option -' => '',
        'In'                => 'in',
        'In From Top'       => 'in-from-top',
        'In From Left'      => 'in-from-left',
        'In From Right'     => 'in-from-right',
        'In From Bottom'    => 'in-from-bottom'
      )
    ) );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'fade_animation_offset',
      'heading'     => __( 'Fade Animation Offset', '__x__' ),
      'description' => __( 'Set how large you want the offset for your fade animation to be.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '45px'
    ) );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'id',
      'heading'     => __( 'ID', '__x__' ),
      'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'class',
      'heading'     => __( 'Class', '__x__' ),
      'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_column', array(
      'param_name'  => 'style',
      'heading'     => __( 'Style', '__x__' ),
      'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );


    //
    // [vc_column_inner]
    //

    vc_remove_param( 'vc_column_inner', 'width' );
    vc_remove_param( 'vc_column_inner', 'offset' );
    vc_remove_param( 'vc_column_inner', 'font_color' );
    vc_remove_param( 'vc_column_inner', 'el_class' );
    vc_remove_param( 'vc_column_inner', 'css' );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'fade',
      'heading'     => __( 'Fade Effect', '__x__' ),
      'description' => __( 'Select to activate the fade effect.', '__x__' ),
      'type'        => 'checkbox',
      'holder'      => 'div',
      'value'       => array(
        '' => 'true'
      )
    ) );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'fade_animation',
      'heading'     => __( 'Fade Animation', '__x__' ),
      'description' => __( 'Select the type of fade animation you want to use.', '__x__' ),
      'type'        => 'dropdown',
      'holder'      => 'div',
      'value'       => array(
        '- Select Option -' => '',
        'In'                => 'in',
        'In From Top'       => 'in-from-top',
        'In From Left'      => 'in-from-left',
        'In From Right'     => 'in-from-right',
        'In From Bottom'    => 'in-from-bottom'
      )
    ) );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'fade_animation_offset',
      'heading'     => __( 'Fade Animation Offset', '__x__' ),
      'description' => __( 'Set how large you want the offset for your fade animation to be.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div',
      'value'       => '45px'
    ) );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'id',
      'heading'     => __( 'ID', '__x__' ),
      'description' => __( '(Optional) Enter a unique ID.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'class',
      'heading'     => __( 'Class', '__x__' ),
      'description' => __( '(Optional) Enter a unique class name.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

    vc_add_param( 'vc_column_inner', array(
      'param_name'  => 'style',
      'heading'     => __( 'Style', '__x__' ),
      'description' => __( '(Optional) Enter inline CSS.', '__x__' ),
      'save_always' => true,
      'type'        => 'textfield',
      'holder'      => 'div'
    ) );

  }

  add_action( 'admin_init', 'x_visual_composer_update_existing_shortcodes' );

}




// Incremental ID Counter for Templates
// =============================================================================

if ( ! function_exists( 'x_visual_composer_templates_id_increment' ) ) {

  function x_visual_composer_templates_id_increment() {
    static $count = 0; $count++;
    return $count;
  }

}



if ( x_visual_composer_integration_on() ) {

// VC Column Inner template
// =============================================================================

// Make [vc_column_inner] behave like the [column] shortcode.
// =============================================================================

function vc_theme_vc_column_inner( $atts, $content = '' ) {
	ob_start();

extract( shortcode_atts( array(
  'id'                    => '',
  'class'                 => '',
  'style'                 => '',
  'width'                 => '',
  'last'                  => '',
  'fade'                  => '',
  'fade_animation'        => '',
  'fade_animation_offset' => '',
  'fade_duration'         => '',
  'bg_color'              => ''
), $atts ) );

$id    = ( $id    != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
$class = ( $class != '' ) ? 'x-column x-sm vc ' . esc_attr( $class ) : 'x-column x-sm vc';
$style = ( $style != '' ) ? $style : '';
$fade_duration         = ( $fade_duration         != ''     ) ? $fade_duration : '750';

switch ( $width ) {
  case '1/1' :
    $width = ' x-1-1';
    break;
  case '1/2' :
    $width = ' x-1-2';
    break;
  case '1/3' :
    $width = ' x-1-3';
    break;
  case '2/3' :
    $width = ' x-2-3';
    break;
  case '1/4' :
    $width = ' x-1-4';
    break;
  case '3/4' :
    $width = ' x-3-4';
    break;
  case '1/5' :
    $width = ' x-1-5';
    break;
  case '1/6' :
    $width = ' x-1-6';
    break;
  case '5/6' :
    $width = ' x-5-6';
    break;
}

if ( $fade == 'true' ) {

  $fade = 'data-fade="true"';

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'column', array( 'fade' => true ) ) : '';

  switch ( $fade_animation ) {
      case 'in' :
        $fade_animation_offset = '';
        break;
      case 'in-from-top' :
        $fade_animation_offset = ' transform: translate(0, -' . $fade_animation_offset . '); ';
        break;
      case 'in-from-left' :
        $fade_animation_offset = ' transform: translate(-' . $fade_animation_offset . ', 0); ';
        break;
      case 'in-from-right' :
        $fade_animation_offset = ' transform: translate(' . $fade_animation_offset . ', 0); ';
        break;
      case 'in-from-bottom' :
        $fade_animation_offset = ' transform: translate(0, ' . $fade_animation_offset . '); ';
        break;
    }

  $fade_animation_style = 'opacity: 0;' . $fade_animation_offset . 'transition-duration: ' . $fade_duration . 'ms;';

} else {
  $data                 = '';
    $fade                 = '';
    $fade_animation_style = '';
}

$output = "<div {$id} class=\"{$class}{$width}{$last}\" style=\"{$style}{$fade_animation_style}{$bg_color}\" {$data}{$fade}>" . do_shortcode( $content ) . "</div>";

echo $output;

return ob_get_clean();
}

// VC Column Template
// =============================================================================

// Make [vc_column] behave like the [column] shortcode.
// =============================================================================

function vc_theme_vc_column( $atts, $content = '' ) {
	ob_start();

extract( shortcode_atts( array(
  'id'                    => '',
  'class'                 => '',
  'style'                 => '',
  'width'                 => '',
  'last'                  => '',
  'fade'                  => '',
  'fade_animation'        => '',
  'fade_animation_offset' => '',
  'fade_duration'         => '',
  'bg_color'              => '',
), $atts ) );

$id    = ( $id    != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
$class = ( $class != '' ) ? 'x-column x-sm vc ' . esc_attr( $class ) : 'x-column x-sm vc';
$style = ( $style != '' ) ? $style : '';
$fade_duration         = ( $fade_duration         != ''     ) ? $fade_duration : '750';

switch ( $width ) {
  case '1/1' :
    $width = ' x-1-1';
    break;
  case '1/2' :
    $width = ' x-1-2';
    break;
  case '1/3' :
    $width = ' x-1-3';
    break;
  case '2/3' :
    $width = ' x-2-3';
    break;
  case '1/4' :
    $width = ' x-1-4';
    break;
  case '3/4' :
    $width = ' x-3-4';
    break;
  case '1/5' :
    $width = ' x-1-5';
    break;
  case '1/6' :
    $width = ' x-1-6';
    break;
  case '5/6' :
    $width = ' x-5-6';
    break;
}

if ( $fade == 'true' ) {

  $fade = 'data-fade="true"';

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'column', array( 'fade' => true ) ) : '';

  switch ( $fade_animation ) {
      case 'in' :
        $fade_animation_offset = '';
        break;
      case 'in-from-top' :
        $fade_animation_offset = ' transform: translate(0, -' . $fade_animation_offset . '); ';
        break;
      case 'in-from-left' :
        $fade_animation_offset = ' transform: translate(-' . $fade_animation_offset . ', 0); ';
        break;
      case 'in-from-right' :
        $fade_animation_offset = ' transform: translate(' . $fade_animation_offset . ', 0); ';
        break;
      case 'in-from-bottom' :
        $fade_animation_offset = ' transform: translate(0, ' . $fade_animation_offset . '); ';
        break;
    }

  $fade_animation_style = 'opacity: 0;' . $fade_animation_offset . 'transition-duration: ' . $fade_duration . 'ms;';

} else {
  $data                 = '';
    $fade                 = '';
    $fade_animation_style = '';
}

$output = "<div {$id} class=\"{$class}{$width}{$last}\" style=\"{$style}{$fade_animation_style}{$bg_color}\" {$data}{$fade}>" . do_shortcode( $content ) . "</div>";

echo $output;

return ob_get_clean();
}

// VC Row Inner Template
// =============================================================================
// Make [vc_row_inner] behave like the [content_band] shortcode.
// =============================================================================

function vc_theme_vc_row_inner( $atts, $content = '' ) {
	ob_start();

extract( shortcode_atts( array(
  'class'              => '',
  'style'              => '',
  'border'             => '',
  'bg_color'           => '',
  'bg_pattern'         => '',
  'bg_image'           => '',
  'bg_video'           => '',
  'bg_video_poster'    => '',
  'no_margin'          => '',
  'padding_top'        => '',
  'padding_bottom'     => '',
  'inner_container'    => '',
  'parallax'           => '',
  'marginless_columns' => ''
), $atts, 'content_band' ) );

$class = ( $class != '' ) ? 'x-content-band vc ' . esc_attr( $class ) : 'x-content-band vc';
$style = ( $style != '' ) ? ' ' . $style : '';
switch ( $border ) {
  case 'top' :
    $border = ' border-top';
    break;
  case 'left' :
    $border = ' border-left';
    break;
  case 'right' :
    $border = ' border-right';
    break;
  case 'bottom' :
    $border = ' border-bottom';
    break;
  case 'vertical' :
    $border = ' border-top border-bottom';
    break;
  case 'horizontal' :
    $border = ' border-left border-right';
    break;
  case 'all' :
    $border = ' border-top border-left border-right border-bottom';
    break;
  default :
    $border = '';
}
$bg_color         = ( $bg_color        != ''     ) ? $bg_color : 'transparent';
$bg_pattern       = ( $bg_pattern      != ''     ) ? $bg_pattern : '';
$bg_pattern_class = ( $bg_pattern      != ''     ) ? ' bg-pattern' : '';
$bg_image         = ( $bg_image        != ''     ) ? $bg_image : '';
$bg_image_class   = ( $bg_image        != ''     ) ? ' bg-image' : '';
$bg_video         = ( $bg_video        != ''     ) ? $bg_video : '';
$bg_video_poster  = ( $bg_video_poster != ''     ) ? $bg_video_poster : '';
$bg_video_class   = ( $bg_video        != ''     ) ? ' bg-video' : '';
$no_margin        = ( $no_margin       == 'true' ) ? ' man' : '';
$padding_top      = ( $padding_top     != ''     ) ? ' padding-top: ' . $padding_top . ';' : '';
$padding_bottom   = ( $padding_bottom  != ''     ) ? ' padding-bottom: ' . $padding_bottom . ';' : '';
switch ( $inner_container ) {
  case 'true' :
    $container_start = '<div class="x-container max width wpb_row">';
    $container_end   = '</div>';
    break;
  default :
    $container_start = '<div class="x-container wpb_row">';
    $container_end   = '</div>';
}
$parallax                 = ( $parallax           == 'true' ) ? $parallax : '';
$parallax_class           = ( $parallax           == 'true' ) ? ' parallax' : '';
$marginless_columns       = ( $marginless_columns == 'true' ) ? $marginless_columns : '';
$marginless_columns_class = ( $marginless_columns == 'true' ) ? ' marginless-columns' : '';

if ( is_numeric( $bg_video_poster ) ) {
  $bg_video_poster_info = wp_get_attachment_image_src( $bg_video_poster, 'full' );
  $bg_video_poster      = $bg_video_poster_info[0];
}

if ( is_numeric( $bg_image ) ) {
  $bg_image_info = wp_get_attachment_image_src( $bg_image, 'full' );
  $bg_image      = $bg_image_info[0];
}

if ( is_numeric( $bg_pattern ) ) {
  $bg_pattern_info = wp_get_attachment_image_src( $bg_pattern, 'full' );
  $bg_pattern      = $bg_pattern_info[0];
}

$count = x_visual_composer_templates_id_increment();

if ( $bg_video != '' ) {

  $js_params = array(
    'type' => 'video'
  );

  $data         = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';
  $video_output = ( function_exists( 'cs_bg_video' ) ) ? cs_bg_video( $bg_video, $bg_video_poster ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_video_class}{$marginless_columns_class}{$border}{$no_margin}\" {$data} style=\"{$padding_top}{$padding_bottom}{$style}\">"
            . $video_output
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} elseif ( $bg_image != '' ) {

  $js_params = array(
    'type'     => 'image',
    'parallax' => ( $parallax == 'true' )
  );

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_image_class}{$parallax_class}{$marginless_columns_class}{$border}{$no_margin}\" {$data} style=\"background-image: url({$bg_image}); background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} elseif ( $bg_pattern != '' ) {

  $js_params = array(
    'type'     => 'pattern',
    'parallax' => ( $parallax == 'true' )
  );

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_pattern_class}{$parallax_class}{$marginless_columns_class}{$border}{$no_margin}\" style=\"background-image: url({$bg_pattern}); background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} else {

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$marginless_columns_class}{$border}{$no_margin}\" style=\"background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

}

echo $output;

return ob_get_clean();
}


// VC Row Template
// =============================================================================
// Make [vc_row] behave like the [content_band] shortcode.
// =============================================================================

function vc_theme_vc_row( $atts, $content = '' ) {
	ob_start();

extract( shortcode_atts( array(
  'class'              => '',
  'style'              => '',
  'border'             => '',
  'bg_color'           => '',
  'bg_pattern'         => '',
  'bg_image'           => '',
  'bg_video'           => '',
  'bg_video_poster'    => '',
  'no_margin'          => '',
  'padding_top'        => '',
  'padding_bottom'     => '',
  'inner_container'    => '',
  'parallax'           => '',
  'marginless_columns' => ''
), $atts, 'content_band' ) );

$class = ( $class != '' ) ? 'x-content-band vc ' . esc_attr( $class ) : 'x-content-band vc';
$style = ( $style != '' ) ? ' ' . $style : '';
switch ( $border ) {
  case 'top' :
    $border = ' border-top';
    break;
  case 'left' :
    $border = ' border-left';
    break;
  case 'right' :
    $border = ' border-right';
    break;
  case 'bottom' :
    $border = ' border-bottom';
    break;
  case 'vertical' :
    $border = ' border-top border-bottom';
    break;
  case 'horizontal' :
    $border = ' border-left border-right';
    break;
  case 'all' :
    $border = ' border-top border-left border-right border-bottom';
    break;
  default :
    $border = '';
}
$bg_color         = ( $bg_color        != ''     ) ? $bg_color : 'transparent';
$bg_pattern       = ( $bg_pattern      != ''     ) ? $bg_pattern : '';
$bg_pattern_class = ( $bg_pattern      != ''     ) ? ' bg-pattern' : '';
$bg_image         = ( $bg_image        != ''     ) ? $bg_image : '';
$bg_image_class   = ( $bg_image        != ''     ) ? ' bg-image' : '';
$bg_video         = ( $bg_video        != ''     ) ? $bg_video : '';
$bg_video_poster  = ( $bg_video_poster != ''     ) ? $bg_video_poster : '';
$bg_video_class   = ( $bg_video        != ''     ) ? ' bg-video' : '';
$no_margin        = ( $no_margin       == 'true' ) ? ' man' : '';
$padding_top      = ( $padding_top     != ''     ) ? ' padding-top: ' . $padding_top . ';' : '';
$padding_bottom   = ( $padding_bottom  != ''     ) ? ' padding-bottom: ' . $padding_bottom . ';' : '';
switch ( $inner_container ) {
  case 'true' :
    $container_start = '<div class="x-container max width wpb_row">';
    $container_end   = '</div>';
    break;
  default :
    $container_start = '<div class="x-container wpb_row">';
    $container_end   = '</div>';
}
$parallax                 = ( $parallax           == 'true' ) ? $parallax : '';
$parallax_class           = ( $parallax           == 'true' ) ? ' parallax' : '';
$marginless_columns       = ( $marginless_columns == 'true' ) ? $marginless_columns : '';
$marginless_columns_class = ( $marginless_columns == 'true' ) ? ' marginless-columns' : '';

if ( is_numeric( $bg_video_poster ) ) {
  $bg_video_poster_info = wp_get_attachment_image_src( $bg_video_poster, 'full' );
  $bg_video_poster      = $bg_video_poster_info[0];
}

if ( is_numeric( $bg_image ) ) {
  $bg_image_info = wp_get_attachment_image_src( $bg_image, 'full' );
  $bg_image      = $bg_image_info[0];
}

if ( is_numeric( $bg_pattern ) ) {
  $bg_pattern_info = wp_get_attachment_image_src( $bg_pattern, 'full' );
  $bg_pattern      = $bg_pattern_info[0];
}

$count = x_visual_composer_templates_id_increment();

if ( $bg_video != '' ) {

  $js_params = array(
    'type' => 'video'
  );

  $data         = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';
  $video_output = ( function_exists( 'cs_bg_video' ) ) ? cs_bg_video( $bg_video, $bg_video_poster ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_video_class}{$marginless_columns_class}{$border}{$no_margin}\" {$data} style=\"{$padding_top}{$padding_bottom}{$style}\">"
            . $video_output
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} elseif ( $bg_image != '' ) {

  $js_params = array(
    'type'     => 'image',
    'parallax' => ( $parallax == 'true' )
  );

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_image_class}{$parallax_class}{$marginless_columns_class}{$border}{$no_margin}\" {$data} style=\"background-image: url({$bg_image}); background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} elseif ( $bg_pattern != '' ) {

  $js_params = array(
    'type'     => 'pattern',
    'parallax' => ( $parallax == 'true' )
  );

  $data = ( function_exists( 'cs_generate_data_attributes' ) ) ? cs_generate_data_attributes( 'content_band', $js_params ) : '';

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$bg_pattern_class}{$parallax_class}{$marginless_columns_class}{$border}{$no_margin}\" {$data} style=\"background-image: url({$bg_pattern}); background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

} else {

  $output = "<div id=\"x-content-band-{$count}\" class=\"{$class}{$marginless_columns_class}{$border}{$no_margin}\" style=\"background-color: {$bg_color};{$padding_top}{$padding_bottom}{$style}\">"
            . $container_start . do_shortcode( $content ) . $container_end
          . '</div>';

}

echo $output;

return ob_get_clean();

	}
}
