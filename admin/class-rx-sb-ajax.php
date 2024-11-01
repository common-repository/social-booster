<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific Ajax files.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/admin
 */

class Rx_Sb_Ajax {

  /*
   * Post from plugin post
   */
  function rx_sb_post() {
    global $wpdb;
    $Network_table = $wpdb->prefix . 'sb_networks';
    $data = $_POST['data'];
    $postid = sanitize_text_field($_POST['postid']);
    $post_status = get_post_status($postid);
    if ($post_status != 'publish') {
      wp_send_json_error('<p>This post is not published yet.</p>');
    }
    $check_networks = unserialize(SOCIAL_BOOSTER_NETWORKS);
    $post_permalink = get_permalink($postid);

    $facebook = new Rx_Sb_Facebook();
    $twitter = new Rx_Sb_Twitter();
    $tumblr = new Rx_Sb_Tumblr();
    $pinterest = new Rx_Sb_Pinterest();

    foreach ($data as $data_value) {
      $network_id = $data_value['id'];
      $message = sanitize_text_field($data_value['caption']);
      $network = $wpdb->get_var("SELECT network FROM $Network_table WHERE id=$network_id ");
      $profile_id = $wpdb->get_var("SELECT profile_id FROM $Network_table WHERE id=$network_id ");

      if (array_key_exists($network, $check_networks)) {
        if ($network == 'facebook') {
            $data_post = $facebook->sb_send_feed_to_facebook($postid, $profile_id, $network_id, $message, $post_permalink);
        }
        if ($network == 'twitter') {
            $data_post = $twitter->sb_send_feed_to_twitter($postid, $profile_id, $network_id, $message, $post_permalink);
        }
        if ($network == 'tumblr') {
            $data_post = $tumblr->sb_send_feed_to_tumblr($postid, $profile_id, $network_id, $message, $post_permalink);
        }
        if ($network == 'pinterest') {
            $board = $wpdb->get_var("SELECT auth_platform_id FROM $Network_table WHERE id=$network_id ");
            if (empty($message)) {
                $message =  get_the_title($postid);
            }
            $data_post = $pinterest->sb_send_feed_to_pinterest($postid, $profile_id, $network_id, $message, $post_permalink, $board);
        }
        if ($network == 'linkedin') {
            $linkedin = new Rx_Sb_Linkedin();
            $data_post = $linkedin->sb_send_feed_to_linkedin($postid, $profile_id, $network_id, $message, $post_permalink);
        }
        if ($network == 'reddit') {
            $reddit = new Rx_Sb_Reddit();
            $data_post = $reddit->sb_send_feed_to_reddit($postid, $profile_id, $network_id, $message, $post_permalink);
        }
      }
    }
    die();
  }

  /*
   * Schedule post
   */
  function rx_sb_schedule() {
    global $wpdb;
    $Network_table = $wpdb->prefix . 'sb_networks';
    $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
    $data = $_POST['data'];
    $postid = sanitize_text_field($_POST['postid']);
    $post_status = get_post_status($postid);
    // if ($post_status != 'publish') {
    //   wp_send_json_error('<p>This post is not published yet.</p>');
    // }

    $check_networks = unserialize(SOCIAL_BOOSTER_NETWORKS);
    $post_permalink = get_permalink($postid);

    $facebook = new Rx_Sb_Facebook();
    $twitter = new Rx_Sb_Twitter();
    $tumblr = new Rx_Sb_Tumblr();
    $pinterest = new Rx_Sb_Pinterest();

    $append = array();

    foreach ($data as $data_value) {
      $network_id = $data_value['id'];
      $network = $wpdb->get_var("SELECT network FROM $Network_table WHERE id=$network_id ");
      if ($data_value['schedule_type'] == 'none' && $data_value['schedule_date_time'] == 'none') {
          wp_send_json_error('<span>Warning:</span> No schedule type found for network: '.$network.'');
      }
      $message = sanitize_text_field($data_value['caption']);
      $post_meta = array(
        'message'=> $message,
        'link'=> $post_permalink,
      );
      $post_meta = serialize($post_meta);
      $network = $wpdb->get_var("SELECT network FROM $Network_table WHERE id=$network_id ");
      $profile_id = $wpdb->get_var("SELECT profile_id FROM $Network_table WHERE id=$network_id ");
      $profile_name = $wpdb->get_var("SELECT prof_name FROM $Network_table WHERE id=$network_id ");

      if (array_key_exists($network, $check_networks)) {
        $schedule = $data_value['schedule_type'];
        $current_time = current_time('mysql', false);
        if ($data_value['schedule_date_time'] != "none") {
            $schedule_time = date('Y-m-d H:i:s', strtotime($data_value['schedule_date_time']));
            $current_time_check = date( 'Y-m-d H:i:s', strtotime( $current_time ) - 60 );
            if ($schedule_time < $current_time_check) {
              wp_send_json_error('<span>Warning:</span> You can not select date or time before current time for '.$network.'');
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

        if ($schedule == 'none') {
          if ($data_value['schedule_date_time'] != "none") {
            $wpdb->insert(
                $schedule_table,
                array(
                    'post_id' => $postid,
                    'post_meta' => $post_meta,
                    'profile_id' => $profile_id,
                    'network_id' => $network_id,
                    'share_type' => 'scheduled',
                    'schedule_type' => $schedule,
                    'schedule_time' => $schedule_time,
                )
            );
          }
        }
        else {
          if ($data_value['schedule_date_time'] != "none") {
              $wpdb->insert(
                  $schedule_table,
                  array(
                      'post_id' => $postid,
                      'post_meta' => $post_meta,
                      'profile_id' => $profile_id,
                      'network_id' => $network_id,
                      'share_type' => 'scheduled',
                      'schedule_type' => $schedule,
                      'schedule_time' => $schedule_time,
                  )
              );
          }
          else {
            if ($network == 'facebook') {
                $data_post = $facebook->sb_send_feed_to_facebook($postid, $profile_id, $network_id, $message, $post_permalink);
            }
            if ($network == 'twitter') {
                $data_post = $twitter->sb_send_feed_to_twitter($postid, $profile_id, $network_id, $message, $post_permalink);
            }
            if ($network == 'tumblr') {
                $data_post = $tumblr->sb_send_feed_to_tumblr($postid, $profile_id, $network_id, $message, $post_permalink);
            }
            if ($network == 'pinterest') {
                $board = $wpdb->get_var("SELECT auth_platform_id FROM $Network_table WHERE id=$network_id ");
                if (empty($message)) {
                    $message =  get_the_title($postid);
                }
                $data_post = $pinterest->sb_send_feed_to_pinterest($postid, $profile_id, $network_id, $message, $post_permalink, $board);
            }
            if ($network == 'linkedin') {
                $linkedin = new Rx_Sb_Linkedin();
                $data_post = $linkedin->sb_send_feed_to_linkedin($postid, $profile_id, $network_id, $message, $post_permalink);
            }
            if ($network == 'reddit') {
                $reddit = new Rx_Sb_Reddit();
                $data_post = $reddit->sb_send_feed_to_reddit($postid, $profile_id, $network_id, $message, $post_permalink);
            }

            $wpdb->insert(
                $schedule_table,
                array(
                    'post_id' => $postid,
                    'post_meta' => $post_meta,
                    'profile_id' => $profile_id,
                    'network_id' => $network_id,
                    'share_type' => 'scheduled',
                    'schedule_type' => $schedule,
                    'schedule_time' => $schedule_time,
                )
            );
          }
        }
        $schedule_id = $wpdb->insert_id;
        $sch_time_line = str_replace(' ', ' / ', $schedule_time);
        $sch_explode = explode(' ', $schedule_time);
        $sch_date = $sch_explode[0];
        $sch_time = $sch_explode[1];
        $key = $network.'&'.$schedule_id;
        $html = '
                  <ul class="network-body">
                      <li class="profile"><span class="network-icon '.$network.'"><i class="fa fa-'.$network.'"></i></span>'.$profile_name.'</li>
                      <li class="time">'.$sch_time_line.'</li>
                      <li class="recurring-type">'.$schedule.'</li>
                      <li class="status">
                        <button class="edit edit-schedule" data-caption="'.$message.'" data-schedule="'.$schedule.'" data-id="'.$schedule_id.'" data-time="'.$sch_time.'" data-date="'.$sch_date.'" title="Edit" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-pencil-square"></i></button>
                        <button class="delete delete-schedule" data-id="'.$schedule_id.'" title="Delete"><i class="fa fa-trash"></i></button>
                      </li>
                  </ul>
                  ';
      $append[$key] = $html;
      }
    }
    wp_send_json_success($append);
    die();
  }

    //--og data---
    function rx_sb_og_data(){
        $postid = $_POST['postid'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        update_post_meta( $postid, 'sb_og_title', $title );
        update_post_meta( $postid, 'sb_og_description', $description );
        $data_array = array(
            'title' => $title,
            'description' => $description,
            'message' => '<p>Successfully updated open graph data.</p>'
        );
        wp_send_json_success($data_array);
    }
}
