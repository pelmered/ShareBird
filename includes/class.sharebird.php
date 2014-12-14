<?php
if(!class_exists('ShareBird')) 
{

class ShareBird
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
    public $plugin_slug = SHAREBIRD_PLUGIN_SLUG;

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
        add_action('wp', array($this, 'plugin_init'), 1);

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        add_filter( 'plugin_action_links_' . SHAREBIRD_PLUGIN_BASENAME, array( $this, 'action_links' ) );
        
        add_action( 'wp_ajax_sharebird_set_count', array( $this, 'ajax_set_count') );

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

    /**
     * Wrapper for getting post title. Adds filters.
     *
     * @param $service
     * @param int $id
     * @return mixed|void
     */
    function get_post_title($service, $id = 0)
    {
        //General filter
        $title_value = apply_filters("sharebird_post_title", get_the_title($id));

        //Service-specific filter
        return apply_filters("sharebird_{$service}_post_title", $title_value);
    }

    /**
     * Wrapper for getting post author. Adds filters.
     * TODO: get_the_author() works only in the loop. This is not great as share buttons may be used outside the main loop.
     *
     * @param $service
     * @return mixed|void
     */
    function get_author($service)
    {
        //General filter
        $author_value = apply_filters("sharebird_author", get_the_author());

        //Service-specific filter
        return apply_filters("sharebird_{$service}_author", $author_value);
    }
    
    function plugin_init()
    {
        $options = $this->get_options();
        
        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        require 'shortcodes.php';

        // Don't perform any output on admin side
        if(!is_admin())
        {
            //Should we display output?
            if($options['default_output'] === true)
            {
                //Which conditionals should we display the post for?
                $display = false;
                foreach($options['output_conditionals'] as $conditional)
                {
                    if(function_exists($conditional) && ($conditional() === true))
                    {
                        //Is this a conditional for a single post page? In that case, let's check allowed post types
                        if(in_array($conditional, array('is_single', 'is_page', 'is_singular')))
                        {
                            //Is the post type amongst the allowed ones?
                            $display = ($options['output_post_types'] === 'all' || (in_array(get_post_type(), $options['output_post_types']))) ? true : false;
                            break;
                        }
                        else
                        {
                            $display = true;
                            break;
                        }
                    }
                }

                //If conditionals match and it's the right post type
                if($display)
                {
                    add_filter('the_content', array($this, 'add_to_content'), 999);

                    //Hook on excerpt as well, if archive.
                    //TODO: This is probably not a great solution as it's theme-dependent
                    if(is_post_type_archive())
                    {
                        add_filter('get_the_excerpt', array($this, 'add_to_content'));
                    }
                }
            }
        }
    }
    
    function output_buttons( $template_name = 'default', $args = array() )
    {
        include( $this->get_template( $template_name, $args ) );
    }

    /**
     * Main function for getting options
     *
     * @return array|mixed|void
     */
    function get_options()
    {
        if( empty( $this->options ) )
        {
            $this->options = apply_filters( 'sharebird_options',
                array(
                    'default_output' => apply_filters( 'sharebird_default_output', true ),
                    'output_conditionals' => apply_filters( 'sharebird_output_conditionals', array('is_front_page', 'is_home', 'is_single', 'is_page', 'is_post_type_archive', 'is_singular')),
                    'output_post_types' => apply_filters( 'sharebird_output_post_types', array('post', 'page')),
                    'output_positions' => apply_filters( 'sharebird_output_positions', array('after')), //before, after or both
                    //TODO: This is ugly, can we fix it without making the hook more difficult to use?
                    'buttons' =>  apply_filters( 'sharebird_buttons', array(
                        'facebook' => true,
                        'twitter' => true,
                        'linkedin' => true,
                        'googleplus' => true
                    )),
                )
            );
        }

        return $this->options;
    }
    

    /**
     * @param string $template_name
     * @param array $args
     * @return mixed|string|void
     */
    function get_template( $template_name = 'default', $args = array() )
    {
        global $sharebird_post, $post;
        
        $sharebird_post = $post;
        
        $template_name = $template_name.'.php';
        $template_path = 'sharebird/';
        $default_path = SHAREBIRD_PLUGIN_PATH . 'templates/';

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
        
        
        //print_r($args);
        
        if( !empty($args['post_id']) && $args['post_id'] > 0 )
        {
            $post = get_post($args['post_id']);
            
            if($post)
            {
                $sharebird_post = $post;
            }
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'sharebird_get_template', $template, $template_name, $args, $template_path, $default_path );
        
        wp_reset_postdata();
        
        return $template;
    }

    function add_to_content( $content )
    {
        $options = $this->get_options();
        ob_start();

        $this->output_buttons();

        //Get output buffer and allow 3rd-party codes to filter HTML data
        $template = apply_filters( 'sharebird_template_html', ob_get_clean(), $options['output_positions'], $content );

        if( in_array('before', $options['output_positions']) )
        {
            //Prepend
            $content = $template.$content;
        }

        if( in_array('after', $options['output_positions']) )
        {
            //after (Append)
            $content = $content.$template;
        }
        
        return $content;
    }
    
    
    
    function get_counts()
    {
        global $post;
	    $post = get_post( $post );
        
        $counts = get_post_meta($post->ID, 'sharebird_counts', true);
        
        return array(
            'facebook' => 0,
            'twitter' => 0,
            'linkedin' => 0,
            'googleplus' => 0,
        );
    }
    
    
    function ajax_set_count() {
        check_ajax_referer( 'sharebird-set-post-count', 'nonce' );
        
        echo 'test';
        
        
        die;
    }
    function set_count($post_id, $counts)
    {
        //TODO: Add validation/sanitation
        
        
        
        if ( ! update_post_meta ($post_id, 'sharebird_counts', $counts) ) { 
            add_post_meta($post_id, 'sharebird_counts', $counts, true );	
        };
    }

    
    function get_basecount( $type )
    {
        if(isset($this->options['buttons'][$type]['basecount']))
        {
            $basecount =  $this->options['buttons'][$type]['basecount'];
        }
        else
        {
            $basecount = 0;
        }
        
	return apply_filters( 'sharebird_get_basecount', $basecount, $type );
    }
    
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-public-styles', SHAREBIRD_PLUGIN_URL . 'assets/css/public.css', array(), self::VERSION);
    }
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-public-sctipts', SHAREBIRD_PLUGIN_URL . 'assets/js/public.js', array(), self::VERSION);
        wp_enqueue_script('simplesharebuttons', SHAREBIRD_PLUGIN_URL . 'assets/js/jquery.simplesharebuttons.js', array(), self::VERSION);
        
        wp_localize_script($this->plugin_slug . '-public-sctipts', 'sharebird_options', array(
            'fetchCounts'               => $this->get_fetch_count(),
            'GooglePlusAPIProviderURI'  => SHAREBIRD_PLUGIN_URL.'includes/APIProviders/GooglePlus.php',
            'ajaxURL'                  => admin_url('/admin-ajax.php'), //Not used
            'setCountNonce'           => wp_create_nonce("sharebird-set-post-count"), //Not used
        ));
    }
    
    function get_fetch_count()
    {
        //return false;
    
        return true;
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
        wp_enqueue_style($this->plugin_slug . '-admin-options-styles', SHAREBIRD_PLUGIN_URL . 'assets/css/admin-options.css', array(), self::VERSION);
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
        wp_enqueue_script($this->plugin_slug . '-admin-options-script', SHAREBIRD_PLUGIN_URL . 'assets/js/admin-options.js', array('jquery', 'chosen', 'ajax-chosen'), self::VERSION);
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

        // Load from /wp-content/plugins/sharebird-xx_XX.mo
        load_textdomain($domain, trailingslashit(WP_LANG_DIR) .'plugins/' . $domain . '-' . $locale . '.mo');

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
     * @param $links
     * @return array
     */
    public function action_links($links)
    {
        $plugin_links = array(
            '<a href="http://wordpress.org/plugins/sharebird/" target="_blank">' . __('Info & Support', $this->plugin_slug) . '</a>',
        );

        return array_merge($plugin_links, $links);
    }
}
}