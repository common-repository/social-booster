<?php


/**
 * The Integration Loader
 */
class Rx_Sb_Module_Integration_Manager {

    /**
     * The integration instances
     *
     * @var array
     */
    public $integrations = array();


    /**
     * Return loaded integrations.
     *
     * @return array
     */
    public function get_integrations() {

        if ( $this->integrations ) {
            return $this->integrations;
        }
        $integrations = apply_filters( 'rx_sb_module_integration', array(
            'Rx_Sb_Dashboard_Module',
            'Rx_Sb_Calendar_Free_Module',
            'Rx_Sb_Settings_Module',
            'Rx_Sb_Setup_Guide_Module',
            'Rx_Sb_Networks_Module',
            'Rx_Sb_Facebook_Auth_Module',
            'Rx_Sb_Twitter_Auth_Module',
            'Rx_Sb_Tumblr_Auth_Module',
            'Rx_Sb_Pinterest_Auth_Module',
            'Rx_Sb_Pinterest_Share_Module',
            'Rx_Sb_Post_Sharing_Module',
            'Rx_Sb_Facebook_Share_Module',
            'Rx_Sb_Page_loader_Module',
            'Rx_Sb_Post_Schedule_Module',
            'Rx_Sb_Recent_Posts_Module',
            'Rx_Sb_Search_Filter_Module',
            'Rx_Sb_Share_Post_Module',
            'Rx_Sb_Switcher_Module',
            'Rx_Sb_Tumblr_Share_Module',
            'Rx_Sb_Twitter_Share_Module',
            'Rx_Sb_Shared_Posts',
            'Rx_Sb_Edit_Schedule_Module',
            'Rx_Sb_Schedule_Posts_Module',
        ));

        // Load integration classes
        foreach ( $integrations as $integration ) {
            $integration_instance = new $integration();
            $this->integrations[ $integration_instance->id ] = $integration_instance;
        }
    }
}
