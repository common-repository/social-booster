<?php

/**
 * Class Rx_Sb_Feed_DB_MANAGER
 */
class Rx_Sb_Feed_DB_MANAGER {

    public $rx_sb_wpdb;

    public function __construct(){
        global $wpdb;
        $this->rx_sb_wpdb = $wpdb;
        $this->create_shared_table();
        $this->create_schedule_table();
        $this->sb_networks();
        $this->sb_profiles();
    }


    /**
     * Log of scheduled posts
     */
    public function create_schedule_table() {
        global $wpdb;
        $sb_scheduled_posts = $wpdb->prefix . 'sb_scheduled_posts';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $sb_scheduled_posts (
                  id int(10) unsigned AUTO_INCREMENT,
                  post_id INT NOT NULL,
                  post_meta longtext,
                  profile_id VARCHAR(20),
                  network_id VARCHAR(20),
                  share_type VARCHAR (10),
                  schedule_type VARCHAR (10),
                  schedule_time VARCHAR (20),
                  PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Posts table for
     * already shared posts
     */
    public function create_shared_table() {
        global $wpdb;
        $sb_profiles = $wpdb->prefix . 'sb_shared_posts';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $sb_profiles (
                  id int(10) unsigned AUTO_INCREMENT,
                  post_id INT NOT NULL,
                  post_meta longtext,
                  published_date DATETIME NOT NULL,
                  profile_id VARCHAR(20),
                  network_id VARCHAR(20),
                  share_type VARCHAR (10),
                  success boolean,
                  error_msg VARCHAR (100),
                  PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }


    /*
     * Create Profile setup table.
     */
    public function sb_profiles() {

        global $wpdb;
        $sb_profiles = $wpdb->prefix . 'sb_profiles';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $sb_profiles (
            id int(11) NOT NULL AUTO_INCREMENT,
            profile_id VARCHAR(20) NOT NULL UNIQUE,
            profile_name VARCHAR(200),
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }


    /**
     * Network table
     */
    public function sb_networks() {

        global $wpdb;
        $sb_networks = $wpdb->prefix . 'sb_networks';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $sb_networks (
            id int(11) NOT NULL AUTO_INCREMENT,
            profile_id VARCHAR(20) NOT NULL,
            prof_name VARCHAR(500),
            network VARCHAR(20),
            auth_type VARCHAR(20),
            auth_platform VARCHAR(20),
            auth_platform_id VARCHAR(500),
            token VARCHAR(500),
            auth_status VARCHAR(20),
            auth_con VARCHAR(20),
            extra VARCHAR(500),
            auth_date DATETIME NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}
