<?php

// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/ADDONS/PAGE-HOME.PHP
// -----------------------------------------------------------------------------
// Addons home page output.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Page Output
// =============================================================================

// Page Output
// =============================================================================

$is_validated            = x_is_validated();
$status_icon_validated   = '<div class="tco-box-status tco-box-status-validated">' . tco_common()->get_admin_icon( 'unlocked' ) . '</div>';
$status_icon_unvalidated = '<div class="tco-box-status tco-box-status-unvalidated">' . tco_common()->get_admin_icon( 'locked' ) . '</div>';
$status_icon_dynamic     = ( $is_validated ) ? $status_icon_validated : $status_icon_unvalidated;

do_action( 'x_addons_before_home' );

?>

<div class="tco-reset tco-wrap tco-wrap-about">

  <div class="tco-content">
    <div class="wrap"></div>

    <!--
    START MAIN
    -->

    <div class="tco-main">

      <?php do_action( 'x_overview_main_content_start' ); ?>

      <?php if ( ! $is_validated ) : ?>
        <div class="tco-row">
          <?php include( 'page-home-box-validation.php' ); ?>
        </div>
      <?php endif; ?>

      <div class="tco-row">
        <?php include( 'page-home-box-automatic-updates.php' ); ?>
        <?php include( 'page-home-box-support.php' ); ?>
      </div>

      <div class="tco-row">
        <?php include( 'page-home-box-design-cloud.php' ); ?>
        <?php include( 'page-home-box-theme-options-manager.php' ); ?>
      </div>

      <?php do_action( 'x_overview_main_content_middle' ); ?>

      <div class="tco-row">
        <?php include( 'page-home-box-extensions.php' ); ?>
      </div>

      <?php do_action( 'cs_max_output_admin_extensions' ); ?>

      <?php if ( $is_validated ) : ?>
        <div class="tco-row">
          <?php include( 'page-home-box-approved-plugins.php' ); ?>
        </div>
      <?php endif; ?>

      <?php do_action( 'x_addons_main_content_end' ); ?>

    </div>

    <!--
    END MAIN and START SIDEBAR
    -->

    <div class="tco-sidebar">
      <div class="tco-cta">
        <a href="https://theme.co/x/" target="_blank"><?php tco_common()->svg( X_SLUG, 'tco-cta-logo-product' ); ?></a>
        <hr class="tco-cta-spacing">
        <a href="https://theme.co/" target="_blank"><?php tco_common()->svg( 'themeco', 'tco-cta-logo-company' ); ?></a>
        <hr class="tco-cta-spacing">
        <p class="tco-cta-note"><?php echo x_i18n('overview', 'separate-license-needed'); ?></p>
        <hr class="tco-cta-spacing">
        <div class="tco-cta-actions">
          <a class="tco-cta-action" href="https://theme.co/account/dashboard/" target="_blank"><?php _e( 'Manage Licenses', '__x__' ); ?></a>
        </div>
        <?php if ( $is_validated ) : ?>
          <hr class="tco-cta-spacing">
          <p class="tco-cta-note" data-tco-module="x-validation-revoke"><?php printf( __( 'Your site is validated. %s.', '__x__' ), '<a href="#" data-tco-module-target="revoke">' . __( 'Revoke validation', '__x__') . '</a>'); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!--
    END SIDEBAR
    -->

  </div>
</div>
