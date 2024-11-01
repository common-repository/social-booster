<?php

/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rx_Sb
 * @subpackage Rx_Sb/includes
 * @author     RexTheme <#>
 */
class Rx_Sb_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        add_option('rx_sb_do_activation_redirect', true);
	    	update_option('rx-sb-version', '2.0');
        $rexDB = new Rx_Sb_Feed_DB_MANAGER();
        // // $rexDB->trigger();
        $rexCron = new Rx_Sb_Cron();
        $rexCron->trigger();
	}
}
