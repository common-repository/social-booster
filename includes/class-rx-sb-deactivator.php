<?php

/**
 * Fired during plugin deactivation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Rx_Sb
 * @subpackage Rx_Sb/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Rx_Sb
 * @subpackage Rx_Sb/includes
 * @author     RexTheme <#>
 */
class Rx_Sb_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$rexCron = new Rx_Sb_Cron();
		$rexCron->untrigger();
	}

}
