<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Disable Ajax
      Description: Disable ajax for more pageviews and ad revenue!
      Addon URI: http://wpsto.re/addons/downloads/disable-ajax
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

Class Tp_Disable_Ajax_Addon {

    /**
     * Register hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        add_filter('tp_poll_enqueue_assets', array( $this, 'disable' ));
        add_filter('tp_poll_rendered', array( $this, 'replace_width' ));
    }

    /**
     * Disable ajax by dequeue totalpoll.js
     * 
     * @since 1.0.0
     * @return void
     */
    public function disable()
    {
        wp_dequeue_script('totalpoll');
    }

    /**
     * Replace data-animate-width
     */
    public function replace_width($content)
    {
        return str_replace('data-animate-width="', 'style="width:', $content);
    }

}

// Bootstrap
new Tp_Disable_Ajax_Addon();
