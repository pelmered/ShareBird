<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WP_Simple_Share_Buttons
{
    /**
     * Plugin version, used for autoatic updates and for cache-busting of style and script file references.
     *
     * @since    0.1.0
     * @var     string
     */
    const VERSION = '0.1.1';

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    0.1.0
     * @var      string
     */
    public $plugin_slug = WP_SSB_PLUGIN_SLUG;

    /**
     * Instance of this class.
     *
     * @since    0.1.0
     * @var      object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     * 
     * @since    0.1.0
     */
    
    /**
     * Plugin options
     *
     * @since    0.1.0
     * @var      array
     */
    public $options = array();
    
    function __construct()
    {
        /*
         * Init plugin. We want to initialize the plugin after init to allow 
         * plugins and themes to hook into the plugin before it is initialized
         */
        add_action('wp_loaded', array($this, 'plugin_init'), 1);

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));
        
        
        add_filter( 'plugin_action_links_' . WP_SSB_PLUGIN_BASENAME, array( $this, 'action_links' ) );
    }
    
    /**
     * Return an instance of this class.
     *
     * @since     0.1.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (null === self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;         
    }

    
    function plugin_init()
    {
        $this->get_options();
        
        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        require 'shortcodes.php';
        
        //Add after the_content
        if( apply_filters( 'wpssb_default_output', true ) )
        {
            add_filter('the_content', array($this, 'add_after_content'), 999);
        }
    }
    
    function output_buttons( $template_name = 'default' )
    {
        include( $this->get_template( $template_name, $args = array() ) );
    }
    
    
    function get_options()
    {
        if( empty( $this->options ) )
        {
            $this->options = apply_filters( 'wpssb_options', 
                array(
                    'output_locations' => array(
                        'after_the_content'
                    ),
                    'buttons' => array(
                        'facebook' => array(
                            'active' => 1,
                            'basecount' => 0
                        ),
                        'twitter' => array(
                            'active' => 1,
                            'basecount' => 0
                        ),
                        'linkedin' => array(
                            'active' => 1,
                            'basecount' => 0
                        ),
                        'googleplus' => array(
                            'active' => 1,
                            'basecount' => 0
                        )
                    )
                )
            );
        }

        return $this->options;        
    }
    
    
    /**
     * 
     * @param string $template_name
     * @param type $args
     */
    function get_template( $template_name = 'default', $args = array() )
    {
        $template_name = $template_name.'.php';
        $template_path = 'wpssb/';
        $default_path = WP_SSB_PLUGIN_PATH . 'templates/';

	// Look within passed path within the theme - this is priority
	$template = locate_template(
            array(
                trailingslashit( $template_path ) . $template_name,
                //$template_name
            )
	);
        
	// Get default template
	if ( ! $template ) {
            $template = $default_path . $template_name;
	}
        
	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'wpssb_get_template', $template, $template_name, $args, $template_path, $default_path );
        
        return $template;
        
    }
    
    function add_before_content($content)
    {
        return $this->add_to_content( $content, 'before' );
    }
    function add_after_content($content)
    {
        return $this->add_to_content( $content, 'after' );
    }
    
    function add_to_content( $content, $pos = 'after' ) 
    {
        if( is_single() ) 
        {
            ob_start();

            $this->output_buttons();
            
            //Get output buffer and allow 3rd-party codes to filter HTML data
            $template = apply_filters( 'wpssb_template_html', ob_get_clean(), $pos, $content );
            
            if( $pos === 'before' )
            {   
                //Prepend
                $content = $template.$content;
            }
            else 
            {   
                //after (Append)
                $content = $content.$template;
            }
        }
        
        return $content;
    }
    
    function get_basecount( $type )
    {
        if(isset($this->options['buttons'][$type]['basecount']))
        {
            $basecount =  $this->options['buttons'][$type]['basecount'];
        }
        else
        {
            $basecount = 10;
        }
        
	return apply_filters( 'wpssb_get_basecount', $basecount, $type );
    }
    
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-public-styles', WP_SSB_PLUGIN_URL . 'assets/css/public.css', array(), self::VERSION);
    }
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-public-sctipts', WP_SSB_PLUGIN_URL . 'assets/js/public.js', array(), self::VERSION);
        wp_enqueue_script('simplesharebuttons', WP_SSB_PLUGIN_URL . 'assets/js/jquery.simplesharebuttons.js', array(), self::VERSION);
        
        wp_localize_script($this->plugin_slug . '-public-sctipts', 'wpssb_options', array(
            'GooglePlusAPIProviderURI'  => WP_SSB_PLUGIN_URL.'includes/APIProviders/GooglePlus.php',
            'ajax_url'                  => admin_url('/admin-ajax.php'), //Not used
            'get_count_nonce'           => wp_create_nonce("wpssb-get-count"), //Not used
        ));
    }
    
    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     0.1.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-admin-options-styles', WP_SSB_PLUGIN_URL . 'assets/css/admin-options.css', array(), self::VERSION);
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     0.1.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-admin-options-script', WP_SSB_PLUGIN_URL . 'assets/js/admin-options.js', array('jquery', 'chosen', 'ajax-chosen'), self::VERSION);
    }
    
    /**
     * Load the plugin text domain for translation.
     *
     * @since    0.1.0
     */
    public function load_plugin_textdomain()
    {
        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(dirname(__FILE__)) . '/languages');
    }
    
    /**
     * Fired when the plugin is activated.
     *
     * @since    0.1.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public function activate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite())
        {
            if ($network_wide)
            {
                // Get all blog ids
                $blog_ids = $this->get_blog_ids();

                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    $this->single_activate();
                }
                restore_current_blog();
            }
            else
            {
                $this->single_activate();
            }
        }
        else
        {
            $this->single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    0.1.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public function deactivate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite())
        {
            if ($network_wide)
            {
                // Get all blog ids
                $blog_ids = $this->get_blog_ids();

                foreach ($blog_ids as $blog_id)
                {
                    switch_to_blog($blog_id);
                    $this->single_deactivate();
                }
                restore_current_blog();
            }
            else
            {
                $this->single_deactivate();
            }
        }
        else
        {
            $this->single_deactivate();
        }
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    0.1.0
     *
     * @param	int	$blog_id ID of the new blog.
     */
    public function activate_new_site($blog_id)
    {
        if (1 !== did_action('wpmu_new_blog'))
        {
            return;
        }
        
        switch_to_blog($blog_id);
        $this->single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    0.1.0
     *
     * @return	array|false	The blog ids, false if no matches.
     */
    private function get_blog_ids()
    {
        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
                    WHERE archived = '0' AND spam = '0'
                    AND deleted = '0'";
        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    0.1.0
     */
    private function single_activate()
    {
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    0.1.0
     */
    private function single_deactivate()
    {
        
    }

    /**
     * action_links function.
     *
     * @access public
     * @param mixed $links
     * @return void
     */
    public function action_links($links)
    {
        $plugin_links = array(
            '<a href="http://wordpress.org/plugins/wp-simple-share-buttons//">' . __('Info & Support', $this->plugin_slug) . '</a>',
        );

        return array_merge($plugin_links, $links);
    }


    
}