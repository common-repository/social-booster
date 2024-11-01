<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Rx_Sb
 *
 * @wordpress-plugin
 * Plugin Name:       Social Booster
 * Plugin URI:        https://rextheme.com/social-booster/
 * Description:       Post automatically from WordPress blog to any social media. It is easy and simple to use.
 * Version:           4.9.0
 * Author:            RexTheme
 * Author URI:        https://rextheme.com/social-booster/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rx-sb
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RX_SB_VERSION', '4.9.0' );
define( 'SOCIAL_BOOSTER_PLUGIN', plugin_basename( __FILE__ ));
define( 'RX_SB_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'RX_SB_PLUGIN_IMAGE_URI', RX_SB_PLUGIN_DIR_URL .'/admin/app/images' );
define( 'SOCIAL_BOOSTER_ON_PRODUCTION', true );


define( 'SOCIAL_BOOSTER_NETWORKS', serialize(

  apply_filters('network_tabs', array(
    'facebook' => array(
        'name'      => 'facebook',
        'prefix'    => 'fb',
        'fa-prefix' => 'fab',
        'fa-icon'   => 'facebook-f',
        'buttons'   =>  array( 'page', 'group' ),
        'component' => 'FacebookAuth',
        'share_component' => 'FacebookShare',
    ),
    'twitter' => array(
        'name'      => 'twitter',
        'prefix'    => 'tw',
        'fa-prefix' => 'fab',
        'fa-icon'   => 'twitter',
        'buttons'    =>  array( 'profile' ),
        'component' => 'TwitterAuth',
        'share_component' => 'TwitterShare',
    ),
    'tumblr' => array(
        'name'      => 'tumblr',
        'prefix'    => 'tb',
        'fa-prefix' => 'fab',
        'fa-icon'   => 'tumblr',
        'buttons'    =>  array( 'profile' ),
        'component' => 'TumblrAuth',
        'share_component' => 'TumblrShare',
    ),

    'pinterest' => array(
        'name'      => 'pinterest',
        'prefix'    => 'pt',
        'fa-prefix' => 'fab',
        'fa-icon'   => 'pinterest-p',
        'buttons'    =>  array( 'profile' ),
        'component' => 'PinterestAuth',
        'share_component' => 'PinterestShare',
    ),
  ))
));


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rx-sb-activator.php
 */
function activate_rx_sb() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rx-sb-activator.php';
    Rx_Sb_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rx-sb-deactivator.php
 */
function deactivate_rx_sb() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-rx-sb-deactivator.php';
    Rx_Sb_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rx_sb' );
register_deactivation_hook( __FILE__, 'deactivate_rx_sb' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rx-sb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rx_sb() {
    $plugin = new Rx_Sb();
    $plugin->run();
}
run_rx_sb();

add_action('admin_init', 'rx_sb_plugin_redirect');
function rx_sb_plugin_redirect() {
    if (get_option('rx_sb_do_activation_redirect', false)) {
        delete_option('rx_sb_do_activation_redirect');
        wp_redirect("admin.php?page=rex-social-booster&activeTab=network#/");
        exit;
    }
}


/**
 * Initialize the tracker
 *
 * @return void
 */
function appsero_init_tracker_sb() {
    $client = new Appsero\Client( '5a8d9a39-0435-4581-95f3-4f427bfa63fd', 'Social Booster', __FILE__ );
    $client->insights()->init();
}
appsero_init_tracker_sb();

function rx_sb_upgrade_to_pro_link( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( 'https://rextheme.com/social-booster/#pricing' ) . '" target="_blank">' . __( 'Upgrade to Pro' ) . '</a>',
    ), $links );
    return $links;
}
if(!apply_filters('is_sb_pro_active', false)) {
  add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rx_sb_upgrade_to_pro_link' );
}

function rx_sb_documentation_link( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( 'https://rextheme.com/docs/how-to-setup-social-media-accounts-with-social-booster/' ) . '" target="_blank">' . __( 'Documentation' ) . '</a>'
    ), $links );
    return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rx_sb_documentation_link' );


add_filter( 'cron_schedules', 'wp_sb_add_every_five_minutes' );
function wp_sb_add_every_five_minutes( $schedules ) {
    $schedules['sb_five_minutes'] = array(
            'interval'  => 300,
            'display'   => __( 'Every 5 Minutes', 'wpvr' )
    );
    return $schedules;
}

//===Black Friday Notice===//
function sb_black_friday_offer_notice(){
    $screen = get_current_screen();
    // if ($screen->id=="toplevel_page_wpvr" || $screen->id=="wpvr_item" || $screen->id=="edit-wpvr_item" || $screen->id=="wp-vr_page_wpvr-addons" || $screen->id=="wp-vr_page_wpvrpro") {

      $current_time = time();
      $date_now = date("Y-m-d", $current_time);
      $notice_info = get_option('sb_bff_notice', array(
          'show_notice' => 'yes',
          'updated_at' => $current_time,
      ));
      if( $notice_info['show_notice'] === 'yes' && $date_now <= '2020-11-27' ) {?>
        <style>
          .sb-black-friday-offer {
              padding: 0!important;
              border: 0;
           }
           .sb-black-friday-offer img {
              display: block;
              width: 100%;
           }
           .sb-black-friday-offer .notice-dismiss {
              top: 4px;
              right: 6px;
              padding: 4px;
              background: #fff;
              border-radius: 100%;
           }
           .sb-black-friday-offer .notice-dismiss:before {
              content: "\f335";
              font-size: 20px;
           }

          </style>
          <div class="sb-black-friday-offer notice notice-warning is-dismissible">
              <a href="https://rextheme.com/black-friday/" target="_blank">
                  <div class="sb-banner-container">
                      <img src="<?php echo RX_SB_PLUGIN_DIR_URL . 'assets/icon/Dashboard_banner.png'; ?>" style="max-width: 100%;">
                  </div>
              </a>
          </div>
      <?php }

    // }
}
add_action('admin_notices', 'sb_black_friday_offer_notice');

add_action("wp_ajax_sb_black_friday_offer_notice_dismiss", "sb_black_friday_offer_notice_dismiss");
function sb_black_friday_offer_notice_dismiss() {
    $current_time = time();
    $info = array(
        'show_notice'   => 'no',
        'updated_at'    => $current_time,
    );
    update_option('sb_bff_notice', $info);
    $result = array(
            'success' => true
    );
    $result = json_encode($result);
    echo $result;
    wp_die();
}
?>
