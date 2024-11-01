<?php


use DirkGroenen\Pinterest\Pinterest;
/**
 * Class Rx_Sb_Pinterest
 */
class Rx_Sb_Pinterest extends Rx_Sb_Network{

    /**
     * Tumblr Authentication
     */
    public function sb_pinterest_auth($profile_id, $profile_name, $client_id, $client_secret, $oauth_token, $user_name, $board_name ) {
        global $wpdb;
        $Profile_table = $wpdb->prefix . 'sb_profiles';
        $Network_table = $wpdb->prefix . 'sb_networks';
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $shared_table = $wpdb->prefix . 'sb_shared_posts';
        $wpdb->delete( $Profile_table, array( 'profile_id' => $profile_id ) );

        $network = 'pinterest';
        $auth_type = 'auto';
        $auth_platform = 'profile';
        $auth_status = 'active';
        $auth_con = 'active';
        $net_id = $wpdb->get_var( "SELECT id  FROM  $Network_table WHERE profile_id =  '$profile_id' AND network = '$network' AND auth_platform = '$auth_platform'");
        $wpdb->delete( $Network_table, array( 'profile_id' => $profile_id, 'network' => $network, 'auth_platform' => $auth_platform ) );
        $date_time = $this->sb_register_date();


        $exist = $wpdb->get_var( "SELECT COUNT(*)  FROM  $Profile_table WHERE profile_id =  '$profile_id'");
        if(!$exist) {
            $wpdb->insert($Profile_table, array(
                'profile_id' => $profile_id,
                'profile_name' => $profile_name,
            ));
        }

        $extra = array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        );

        $wpdb->insert(
            $Network_table,
            array(
                'profile_id' => $profile_id,
                'network' => $network,
                'auth_type' => $auth_type,
                'auth_platform' => $auth_platform,
                'auth_platform_id' => $board_name,
                'token' => $oauth_token,
                'auth_status' => $auth_status,
                'auth_con' => $auth_con,
                'prof_name' => $user_name,
                'extra' => serialize($extra),
                'auth_date' => $date_time,
            )
        );
        $new_id = $wpdb->insert_id;
        if ($net_id) {
          $wpdb->update($schedule_table, array('network_id' => $new_id), array('network_id' => $net_id));
          $wpdb->update($shared_table, array('network_id' => $new_id), array('network_id' => $new_id));
        }
        if ($wpdb->last_error !== '') {
            return array(
                'type'=> 'Error',
                'message'=> 'Failed to authorize and save data'
            );
        }
        else {
            return array(
                'type'=> 'Success',
                'message'=> 'Successfully authorized and saved data'
            );
        }
    }

    public function sb_send_feed_to_pinterest($post_id, $profile_id, $network_id, $message='', $link, $board, $share_type = 'instant') {

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $user_name = $wpdb->get_var("SELECT prof_name FROM $network_table WHERE id=$network_id ");

        $access_token = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$network_id ");

        if (empty($message)) {
            $message =  get_the_title($post_id);
        }

        //==wordpress tag addition===//
        $tagEnabler = get_option( 'tagEnabler' );
        if ($tagEnabler) {
          $get_post_tags = get_the_tags( $post_id );
          foreach ($get_post_tags as $tags) {
            $message .= ' #'.$tags->name;
          }
        }
        //==wordpress tag addition end===//

        $message = urlencode($message);
        $link = urlencode($link);


        $data_array = array(
            'title' => $message,
            'url' => $link,
        );
        $published_date = $this->sb_register_date();
        $featured_img_url = urlencode(get_the_post_thumbnail_url($post_id,'full'));
        if (!$featured_img_url) {
          $this->save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type, false, 'No post feature image found to pin');
          return 'error';
        }

        if (!$message) {
          $this->save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type, false, 'Pin note is required');
          return 'error';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.pinterest.com/v1/pins/?board=$user_name%2F$board&note=$message&link=$link&image_url=$featured_img_url&access_token=$access_token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          $this->save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type, false, 'Failed to submit post');
          return 'error';

        } else {
          $response_decode = json_decode($response, true);

          if ($response_decode['data']) {
            $this->save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type, true, '');
            return 'success';
          }
          else {
            $this->save_shared_posts($post_id, $profile_id, $network_id, $data_array, $published_date, $share_type, false, $response_decode['message']);
            return 'error';
          }
        }
    }

    /**
     * User authorization status
     */
    public function sb_pt_auth_status($id) {


    }

    /**
     * User authorization warning notice
     */
    public function sb_pt_auth_warning_notice() {

    }
}
