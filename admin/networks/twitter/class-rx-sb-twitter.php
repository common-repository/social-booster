<?php

/**
 * Class Rx_Sb_Twitter
 */
class Rx_Sb_Twitter extends Rx_Sb_Network {

    public function sb_twitter_auth($profile_id, $profile_name, $oauth_token, $oauth_token_secret, $screen_name, $extra) {

        global $wpdb;
        $Profile_table = $wpdb->prefix . 'sb_profiles';
        $Network_table = $wpdb->prefix . 'sb_networks';
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $shared_table = $wpdb->prefix . 'sb_shared_posts';
        $wpdb->delete( $Profile_table, array( 'profile_id' => $profile_id ) );

        $network = 'twitter';
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

        $wpdb->insert(
            $Network_table,
            array(
                'profile_id' => $profile_id,
                'network' => $network,
                'auth_type' => $auth_type,
                'auth_platform' => $auth_platform,
                'auth_platform_id' => $oauth_token,
                'token' => $oauth_token_secret,
                'auth_status' => $auth_status,
                'auth_con' => $auth_con,
                'prof_name' => $screen_name,
                'extra' => $extra,
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

    public function sb_send_feed_to_twitter($post_id, $network_id, $id,$message,$link, $share_type = 'instant') {
        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $auth_token = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $auth_token_secret = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");
        $extra = $wpdb->get_var("SELECT extra FROM $network_table WHERE id=$id ");
        $extra_array = unserialize($extra);

        //==wordpress tag addition===//
        $tagEnabler = get_option( 'tagEnabler' );
        if ($tagEnabler) {
          $get_post_tags = get_the_tags( $post_id );
          foreach ($get_post_tags as $tags) {
            $message .= ' #'.$tags->name;
          }
        }
        //==wordpress tag addition end===//

        //===UTM setup===//
        $utmEnabler = get_option( 'utmEnabler' );
        $utmSource = get_option( 'utmSource' );
        $utmMedia = get_option( 'utmMedia' );
        $utmCampaign = get_option( 'utmCampaign' );
        if ($utmEnabler && $utmSource && $utmMedia && $utmCampaign) {
          $link = $link.'?utm_source='.$utmSource.'&utm_medium='.$utmMedia.'&utm_campaign='.$utmCampaign.'';
        }
        //===UTM setup===//

        //===Bitly setup===//
        $bitly_enabler = get_option( 'bitly_enabler' );
        $bitly_login = get_option( 'bitly_login' );
        $bitly_api_key = get_option( 'bitly_api_key' );
        if ($bitly_enabler && $bitly_login && $bitly_api_key) {
            $short_link = $this->rx_sb_make_bitly_url($link,$bitly_login,$bitly_api_key,'json');
            if ($short_link) {
              $link = $short_link;
            }
        }
        //===Bitly setup===//

        //===tiny url setup===//
        $tiny_url_enabler = get_option( 'tinyUrlEnabler' );
        if ($tiny_url_enabler) {
            $tiny_link = $this->rx_sb_get_tiny_url($link);
            if ($tiny_link) {
              $link = $tiny_link;
            }
        }
        //===tiny url setup end===//

        //===isgd url setup===//
        $isgdEnabler = get_option( 'isgdEnabler' );
        if ($isgdEnabler) {
            $isgd_link = $this->rx_sb_isgdShorten($link);
            if ($isgd_link['shortURL']) {
              $link = $isgd_link['shortURL'];
            }
        }
        //===isgd url setup end===//

        $status = $message.' '.$link;
        $data_array = array(
            'status' => $status,
        );
        if (empty($status)) {
            return('No status to post');
        }
        $consumer_key = $extra_array['consumer_key'];
        $consumer_secret = $extra_array['consumer_secret'];

        \Codebird\Codebird::setConsumerKey( $consumer_key, $consumer_secret );
        $cb = \Codebird\Codebird::getInstance();

        $cb->setToken($auth_token, $auth_token_secret);
        $params = $data_array;
        $published_date = $this->sb_register_date();
        try {
            $reply = $cb->statuses_update($params);
            $this->save_shared_posts($post_id, $network_id, $id, $data_array, $published_date, $share_type, true, '');
            return true;
        } catch(Exception $e) {
            $this->save_shared_posts($post_id, $network_id, $id, $data_array, $published_date, $share_type, false, 'Post was not published');
            return false;
        }
    }

    /**
     * User authorization status
     */
    public function sb_tw_auth_status($id) {

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $auth_token = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $auth_token_secret = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");
        $screen_name = $wpdb->get_var("SELECT prof_name FROM $network_table WHERE id=$id ");
        $extra = $wpdb->get_var("SELECT extra FROM $network_table WHERE id=$id ");
        $extra_array = unserialize($extra);
        $data_array = array(
            'screen_name' => $screen_name,
        );

        $consumer_key = $extra_array['consumer_key'];
        $consumer_secret = $extra_array['consumer_secret'];

        \Codebird\Codebird::setConsumerKey( $consumer_key, $consumer_secret );
        $cb = \Codebird\Codebird::getInstance();

        $cb->setToken($auth_token, $auth_token_secret);
        $params = $data_array;
        $reply = $cb->users_show($params);
        if ($reply->screen_name) {
            return 'active';
        }
        else {
            return 'inactive';
        }
    }

    /**
     * User authorization warning notice
     */
    public function sb_tw_auth_warning_notice() {
        $twitter_warning = get_option('twitter_auth_expire');
        if ($twitter_warning == '1') {
            update_option('twitter_auth_expire','0');
            echo '
              <div class="notice notice-warning is-dismissible" >
                <p>Twitter authorization expired or suspended. Please authoriztion again.</p>
              </div>
            ';
        }
    }
}
