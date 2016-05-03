<?php

/*
  Plugin Name: TotalPoll | Shared By Themes24x7.com
  Description: Yet another powerful poll plugin!
  Plugin URI: http://wpsto.re/plugins/total-poll
  Author: WPStore
  Author URI: http://wpsto.re
  Version: 2.7
  Text Domain: totalpoll
  Domain Path: languages
 */

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * TotalPoll Singleton Bootstraper.
 * 
 * @since 2.0.0
 * @pakcage TotalPoll
 */

class TotalPoll {

    /**
     * Instance container.
     * @since 2.0.0
     * @access private
     * @type instance Instance.
     */
    private static $instance;

    /**
     * Components container.
     * @since 2.5.0
     * @access private
     * @type array container.
     */
    private static $components = array();

    /**
     * Get TotalPoll instance.
     * @since 2.0.0
     * @return instance Current instance.
     */
    public static function getInstance($component = null)
    {
        if ( !isset(self::$instance) && !( self::$instance instanceof TotalPoll ) ):

            if ( !isset($_SESSION) ):
                session_start();
            endif;

            self::$components = array(
                'request' => array(
                    'class' => 'TP_Request',
                    'includes' => array( 'class-request.php' )
                ),
                'poll' => array(
                    'class' => 'TP_Poll',
                    'includes' => array( 'helpers.php', 'poll.helpers.php', 'class-poll.php' )
                ),
                'addons' => array(
                    'class' => 'TP_Addons',
                    'includes' => array( 'class-addons.php' )
                ),
                'template' => array(
                    'class' => 'TP_Template',
                    'includes' => array( 'template-tags.php', 'class-template.php' )
                ),
                'logs' => array(
                    'class' => 'TP_Logs',
                    'includes' => array( 'class-logs.php' )
                ),
                'security' => array(
                    'class' => 'TP_Security',
                    'includes' => array( 'class-security.php' )
                ),
                'admin' => array(
                    'class' => 'TP_Admin',
                    'includes' => array( 'helpers.php', 'class-admin.php' )
                ),
                'customizer' => array(
                    'class' => 'TP_Poll_Customizer',
                    'includes' => array( 'class-poll-customizer.php' )
                ),
                'editor' => array(
                    'class' => 'TP_Poll_Editor',
                    'includes' => array( 'poll.helpers.php', 'class-poll-customizer-fields.php', 'class-poll-editor.php' )
                )
            );

            self::$instance = new TotalPoll;
            self::$instance->constants();
            self::$instance->includes();
            self::$instance->textdomain();
            self::$instance->hooks();

            // Setup poll object
            add_action('wp', array( self::$instance, 'setup' ));

            // Register shortcode
            add_shortcode('total-poll', array( self::$instance, 'shortcode' ));

            /**
             * Init
             * Init hook for TotalPoll
             * 
             * @since 2.0.0
             * @action tp_init
             * @param Instance
             */
            do_action('tp_init', self::$instance);
        elseif ( $component !== null ):
            if ( isset(self::$components[$component]) && !isset(self::$components[$component]['instance']) ):

                foreach ( self::$components[$component]['includes'] as $include ):
                    require_once( TP_PATH . 'includes' . DS . $include );
                endforeach;

                return self::$components[$component]['instance'] = new self::$components[$component]['class'];

            elseif ( isset(self::$components[$component]['instance']) ):

                return self::$components[$component]['instance'];

            else:

                return false;

            endif;

        endif;

        return self::$instance;
    }

    public function setup()
    {
        if ( is_singular('poll') ):
            add_filter('the_content', array( TotalPoll('poll'), 'single_post' ));
        endif;
    }

    public function shortcode($attrs)
    {
        return TotalPoll('poll')->shortcode($attrs);
    }

    /**
     * Define useful constants.
     * 
     * @since 2.0.0
     * @return void
     */
    private function constants()
    {

        /**
         * Directory separator.
         * 
         * @since 2.0.0
         * @type string
         */
        if ( !defined('DS') )
            define('DS', DIRECTORY_SEPARATOR);

        /**
         * TotalPoll text doamin
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_TD', 'totalpoll');

        /**
         * TotalPoll base directory path.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_PATH', plugin_dir_path(__FILE__));

        /**
         * TotalPoll base directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_URL', plugin_dir_url(__FILE__));

        /**
         * TotalPoll templates directory path.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_TEMPLATES_PATH', TP_PATH . 'templates' . DS);

        /**
         * TotalPoll templates directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_TEMPLATES_URL', TP_URL . 'templates/');

        /**
         * TotalPoll root file (this file).
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_ROOT_FILE', __FILE__);

        /**
         * TotalPoll addons directory path.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_ADDONS_PATH', TP_PATH . 'addons' . DS);

        /**
         * TotalPoll addons directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_ADDONS_URL', TP_URL . 'addons/');

        /**
         * TotalPoll assets directory path.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_ASSETS_PATH', TP_PATH . 'assets' . DS);

        /**
         * TotalPoll assets directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_ASSETS_URL', TP_URL . 'assets/');

        /**
         * TotalPoll JS assets directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_JS_ASSETS', TP_ASSETS_URL . 'js/');

        /**
         * TotalPoll CSS assets directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_CSS_ASSETS', TP_ASSETS_URL . 'css/');

        /**
         * TotalPoll images assets directory URL.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_IMAGES_ASSETS', TP_ASSETS_URL . 'images/');

        /**
         * TotalPoll current version
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_VERSION', '2.7');

        /**
         * TotalPoll store URL
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_WEBSITE', 'http://totalpoll.com');

        /**
         * TotalPoll store URL
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_STORE', 'http://wpsto.re/plugins/total-poll?store');

        /**
         * TotalPoll directory name.
         * 
         * @since 2.0.0
         * @type string
         */
        define('TP_DIRNAME', dirname(plugin_basename(__FILE__)));
    }

    /**
     * Load TotalPoll textdomain.
     * 
     * @since 2.0.0
     * @return bool
     */
    public function textdomain()
    {
        return load_plugin_textdomain(TP_TD, false, TP_DIRNAME . '/languages/');
    }

    /**
     * Load required files (modules, addons, templates ..).
     * 
     * @since 2.0.0
     * @global string $wp_version
     * @return void
     */
    private function includes()
    {
        require_once( TP_PATH . 'includes/post-type.php' );
        require_once( TP_PATH . 'includes/class-widgets.php' );
    }

    /**
     * Register TotalPol wigets.
     * 
     * @since 2.0.0
     * @return void
     */
    public function widgets()
    {
        register_widget('TP_Widget');
        register_widget('TP_Latest_Widget');
        register_widget('TP_Random_Widget');
    }

    /**
     * Register hooks (actions & filters).
     * 
     * @since 2.0.0
     * @return void
     */
    private function hooks()
    {
        // Activation
        register_activation_hook(__FILE__, array( $this, 'activate' ));
        // Deactivation
        register_deactivation_hook(__FILE__, array( $this, 'deactivate' ));
        // Widget
        add_action('widgets_init', array( $this, 'widgets' ));
        // Requests
        if ( isset($_REQUEST['tp_action']) ):
            // Init
            TotalPoll('request');
            // Capture actions
            add_action('wp', array( $this, 'capture_action' ), 11);
        endif;
        // Post-type
        add_action('init', 'tp_register_post_type');
        add_filter('post_updated_messages', 'tp_update_messages');
    }

    /**
     * Capture actions from POST, GET and AJAX
     * 
     * @since 2.0.0
     * @return void
     */
    public function capture_action()
    {
        /**
         * AJAX
         */
        if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ):
            if ( isset($_REQUEST['tp_action']) ):
                /**
                 * TotalPoll Ajax request
                 * 
                 * @since 2.0.0
                 * @type string
                 */
                define('TP_AJAX', true);
                /**
                 * Capture ajax requests
                 * 
                 * @since 2.0.0
                 * @action tp_capture_ajax_{$_REQUEST['tp_action']}
                 */
                do_action("tp_capture_ajax_{$_REQUEST['tp_action']}");
            endif;
        endif;

        /**
         * POST & GET
         */
        if ( isset($_POST['tp_action']) ):
            /**
             * Capture post requests
             * 
             * @since 2.0.0
             * @action tp_capture_ajax_{$_POST['tp_action']}
             */
            do_action("tp_capture_post_{$_POST['tp_action']}");
        endif;
        if ( isset($_GET['tp_action']) ):
            /**
             * Capture get requests
             * 
             * @since 2.0.0
             * @action tp_capture_ajax_{$_GET['tp_action']}
             */
            do_action("tp_capture_get_{$_GET['tp_action']}");
        endif;
    }

    /**
     * Activation.
     * 
     * @since 2.0.0
     * @return void
     */
    public function activate()
    {
        tp_register_post_type();
        flush_rewrite_rules();
    }

    /**
     * Deactivation.
     * 
     * @since 2.4.0
     * @return void
     */
    public function deactivate()
    {
        flush_rewrite_rules();
    }

}

/**
 * Get instance.
 * 
 * @package TotalPoll
 * @since 2.0.0
 * @return instance Current instance of TotalPoll.
 */
function TotalPoll($component = null)
{
    return TotalPoll::getInstance($component);
}

/**
 * Bootstrap, and let the fun begin.
 */
TotalPoll();

if ( is_admin() ):
    TotalPoll('addons');
    TotalPoll('admin');
    TotalPoll('editor');
endif;