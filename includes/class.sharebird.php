<?php
if(!class_exists('ShareBird')) 
{

class ShareBird
{
    /**
     * Plugin version, used for automatic updates and for cache-busting of style and script file references.
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

        // AJAX callback to get Google+ share count
        // Both for logged in and unprivileged users
        add_action('wp_ajax_get_googleplus', array($this, 'ajax_get_googleplus'));
        add_action('wp_ajax_nopriv_get_googleplus', array($this, 'ajax_get_googleplus'));
    }

    /**
     *
     */
    function ajax_get_googleplus()
    {
        $url = isset($_GET['url']) ?  $_GET['url'] : null;
        //check_ajax_referer( 'sharebird-set-post-count', 'nonce' );
        //TODO: Cache + secure?
        //TODO: Limit to current domain?

        if($url !== null)
        {
            $raw_share_button = wp_remote_get('https://plusone.google.com/_/+1/fastbutton?url=' . urlencode($url));

            if(!is_wp_error($raw_share_button))
            {
                $share_count = array();
                /**
                 * http://stackoverflow.com/questions/15367687/how-to-get-the-1-count-in-google-plus-using-any-api
                 * http://stackoverflow.com/questions/21524077/getting-google-1-page-shares-via-ajax-hidden-api
                 */
                preg_match('/.*\.__SSR.*{c:\s*([0-9]*)\..*/', $raw_share_button['body'], $share_count);

                if(sizeof($share_count) === 2)
                    echo $share_count[1];
                else
                    echo 0;
            }
            else
                echo 0;
        }
        else
            echo 0;

        die;
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
     *
     * @param $service
     * @return mixed|void
     */
    function get_author($service, $id)
    {
        $post = get_post($id);
        $author = get_user_by('id', $post->post_author);

        //General filter
        $author_value = apply_filters("sharebird_author", apply_filters('the_author', is_object($author) ? $author->display_name : null));

        //Service-specific filter
        return apply_filters("sharebird_{$service}_author", $author_value);
    }

    /**
     * Initialize the plugin
     */
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

    /**
     * Load the template that outputs the button HTML.
     *
     * @param string $template_name
     * @param array $args
     */
    function output_buttons( $args = array() )
    {
        if( !is_array( $args ))
        {
            $args = array();
        }

        if( empty( $args ) || !isset( $args['template'] ) || empty( $args['template'] ) )
        {
            $args['template'] = 'sharebird-buttons.php';
        }

        $this->include_template( $args['template'], $args );
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
     * @param $template
     * @param array $args
     */
    function include_template( $template, $args = array() )
    {
        // Get post object
        if(isset($args['post_id']))
        {
            $post = get_post($args['post_id']);
        }
        else
        {
            //Get current post (the_loop)
            $post = get_post();
        }

        $args['plugin_slug'] = $this->plugin_slug;
        $args['post'] = $post;

        $data = apply_filters('sharebird_post_data', $args, $template);

        //Look in theme folder first, then plugin folder
        if(locate_template($template) === '')
        {
            include SHAREBIRD_PLUGIN_PATH . 'templates/' . $template;
        }
        else
        {
            include locate_template($template);
        }
    }

    function add_to_content( $content )
    {
        $options = $this->get_options();
        ob_start();

        $this->output_buttons();

        //Get output buffer and allow 3rd-party codes to filter HTML data
        $template = apply_filters( 'sharebird_template_html', ob_get_clean(), $options['output_positions'], $content );


        //Prepend to content
        if( in_array('before', $options['output_positions']) )
        {
            $content = $template.$content;
        }

        //Append to content
        if( in_array('after', $options['output_positions']) )
        {
            $content = $content.$template;
        }
        
        return $content;
    }
    
    
    
    function get_counts($id = 0)
    {
        if($id !== 0)
        {
            $post = get_post($id);
        }
        else
        {
            $post = get_post(get_the_ID());
        }

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
        
        echo '0';

        die;
    }
    function set_count($post_id, $counts)
    {
        //TODO: Add validation/sanitation
        
        
        
        if ( ! update_post_meta ($post_id, 'sharebird_counts', $counts) )
        {
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

        wp_enqueue_script('sharebird_socialjs', SHAREBIRD_PLUGIN_URL . 'assets/js/jquery.socialjs.js', array(), self::VERSION);
        wp_localize_script($this->plugin_slug . '-public-sctipts', 'sharebird_options', array(
            'fetchCounts'               => $this->get_fetch_count(),
            'GooglePlusAPIProviderURI'  => plugins_url('APIProviders/GooglePlusImproved.php', __FILE__),
            'ajaxURL'                  => admin_url('/admin-ajax.php'),
            'setCountNonce'           => wp_create_nonce("sharebird-set-post-count")
        ));
    }

    /**
     * FIXME: Implement
     *
     * @return bool
     */
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