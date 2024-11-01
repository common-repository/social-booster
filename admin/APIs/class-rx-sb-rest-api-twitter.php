<?php

/**
 * REST_API Handler Facebook
 */
class Rx_Sb_Rest_Api_twitter{

    /**
     * Add facebook authorization profile
     */
    public static function sb_add_tw_auth($data) {
        update_option( 'network_authentication', 'twitter' );
        $consumer_key = $data->get_param( 'consumer_key' );
        $consumer_secret = $data->get_param( 'consumer_secret' );
        $profile_id = $data->get_param( 'profile_id' );
        $profile_name = $data->get_param( 'profile_name' );
        update_option( 'twitter_consumer_key', $consumer_key );
        update_option( 'twitter_consumer_secret', $consumer_secret );
        update_option( 'twitter_profile_id', $profile_id );
        update_option( 'twitter_profile_name', $profile_name );
        \Codebird\Codebird::setConsumerKey($consumer_key,$consumer_secret);
        $cb = \Codebird\Codebird::getInstance();
        $reply = $cb->oauth_requestToken([
            'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
        ]);

        $_SESSION['oauth_verify'] = true;
        $auth_url = $cb->oauth_requestToken();

        $url = $auth_url->oauth_token;
        if (empty($url)) {
            return ('Error occured. Please check again your consumer key and consumer secret');
        }
        $authorize_url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $url ;
        return $authorize_url;
    }

    /**
     * Read facebook authorization profile
     */
    public static function sb_read_tw_auth_data($data) {


        return new \WP_REST_Response( array(
            'success' => true,
            'Data' => $data
        ), 200 );
    }

    /**
     * Edit facebook authorization profile
     */
    public static function sb_edit_tw_auth_data($data) {


        return new \WP_REST_Response( array(
            'success' => true,
            'Data' => $data
        ), 200 );
    }

    /**
     * Delete facebook authorization profile
     */
    public static function sb_delete_tw_auth_data($data) {

        global $wpdb;
        $Network_table = $wpdb->prefix . 'sb_networks';

        $id = $data->get_param( 'id' );
        $wpdb->delete( $Network_table, array( 'id' => $id ) );

        return new \WP_REST_Response( array(
            'success' => true,
        ), 200 );
    }

    public static function sb_tweet($data) {
        $post_id = $data->get_param( 'post_id' );
        $network_id = $data->get_param( 'network_id' );
        $id = $data->get_param( 'id' );
        $status = $data->get_param( 'status' );
        $link = $data->get_param( 'link' );
        $twitter = new Rx_Sb_Twitter();
        $data_post = $twitter->sb_send_feed_to_twitter($post_id, $network_id, $id, $status, $link);
        if(!$data_post)
            return new \WP_REST_Response(null, 404);
        return new \WP_REST_Response( array(
            'success' => true,
        ), 200 );
    }

}
