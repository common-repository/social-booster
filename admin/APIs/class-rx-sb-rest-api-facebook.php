
<?php

/**
 * REST_API Handler Facebook
 */
class Rx_Sb_Rest_Api_facebook{

    /**
     * Add facebook authorization profile
     */
    public static function sb_add_fb_auth($data) {
        $facebook = new Rx_Sb_Facebook();
        $app_id = $data->get_param( 'app_id' );
        $app_secret = $data->get_param( 'app_secret' );
        $platform = $data->get_param( 'platform' );
        $platform_id = $data->get_param( 'platform_id' );
        $accesstoken = $data->get_param( 'accesstoken' );
        $profile_id = $data->get_param( 'profile_id' );
        $profile_name = $data->get_param( 'profile_name' );

        if ($platform == 'page') {
            $data_save = $facebook->sb_page_auth($profile_id,$profile_name,$app_id,$app_secret,$platform,$platform_id,$accesstoken);
        }
        elseif ($platform == 'group') {
            $data_save = $facebook->sb_group_auth($profile_id,$profile_name,$app_id,$app_secret,$platform,$platform_id,$accesstoken);
        }

        return new \WP_REST_Response( array(
            'success' => true,
            'data' => $data_save
        ), 200 );
    }

    /**
     * Read facebook authorization profile
     */
    public static function sb_read_fb_auth_data($data) {


        return new \WP_REST_Response( array(
            'success' => true,
            'Data' => $data
        ), 200 );
    }

    /**
     * Edit facebook authorization profile
     */
    public static function sb_edit_fb_auth_data($data) {

        return new \WP_REST_Response( array(
            'success' => true,
            'Data' => $data
        ), 200 );
    }

    /**
     * Delete facebook authorization profile
     */
    public static function sb_delete_fb_auth_data($data) {

        global $wpdb;
        $Network_table = $wpdb->prefix . 'sb_networks';

        $id = $data->get_param( 'id' );
        $wpdb->delete( $Network_table, array( 'id' => $id ) );

        return new \WP_REST_Response( array(
            'success' => true,
        ), 200 );
    }



    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function instant_share_fb(WP_REST_Request $request) {
        $post_id = $request->get_param( 'post_id' );
        $network_id = $request->get_param( 'network_id' );
        $id = $request->get_param('id');
        $message = $request->get_param('message');
        $link = $request->get_param('link');
        $facebook = new Rx_Sb_Facebook();
        $data_post = $facebook->sb_send_feed_to_facebook($post_id, $network_id, $id, $message, $link);
        if(!$data_post)
            return new \WP_REST_Response(null, 404);
        return new \WP_REST_Response( array(
            'success' => true,
        ), 200 );
    }

}
