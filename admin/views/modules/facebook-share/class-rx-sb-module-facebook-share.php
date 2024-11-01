<?php
class Rx_Sb_Facebook_Share_Module extends Rx_Sb_Abstract_Modules {

    public function __construct() {
        $this->id         = 'facebook-share';
        $this->template   = dirname( __FILE__ ) . '/template.php';
        add_filter( 'admin_footer', array( $this, 'load_template' ) );
    }
}
