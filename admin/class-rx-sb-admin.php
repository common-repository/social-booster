<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/admin
 * @author     RexTheme <#>
 */
class Rx_Sb_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * Main plugin screen.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_screen    The ID of this plugin.
     */
    private $plugin_screen;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rx_Sb_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rx_Sb_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */


        global $post;
        $screen = get_current_screen();
        $post_types = get_post_types( array( 'public' => true ), 'names' );
        $cpt_array = array();
        foreach ($post_types as $key => $value) {
          $cpt_array[] = $value;
        }
        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if (in_array($post->post_type, $cpt_array)) {
                wp_enqueue_style( 'rex-jquery-ui', RX_SB_PLUGIN_DIR_URL . 'admin/css/jquery-ui.min.css', array(), '1.12.1', 'all' );
                wp_enqueue_style( 'rex-jquery-ui-timepicker', RX_SB_PLUGIN_DIR_URL . 'admin/css/jquery-ui-timepicker-addon.min.css', array(), '1.6.3', 'all' );
                wp_enqueue_style( 'rex-jquery-ui-date-timepicker-css', RX_SB_PLUGIN_DIR_URL . 'admin/css/jquery.datetimepicker.min.css', array(), '1.6.3', 'all' );
                wp_enqueue_style( 'font-awesome', RX_SB_PLUGIN_DIR_URL . 'admin/css/font-awesome.min.css', array(), '4.7.0', 'all' );
                wp_enqueue_style( 'sb-admin-style', RX_SB_PLUGIN_DIR_URL . 'admin/css/main.css', array(), $this->version, 'all' );
            }
        }

        if ( $hook === 'edit.php' ) {
            return;
        }

        if(in_array( $screen->id, array( $this->plugin_screen))) {
            wp_enqueue_style( 'sb-style', RX_SB_PLUGIN_DIR_URL . 'assets/css/style.css', array(), $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook) {



        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Rx_Sb_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Rx_Sb_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        global $post;
        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $network_data = $wpdb->get_results("SELECT * FROM $network_table");
        $rest_uri = get_rest_url();
        $permalink_structure = get_option( 'permalink_structure' );
        $screen = get_current_screen();
        $post_types = get_post_types( array( 'public' => true ), 'names' );
        $cpt_array = array();
        foreach ($post_types as $key => $value) {
          $cpt_array[] = $value;
        }
        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if (in_array($post->post_type, $cpt_array)) {
                wp_enqueue_script("jquery-ui",RX_SB_PLUGIN_DIR_URL ."/admin/js/jquery-ui-timepicker-addon.min.js" ,array('jquery', 'jquery-ui-datepicker'), time(), true);
                wp_enqueue_script("rex-jquery-ui-date-timepicker",RX_SB_PLUGIN_DIR_URL ."/admin/js/jquery.datetimepicker.full.min.js" ,array('jquery'), time(), true);
                wp_enqueue_script("sb-admin",RX_SB_PLUGIN_DIR_URL ."/admin/js/admin.js" ,array('jquery','jquery-ui-datepicker'), time(), true);
                wp_localize_script('sb-admin' , 'rx_obj', array(
                        'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                        'api_url'	              => $rest_uri.''.$this->plugin_name.'/v1',
                        'permalink_structure'	=> $permalink_structure,
                        'network_data'	        => $network_data,
                        'ajax_nonce'            => wp_create_nonce('rx_obj'),
                    )
                );
            }
        }

        if ( $hook === 'edit.php' ) {
            return;
        }


        if($screen->id === $this->plugin_screen) {
            $production_build = SOCIAL_BOOSTER_ON_PRODUCTION ? 'min.' : '';
            wp_enqueue_editor();
            wp_enqueue_media();
            $is_sb_pro_active = apply_filters('is_sb_pro_active', false);
            $plugin_dir_url = $is_sb_pro_active ? RX_SB_PRO_PLUGIN_DIR_URL : RX_SB_PLUGIN_DIR_URL;

            wp_enqueue_script( 'sb-vendors-admin', $plugin_dir_url . "assets/js/vendors~admin.{$production_build}js", array() , $this->version, true );
            wp_enqueue_script( 'sb-vendors-admin-vendor', $plugin_dir_url . "assets/js/vendors~admin~vendor.{$production_build}js", array() , $this->version, true );
            wp_enqueue_script( 'sb-vendors-vendor', $plugin_dir_url . "assets/js/vendors~vendor.{$production_build}js", array(), $this->version, true );
            wp_enqueue_script( 'sb-plugin', $plugin_dir_url . "assets/js/admin.{$production_build}js", array() , $this->version, true );
            wp_enqueue_script( 'sb-runtime', $plugin_dir_url . "assets/js/runtime.{$production_build}js", array(), $this->version, true );
            wp_enqueue_script( 'sb-vendor', $plugin_dir_url . "assets/js/vendor.{$production_build}js", array(), $this->version, true );


            wp_localize_script( 'sb-plugin' , 'rx_sb_obj', array(
                    'api_nonce'           => wp_create_nonce( 'wp_rest' ),
                    'site_admin'          => admin_url(),
                    'site_url'            => home_url(),
                    'plugin_dir_url'      => RX_SB_PLUGIN_DIR_URL,
                    'api_url'	            => $rest_uri.''.$this->plugin_name.'/v1',
                    'permalink_structure'	=> $permalink_structure,
                    'routes'              => $this->get_sb_routes(),
                    'routeComponents'     => array( 'default' => null ),
                )
            );
        }

        wp_enqueue_script("sb-global",RX_SB_PLUGIN_DIR_URL ."/admin/js/global.js" ,array('jquery'), $this->version, true);
        wp_localize_script('sb-global' , 'rxsb_global_obj', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce('rxsb_global_obj'),
            )
        );

    }


    /**
     * Module Integration
     */
    public function rx_sb_admin_initialization() {
        $module_integration = new Rx_Sb_Module_Integration_Manager();
        $module_integration->get_integrations();
    }

    /**
     * Plugin admin menu
     */
    public function admin_menu() {
        $this->plugin_screen = add_menu_page(__( 'Social Booster', 'rex-social-booster' ), __( 'Social Booster', 'rex-social-booster' ), 'manage_options', 'rex-social-booster', array($this, 'plugin_page' ), RX_SB_PLUGIN_DIR_URL.'assets/icon/icon.png');
        do_action('rx_sb_pro_license_page');
    }


    /**
     * Set default admin tabs
     * @param array $tabs
     * @return array
     */
    public function set_default_tabs($tabs = array()) {

        $tabs['dashboard']   = array(
            'label' => __('Dashboard', 'rex-social-booster'),
            'icon'  => RX_SB_PLUGIN_IMAGE_URI . '/house.png',
            'hover_icon'  => RX_SB_PLUGIN_IMAGE_URI . '/house-hover.png',
            'disabled'  => false,
            'tooltip'  => false,
            'component'  => 'Dashboard',
        );

        $tabs['networks']   = array(
            'label' => __('Networks', 'rex-social-booster'),
            'icon'  => RX_SB_PLUGIN_IMAGE_URI . '/move.png',
            'hover_icon'  => RX_SB_PLUGIN_IMAGE_URI . '/move.png',
            'disabled'  => false,
            'tooltip'  => false,
            'component'  => 'Networks',
        );

        $tabs['settings']   = array(
            'label' => __('Settings', 'rex-social-booster'),
            'icon'  => RX_SB_PLUGIN_IMAGE_URI . '/settings.png',
            'hover_icon'  => RX_SB_PLUGIN_IMAGE_URI . '/settings-hover.png',
            'disabled'  => false,
            'tooltip'  => false,
            'component'  => 'Settings',
        );

        $tabs['post-sharing']   = array(
            'label' => __('Post & Sharing', 'rex-social-booster'),
            'icon'  => RX_SB_PLUGIN_IMAGE_URI . '/transfer.png',
            'hover_icon'  => RX_SB_PLUGIN_IMAGE_URI . '/transfer-hover.png',
            'disabled'  => false,
            'tooltip'  => false,
            'component'  => 'PostSharing',
        );

        $tabs['setup-guide']   = array(
            'label' => __('Setup guide', 'rex-social-booster'),
            'icon'  => RX_SB_PLUGIN_IMAGE_URI . '/clipboard.png',
            'hover_icon'  => RX_SB_PLUGIN_IMAGE_URI . '/clipboard-hover.png',
            'disabled'  => false,
            'tooltip'  => false,
            'component'  => 'SetupGuide',
        );
        return $tabs;
    }

    /**
     * attach vue components
     */
    public function sb_admin_spa() {
        require dirname( __FILE__ ) . '/views/sb-spa-components.php';
    }


    public function set_dashboard_content() {
        require dirname( __FILE__ ) . '/views/sb-spa-components.php';
    }


    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page() {
        echo '<div id="rx-sb-app">
            <div class="sb-loader">
                <div class="spring-spinner">
                    <div class="spring-spinner-part top">
                        <div class="spring-spinner-rotator"></div>
                    </div>
                    <div class="spring-spinner-part bottom">
                        <div class="spring-spinner-rotator"></div>
                    </div>
                </div>
            </div>
        </div>';
    }


    /**
     * define SB routes
     * @return array
     */
    public function get_sb_routes() {
        $routes = array(
            array(
                'path'      => '/',
                'name'      => 'home',
                'component' => 'Home'
            )
        );
        return apply_filters( 'rx_sb_routes', $routes );
    }

    /**
     * Plugin meta box
     */
    public function rx_sb_add_post_meta_boxes() {

        $cpt_array = array();
        $data = get_option( 'rx_sb_supported_post_types' );
        if ($data) {
          $cpt_array = explode(",",$data);
        }

        add_meta_box(
            'rx_sb_post_meta_box',
            esc_html__( 'Social Booster', 'rx-sb' ),
            array($this, 'rx_sb_post_metabox_callback'),
            $cpt_array,
            'normal',
            'high'
        );
    }


    /**
     * @param $post
     */
    public function rx_sb_post_metabox_callback($post) {
        require plugin_dir_path( __FILE__ ) . '/partials/rx-sb-post-meta-box.php';
    }



    public function sb_admin_footer_styles() {
        $screen = get_current_screen();
        if($screen->id === 'toplevel_page_rex-social-booster') {
            echo '';
        }
    }


    /**
     * Network redirection after
     * auth setup
     */
    public function rx_sb_network_auth () {
        $running = get_option( 'network_authentication' );
        if ($running === 'twitter') {
            update_option( 'network_authentication', 'Dead' );
            if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){
                $twitter = new Rx_Sb_Twitter();
                $auth_token = $_GET['oauth_token'];
                $auth_verifier = $_GET['oauth_verifier'];
                $contextOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'allow_self_signed' => true
                    ]
                ];
                $sslContext = stream_context_create($contextOptions);
                $url = 'https://api.twitter.com/oauth/access_token?oauth_token='.$auth_token.'&oauth_verifier='.$auth_verifier.'';
                $json = file_get_contents($url,false, $sslContext);
                $data = (explode("&",$json));
                $oauth_token = explode("=",$data[0]);
                $oauth_token = $oauth_token[1];
                $oauth_token_secret = explode("=",$data[1]);
                $oauth_token_secret = $oauth_token_secret[1];
                $screen_name = explode("=",$data[3]);
                $screen_name = $screen_name[1];
                $profile_id = get_option('twitter_profile_id');
                $profile_name = get_option('twitter_profile_name');
                $consumer_key = get_option('twitter_consumer_key');
                $consumer_secret = get_option('twitter_consumer_secret');
                $extra_array = array(
                    'consumer_key' => $consumer_key,
                    'consumer_secret' => $consumer_secret,
                );
                $extra = serialize($extra_array);
                delete_option( 'twitter_profile_id' );
                delete_option( 'twitter_profile_name' );
                delete_option( 'twitter_consumer_key' );
                delete_option( 'twitter_consumer_secret' );
                $saved = $twitter->sb_twitter_auth($profile_id, $profile_name, $oauth_token, $oauth_token_secret, $screen_name, $extra);
                if($saved['type'] == 'Error') {
                    header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=failed#/'));
                }elseif ($saved['type'] === 'Success') {
                  header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=twitter#/'));
                    // header('Location: '. admin_url('/admin.php?page=rex-social-booster&activeTab=network#/'));
                }

            }
        }
        elseif ($running === 'tumblr') {
            update_option( 'network_authentication', 'Dead' );
            if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ){

                $tumblr = new Rx_Sb_Tumblr();

                $consumer_key = get_option('tumblr_consumer_key');
                $consumer_secret = get_option('tumblr_consumer_secret');

                $tumblr_request_token = get_option('tumblr_request_token');
                $tumblr_request_token_secret = get_option('tumblr_request_token_secret');

                $client = new Tumblr\API\Client($consumer_key, $consumer_secret, $tumblr_request_token, $tumblr_request_token_secret);
                $requestHandler = $client->getRequestHandler();
                $requestHandler->setBaseUrl('https://www.tumblr.com/');

                $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $verifier = $_GET['oauth_verifier'];

                $resp = $requestHandler->request('POST', 'oauth/access_token', array('oauth_verifier' => $verifier));
                $out = $result = $resp->body;

                $data = array();
                parse_str($out, $data);

                // and print out our new keys
                $oauth_token = $data['oauth_token'];
                $oauth_token_secret = $data['oauth_token_secret'];

                $profile_id = get_option('tumblr_profile_id');
                $profile_name = get_option('tumblr_profile_name');

                delete_option( 'tumblr_profile_id' );
                delete_option( 'tumblr_profile_name' );
                delete_option( 'tumblr_consumer_key' );
                delete_option( 'tumblr_consumer_secret' );
                delete_option( 'tumblr_request_token' );
                delete_option( 'tumblr_request_token_secret' );
                $saved = $tumblr->sb_tumblr_auth($profile_id, $profile_name, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

                if($saved['type'] === 'Error') {
                    header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=failed#/'));
                }elseif ($saved['type'] === 'Success') {
                    header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=tumblr#/'));
                }
            }
        }
        elseif ($running === 'pinterest') {
          update_option( 'network_authentication', 'Dead' );
            if(isset($_GET['code']) && isset($_GET['state']) ){

                $pinterest = new Rx_Sb_Pinterest();

                $client_id = get_option('pinterest_client_id');
                $client_secret = get_option('pinterest_client_secret');

                $board_name = get_option('board_name');

                $client = new DirkGroenen\Pinterest\Pinterest($client_id, $client_secret);
                $token = $client->auth->getOAuthToken($_GET["code"]);
                $client->auth->setOAuthToken($token->access_token);
                $me = $client->users->me(array(
                    'fields' => 'username'
                ));
                $user_name = $me->username;


                $profile_id = get_option('pinterest_profile_id');
                $profile_name = get_option('pinterest_profile_name');

                delete_option( 'pinterest_profile_id' );
                delete_option( 'pinterest_profile_name' );

                delete_option( 'pinterest_client_id' );
                delete_option( 'pinterest_client_secret' );
                delete_option( 'board_name' );

                $saved = $pinterest->sb_pinterest_auth($profile_id, $profile_name, $client_id, $client_secret, $token->access_token, $user_name, $board_name );

                if($saved['type'] === 'Error') {
                    header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=failed#/'));
                }elseif ($saved['type'] === 'Success') {
                    header('Location: '. admin_url('/admin.php?page=rex-social-booster&auth_success=pinterest#/'));
                }
            }
        }
    }


    /**
     * When user update the
     * plugin
     */
    public function plugin_update_functions() {
        /*
         * Create Schedule Table
         */
        update_option('rx-sb-version', '3.0');
        global $wpdb;
        $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $sb_scheduled_posts (
                  id int(10) unsigned AUTO_INCREMENT,
                  post_id INT NOT NULL,
                  post_meta longtext,
                  profile_id VARCHAR(20),
                  network_id VARCHAR(20),
                  share_type VARCHAR (10),
                  schedule_type VARCHAR (10),
                  schedule_time VARCHAR (20),
                  PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }


    /**
     * Plugin update complete hook
     * @param $upgrader_object
     * @param $options
     */
    function plugin_update_completed( $upgrader_object, $options ) {
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == SOCIAL_BOOSTER_PLUGIN ) {
                    update_option('rx-sb-version', '3.0');
                    global $wpdb;
                    $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
                    $charset_collate = $wpdb->get_charset_collate();
                    $sql = "CREATE TABLE IF NOT EXISTS $sb_scheduled_posts (
                      id int(10) unsigned AUTO_INCREMENT,
                      post_id INT NOT NULL,
                      post_meta longtext,
                      profile_id VARCHAR(20),
                      network_id VARCHAR(20),
                      share_type VARCHAR (10),
                      schedule_type VARCHAR (10),
                      schedule_time VARCHAR (20),
                      PRIMARY KEY (id)
                ) $charset_collate;";

                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta( $sql );
                }
            }
        }
    }
    public function rex_delete_post_trigger($post_id) {
     global $wpdb;
     $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
     $sb_shared_posts = $wpdb->prefix . 'sb_shared_posts';
     $wpdb->delete( $sb_scheduled_posts, array( 'post_id' => $post_id ) );
     $wpdb->delete( $sb_shared_posts, array( 'post_id' => $post_id ) );
     }
}
