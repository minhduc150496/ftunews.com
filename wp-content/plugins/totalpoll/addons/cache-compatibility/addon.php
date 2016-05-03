<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Cache Compatibility
      Description: Make TotalPoll works with popular cache plugins (W3TC, WP SuperCache and Quick Cache).
      Addon URI: http://wpsto.re/addons/downloads/cache-compatibility/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 2.0
      Required: 2.0
     */

/**
 * Cache compatible addons.
 * 
 * @version 2.0.0
 * @package TotalPoll\Addons\CacheCompatibility
 */
Class TP_Cache_Compatible {

    /**
     * Register some hooks.
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        add_filter('tp_poll_render_with_style', array( $this, 'render' ));
        add_action('wp_ajax_load_tp', array( $this, 'render_ajax' ));
        add_action('wp_ajax_nopriv_load_tp', array( $this, 'render_ajax' ));
    }

    public function render_ajax()
    {
        global $poll;
        if ( TotalPoll('poll')->load($_REQUEST['tp_poll_id']) ):
            remove_filter('tp_poll_render_with_style', array( $this, 'render' ));
            TotalPoll('poll')->render();
        endif;
        wp_die();
    }

    /**
     * Render
     * 
     * @since 1.0.0
     * @global object $poll
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        global $poll;

        if ( !isset($_REQUEST['tp_action']) ):
            $id = uniqid('tp-');
            $content = sprintf('<div id="%1$s"></div><script>var tp_async_polls = tp_async_polls || [];tp_async_polls.push({ id: %2$s, container: "#%1$s" });</script>', $id, $poll->id);
            if ( !wp_script_is('tp-async-load', 'enqueued') ):
                wp_enqueue_script('tp-async-load', TP_ADDONS_URL . basename(dirname(__FILE__)) . '/async-load.min.js', array( 'jquery', 'totalpoll' ), TP_VERSION, true);
                wp_localize_script('tp-async-load', 'totalpoll_cache_compatibility', array( 'ajaxurl' => admin_url('admin-ajax.php') ));
            endif;
            add_filter('tp_poll_rendered', array( $this, 'flush_polls_cache' ));
        endif;

        return $content;
    }

    public function flush_polls_cache($content)
    {
        global $cached_rendered_polls;
        $cached_rendered_polls = array();
        return $content;
    }

}

// Bootstrap it
new TP_Cache_Compatible();
