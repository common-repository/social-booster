<?php

/**
 * Class Rx_Sb_Tumblr
 */
class Rx_Sb_Tumblr extends Rx_Sb_Network{

    /**
     * Tumblr Authentication
     */
    public function sb_tumblr_auth($profile_id, $profile_name, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret) {
        global $wpdb;
        $Profile_table = $wpdb->prefix . 'sb_profiles';
        $Network_table = $wpdb->prefix . 'sb_networks';
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $shared_table = $wpdb->prefix . 'sb_shared_posts';
        $wpdb->delete( $Profile_table, array( 'profile_id' => $profile_id ) );

        $network = 'tumblr';
        $auth_type = 'auto';
        $auth_platform = 'profile';
        $auth_status = 'active';
        $auth_con = 'active';
        $net_id = $wpdb->get_var( "SELECT id  FROM  $Network_table WHERE profile_id =  '$profile_id' AND network = '$network' AND auth_platform = '$auth_platform'");
        $wpdb->delete( $Network_table, array( 'profile_id' => $profile_id, 'network' => $network, 'auth_platform' => $auth_platform ) );
        $date_time = $this->sb_register_date();
        $blog_url = get_option('tumblr_blog_url');
        $extra_array = array(
          'consumer_key' => $consumer_key,
          'consumer_secret' => $consumer_secret,
        );
        $extra = serialize($extra_array);

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
                'prof_name' => $blog_url,
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

    public function sb_send_feed_to_tumblr($post_id, $network_id, $id,$message='',$link, $share_type = 'instant') {
        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $auth_token = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $auth_token_secret = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");
        $extra = $wpdb->get_var("SELECT extra FROM $network_table WHERE id=$id ");
        $extra_array = unserialize($extra);
        $featured_img_url = get_the_post_thumbnail_url($post_id,'full');
        $excerpt = wp_strip_all_tags(get_the_excerpt($post_id));

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

        $data_array = array(
            'type' => 'link',
            'title' => $message,
            'url' => $link,
            'thumbnail' => $featured_img_url,
            'excerpt' => $excerpt,
        );
        if (empty($featured_img_url)) {
          unset($data_array['thumbnail']);
        }
        if (empty($excerpt)) {
          unset($data_array['excerpt']);
        }
        if (empty($message) && empty($link)) {
            return('No status to post');
        }

        $consumer_key = $extra_array['consumer_key'];
        $consumer_secret = $extra_array['consumer_secret'];

        $bgname = $wpdb->get_var("SELECT prof_name FROM $network_table WHERE id=$id ");
        $client = new Tumblr\API\Client($consumer_key, $consumer_secret, $auth_token, $auth_token_secret);
        $postData = $data_array;
        $published_date = $this->sb_register_date();
        try {
            $reply = $client->createPost($bgname, $postData);
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
    public function sb_tm_auth_status($id) {

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $auth_token = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $auth_token_secret = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");
        $extra = $wpdb->get_var("SELECT extra FROM $network_table WHERE id=$id ");
        $extra_array = unserialize($extra);
        $consumer_key = $extra_array['consumer_key'];
        $consumer_secret = $extra_array['consumer_secret'];
        $client = new Tumblr\API\Client($consumer_key, $consumer_secret, $auth_token, $auth_token_secret);
        $reply = $client->getUserInfo();
        if ($reply->user->name) {
            return 'active';
        }
        else {
            return 'inactive';
        }
    }

    /**
     * User authorization warning notice
     */
    public function sb_tm_auth_warning_notice() {
        $twitter_warning = get_option('tumblr_auth_expire');
        if ($twitter_warning == '1') {
            update_option('tumblr_auth_expire','0');
            echo '
      <div class="notice notice-warning is-dismissible" >
      <p>Tumblr authorization expired or suspended. Please authoriztion again.</p>
      </div>
      ';
        }
    }
}
