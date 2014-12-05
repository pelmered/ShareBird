<?php
/**
 * @package   sharebird
 * @author    Peter Elmered <peter@elmered.com>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/sharebird/
 * @copyright 2014 Peter Elmered
 *
 * @wordpress-plugin
 * Plugin Name: ShareBird Social Buttons
 * Plugin URI:  http://wordpress.org/plugins/sharebird/
 * Description: 
 * Version:     0.1.1
 * Author:      pekz0r, khromov
 * Author URI:  http://elmered.com
 * Text Domain: sharebird
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

$class_name = 'ShareBird';

if( !defined('SHAREBIRD_PLUGIN_NAME') )
{
    define('SHAREBIRD_PLUGIN_NAME', 'ShareBird');
}
if( !defined('SHAREBIRD_PLUGIN_BASENAME'))
{
    define('SHAREBIRD_PLUGIN_BASENAME', untrailingslashit(plugin_basename(__FILE__)));
}
if( !defined('SHAREBIRD_PLUGIN_SLUG'))
{
    define('SHAREBIRD_PLUGIN_SLUG', 'sharebird');
}
if( !defined('SHAREBIRD_PLUGIN_PATH'))
{
    define('SHAREBIRD_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
}
if( !defined('SHAREBIRD_PLUGIN_URL'))
{
    define('SHAREBIRD_PLUGIN_URL', plugins_url('', __FILE__).'/');
}

add_action( 'plugins_loaded', 'ShareBird' );    

if( !class_exists( $class_name ) && !function_exists( $class_name ))
{
    function ShareBird()
    {
        require_once( SHAREBIRD_PLUGIN_PATH .'includes/class.sharebird.php' );

        return ShareBird::get_instance();
    }
}


// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook(__FILE__, array($class_name(), 'activate'));
//Deletes all data if plugin deactivated
register_deactivation_hook(__FILE__, array($class_name(), 'deactivate'));
