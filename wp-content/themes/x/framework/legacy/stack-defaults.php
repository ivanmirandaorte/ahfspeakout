<?php

function x_theme_options_get_defaults() {
  global $x_stack_defaults;
  return $x_stack_defaults;
}

function x_theme_options_get_default( $key ) {
  global $x_stack_defaults;
  return isset( $x_stack_defaults[$key]) ? $x_stack_defaults[$key] : false;
}

function x_theme_options_breakpoints() {
  return [ 480, 767, 979, 1200 ];
}

add_filter('cs_breakpoint_default_ranges', 'x_theme_options_breakpoints');
add_filter('cs_allow_breakpoint_base_change', '__return_true');

$powered_by = '';

if ( X_SLUG === 'x' ) {
  $powered_by = '<p>POWERED BY THE <a href="//theme.co/x/" title="X &ndash; The Ultimate WordPress Theme" rel="nofollow">X THEME</a></p>';
} else if (X_SLUG === 'pro') {
  $powered_by = '<p>POWERED BY <a href="//theme.co/pro/" title="Pro &ndash; The Most Advanced Website Creator for WordPress." rel="nofollow">PRO</a></p>';
}

// 'footer-powered-by' => '<p>POWERED BY THE <a href="//theme.co/x/" title="X &ndash; The Ultimate WordPress Theme" rel="nofollow">X THEME</a></p>'
//
// Master list of Theme Option keys and default values used for creating
// backup files, resetting options, and for utilizing defaults if nothing is
// brought through from Theme Options (i.e. a user doesn't change an option).
//

$blog_title = __( 'The Blog', '__x__' );
$shop_title = __( 'The Shop', '__x__' );

global $x_stack_defaults;

$x_stack_defaults = array(

  'x_stack'                                             => 'integrity',
  'x_integrity_design'                                  => 'light',
  'x_integrity_topbar_transparency_enable'              => '',
  'x_integrity_navbar_transparency_enable'              => '',
  'x_integrity_footer_transparency_enable'              => '',
  'x_integrity_blog_header_enable'                      => '1',
  'x_integrity_blog_title'                              => $blog_title,
  'x_integrity_blog_subtitle'                           => __( 'Welcome to our little corner of the Internet. Kick your feet up and stay a while.', '__x__' ),
  'x_integrity_portfolio_archive_sort_button_text'      => __( 'Sort Portfolio', '__x__' ),
  'x_integrity_portfolio_archive_post_sharing_enable'   => '',
  'x_integrity_shop_header_enable'                      => '1',
  'x_integrity_shop_title'                              => $shop_title,
  'x_integrity_shop_subtitle'                           => __( 'Welcome to our online store. Take some time to browse through our items.', '__x__' ),
  'x_renew_topbar_background'                           => '#1f2c39',
  'x_renew_logobar_background'                          => '#2c3e50',
  'x_renew_navbar_background'                           => '#2c3e50',
  'x_renew_navbar_button_color'                         => '#ffffff',
  'x_renew_navbar_button_background'                    => '#3e5771',
  'x_renew_navbar_button_background_hover'              => '#476481',
  'x_renew_footer_background'                           => '#2c3e50',
  'x_renew_topbar_text_color'                           => '#ffffff',
  'x_renew_topbar_link_color_hover'                     => '#959baf',
  'x_renew_footer_text_color'                           => '#ffffff',
  'x_renew_blog_title'                                  => __( 'The Blog', '__x__' ),
  'x_renew_entry_icon_color'                            => '#dddddd',
  'x_renew_entry_icon_position'                         => 'standard',
  'x_renew_entry_icon_position_horizontal'              => '18%',
  'x_renew_entry_icon_position_vertical'                => '25px',
  'x_renew_shop_title'                                  => $shop_title,
  'x_icon_post_title_icon_enable'                       => '1',
  'x_icon_post_standard_colors_enable'                  => '',
  'x_icon_post_standard_color'                          => '#d1f2eb',
  'x_icon_post_standard_background'                     => '#16a085',
  'x_icon_post_image_colors_enable'                     => '',
  'x_icon_post_image_color'                             => '#d1eedd',
  'x_icon_post_image_background'                        => '#27ae60',
  'x_icon_post_gallery_colors_enable'                   => '',
  'x_icon_post_gallery_color'                           => '#d1eedd',
  'x_icon_post_gallery_background'                      => '#27ae60',
  'x_icon_post_video_colors_enable'                     => '',
  'x_icon_post_video_color'                             => '#e9daef',
  'x_icon_post_video_background'                        => '#8e44ad',
  'x_icon_post_audio_colors_enable'                     => '',
  'x_icon_post_audio_color'                             => '#cfd4d9',
  'x_icon_post_audio_background'                        => '#2c3e50',
  'x_icon_post_quote_colors_enable'                     => '',
  'x_icon_post_quote_color'                             => '#fcf2c8',
  'x_icon_post_quote_background'                        => '#f1c40f',
  'x_icon_post_link_colors_enable'                      => '',
  'x_icon_post_link_color'                              => '#f9d0cc',
  'x_icon_post_link_background'                         => '#c0392b',
  'x_icon_shop_title'                                   => $shop_title,
  'x_ethos_topbar_background'                           => '#222222',
  'x_ethos_navbar_background'                           => '#333333',
  'x_ethos_sidebar_widget_headings_color'               => '#333333',
  'x_ethos_sidebar_color'                               => '#333333',
  'x_ethos_post_carousel_enable'                        => '',
  'x_ethos_post_carousel_count'                         => '6',
  'x_ethos_post_carousel_display'                       => 'most-commented',
  'x_ethos_post_carousel_display_count_extra_large'     => '5',
  'x_ethos_post_carousel_display_count_large'           => '4',
  'x_ethos_post_carousel_display_count_medium'          => '3',
  'x_ethos_post_carousel_display_count_small'           => '2',
  'x_ethos_post_carousel_display_count_extra_small'     => '1',
  'x_ethos_post_slider_blog_enable'                     => '',
  'x_ethos_post_slider_blog_height'                     => '425px',
  'x_ethos_post_slider_blog_count'                      => '5',
  'x_ethos_post_slider_blog_display'                    => 'most-commented',
  'x_ethos_post_slider_archive_enable'                  => '',
  'x_ethos_post_slider_archive_height'                  => '425px',
  'x_ethos_post_slider_archive_count'                   => '5',
  'x_ethos_post_slider_archive_display'                 => 'most-commented',
  'x_ethos_filterable_index_enable'                     => '',
  'x_ethos_filterable_index_categories'                 => '',
  'x_ethos_shop_title'                                  => $shop_title,
  'x_layout_site'                                       => 'full-width',
  'x_layout_site_max_width'                             => '1200px',
  'x_layout_site_width'                                 => '88%',
  'x_layout_content'                                    => 'full-width',
  'x_layout_content_width'                              => '72%',
  'x_layout_sidebar_width'                              => '250',
  'x_design_bg_color'                                   => '#f3f3f3',
  'x_design_bg_image_pattern'                           => '',
  'x_design_bg_image_full'                              => '',
  'x_design_bg_image_full_fade'                         => '750',
  'x_root_font_size_mode'                               => 'stepped',
  'x_root_font_size_stepped_unit'                       => 'px',
  'x_root_font_size_stepped_xs'                         => '14',
  'x_root_font_size_stepped_sm'                         => '14',
  'x_root_font_size_stepped_md'                         => '14',
  'x_root_font_size_stepped_lg'                         => '14',
  'x_root_font_size_stepped_xl'                         => '14',
  'x_root_font_size_scaling_unit'                       => 'px',
  'x_root_font_size_scaling_min'                        => '14',
  'x_root_font_size_scaling_max'                        => '14',
  'x_root_font_size_scaling_lower_limit'                => '500',
  'x_root_font_size_scaling_upper_limit'                => '1000',
  'x_google_fonts_subsets'                              => '',
  'x_google_fonts_subset_cyrillic'                      => '',
  'x_google_fonts_subset_greek'                         => '',
  'x_google_fonts_subset_vietnamese'                    => '',
  'x_body_font_family'                                  => 'Lato',
  'x_body_font_color'                                   => '#999999',
  'x_content_font_size_rem'                             => '1rem',
  'x_body_font_weight'                                  => '400',
  'x_headings_font_family'                              => 'Lato',
  'x_headings_font_color'                               => '#272727',
  'x_headings_font_weight'                              => '700',
  'x_h1_letter_spacing'                                 => '-0.035em',
  'x_h2_letter_spacing'                                 => '-0.035em',
  'x_h3_letter_spacing'                                 => '-0.035em',
  'x_h4_letter_spacing'                                 => '-0.035em',
  'x_h5_letter_spacing'                                 => '-0.035em',
  'x_h6_letter_spacing'                                 => '-0.035em',
  'x_headings_uppercase_enable'                         => '',
  'x_headings_widget_icons_enable'                      => '',
  'x_site_link_color'                                   => '#ff2a13',
  'x_site_link_color_hover'                             => '#d80f0f',
  'x_site_link_oembed'                                  => true,
  'x_site_link_oembed_own_site'                         => true,
  'x_button_style'                                      => 'real',
  'x_button_shape'                                      => 'rounded',
  'x_button_size'                                       => 'regular',
  'x_button_color'                                      => '#ffffff',
  'x_button_background_color'                           => '#ff2a13',
  'x_button_border_color'                               => '#ac1100',
  'x_button_bottom_color'                               => '#a71000',
  'x_button_color_hover'                                => '#ffffff',
  'x_button_background_color_hover'                     => '#ef2201',
  'x_button_border_color_hover'                         => '#600900',
  'x_button_bottom_color_hover'                         => '#a71000',
  'x_navbar_positioning'                                => 'static-top',
  'x_logo_navigation_layout'                            => 'inline',
  'x_logobar_adjust_spacing_top'                        => '15px',
  'x_logobar_adjust_spacing_bottom'                     => '15px',
  'x_navbar_height'                                     => '90px',
  'x_navbar_width'                                      => '235px',
  'x_logo_text'                                         => '',
  'x_logo_font_family'                                  => 'Lato',
  'x_logo_font_color'                                   => '#272727',
  'x_logo_font_size'                                    => '42px',
  'x_logo_font_weight'                                  => '700',
  'x_logo_letter_spacing'                               => '-0.035em',
  'x_logo_uppercase_enable'                             => '',
  'x_logo_visually_hidden_h1'                           => false,
  'x_logo'                                              => '',
  'x_logo_width'                                        => '',
  'x_logo_adjust_navbar_top'                            => '22px',
  'x_logo_adjust_navbar_side'                           => '30px',
  'x_navbar_font_family'                                => 'Lato',
  'x_navbar_link_color'                                 => '#999999',
  'x_navbar_link_color_hover'                           => '#272727',
  'x_navbar_font_size'                                  => '13px',
  'x_navbar_font_weight'                                => '700',
  'x_navbar_letter_spacing'                             => '0.085em',
  'x_navbar_uppercase_enable'                           => '1',
  'x_navbar_adjust_links_top'                           => '37px',
  'x_navbar_adjust_links_side'                          => '50px',
  'x_navbar_adjust_links_top_spacing'                   => '20px',
  'x_header_search_enable'                              => '',
  'x_navbar_adjust_button_size'                         => '24px',
  'x_navbar_adjust_button'                              => '20px',
  'x_header_widget_areas'                               => '2',
  'x_widgetbar_button_background'                       => '#000000',
  'x_widgetbar_button_background_hover'                 => '#444444',
  'x_topbar_display'                                    => '',
  'x_topbar_content'                                    => '',
  'x_breadcrumb_display'                                => '1',
  'x_footer_widget_areas'                               => '3',
  'x_footer_bottom_display'                             => '1',
  'x_footer_menu_display'                               => '1',
  'x_footer_social_display'                             => '1',
  'x_footer_content_display'                            => '1',
  'x_footer_content'                                    => $powered_by,
  'x_footer_scroll_top_display'                         => '',
  'x_footer_scroll_top_position'                        => 'right',
  'x_footer_scroll_top_display_unit'                    => '75%',
  'x_blog_style'                                        => 'standard',
  'x_blog_layout'                                       => 'sidebar',
  'x_blog_masonry_columns'                              => '2',
  'x_archive_style'                                     => 'standard',
  'x_archive_layout'                                    => 'sidebar',
  'x_archive_masonry_columns'                           => '2',
  'x_blog_enable_post_meta'                             => '',
  'x_blog_enable_full_post_content'                     => '',
  'x_blog_excerpt_length'                               => '60',
  'x_portfolio_enable'                                  => '1',
  'x_custom_portfolio_slug'                             => 'portfolio-item',
  'x_portfolio_enable_cropped_thumbs'                   => '',
  'x_portfolio_enable_post_meta'                        => '1',
  'x_portfolio_tag_title'                               => __( 'Skills', '__x__' ),
  'x_portfolio_launch_project_title'                    => __( 'Launch Project', '__x__' ),
  'x_portfolio_launch_project_button_text'              => __( 'See it Live!', '__x__' ),
  'x_portfolio_share_project_title'                     => __( 'Share this Project', '__x__' ),
  'x_portfolio_enable_social'                           => '1',
  'x_portfolio_enable_facebook_sharing'                 => '1',
  'x_portfolio_enable_twitter_sharing'                  => '1',
  'x_portfolio_enable_linkedin_sharing'                 => '',
  'x_portfolio_enable_pinterest_sharing'                => '',
  'x_portfolio_enable_reddit_sharing'                   => '',
  'x_portfolio_enable_email_sharing'                    => '',
  'x_bbpress_enable_templates'                          => true,
  'x_bbpress_layout_content'                            => 'sidebar',
  'x_bbpress_enable_quicktags'                          => '',
  'x_bbpress_header_menu_enable'                        => '',
  'x_buddypress_enable'                                 => true,
  'x_buddypress_templates_enable'                       => true,
  'x_buddypress_layout_content'                         => 'sidebar',
  'x_buddypress_header_menu_enable'                     => '',
  'x_buddypress_activity_title'                         => __( 'Activity', '__x__' ),
  'x_buddypress_groups_title'                           => __( 'Groups', '__x__' ),
  'x_buddypress_blogs_title'                            => __( 'Sites', '__x__' ),
  'x_buddypress_members_title'                          => __( 'Members', '__x__' ),
  'x_buddypress_register_title'                         => __( 'Create An Account', '__x__' ),
  'x_buddypress_activate_title'                         => __( 'Activate Your Account', '__x__' ),
  'x_buddypress_activity_subtitle'                      => __( 'Meet new people, get involved, and stay connected.', '__x__' ),
  'x_buddypress_groups_subtitle'                        => __( 'Find others with similar interests and get plugged in.', '__x__' ),
  'x_buddypress_blogs_subtitle'                         => __( 'See what others are writing about. Learn something new and exciting today!', '__x__' ),
  'x_buddypress_members_subtitle'                       => __( 'Meet your new online community. Kick up your feet and stay awhile.', '__x__' ),
  'x_buddypress_register_subtitle'                      => __( 'Just fill in the fields below and we\'ll get a new account set up for you in no time!', '__x__' ),
  'x_buddypress_activate_subtitle'                      => __( 'You\'re almost there! Simply enter your activation code below and we\'ll take care of the rest.', '__x__' ),
  'x_woocommerce_header_menu_enable'                    => '',
  'x_woocommerce_header_hide_empty_cart'                => '',
  'x_woocommerce_header_cart_info'                      => 'outer-inner',
  'x_woocommerce_header_cart_style'                     => 'square',
  'x_woocommerce_header_cart_layout'                    => 'inline',
  'x_woocommerce_header_cart_adjust'                    => '30px',
  'x_woocommerce_header_cart_content_inner'             => 'count',
  'x_woocommerce_header_cart_content_outer'             => 'total',
  'x_woocommerce_header_cart_content_inner_color'       => '#ffffff',
  'x_woocommerce_header_cart_content_inner_color_hover' => '#ffffff',
  'x_woocommerce_header_cart_content_outer_color'       => '#b7b7b7',
  'x_woocommerce_header_cart_content_outer_color_hover' => '#272727',
  'x_woocommerce_shop_layout_content'                   => 'sidebar',
  'x_woocommerce_shop_columns'                          => '3',
  'x_woocommerce_shop_count'                            => '12',
  'x_woocommerce_shop_placeholder_thumbnail'            => '',
  'x_woocommerce_product_tabs_enable'                   => '1',
  'x_woocommerce_product_tab_description_enable'        => '1',
  'x_woocommerce_product_tab_additional_info_enable'    => '1',
  'x_woocommerce_product_tab_reviews_enable'            => '1',
  'x_woocommerce_product_related_enable'                => '1',
  'x_woocommerce_product_related_columns'               => '4',
  'x_woocommerce_product_related_count'                 => '4',
  'x_woocommerce_product_upsells_enable'                => '1',
  'x_woocommerce_product_upsell_columns'                => '4',
  'x_woocommerce_product_upsell_count'                  => '4',
  'x_woocommerce_cart_cross_sells_enable'               => '1',
  'x_woocommerce_cart_cross_sells_columns'              => '4',
  'x_woocommerce_cart_cross_sells_count'                => '4',
  'x_woocommerce_ajax_add_to_cart_color'                => '#545454',
  'x_woocommerce_ajax_add_to_cart_bg_color'             => '#000000',
  'x_woocommerce_ajax_add_to_cart_color_hover'          => '#ffffff',
  'x_woocommerce_ajax_add_to_cart_bg_color_hover'       => '#46a546',
  'x_woocommerce_widgets_image_alignment'               => 'left',
  'x_social_facebook'                                   => '',
  'x_social_twitter'                                    => '',
  'x_social_linkedin'                                   => '',
  'x_social_xing'                                       => '',
  'x_social_foursquare'                                 => '',
  'x_social_youtube'                                    => '',
  'x_social_vimeo'                                      => '',
  'x_social_instagram'                                  => '',
  'x_social_pinterest'                                  => '',
  'x_social_dribbble'                                   => '',
  'x_social_flickr'                                     => '',
  'x_social_github'                                     => '',
  'x_social_behance'                                    => '',
  'x_social_tumblr'                                     => '',
  'x_social_whatsapp'                                   => '',
  'x_social_soundcloud'                                 => '',
  'x_social_rss'                                        => '',
  'x_social_open_graph'                                 => '',
  'x_social_fallback_image'                             => '',
  'x_custom_styles'                                     => '',
  'x_custom_scripts'                                    => '',
  'x_fixed_menu_scroll'                                 => 'overflow-visible',
  'x_enable_font_manager'                               => true,
  'x_body_font_family_selection'                        => 'system:helveticaneue',
  'x_body_font_weight_selection'                        => 'fw-normal',
  'x_headings_font_family_selection'                    => 'system:helveticaneue',
  'x_headings_font_weight_selection'                    => 'fw-bold',
  'x_logo_font_family_selection'                        => 'system:helveticaneue',
  'x_logo_font_weight_selection'                        => 'fw-bold',
  'x_navbar_font_family_selection'                      => 'system:helveticaneue',
  'x_navbar_font_weight_selection'                      => 'fw-bold',
  'x_body_font_italic'                                  => false,
  'x_headings_font_italic'                              => false,
  'x_logo_font_italic'                                  => false,
  'x_navbar_font_italic'                                => false,
  'x_font_awesome_solid_enable'                         => '1',
  'x_font_awesome_regular_enable'                       => '1',
  'x_font_awesome_light_enable'                         => '1',
  'x_font_awesome_brands_enable'                        => '1',
  'x_breakpoint_base'                                   => 4,
  'x_breakpoint_ranges'                                 => x_theme_options_breakpoints()
);

if ( function_exists('cornerstone_options_register_options')) {
  cornerstone_options_register_options( x_theme_options_get_defaults() );
}


add_filter( 'option_x_body_font_family_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_family', $value );
});

add_filter( 'option_x_headings_font_family_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_family', $value );
});

add_filter( 'option_x_logo_font_family_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_family', $value );
});

add_filter( 'option_x_navbar_font_family_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_family', $value );
});

add_filter( 'option_x_body_font_weight_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_weight', $value );
});

add_filter( 'option_x_headings_font_weight_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_weight', $value );
});

add_filter( 'option_x_logo_font_weight_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_weight', $value );
});

add_filter( 'option_x_navbar_font_weight_selection', function( $value ) {
  return apply_filters( 'cs_migrate_font_weight', $value );
});
