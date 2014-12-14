<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package    sharebird
 * @author    Peter Elmered <peter@elmered.com>
 * @link      http://extendwp.com
 * @copyright 2013 Peter Elmered
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Cleanup all registerd options
require_once 'sharebird.php';

delete_option(SHAREBIRD_PLUGIN_SLUG.'_options');
