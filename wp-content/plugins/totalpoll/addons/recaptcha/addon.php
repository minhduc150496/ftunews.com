<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: reCaptcha
      Description: More credible polls with Captcha.
      Addon URI: http://wpsto.re/addons/downloads/captcha/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 2.0
      Required: 2.0
     */

/**
 * Captcha addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\Captcha
 */
Class TP_Captcha_Addon {

    public $private_key, $public_key;

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Load textdomain
        load_plugin_textdomain('tp-captcha-addon', false, dirname(__FILE__) . '/languages/');
        // Menu
        add_action('admin_menu', array( $this, 'menus' ), 99);
        // Settings
        add_action('admin_init', array( $this, 'settings' ));
        // Poll settings
        add_action('tp_admin_editor_after_limitations_content', array( $this, 'poll_settings' ));
        // Load assets
        add_action('tp_poll_enqueue_assets', array( $this, 'assets' ));
        // Some of styling override
        add_filter('tp_template_pre_get_css', array( $this, 'css' ));
        // Buttons
        add_action('tp_poll_other_buttons', array( $this, 'captcha' ));
        // Vote ability
        add_filter('tp_security_vote_ability', array( $this, 'ability' ));
    }

    /**
     * Register menus
     * 
     * @since 1.0.0
     * @return void
     */
    public function menus()
    {
        add_submenu_page('edit.php?post_type=poll', __('Captcha', 'tp-captcha-addon'), __('Captcha', 'tp-captcha-addon'), 'install_themes', 'tp-captcha', array( $this, 'global_settings' ));
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
     * Register settings
     * 
     * @since 1.0.0
     * @return void
     */
    public function settings()
    {
        register_setting('tp-captcha-settings', 'tp_captcha_only_for_guests');
        register_setting('tp-captcha-settings', 'tp_captcha_public_key');
        register_setting('tp-captcha-settings', 'tp_captcha_private_key');
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
     * Load assets
     * 
     * @since 2.0.0
     * @return void
     */
    public function assets($options)
    {
        wp_register_script('recaptcha-helper', TP_ADDONS_URL . basename(dirname(__FILE__)) . '/helper.min.js', array( 'jquery' ), TP_VERSION);
        wp_register_script('recaptcha', '//www.google.com/recaptcha/api.js?onload=_tpReCaptcha&render=explicit', array( 'recaptcha-helper' ), TP_VERSION);
    }

    /**
     * CSS stylying
     * 
     * @since 2.0.0
     * @return void
     */
    public function css($css)
    {
        return $css . ".g-recaptcha > div > div { margin: 10px auto; }";
    }

    /**
     * Display captcha HTML
     * 
     * @since 1.0.0
     * @return void
     */
    public function captcha()
    {
        global $poll;
        if ( isset($poll->limitations->captcha) && isset($poll->showing_vote) ):

            $publickey = get_option('tp_captcha_public_key', '');
            $guests = (bool) get_option('tp_captcha_only_for_guests', false);

            if ( !empty($publickey) && ( $guests === false || ($guests === true && !is_user_logged_in() ) ) ):

                wp_enqueue_script('recaptcha');

                printf('<div class="g-recaptcha" data-sitekey="%1$s"></div>', $publickey);

                if ( isset($_REQUEST['tp_action']) ):
                    echo '<script type="text/javascript">_tpReCaptcha();</script>';
                endif;

            endif;
        endif;
    }

    /**
     * Proceed the poll according to captcha value
     * 
     * @since 1.0.0
     * @param bool $ability
     * @return bool
     */
    public function ability($ability)
    {
        global $poll;

        // Check if captcha is enabled for this poll and there's a captcha field
        if (
                $ability &&
                isset($poll->limitations->captcha) &&
                !get_option('tp_captcha_only_for_guests', false) &&
                isset($_REQUEST['tp_action']) && $_REQUEST['tp_action'] === 'vote' &&
                !empty($_REQUEST['tp_choices'])
        ):

            if ( $ability = isset($_REQUEST['g-recaptcha-response']) ):
                // Check captcha
                $privatekey = get_option('tp_captcha_private_key');
                $ability = $this->check_captcha($privatekey, $_REQUEST['g-recaptcha-response'], TotalPoll()->security->ip);

            endif;

            if ( !$ability ):
                // Render vote.php
                add_filter_to_current_poll('tp_poll_render_file', array( $this, 'render_vote' ));
                // Message
                add_filter_to_current_poll('tp_template_get_part_header.php', array( $this, 'message' ));
            endif;

            unset($_REQUEST['g-recaptcha-response']);

        endif;

        return $ability;
    }

    /**
     * Render vote.php
     * 
     * @since 1.0.0
     * @return string
     */
    public function render_vote($file)
    {
        global $poll;
        $poll->showing_vote = true;
        return 'vote.php';
    }

    /**
     * Display incorrect code message
     * 
     * @since 1.0.0
     * @param string $content
     * @return string
     */
    public function message($content)
    {
        global $poll;

        unset($poll->showing_results);

        $message = sprintf('<p class="tp-warning">%s</p>', __('The verification code was incorrect!', 'tp-captcha-addon'));

        return $message . $content;
    }

    /**
     * Checks reCaptcha if its correct or not
     * 
     * @since 2.0.0
     * @param string $privatekey, string $value, string $ip
     * @return bool
     */
    protected function check_captcha($privatekey, $value, $ip)
    {
        if ( $privatekey == '' || $ip == '' )
            return false;

        $req = "secret=$privatekey&response=$value&remoteip=$ip";
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify?" . $req);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '10');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = json_decode(curl_exec($ch), true);
        $err_val = curl_error($ch);

        if ( !empty($err_val) || !$response || !is_array($response) || !array_key_exists('success', $response) || $response['success'] == false ):
            return false;
        endif;

        return true;
    }

}

// Bootstrap
new TP_Captcha_Addon();
