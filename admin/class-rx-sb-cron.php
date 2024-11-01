<?php

/**
 * Class Rx_Sb_Feed_DB_MANAGER
 */
class Rx_Sb_Cron {

    public function trigger() {
      $this->sb_schedule_event();
    }

    public function untrigger() {
      $this->sb_clear_event();
    }

    public function sb_schedule_event() {
      if (! wp_next_scheduled ( 'sb_daily_auth_status_check' )) {
          wp_schedule_event( time(), 'daily', 'sb_daily_auth_status_check');
      }
      // if (! wp_next_scheduled ( 'sb_hourly_schedule_check' )) {
      //     wp_schedule_event( time(), 'hourly', 'sb_hourly_schedule_check');
      // }
      if (! wp_next_scheduled ( 'wp_sb_add_every_five_minutes' )) {
          wp_schedule_event( time(), 'sb_five_minutes', 'sb_hourly_schedule_check');
      }
    }


    /**
     * Daily cron
     */
    public function sb_daily_status() {

      $facebook = new Rx_Sb_Facebook();
      $twitter = new Rx_Sb_Twitter();
      $tumblr = new Rx_Sb_Tumblr();
      if (class_exists('Rx_Sb_Linkedin')) {
        $linkedin = new Rx_Sb_Linkedin();
      }
      if (class_exists('Rx_Sb_Reddit')) {
        $reddit = new Rx_Sb_Reddit();
      }

      global $wpdb;
      $Network_table = $wpdb->prefix . 'sb_networks';
      $data =   $wpdb->get_results("SELECT *FROM $Network_table");
      if (!empty($data)) {
        foreach ($data as $info) {
          //===facebook===//
          if ($info->network == 'facebook') {
          	$report = $facebook->sb_fb_auth_status($info->id);
            if ($report == 'inactive') {
              $result = $facebook->sb_update_connection_status($info->id,'inactive');
              update_option('facebook_auth_expire','1');
            }
          }
          //===twitter===//
          if ($info->network == 'twitter') {
          	$report = $twitter->sb_tw_auth_status($info->id);
            if ($report == 'inactive') {
              $result = $twitter->sb_update_connection_status($info->id,'inactive');
              update_option('twitter_auth_expire','1');
            }
          }
          //===tumblr===//
          if ($info->network == 'tumblr') {
          	$report = $tumblr->sb_tm_auth_status($info->id);
            if ($report == 'inactive') {
              $result = $tumblr->sb_update_connection_status($info->id,'inactive');
              update_option('tumblr_auth_expire','1');
            }
          }

          //===linkedin===//
          if ($info->network == 'linkedin') {
            if ($linkedin) {
              $report = $linkedin->sb_ld_auth_status($info->id);
              if ($report == 'inactive') {
                $result = $linkedin->sb_update_connection_status_pro($info->id,'inactive');
                update_option('linkedin_auth_expire','1');
              }
            }
          }

          //===reddit===//
          // if ($info->network == '$reddit') {
          //   if ($reddit) {
          //     $report = $reddit->sb_ld_auth_status($info->id);
          //     if ($report == 'inactive') {
          //       $result = $linkedin->sb_update_connection_status_pro($info->id,'inactive');
          //       update_option('linkedin_auth_expire','1');
          //     }
          //   }
          // }

        }
      }
    }

    /**
     * Hourly cron
     */
    public function sb_hourly_schedule_check() {
      global $wpdb;
      $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
      $network_table = $wpdb->prefix . 'sb_networks';
      $data =   $wpdb->get_results("SELECT *FROM $schedule_table  ");
      $facebook = new Rx_Sb_Facebook();
      $twitter = new Rx_Sb_Twitter();
      $tumblr = new Rx_Sb_Tumblr();
      $pinterest = new Rx_Sb_Pinterest();
      $publish_type = 'shared';
      $network_check = unserialize(SOCIAL_BOOSTER_NETWORKS);
      $current_time = current_time('mysql', false);

      if (!empty($data)) {
        foreach ($data as $info) {
            $schedule_time = $info->schedule_time;
            $schedule_type = $info->schedule_type;

            if ($schedule_time <= $current_time) {

              if ($schedule_type == 'daily') {
                $new_schedule_time = date( 'Y-m-d H:i:s', strtotime( $schedule_time ) + 86400 );
              }
              elseif($schedule_type == 'weekly') {
                $new_schedule_time = date( 'Y-m-d H:i:s', strtotime( $schedule_time ) + 604800 );
              }
              elseif($schedule_type == 'monthly') {
                $new_schedule_time = date( 'Y-m-d H:i:s', strtotime( $schedule_time ) + 2628000 );
              }
              elseif($schedule_type == 'none'){
                $new_schedule_time = 'none';
              }

              $post_meta = unserialize($info->post_meta);
              $message = $post_meta['message'];

              $link = get_permalink($info->post_id);
              $network_id = $info->network_id;

              $network_type = $wpdb->get_var("SELECT network FROM $network_table WHERE id=$network_id ");


              $post_status = get_post_status ( $info->post_id );

              if ($post_status == 'publish') {
                if ($network_type == 'facebook') {
                  $data_post = $facebook->sb_send_feed_to_facebook($info->post_id, $info->profile_id, $info->network_id, $message, $link, $publish_type);
                  if ($data_post) {
                    if ($new_schedule_time == 'none') {
                        $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                    }
                    else {
                      $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                    }
                  }
                }
                if ($network_type == 'twitter') {
                  $data_post = $twitter->sb_send_feed_to_twitter($info->post_id, $info->profile_id, $info->network_id, $message, $link, $publish_type);
                  if ($data_post) {
                    if ($new_schedule_time == 'none') {
                        $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                    }
                    else {
                      $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                    }
                  }
                }
                if ($network_type == 'tumblr') {
                  $data_post = $tumblr->sb_send_feed_to_tumblr($info->post_id, $info->profile_id, $info->network_id, $message, $link, $publish_type);
                  if ($data_post) {
                    if ($new_schedule_time == 'none') {
                        $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                    }
                    else {
                      $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                    }
                  }
                }

                if ($network_type == 'pinterest') {
                  $board = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$info->network_id ");
                  if (empty($message)) {
                      $message =  get_the_title($info->post_id);
                  }
                  $data_post = $pinterest->sb_send_feed_to_pinterest($info->post_id, $info->profile_id, $info->network_id, $message, $link, $board, $publish_type);
                  if ($data_post) {
                    if ($new_schedule_time == 'none') {
                        $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                    }
                    else {
                      $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                    }
                  }
                }

                if ($network_type == 'linkedin') {
                  if(array_key_exists($network_type, $network_check)) {
                    $linkedin = new Rx_Sb_Linkedin();
                    $data_post = $linkedin->sb_send_feed_to_linkedin($info->post_id, $info->profile_id, $info->network_id, $message, $link, $publish_type);
                    if ($data_post) {
                      if ($new_schedule_time == 'none') {
                          $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                      }
                      else {
                        $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                      }
                    }
                  }
                }

                if ($network_type == 'reddit') {
                  if(array_key_exists($network_type, $network_check)) {
                    $reddit = new Rx_Sb_Reddit();
                    $data_post = $reddit->sb_send_feed_to_reddit($info->post_id, $info->profile_id, $info->network_id, $message, $link, $publish_type);
                    if ($data_post) {
                      if ($new_schedule_time == 'none') {
                          $wpdb->delete( $schedule_table, array( 'id' => $info->id ) );
                      }
                      else {
                        $wpdb->update($schedule_table, array('schedule_time'=>$new_schedule_time), array('id'=>$info->id));
                      }
                    }
                  }
                }
              }
            }
        }
      }
    }


    /**
     * Clear
     */
    public function sb_clear_event() {
      wp_clear_scheduled_hook( 'sb_daily_auth_status_check' );
      wp_clear_scheduled_hook( 'sb_hourly_schedule_check' );
    }
}
