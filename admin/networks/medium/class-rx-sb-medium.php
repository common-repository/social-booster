<?php
use JonathanTorres\MediumSdk\Medium;
/**
 * Class Rx_Sb_Medium
 */
class Rx_Sb_Medium extends Rx_Sb_Network{

    /**
     * Medium Authentication
     */
    public function sb_Medium_auth() {
        $credentials = [
            'client-id' => 'CLIENT-ID',
            'client-secret' => 'CLIENT-SECRET',
            'redirect-url' => 'http://example.com/callback',
            'state' => 'somesecret',
            'scopes' => 'scope1,scope2',
        ];

        $medium = new Medium($credentials);

        return $medium;
    }

    public function sb_send_feed_to_Medium($post_id, $network_id, $id,$message='',$link, $share_type = 'instant') {

    }

    /**
     * User authorization status
     */
    public function sb_medium_auth_status($id) {

    }

    /**
     * User authorization warning notice
     */
    public function sb_medium_auth_warning_notice() {

    }
}
