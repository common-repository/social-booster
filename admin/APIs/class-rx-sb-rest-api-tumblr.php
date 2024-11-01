
<?php

/**
 * REST_API Handler Facebook
 */
class Rx_Sb_Rest_Api_tumblr{

  /**
   * Add facebook authorization profile
   */
  public static function sb_add_tm_auth($data) {

    update_option( 'network_authentication', 'tumblr' );
    $consumer_key = $data->get_param( 'consumer_key' );
    $consumer_secret = $data->get_param( 'consumer_secret' );

    $profile_id = $data->get_param( 'profile_id' );
    $profile_name = $data->get_param( 'profile_name' );
    $tumblr_url = $data->get_param( 'blog_url' );

    update_option( 'tumblr_consumer_key', $consumer_key );
    update_option( 'tumblr_consumer_secret', $consumer_secret );

    update_option( 'tumblr_profile_id', $profile_id );
    update_option( 'tumblr_profile_name', $profile_name );
    update_option( 'tumblr_blog_url', $tumblr_url );

    $client = new Tumblr\API\Client($consumer_key, $consumer_secret);
    $requestHandler = $client->getRequestHandler();
    $requestHandler->setBaseUrl('https://www.tumblr.com/');

    $resp = $requestHandler->request('POST', 'oauth/request_token', array());

    $out = $result = $resp->body;
    $data = array();
    parse_str($out, $data);

    update_option( 'tumblr_request_token', $data['oauth_token'] );
    update_option( 'tumblr_request_token_secret', $data['oauth_token_secret'] );

    // $_SESSION['request_token'] = $data['oauth_token'];
    // $_SESSION['request_token_secret'] = $data['oauth_token_secret'];

    if($data['oauth_callback_confirmed']) {
        $url = 'https://www.tumblr.com/oauth/authorize?oauth_token=' . $data['oauth_token'];
        return $url;
    } else {
      return ('Could not connect to Tumblr. Refresh the page or try again later.');
    }
  }

  /**
   * Read facebook authorization profile
   */
  public static function sb_read_tm_auth_data($data) {


      return new \WP_REST_Response( array(
          'success' => true,
          'Data' => $data
      ), 200 );
  }

  /**
   * Edit facebook authorization profile
   */
  public static function sb_edit_tm_auth_data($data) {


      return new \WP_REST_Response( array(
          'success' => true,
          'Data' => $data
      ), 200 );
  }

  /**
   * Delete facebook authorization profile
   */
  public static function sb_delete_tm_auth_data($data) {

    global $wpdb;
    $Network_table = $wpdb->prefix . 'sb_networks';

    $id = $data->get_param( 'id' );
    $wpdb->delete( $Network_table, array( 'id' => $id ) );

    return new \WP_REST_Response( array(
        'success' => true,
    ), 200 );
  }

  public static function sb_tumb($data) {
      $post_id = $data->get_param( 'post_id' );
      $network_id = $data->get_param( 'network_id' );
      $id = $data->get_param( 'id' );
      $message = $data->get_param('message');
      $link = $data->get_param('link');
      $tumblr = new Rx_Sb_Tumblr();
      $data_post = $tumblr->sb_send_feed_to_tumblr($post_id, $network_id, $id, $message, $link);
      if(!$data_post)
          return new \WP_REST_Response(null, 404);
      return new \WP_REST_Response( array(
          'success' => true,
      ), 200 );
  }

}
