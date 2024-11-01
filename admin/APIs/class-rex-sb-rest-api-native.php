<?php


class Rx_Sb_Native_Rest_Api {

    /**
     * Namespace of of this endpoints.
     *
     * @since    0.8.1
     *
     * @var      object
     */
    public  $namespace = '';

    /**
     * Get total no of posts
     * @return WP_REST_Response
     */
    public static function total_posts() {
        $count_posts = wp_count_posts();
        $published_posts = $count_posts->publish;
        return new \WP_REST_Response( array(
            'success'   => true,
            'total'     => $published_posts
        ), 200 );
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function load_posts(WP_REST_Request $request) {
        $per_page = $request->get_param('limit') ? $request->get_param('limit') : -1;
        $offset = $request->get_param('offset') ? $request->get_param('offset') : 0;
        $author = $request->get_param('author') ? $request->get_param('author') : null;
        $s = $request->get_param('s') ? $request->get_param('s') : null;
        $cat = $request->get_param('cat') ? $request->get_param('cat') : null;
        $post_type = $request->get_param('post_type') ? $request->get_param('post_type') : 'post';
        $order = $request->get_param('order') ? ($request->get_param('order') === 'new' ? 'DESC' : 'ASC') : 'DESC';
        $arr = array(
            'posts_per_page' => $per_page,
            'offset'         => $offset,
        );
        if($s)
            $arr['s'] = $s;
        if($author)
            $arr['author'] = $author;
        if($cat)
            $arr['category'] = $cat;
        if($post_type)
            $arr['post_type'] = $post_type;
        if($order)
            $arr['order'] = $order;

        $posts = get_posts($arr);
        $post_array = [];
        if($posts) {
            foreach ($posts as $post) {
                $post_array[] = array(
                    'id'        =>  $post->ID,
                    'title'     =>  $post->post_title,
                    'excerpt'   =>  self::rx_sb_get_excerpt($post->ID),
                    'url'       =>  get_the_permalink($post->ID),
                    'img'       =>  self::rx_sb_get_featured_img($post->ID),
                    'author'    =>  self::rx_sb_get_post_author($post->post_author),
                    'post_date' =>  $post->post_date,
                    'post_time' =>  $post->post_date,
                );
            }
        }
        return new \WP_REST_Response( array(
            'success'   => true,
            'posts'     => $post_array
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function load_instant_shared_posts(WP_REST_Request $request) {
        global $wpdb;
        $post_table = $wpdb->prefix. 'sb_shared_posts';
        $network_table = $wpdb->prefix. 'sb_networks';
        $result = $wpdb->get_results("SELECT network.id as network_id,network.profile_id,network.prof_name,network.network,network.auth_platform,post.post_id,post.published_date, post.share_type,post.id, post.success, post.error_msg
                                      FROM {$network_table} AS network
                                      INNER JOIN {$post_table} as post ON network.id = post.network_id WHERE post.share_type = 'instant';");

        $shared_posts = [];
        if($result) {
            foreach ($result as $post) {
                $title = get_the_title($post->post_id);
                $title = str_replace('[&hellip;]', '...', $title);
                $title = str_replace('&#8217;', "'", $title);
                $title = str_replace('&#039;', "'", $title);
                $title = str_replace('&nbsp;', " ", $title);
                $title = str_replace('&#8217;', "'", $title);

                $shared_posts[$post->post_id]['post_id'] = $post->post_id;
                $shared_posts[$post->post_id]['post_title'] = $title;
                $shared_posts[$post->post_id]['img'] = self::rx_sb_get_featured_img($post->post_id);
                $shared_posts[$post->post_id]['excerpt'] = self::rx_sb_get_excerpt($post->post_id);
                $shared_posts[$post->post_id]['url'] = get_the_permalink($post->post_id);
                $shared_posts[$post->post_id]['posts'][] = array(
                    'id'            => $post->id,
                    'network_id'    => $post->network_id,
                    'profile_id'    => $post->profile_id,
                    'prof_name'     => $post->prof_name,
                    'network'       => $post->network,
                    'share_type'    => $post->share_type,
                    'auth_platform' => $post->auth_platform,
                    'published_date'=> $post->published_date,
                    'success'=> $post->success,
                    'error_msg'=> $post->error_msg,
                );
            }
        }
        return new \WP_REST_Response( array(
            'success'   => true,
            'posts'     => $shared_posts
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function load_shared_posts(WP_REST_Request $request) {
        global $wpdb;
        $post_table = $wpdb->prefix. 'sb_shared_posts';
        $network_table = $wpdb->prefix. 'sb_networks';
        $result = $wpdb->get_results("SELECT network.id as network_id,network.profile_id,network.prof_name,network.network,network.auth_platform,post.post_id,post.published_date, post.share_type,post.id, post.success, post.error_msg
                                      FROM {$network_table} AS network
                                      INNER JOIN {$post_table} as post ON network.id = post.network_id WHERE post.share_type = 'shared';");

        $shared_posts = [];
        if($result) {
            foreach ($result as $post) {
                $title = get_the_title($post->post_id);
                $title = str_replace('[&hellip;]', '...', $title);
                $title = str_replace('&#8217;', "'", $title);
                $title = str_replace('&#039;', "'", $title);
                $title = str_replace('&nbsp;', " ", $title);
                $title = str_replace('&#8217;', "'", $title);

                $shared_posts[$post->post_id]['post_id'] = $post->post_id;
                $shared_posts[$post->post_id]['post_title'] = $title;
                $shared_posts[$post->post_id]['img'] = self::rx_sb_get_featured_img($post->post_id);
                $shared_posts[$post->post_id]['excerpt'] = self::rx_sb_get_excerpt($post->post_id);
                $shared_posts[$post->post_id]['url'] = get_the_permalink($post->post_id);
                $shared_posts[$post->post_id]['posts'][] = array(
                    'id'            => $post->id,
                    'network_id'    => $post->network_id,
                    'profile_id'    => $post->profile_id,
                    'prof_name'     => $post->prof_name,
                    'network'       => $post->network,
                    'share_type'    => $post->share_type,
                    'auth_platform' => $post->auth_platform,
                    'published_date'=> $post->published_date,
                    'success'=> $post->success,
                    'error_msg'=> $post->error_msg,
                );
            }
        }
        return new \WP_REST_Response( array(
            'success'   => true,
            'posts'     => $shared_posts
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function get_all_authors(WP_REST_Request $request) {
        $authors = get_users();

        $user_array = [];
        $user_array[] = array(
            'value'    => null,
            'text'  => 'All Authors',
        );
        if($authors) {
            foreach ($authors as $author) {
                $user_array[] = array(
                    'value'    => $author->ID,
                    'text'  => $author->display_name,
                );
            }
        }
        return new \WP_REST_Response( array(
            'success'   => true,
            'authors'     => $user_array
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function get_all_categories(WP_REST_Request $request) {
        $categories = get_categories( array(
            'orderby' => 'name',
            'order'   => 'ASC'
        ) );


        $cat_array = [];
        $cat_array[] = array(
            'value'    => null,
            'text'  => 'All Categories',
        );
        if($categories) {
            foreach ($categories as $category) {
                $cat_array[] = array(
                    'value'    => $category->term_id,
                    'text'  => $category->name,
                );
            }
        }
        return new \WP_REST_Response( array(
            'success'   => true,
            'categories'     => $cat_array
        ), 200 );
    }


    /**
     * @param $post_id
     * @param int $limit
     * @return array|mixed|string|void
     */
     public static function rx_sb_get_excerpt($post_id, $limit = 30) {
         $permalink = get_permalink($post_id);
         $tags = get_meta_tags($permalink);
         $tags = str_replace('[&hellip;]', '...', $tags);
         $tags = str_replace('&#8217;', "'", $tags);
         $tags = str_replace('&#039;', "'", $tags);
         $tags = str_replace('&nbsp;', " ", $tags);
         $tags = str_replace('&#8217;', "'", $tags);
         if ($tags['description']) {
           return $tags['description'];
         }
         elseif ($tags['twitter:description']) {
           return $tags['twitter:description'];
         }else{
           return '';
         }
     }


    /**
     * @param $post_id
     * @return false|string
     */
    public static function rx_sb_get_featured_img($post_id) {
        if($post_id) {
            $featured_img = get_the_post_thumbnail_url($post_id, 'thumbnail');
            return $featured_img ? $featured_img : 'https://via.placeholder.com/150x150';
        }
        return 'https://via.placeholder.com/150x150';
    }


    /**
     * @param $post_id
     * @return string
     */
    public static function rx_sb_get_post_author($author_id) {
        if($author_id) {
            return get_the_author_meta('display_name', $author_id);
        }
        return '';
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function create_profile(WP_REST_Request $request) {
        global $wpdb;
        $profile_table = $wpdb->prefix . 'sb_profiles';
        $profile_name = $request->get_param('profile_name') ? $request->get_param('profile_name') : 'My Profile';
        $profile_id = $request->get_param('profile_id') ? $request->get_param('profile_id') : 'rx_'.self::generateRandomString();

        $exist = $wpdb->get_var( "SELECT COUNT(*)  FROM  $profile_table WHERE profile_id =  '$profile_id'");

        if(!$exist) {
            $wpdb->insert($profile_table, array(
                'profile_id' => $profile_id,
                'profile_name' => $profile_name,
            ));
        }else {
            $wpdb->update($profile_table,
                array(
                        'profile_name' => $profile_name,
                    ),
                array('profile_id'=>$profile_id)
            );
        }

        if(!$wpdb->last_error) {
            return new \WP_REST_Response( array(
                'success'   => true,
            ), 200 );
        }
        return new WP_Error( 'cant-update', __( 'Error', 'rx-sb'), array( 'status' => 500 ) );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function get_all_profile(WP_REST_Request $request) {
        global $wpdb;
        $profile_table = $wpdb->prefix . 'sb_profiles';
        $network_table = $wpdb->prefix . 'sb_networks';
        $result = $wpdb->get_results('SELECT * FROM '.$profile_table);

        $networks = unserialize(SOCIAL_BOOSTER_NETWORKS);

        $profiles = [];
        if($result) {
            foreach ($result as $profile) {
                $profiles[$profile->profile_id]['profile_name'] = $profile->profile_name;
                $profiles[$profile->profile_id]['profile_id'] = $profile->profile_id;
                $result = $wpdb->get_results("SELECT network.id,network.network,network.auth_platform,
                                      network.auth_status, network.auth_con, network.auth_date, network.prof_name
                                      FROM {$network_table} AS network
                                      WHERE profile_id = '$profile->profile_id'");

                if($result) {
                    foreach ($result as $network) {

                        if(array_key_exists($network->network, $networks)) {
                          $component_name = $networks[$network->network]['share_component'];
                          $profiles[$profile->profile_id]['networks'][] = array(
                              'id' => $network->id,
                              'network' => $network->network,
                              'network_id' => $profile->profile_id,
                              'auth_platform' => $network->auth_platform,
                              'auth_status' => $network->auth_status,
                              'auth_con' => $network->auth_con,
                              'auth_date' => $network->auth_date,
                              'prof_name' => $network->prof_name,
                              'component' => $component_name
                          );
                        }
                    }
                }else {
                    $profiles[$profile->profile_id]['networks'] = array();
                }

            }
        }


        return new \WP_REST_Response( array(
            'success'   => true,
            'profiles'  => $profiles
        ), 200 );

    }


    /**
     * @param WP_REST_Request $request
     * @return WP_Error|WP_REST_Response
     */
    public static function delete_profile(WP_REST_Request $request) {
        global $wpdb;
        $id = $request->get_param('id');
        $network_table = $wpdb->prefix . 'sb_networks';
        $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
        $sb_shared_posts = $wpdb->prefix . 'sb_shared_posts';
        if($id) {
            $wpdb->delete( $network_table, array( 'id' => $id ) );
            $wpdb->delete( $sb_scheduled_posts, array( 'network_id' => $id ) );
            $wpdb->delete( $sb_shared_posts, array( 'network_id' => $id ) );
            return new \WP_REST_Response( array(
                'success'   => true,
            ), 200 );
        }
        return new WP_Error( 'cant-update', __( 'Error', 'rx-sb'), array( 'status' => 500 ) );
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_Error|WP_REST_Response
     */
    public static function delete_master_profile(WP_REST_Request $request) {
        global $wpdb;
        $id = $request->get_param('id');
        $profile_table = $wpdb->prefix . 'sb_profiles';
        $network_table = $wpdb->prefix . 'sb_networks';
        $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
        $sb_shared_posts = $wpdb->prefix . 'sb_shared_posts';
        if($id) {
            $wpdb->delete( $profile_table, array( 'profile_id' => $id ) );
            $wpdb->delete( $network_table, array( 'profile_id' => $id ) );
            $wpdb->delete( $sb_scheduled_posts, array( 'profile_id' => $id ) );
            $wpdb->delete( $sb_shared_posts, array( 'profile_id' => $id ) );
            return array(
                'type'=> 'success',
                'message'=> 'Successfully deleted'
            );
        }
        return array(
            'type'=> 'error',
            'message'=> 'Failed to delete'
        );
    }


    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * @return array|null|object|string
     */
    public static function get_all_scheduled_posts() {
      global $wpdb;
      $post_table = $wpdb->prefix. 'sb_scheduled_posts';
      $network_table = $wpdb->prefix. 'sb_networks';
      $result = $wpdb->get_results("SELECT network.id as network_id,network.profile_id,network.prof_name,network.network,network.auth_platform,post.post_id,post.post_meta,post.schedule_time, post.schedule_type,post.id
                                    FROM {$network_table} AS network
                                    INNER JOIN {$post_table} as post ON network.id = post.network_id");

      $shared_posts = [];

      if($result) {
          foreach ($result as $post) {
              $title = get_the_title($post->post_id);
              $title = str_replace('[&hellip;]', '...', $title);
              $title = str_replace('&#8217;', "'", $title);
              $title = str_replace('&#039;', "'", $title);
              $title = str_replace('&nbsp;', " ", $title);
              $title = str_replace('&#8217;', "'", $title);

              $date_time_explode = explode(' ', $post->schedule_time);
              $schedule_on = str_replace(' ', ' / ', $post->schedule_time);
              $date = $date_time_explode[0];
              $time = $date_time_explode[1];
              $shared_posts[$post->post_id]['post_id'] = $post->post_id;
              $shared_posts[$post->post_id]['post_title'] = $title;
              $shared_posts[$post->post_id]['img'] = self::rx_sb_get_featured_img($post->post_id);
              $shared_posts[$post->post_id]['excerpt'] = self::rx_sb_get_excerpt($post->post_id);
              $shared_posts[$post->post_id]['url'] = get_the_permalink($post->post_id);
              $shared_posts[$post->post_id]['posts'][] = array(
                  'id'            => $post->id,
                  'network_id'    => $post->network_id,
                  'profile_id'    => $post->profile_id,
                  'prof_name'     => $post->prof_name,
                  'caption'       => unserialize($post->post_meta)['message'],
                  'network'       => $post->network,
                  'schedule_type'    => $post->schedule_type,
                  'auth_platform' => $post->auth_platform,
                  'scheduled_on' => $schedule_on,
                  'date'=> $date,
                  'time'=> $time,
              );
          }
      }
      return new \WP_REST_Response( array(
          'success'   => true,
          'posts'     => $shared_posts
      ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function sb_read_schedule_data(WP_REST_Request $request) {
        return new \WP_REST_Response( array(
            'success'   => true,
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function sb_edit_schedule_data(WP_REST_Request $request) {
        return new \WP_REST_Response( array(
            'success'   => true,
        ), 200 );
    }


    /**
     * edit schedule
     * @param WP_REST_Request $request
     * @return WP_Error|WP_REST_Response
     */
    public static function rx_sb_edit_schedule(WP_REST_Request $request) {
        global $wpdb;
        $id = $request->get_param('id');
        $schedule = $request->get_param('schedule');

        $caption = $request->get_param('caption');
        $date = $request->get_param('date');
        $time = $request->get_param('time');

        if (!empty($date) && !empty($time)) {
          $date_time = $date.' '.$time;
          $schedule_time = date('Y-m-d H:i:s', strtotime($date_time));
        }
        else {
            $schedule_time = 'none';
        }

        if ($schedule == 'none' && $schedule_time == 'none') {
          return new \WP_REST_Response( array(
            'success'   => false,
            'message'   => 'No schedule type found',
          ), 200 );
        }

        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $the_schedule = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$schedule_table} WHERE ID = %s", $id ) );
        $post_meta = unserialize($the_schedule->post_meta);
        $post_meta['message'] = $caption;
        if ($schedule_time != 'none') {
          $result = $wpdb->update($schedule_table, array( 'schedule_type'=>$schedule, 'post_meta'=>serialize($post_meta), 'schedule_time'=>$schedule_time ), array('id'=>$id));
        }
        else {
          $result = $wpdb->update($schedule_table, array( 'schedule_type'=>$schedule, 'post_meta'=>serialize($post_meta) ), array('id'=>$id));
        }

        if($result) {
            return new \WP_REST_Response( array(
              'success'   => true,
              'message'   => 'Successfully updated the data.',
            ), 200 );
        }else {
          return new \WP_REST_Response( array(
            'success'   => false,
            'message'   => 'Failed to updated the data',
          ), 200 );
        }
        return new \WP_REST_Response( array(
          'success'   => false,
          'message'   => 'Failed to updated the data',
        ), 200 );
    }


    /**
     * @param WP_REST_Request $request
     * @return string
     */
    public function sb_delete_schedule_data(WP_REST_Request $request) {
        global $wpdb;
        $id = $request->get_param('id');
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $wpdb->delete( $schedule_table, array( 'id' => $id ) );
        if ($wpdb->last_error !== '') {
            return('Error deleting data');
        }
        else {
            return ('Success');
        }

    }
}
