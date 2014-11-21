<?php
/**
 * @package   wp-simple-share-buttons
 * @author    Peter Elmered <peter@elmered.com>
 * @license   GPL-2.0+
 * @link      http://elmered.com
 * @copyright 2014 Peter Elmered
 *
 * @wordpress-plugin
 * Plugin Name: WP Simple Share Buttons
 * Plugin URI:  http://wordpress.org/plugins/wp-simple-share-buttons/
 * Description: SimpleShareButtons for WordPress.
 * Version:     0.1.0
 * Author:      Peter Elmered
 * Author URI:  http://elmered.com
 * Text Domain: wp-simple-share-buttons
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
//For debuging
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$class_name = 'WP_Simple_Share_Buttons';

if( !defined('WP_SSB_PLUGIN_NAME') )
{
    define('WP_SSB_PLUGIN_NAME', 'WP Simple Share Buttons');
}
if( !defined('WP_SSB_PLUGIN_BASENAME'))
{
    define('WP_SSB_PLUGIN_BASENAME', untrailingslashit(plugin_basename(__FILE__)));
}
if( !defined('WP_SSB_PLUGIN_SLUG'))
{
    define('WP_SSB_PLUGIN_SLUG', 'wp-simple-share-buttons');
}
if( !defined('WP_SSB_PLUGIN_PATH'))
{
    define('WP_SSB_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
}
if( !defined('WP_SSB_PLUGIN_URL'))
{
    define('WP_SSB_PLUGIN_URL', plugins_url('', __FILE__).'/');
}

add_action( 'plugins_loaded', 'WP_Simple_Share_Buttons' );    

if( !class_exists( $class_name ) && !function_exists( $class_name ))
{
    function WP_Simple_Share_Buttons()
    {
        require_once( WP_SSB_PLUGIN_PATH .'includes/wpssb.php' );

        return WP_Simple_Share_Buttons::get_instance();
    }
}



// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array($class_name(), 'activate'));
//Deletes all data if plugin deactivated
register_deactivation_hook(__FILE__, array($class_name(), 'deactivate'));
