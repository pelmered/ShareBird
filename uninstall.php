<?php
/**
 * Fired when the plugin is uninstalled.
 *
* @package    wp-simple-share-buttons
 * @author    Peter Elmered <peter@elmered.com>
 * @link      http://extendwp.com
 * @copyright 2013 Peter Elmered
  */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Cleanup all registerd options
require_once 'wp-simple-share-buttons.php';

delete_option(WP_SSB_PLUGIN_SLUG.'_options');
