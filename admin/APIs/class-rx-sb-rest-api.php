<?php

/**
 * REST_API Handler
 */
class Rx_Sb_Rest_Api {

    /**
     * Instance of this class.
     *
     * @since    0.8.1
     *
     * @var      object
     */
    protected static $instance = null;


    /**
     * Namespace of of this endpoints.
     *
     * @since    0.8.1
     *
     * @var      object
     */
    public  $namespace = '';


    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     0.8.1
     */
    public function __construct() {
        $version = '1';
        $this->plugin_slug = 'rx-sb';
        $this->namespace = $this->plugin_slug . '/v' . $version;
        $this->do_hooks();
    }


    /**
     * Set up WordPress hooks and filters
     *
     * @return void
     */
    public function do_hooks() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Return an instance of this class.
     *
     * @since     0.8.1
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
            self::$instance->do_hooks();
        }

        return self::$instance;
    }


    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        /**
         * Plugin native api
         */
        register_rest_route($this->namespace, '/getAllAuthors', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'get_all_authors'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));
        register_rest_route($this->namespace, '/getAllCategories', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'get_all_categories'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));
        register_rest_route($this->namespace, '/totalPosts', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'total_posts'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));
        register_rest_route($this->namespace, '/loadPosts', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'load_posts'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));

        register_rest_route($this->namespace, '/loadInstantSharedPosts', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'load_instant_shared_posts'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));

        register_rest_route($this->namespace, '/loadSharedPosts', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'load_shared_posts'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));

        register_rest_route($this->namespace, '/addSchedule', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array($this, 'rx_sb_add_schedule'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));

        /**
         * Network tab endpoints
         */
        register_rest_route($this->namespace, '/createProfile', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'create_profile'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
                'args'                => [
                    'profile_id' => array(
                        'required' => true,
                    ),
                    'profile_name'  => array(
                        'required' => true,
                    )
                ],
            ),
        ));
        register_rest_route($this->namespace, '/deleteProfile', array(
            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'delete_profile'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
                'args'                => [
                    'id' => array(
                        'required' => true,
                    )
                ],
            ),
        ));
        register_rest_route($this->namespace, '/deleteMasterProfile', array(
            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'delete_master_profile'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
                'args'                => [
                    'id' => array(
                        'required' => true,
                    )
                ],
            ),
        ));
        register_rest_route($this->namespace, '/getAllProfile', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'get_all_profile'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));



        /**
         * edit network
         */
        register_rest_route($this->namespace, '/editNetworkStatus', array(
            array(
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => array($this, 'sb_edit_network_status'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));


        /**
         * FB Endpoints
         */
        register_rest_route($this->namespace, '/addFbAuth', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_facebook', 'sb_add_fb_auth'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));
        register_rest_route($this->namespace, '/FbAuth/(?P<id>[\d]+)', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Rest_Api_facebook', 'sb_read_fb_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => array('Rx_Sb_Rest_Api_facebook', 'sb_edit_fb_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Rest_Api_facebook', 'sb_delete_fb_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));
        register_rest_route($this->namespace, '/instantShareFb', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_facebook', 'instant_share_fb'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
                'args'                => [
                    'id' => array(
                        'required' => true,
                    ),
                    'message'  => array(
                        'required' => true,
                    ),
                    'link'  => array(
                        'required' => true,
                    )
                ],
            ),
        ));


        /**
         * Twitter Endpoints
         */
        register_rest_route($this->namespace, '/addTwAuth', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_twitter', 'sb_add_tw_auth'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));
        register_rest_route($this->namespace, '/instantShareTw', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_twitter', 'sb_tweet'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));
        register_rest_route($this->namespace, '/TwAuth/(?P<id>[\d]+)', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Rest_Api_twitter', 'sb_read_tw_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => array('Rx_Sb_Rest_Api_twitter', 'sb_edit_tw_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Rest_Api_twitter', 'sb_delete_tw_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));



        /**
         * Tumblr Endpoints
         */
        register_rest_route($this->namespace, '/addTbAuth', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_tumblr', 'sb_add_tm_auth'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));
        register_rest_route($this->namespace, '/instantShareTb', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_tumblr', 'sb_tumb'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));
        register_rest_route($this->namespace, '/TmAuth/(?P<id>[\d]+)', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Rest_Api_tumblr', 'sb_read_tm_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => array('Rx_Sb_Rest_Api_tumblr', 'sb_edit_tm_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Rest_Api_tumblr', 'sb_delete_tm_auth_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));


        /**
         * Pinterest endpoints
         */
        register_rest_route($this->namespace, '/addPinterestAuth', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_Pinterest', 'sb_add_pint_auth'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));

        register_rest_route($this->namespace, '/instantSharePt', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Rest_Api_Pinterest', 'sb_pin'),
                // 'permission_callback' => array($this, 'sb_permission_check'),
            ),
        ));



        register_rest_route($this->namespace, '/allScheduledPosts', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'get_all_scheduled_posts'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),

            ),
        ));

        register_rest_route($this->namespace, '/ScheduleData/(?P<id>[\d]+)', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'sb_read_schedule_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),

            array(
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'sb_edit_schedule_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
            array(
                'methods' => \WP_REST_Server::DELETABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'sb_delete_schedule_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));


        /**
         * Endpoint for scheduled posts
         */
        register_rest_route($this->namespace, '/editSchedule', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array('Rx_Sb_Native_Rest_Api', 'rx_sb_edit_schedule'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
                'args'                => [
                    'id' => array(
                        'required' => true,
                    ),
                    'schedule'  => array(
                        'required' => true,
                    ),
                    'caption'  => array(
                        'required' => true,
                    )
                ],
            ),
        ));

        register_rest_route($this->namespace, '/submitBitly', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array($this, 'rx_sb_submit_bitly_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));

        register_rest_route($this->namespace, '/getBitly', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'rx_sb_get_bitly_data'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));

        register_rest_route($this->namespace, '/getPostTypeslist', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'rx_sb_get_post_types_list'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));

        register_rest_route($this->namespace, '/submitPostTypeslist', array(
            array(
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => array($this, 'rx_sb_submit_post_types_list'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));

        register_rest_route($this->namespace, '/getPostTypesSelected', array(
            array(
                'methods' => \WP_REST_Server::READABLE,
                'callback' => array($this, 'rx_sb_get_post_types_selected'),
                // 'permission_callback' => array($this, 'rx_sb_permissions_check'),
            ),
        ));
    }

    /**
     * Edit network status
     */
    public static function sb_edit_network_status( WP_REST_Request $request ) {
        $id = $request->get_param( 'id' );
        $checked = $request->get_param( 'checked' );

        if ($checked == 'true') {
            $status = 'active';
        }
        elseif($checked == 'false') {
            $status = 'inactive';
        }

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $wpdb->update($network_table, array('auth_status'=>$status), array('id'=>$id));
        if ($wpdb->last_error !== '') {
            return('Failed');
        }
        else {
            return('Success');
        }
    }

    public function rx_sb_submit_bitly_data( $request ) {
      $tiny_url_enabler = get_option( 'tinyUrlEnabler' );
      $isgdEnabler = get_option( 'isgdEnabler' );
      if ($tiny_url_enabler || $isgdEnabler) {
        return 'error';
      }
      $enabler = $request->get_param('enabler');
      $login = $request->get_param('login');
      $api_key = $request->get_param('api_key');
      update_option( 'bitly_enabler', $enabler );
      update_option( 'bitly_login', $login );
      update_option( 'bitly_api_key', $api_key );
      return 'success';
    }

    public function rx_sb_get_bitly_data( $request ) {
      $enabler = false;
      $enabler = get_option( 'bitly_enabler' );
      $login = '';
      $login = get_option( 'bitly_login' );
      $api_key = '';
      $api_key = get_option( 'bitly_api_key' );
      $bitlydata = array(
        'bitly_enabler' => $enabler,
        'bitly_login' => $login,
        'api_key' => $api_key
      );
      return $bitlydata;
    }

    public function rx_sb_add_schedule( $request ) {
        $media = $request->get_param('media');
        $medias = explode(',',$media);
        $caption = $request->get_param('caption');
        $post = $request->get_param('post');
        $link = get_permalink($post);

        $schedule_type = $request->get_param('schedule');
        $date = $request->get_param('date');

        $time = $request->get_param('time');
        if (!empty($date) && !empty($time)) {
            $date_time = $date.' '.$time;
        }
        else {
          $date_time = 'none';
        }

        if ($schedule_type == 'none' && $date_time == 'none') {
          return(array(
            'status'=>'error',
            'message'=>'Select schedule type'
          ));
        }

        if (empty($media)) {
          return(array(
            'status'=>'error',
            'message'=>'Select at least one media'
          ));
        }
        $post_meta = array(
          'message'=> $caption,
          'link'=> $link,
        );
        if (empty($post)) {
          return(array(
            'status'=>'error',
            'message'=>'Select a post to schedule'
          ));
        }
        $post_meta = serialize($post_meta);
        $schedule = $schedule_type;
        $current_time = current_time('mysql', false);

        if ($date_time != 'none') {
          $date_time = $date.' '.$time;
          $schedule_time = date('Y-m-d H:i:s', strtotime($date_time));

          $current_time_check = date( 'Y-m-d H:i:s', strtotime( $current_time ) - 60 );


          if ($schedule_time < $current_time_check) {
            return(array(
              'status'=>'error',
              'message'=>'You can not select date or time before current time'
            ));
          }
        }
        else {
          if ($schedule == 'daily') {
            $schedule_time = date( 'Y-m-d H:i:s', strtotime( $current_time ) + 86400 );
          }
          elseif($schedule == 'weekly') {
            $schedule_time = date( 'Y-m-d H:i:s', strtotime( $current_time ) + 604800 );
          }
          elseif($schedule == 'monthly') {
            $schedule_time = date( 'Y-m-d H:i:s', strtotime( $current_time ) + 2628000 );
          }
        }

        global $wpdb;
        $Network_table = $wpdb->prefix . 'sb_networks';
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $data =   $wpdb->get_results("SELECT *FROM $Network_table  ");
        foreach ($data as $info) {
          if($schedule == 'none') {
            if (in_array($info->id, $medias)) {
              if ($info->auth_status == 'active' && $info->auth_con == 'active') {
                $wpdb->insert(
                    $schedule_table,
                    array(
                        'post_id' => $post,
                        'post_meta' => $post_meta,
                        'profile_id' => $info->profile_id,
                        'network_id' => $info->id,
                        'share_type' => 'scheduled',
                        'schedule_type' => $schedule,
                        'schedule_time' => $schedule_time,
                    )
                );
              }
            }
          }
          else {
            if (in_array($info->id, $medias)) {
              if ($info->auth_status == 'active' && $info->auth_con == 'active') {
                $wpdb->insert(
                    $schedule_table,
                    array(
                        'post_id' => $post,
                        'post_meta' => $post_meta,
                        'profile_id' => $info->profile_id,
                        'network_id' => $info->id,
                        'share_type' => 'scheduled',
                        'schedule_type' => $schedule,
                        'schedule_time' => $schedule_time,
                    )
                );
              }
            }
          }
        }
        if ($wpdb->last_error !== '') {
          return(array(
            'status'=>'error',
            'message'=>'Failed to save data'
          ));
        }
        else {
          return(array(
            'status'=>'success',
            'message'=>'Successfully saved data'
          ));
        }
    }

    public function rx_sb_get_post_types_list( $request ) {
      $post_types = get_post_types( array( 'public' => true ), 'names' );
      $cpt_array = array();
      foreach ($post_types as $key => $value) {
        $cpt_array[] = array('text' => $key, 'value' => $value);
      }
      return $cpt_array;
    }

    public function rx_sb_submit_post_types_list( $request ) {
      $data = $request->get_param('data');
      update_option( 'rx_sb_supported_post_types', $data );
      return 'success';
    }

    public function rx_sb_get_post_types_selected( $request ) {
      $data = get_option( 'rx_sb_supported_post_types' );
      if($data){
        $cpt_array = explode(",",$data);
        return $cpt_array;
      }
    }

    public function rx_sb_get_all_media_files( $request ) {

      $args = array(
        'post_type' => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => null, // any parent
        );
      $attachments = get_posts($args);
      return $attachments;
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function rx_sb_permissions_check( $request ) {
        return true;
        // return current_user_can( 'manage_options' );
    }
}
