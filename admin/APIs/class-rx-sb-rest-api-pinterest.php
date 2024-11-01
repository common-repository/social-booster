<?php


use DirkGroenen\Pinterest\Pinterest;

/**
 * REST_API Handler Pinterest
 */
class Rx_Sb_Rest_Api_Pinterest{

    /**
     * Add Pinterest authorization profile
     */
    public static function sb_add_pint_auth($data) {
        update_option( 'network_authentication', 'pinterest' );
        $client_id = $data->get_param('client_id');
        $client_secret = $data->get_param('client_secret');
        $board_name = $data->get_param('board_name');

        $profile_id = $data->get_param( 'profile_id' );
        $profile_name = $data->get_param( 'profile_name' );

        update_option( 'pinterest_profile_id', $profile_id );
        update_option( 'pinterest_profile_name', $profile_name );

        update_option( 'pinterest_client_id', $client_id );
        update_option( 'pinterest_client_secret', $client_secret );
        update_option( 'board_name', $board_name );
        $pinterest = new Pinterest($client_id, $client_secret);
        $loginurl = $pinterest->auth->getLoginUrl(admin_url('/admin.php?page=rex-social-booster#/'), array('read_public', 'write_public'));
        return $loginurl;
    }

    public static function sb_pin($data) {

        global $wpdb;
        $network_table = $wpdb->prefix . 'sb_networks';

        $post_id = $data->get_param( 'post_id' );
        $featured_img_url = get_the_post_thumbnail_url($post_id,'full');
        if (!$featured_img_url) {
          return new \WP_REST_Response( array(
              'error' => 'No feature image found to pin',
          ), 200 );
        }
        $profile_id = $data->get_param( 'network_id' );
        $network_id = $data->get_param( 'id' );
        $message = $data->get_param('message');
        if (empty($message)) {
          $message = get_the_title($post_id);
        }
        $link = $data->get_param('link');

        $board = $wpdb->get_var("SELECT auth_platform_id FROM $network_table WHERE id=$network_id ");

        $pinterest = new Rx_Sb_Pinterest();
        $data_post = $pinterest->sb_send_feed_to_pinterest($post_id, $profile_id, $network_id, $message, $link, $board);
        if(!$data_post)
            return new \WP_REST_Response(null, 404);
        return new \WP_REST_Response( array(
            'success' => true,
        ), 200 );
    }
}
