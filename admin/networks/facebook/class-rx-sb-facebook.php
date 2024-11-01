<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/admin/facebook
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/admin/facebook
 * @author     RexTheme <#>
 */

class Rx_Sb_Facebook extends Rx_Sb_Network {
    /**
     * User profile authorization
     */
    public function sb_profile_auth($profile_id, $appid, $platform, $platform_id, $accesstoken) {

    }

    /**
     * User profile authorization
     */
    public function sb_page_auth($profile_id, $profile_name, $appid, $app_secret, $platform, $platform_id, $accesstoken) {

        global $wpdb;
        $Profile_table = $wpdb->prefix . 'sb_profiles';
        $Network_table = $wpdb->prefix . 'sb_networks';
        $schedule_table = $wpdb->prefix . 'sb_scheduled_posts';
        $shared_table = $wpdb->prefix . 'sb_shared_posts';
        $network = 'facebook';

        if ($appid == '423374891779153') {
            $auth_type = 'auto';
        }
        else {
            $auth_type = 'manual';
        }
        $auth_platform = 'page';
        $status = 'active';
        $connected = 'active';
        $date_time = $this->sb_register_date();
        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'allow_self_signed' => true
            ]
        ];
        $sslContext = stream_context_create($contextOptions);
        $long_lived_url = 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id='.$appid.'&client_secret='.$app_secret.'&fb_exchange_token='.$accesstoken.'';
        $long_lived_json = file_get_contents($long_lived_url,false, $sslContext);
        $long_lived_obj = json_decode($long_lived_json,true);
        if ($long_lived_obj['access_token']) {
            $long_lived_accesstoken = $long_lived_obj['access_token'];
        }
        else {
            return array(
                'type'=> 'Error',
                'message'=> 'Failed to get long lived access token'
            );
        }
        $nameurl = 'https://graph.facebook.com/'.$platform_id.'?fields=name&access_token='.$long_lived_accesstoken.'';
        $name_json = file_get_contents($nameurl,false, $sslContext);
        $name_obj = json_decode($name_json,true);
        if ($name_obj['name']) {
            $net_profile_name = $name_obj['name'];
        }


        $url = 'https://graph.facebook.com/'.$platform_id.'?fields=access_token&access_token='.$long_lived_accesstoken.'';
        $json = file_get_contents($url,false, $sslContext);
        $obj = json_decode($json,true);

        if ($obj['access_token']) {
            $token = $obj['access_token'];
        }
        else {
            return array(
                'type'=> 'Error',
                'message'=> 'Failed to get page access token'
            );
        }

        $exist = $wpdb->get_var( "SELECT COUNT(*)  FROM  $Profile_table WHERE profile_id =  '$profile_id'");
        if(!$exist) {
            $wpdb->insert($Profile_table, array(
                'profile_id' => $profile_id,
                'profile_name' => $profile_name,
            ));
        }
        $net_id = $wpdb->get_var( "SELECT id  FROM  $Network_table WHERE profile_id =  '$profile_id' AND network = '$network' AND auth_platform = '$auth_platform'");
        $wpdb->delete( $Network_table, array( 'profile_id' => $profile_id, 'network' => $network, 'auth_platform' => $platform ) );
        $wpdb->insert(
            $Network_table,
            array(
                'profile_id' => $profile_id,
                'network' => $network,
                'auth_type' => $auth_type,
                'auth_platform' => $platform,
                'auth_platform_id' => $platform_id,
                'token' => $token,
                'auth_status' => $status,
                'auth_con' => $connected,
                'prof_name' => $net_profile_name,
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

    /**
     * User group authorization
     */
    public function sb_group_auth($profile_id, $profile_name, $appid, $app_secret, $platform, $platform_id, $accesstoken) {
        global $wpdb;
        $Profile_table = $wpdb->prefix . 'sb_profiles';
        $Network_table = $wpdb->prefix . 'sb_networks';
        $network = 'facebook';

        if ($appid == '423374891779153') {
            $auth_type = 'auto';
        }
        else {
            $auth_type = 'manual';
        }
        $auth_platform = 'group';
        $status = 'active';
        $connected = 'active';
        $date_time = $this->sb_register_date();

        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'allow_self_signed' => true
            ]
        ];
        $sslContext = stream_context_create($contextOptions);
        $long_lived_url = 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id='.$appid.'&client_secret='.$app_secret.'&fb_exchange_token='.$accesstoken.'';
        $long_lived_json = file_get_contents($long_lived_url,false, $sslContext);
        $long_lived_obj = json_decode($long_lived_json,true);
        if ($long_lived_obj['access_token']) {
            $long_lived_accesstoken = $long_lived_obj['access_token'];
        }
        else {
            return array(
                'type'=> 'Error',
                'message'=> 'Failed to get long lived access token'
            );
        }

        $nameurl = 'https://graph.facebook.com/'.$platform_id.'?fields=name&access_token='.$long_lived_accesstoken.'';
        $name_json = file_get_contents($nameurl,false, $sslContext);
        $name_obj = json_decode($name_json,true);
        if ($name_obj['name']) {
            $net_profile_name = $name_obj['name'];
        }

        $exist = $wpdb->get_var( "SELECT COUNT(*)  FROM  $Profile_table WHERE profile_id =  '$profile_id'");
        if(!$exist) {
            $wpdb->insert($Profile_table, array(
                'profile_id' => $profile_id,
                'profile_name' => $profile_name,
            ));
        }
        $wpdb->delete( $Network_table, array( 'profile_id' => $profile_id, 'network' => $network, 'auth_platform' => $platform ) );
        $wpdb->insert(
            $Network_table,
            array(
                'profile_id' => $profile_id,
                'network' => $network,
                'auth_type' => $auth_type,
                'auth_platform' => $platform,
                'auth_platform_id' => $platform_id,
                'token' => $long_lived_accesstoken,
                'auth_status' => $status,
                'auth_con' => $connected,
                'prof_name' => $net_profile_name,
                'auth_date' => $date_time,
            )
        );
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

    /**
     * User feed post
     */
    public function sb_send_feed_to_facebook($post_id, $network_id, $id,$message,$link, $share_type = 'instant') {

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';
        $platform_id = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $token = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");

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
            'message' => $message,
            'link' => $link,
            'access_token' => $token
        );
        if (empty($message) && empty($link)) {
            return ('No data to post on facebook');
        }
        if (empty($message)) {
            unset($data_array['message']);
        }
        if (empty($link)) {
            unset($data_array['link']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/'.$platform_id.'/feed');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_array));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $error = false;
        if (curl_error($ch)) {
            $error = true;
        }
        curl_close ($ch);
        $published_date = $this->sb_register_date();
        if($error) {
            $this->save_shared_posts($post_id, $network_id, $id, $data_array, $published_date, $share_type, false, 'Post was not published');
            return true;
        }
        $this->save_shared_posts($post_id, $network_id, $id, $data_array, $published_date, $share_type, true, '');
        return true;
    }

    /**
     * User photo post
     */
    public function sb_send_photo_to_facebook($id,$caption,$url) {

    }

    /**
     * User authorization status
     */
    public function sb_fb_auth_status($id) {

        global $wpdb;
        $contextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'allow_self_signed' => true
            ]
        ];
        $sslContext = stream_context_create($contextOptions);
        $network_table = $wpdb->prefix . 'sb_networks';
        $platform_id = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$id ");
        $token = $wpdb->get_var("SELECT token FROM $network_table WHERE id=$id ");
        $nameurl = 'https://graph.facebook.com/'.$platform_id.'?fields=name&access_token='.$token.'';
        $name_json = file_get_contents($nameurl,false, $sslContext);
        $name_obj = json_decode($name_json,true);
        if ($name_obj == null) {
            return 'inactive';
        }
        if ($name_obj['name']) {
            return 'active';
        }
        else {
            return 'inactive';
        }
    }

    /**
     * User authorization warning notice
     */
    public function sb_fb_auth_warning_notice() {
        $twitter_warning = get_option('facebook_auth_expire');
        if ($twitter_warning == '1') {
            update_option('facebook_auth_expire','0');
            echo '
        <div class="notice notice-warning is-dismissible" >
        <p>Facebook authorization expired or suspended. Please authoriztion again.</p>
        </div>
        ';
        }
    }
}
