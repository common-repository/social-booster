<?php
class Rx_Sb_Setup_Guide_Module extends Rx_Sb_Abstract_Modules {

    public function __construct() {
        $this->id         = 'setupguide';
        $this->template   = dirname( __FILE__ ) . '/template.php';
        add_filter( 'admin_footer', array( $this, 'load_template' ) );
    }
}
