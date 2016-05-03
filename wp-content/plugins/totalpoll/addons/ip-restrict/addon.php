<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: IP Restrict
      Description: Restrict voting by IP
      Addon URI: http://wpsto.re/addons/downloads/ip-restrict/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Ip Restriction addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\IpRestriction
 */
Class TP_IP_Restrict_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Textdomain
        load_plugin_textdomain('tp-ipr-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Menu
        add_action('admin_menu', array( $this, 'menus' ), 99);
        // Settings
        add_action('admin_init', array( $this, 'settings' ));
        // Poll settings
        add_action('tp_admin_editor_after_limitations_content', array( $this, 'poll_settings' ));
        // Vote ability
        add_filter('tp_security_vote_ability', array( $this, 'ability' ));
    }

    /**
     * Check vote ability
     * 
     * @global object $poll
     * @param boolean $ability
     * @return boolean
     */
    public function ability($ability)
    {
        global $poll;
        // Some checks before starting
        if ( isset($poll->limitations->restrict_by_ip) && !TotalPoll('security')->is_ipv6 ):
            // Get list
            $list = get_option("tp_ipr_{$poll->limitations->ip->list}", false);
            // Not empty? let's start
            if ( $list ):
                // Make an array of IPs
                $ips = (array) explode("\r\n", $list);
                foreach ( $ips as $ip ):
                    // Replace '*' and 'x' with equivalent in regex (one or more digit)
                    $ip = str_replace(array( '*', 'x' ), '\d+', $ip);
                    // Get know the parts of this ip
                    $ip_parts = explode('.', $ip);
                    // Bad IP? Proceed then
                    if ( count($ip_parts) != 4 )
                        break;
                    // Let's build the regex
                    $regex = sprintf("/%s/sim", str_replace('.', '\.', $ip));
                    // Then match it
                    if ( ($poll->limitations->ip->list == 'whitelist' && !preg_match($regex, TotalPoll('security')->ip)) ||
                            ($poll->limitations->ip->list == 'blacklist' && preg_match($regex, TotalPoll('security')->ip))
                    ):
                        // Render vote.php
                        add_filter_to_current_poll('tp_poll_render_file', array( $this, 'render_vote' ));
                        // Display restriction message
                        add_filter_to_current_poll('tp_template_get_part_header.php', array( $this, 'message' ));
                        // Disable voting
                        $ability = false;
                        continue;
                    endif;

                endforeach;
            endif;
        endif;
        return $ability;
    }

    /**
     * Register menus
     * 
     * @since 1.0.0
     * @return void
     */
    public function menus()
    {
        add_submenu_page('edit.php?post_type=poll', __('IP Restrictions', 'tp-ipr-addon'), __('IP Restrictions', 'tp-ipr-addon'), 'install_themes', 'tp-ip-restrict', array( $this, 'global_settings' ));
    }

    /**
     * Register settings
     * 
     * @since 1.0.0
     * @return void
     */
    public function settings()
    {
        register_setting('tp-ipr-settings', 'tp_ipr_whitelist');
        register_setting('tp-ipr-settings', 'tp_ipr_blacklist');
    }

    /**
     * Settings page
     * 
     * @since 1.0.0
     * @return void
     */
    public function global_settings()
    {
        include_once('settings.php');
    }

    /**
     * Poll editor special settings
     * 
     * @since 1.0.0
     * @return void
     */
    public function poll_settings($options)
    {
        include_once('poll-settings.php');
    }

    /**
     * Render vote.php
     * 
     * @since 1.0.0
     * @return string
     */
    public function render_vote($file)
    {
        return 'vote.php';
    }

    /**
     * Display restriction message
     * 
     * @since 1.0.0
     * @param string $content
     * @return string
     */
    public function message($content)
    {
        global $poll;

        unset($poll->showing_results);

        // Hide buttons
        add_filter_to_current_poll('tp_poll_display_buttons', '__return_false');

        $message = sprintf('<p class="tp-warning">%s</p>', __("This poll isn't available in your region.", 'tp-ipr-addon'));

        return $message . $content;
    }

}

// Bootstrap
new TP_IP_Restrict_Addon();
