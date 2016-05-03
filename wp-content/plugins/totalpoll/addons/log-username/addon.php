<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Log usernames
      Description: Add username to the log
      Addon URI: #
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 2.0
      Required: 2.4
     */

/**
 * Archive Link addon.
 * 
 * @version 2.0.0
 * @package TotalPoll\Addons\LogUsername
 */
Class TP_Log_Username {

    /**
     * Register hooks.
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        add_filter('tp_request_log_extra', array( $this, 'add_username' ));
    }

    /**
     * Customize log expression.
     * 
     * @since 1.0.0
     * @param string $extra
     * @return string
     */
    public function add_username($extra)
    {
        return $extra .= sprintf('[username: %s]', wp_get_current_user()->user_login);
    }

}

// Bootstrap
new TP_Log_Username();
