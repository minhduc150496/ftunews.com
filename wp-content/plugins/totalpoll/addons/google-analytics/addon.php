<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Google Analytics
      Description: Simple intergration with google analytics.
      Addon URI: http://wpsto.re/addons/downloads/google-analytics/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Google Analytics addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\GoogleAnalytics
 */
Class TP_Google_Analytics_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Textdomain
        load_plugin_textdomain('tp-ga-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Menu
        add_action('admin_menu', array( $this, 'menus' ), 99);
        // Settings
        add_action('admin_init', array( $this, 'settings' ));
        // Embed GA code
        add_action('wp_head', array( $this, 'code' ));
        // Embed GA event emitter
        if ( !empty($_REQUEST['tp_choices']) ):
            add_filter('tp_poll_render', array( $this, 'send' ));
        endif;
    }

    /**
     * Register menus
     * 
     * @since 1.0.0
     * @return void
     */
    public function menus()
    {
        add_submenu_page('edit.php?post_type=poll', __('Google Analytics', 'tp-ga-addon'), __('Google Analytics', 'tp-ga-addon'), 'install_themes', 'tp-google-analytics', array( $this, 'global_settings' ));
    }

    /**
     * Register settings
     * 
     * @since 1.0.0
     * @return void
     */
    function settings()
    {
        register_setting('tp-ga-settings', 'tp_ga_code');
    }

    /**
     * Settings page
     * 
     * @since 1.0.0
     * @return void
     */
    public function global_settings()
    {
        /**
         * Include settings
         */
        include_once('settings.php');
    }

    /**
     * Output GA code
     * 
     * @since 1.0.0
     * @return void
     */
    public function code()
    {
        echo get_option('tp_ga_code', '');
    }

    /**
     * Build GA event emitter script
     * 
     * @since 1.0.0
     * @global object $poll
     * @param type $content Render result
     * @return string
     */
    public function send($content = '')
    {
        global $poll;

        // Code container
        $ga = '';

        // Check the current poll
        if ( isset($_REQUEST['tp_poll_id']) && $poll->id == $_REQUEST['tp_poll_id'] ):
            // Script container
            $script = "<script>try {%s} catch(e){console.log('%s');}</script>";
            // Calls container
            $ga_calls = '';
            foreach ( $poll->choices as $index => $choice ):
                if ( in_array($choice->id, (array) $_REQUEST['tp_choices']) ):
                    // Send votes to GA
                    $ga_calls .= sprintf("ga('send', 'event', '[POLLS] %s', 'votes', '#%s', %d);", esc_attr($poll->question), $index + 1, $choice->votes);
                endif;
            endforeach;
            // Now concate
            $ga = sprintf($script, $ga_calls, defined('WP_DEBUG') && WP_DEBUG === true
                                ? '[TotalPoll][Google Analytics Addon]: Please setup Google Analytics.'
                                : '');
        endif;
        return $content . $ga;
    }

}

// Bootstrap it
new TP_Google_Analytics_Addon();
