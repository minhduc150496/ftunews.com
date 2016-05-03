<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Anti-cheating and limitations
 * 
 * @since 2.0.0
 * @package TotalPoll\Security
 */

Class TP_Security {

    /**
     * Current IP.
     * 
     * @since 2.0.0
     * @access public
     * @type string
     */
    public $ip;

    /**
     * Current IP Range (192.193.194.***).
     * 
     * @since 2.0.0
     * @access public
     * @type string
     */
    public $ip_range;

    /**
     * Type of current IP.
     * 
     * @since 2.0.0
     * @access public
     * @type bool
     */
    public $is_ipv6;

    /**
     * Assign IP.
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {

	if ( isset($_SERVER['HTTP_CLIENT_IP']) ):
	    $this->ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ):
	    $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif ( isset($_SERVER['HTTP_X_FORWARDED']) ):
	    $this->ip = $_SERVER['HTTP_X_FORWARDED'];
	elseif ( isset($_SERVER['HTTP_FORWARDED_FOR']) ):
	    $this->ip = $_SERVER['HTTP_FORWARDED_FOR'];
	elseif ( isset($_SERVER['HTTP_FORWARDED']) ):
	    $this->ip = $_SERVER['HTTP_FORWARDED'];
	elseif ( isset($_SERVER['REMOTE_ADDR']) ):
	    $this->ip = $_SERVER['REMOTE_ADDR'];
	endif;

	$this->ip_range = implode('.', explode('.', $this->ip, -1)) . '.';
	
	// Check ip version
	if ( !preg_match('/^\d{1,3}(\.\d{1,3}){3,3}$/', $this->ip) ):
	    $this->is_ipv6 = true;
	endif;
    }

    /**
     * Check vote ability.
     * 
     * @since 2.0.0
     * @global object $poll Poll object
     * @return bool
     */
    public function has_ability_to_vote()
    {
	global $poll;

	$session_layer = $this->is_layer_enabled('session') && $this->check_session_layer();
	$cookies_layer = $this->is_layer_enabled('cookies') && $this->check_cookies_layer();
	$ip_layer = $this->is_layer_enabled('ip') && $this->check_ip_layer();
	$ip_range_layer = $this->is_layer_enabled('ip_range') && $this->check_ip_range_layer();

	if ( $session_layer || $cookies_layer || $ip_layer ||
		$ip_range_layer || is_poll_quota_exceeded() || !is_poll_started() || is_poll_finished() )
	    return (bool) apply_tp_filters('tp_security_vote_ability', false);
	/**
	 * Vote ability.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_vote_ability
	 * @param Current ability
	 */
	return (bool) apply_tp_filters('tp_security_vote_ability', true);
    }

    /**
     * Lock vote ability using enabled layers.
     * 
     * @since 2.0.0
     * @return void
     */
    public function lock_vote_ability()
    {
	// Session
	if ( $this->is_layer_enabled('session') ):
	    $this->add_session_layer();
	endif;

	// Cookies
	if ( $this->is_layer_enabled('cookies') ):
	    $this->add_cookies_layer();
	endif;

	// IP
	if ( $this->is_layer_enabled('ip') ):
	    $this->add_ip_layer();
	endif;

	// IP Range
	if ( $this->is_layer_enabled('ip_range') && !$this->is_ipv6 ):
	    $this->add_ip_range_layer();
	endif;

	/**
	 * Lock vote ability.
	 * 
	 * @since 2.0.0
	 * @action tp_security_lock_vote_ability
	 */
	do_tp_action('tp_security_lock_vote_ability');
    }

    /**
     * Session layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function add_session_layer()
    {
	global $poll;
	$_SESSION[$poll->special_id] = true;
	/**
	 * Session lock.
	 * 
	 * @since 2.0.0
	 * @action tp_security_lock_vote_by_session
	 * @param Special ID
	 */
	do_tp_action('tp_security_lock_vote_by_session', $poll->special_id);
    }

    /**
     * Cookies layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function add_cookies_layer()
    {
	global $poll;
	setcookie('wp_' . md5($poll->special_id), true, time() + (MINUTE_IN_SECONDS * intval($poll->limitations->cookies->timeout)), COOKIEPATH, COOKIE_DOMAIN);
	/**
	 * Cookies lock
	 * 
	 * @since 2.0.0
	 * @action tp_security_lock_vote_by_cookies
	 * @param Special ID
	 * @param Timeout
	 */
	do_tp_action('tp_security_lock_vote_by_cookies', $poll->special_id, intval($poll->limitations->cookies->timeout));
    }

    /**
     * IP Layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function add_ip_layer()
    {
	global $poll;
	set_transient($this->ip . '-' . $poll->special_id, true, $poll->limitations->ip->timeout * MINUTE_IN_SECONDS);
	/**
	 * IP lock
	 * 
	 * @since 2.0.0
	 * @action tp_security_lock_vote_by_ip
	 * @param Special ID
	 * @param Timeout
	 */
	do_tp_action('tp_security_lock_vote_by_ip', $poll->special_id, $poll->limitations->ip->timeout);
    }

    /**
     * IP Range Layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function add_ip_range_layer()
    {
	global $poll;
	set_transient($this->ip_range . '*-' . $poll->special_id, true, $poll->limitations->ip->timeout);
	/**
	 * IP Range lock
	 * 
	 * @since 2.0.0
	 * @action tp_security_lock_vote_by_ip_range
	 * @param Special ID
	 * @param Timeout
	 */
	do_tp_action('tp_security_lock_vote_by_ip_range', $poll->special_id, $poll->limitations->ip->timeout);
    }

    /**
     * Check layer if enabled.
     * 
     * @since 2.0.0
     * @global object $poll
     * @param string $layer Layer name
     * @return bool
     */
    public function is_layer_enabled($layer)
    {
	global $poll;
	/**
	 * Security layer check.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_layer_enabled
	 * @param State
	 * @param Layer name
	 */
	return apply_tp_filters('tp_security_layer_enabled', isset($poll->limitations->revote->{$layer}), $layer);
    }

    /**
     * Check session layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return bool
     */
    public function check_session_layer()
    {
	global $poll;
	/**
	 * Session layer check.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_check_session_layer
	 * @param State
	 * @param Special ID
	 */
	return apply_tp_filters('tp_security_check_session_layer', isset($_SESSION[$poll->special_id]), $poll->special_id);
    }

    /**
     * Check cookies layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return boold
     */
    public function check_cookies_layer()
    {
	global $poll;
	/**
	 * Cookies layer check.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_check_cookies_layer
	 * @param State
	 * @param Special ID
	 */
	return apply_tp_filters('tp_security_check_cookies_layer', isset($_COOKIE['wp_' . md5($poll->special_id)]), $poll->special_id);
    }

    /**
     * Check IP layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return boold
     */
    public function check_ip_layer()
    {
	global $poll;
	/**
	 * IP layer check.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_check_ip_layer
	 * @param State
	 * @param Special ID
	 */
	return apply_tp_filters('tp_security_check_ip_layer', (bool) get_transient($this->ip . '-' . $poll->special_id), $poll->special_id);
    }

    /**
     * Check IP range layer.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return bool
     */
    public function check_ip_range_layer()
    {
	global $poll;

	if ( $this->is_ipv6 )
	/**
	 * Check IP range.
	 * 
	 * @since 2.0.0
	 * @filter tp_security_check_ip_range_layer
	 * @param State
	 */
	    return apply_tp_filters('tp_security_check_ip_range_layer', false, $poll->special_id);

	if ( get_transient($this->ip_range . '*-' . $poll->special_id, false) ):
	    return apply_tp_filters('tp_security_check_ip_range_layer', true, $poll->special_id);
	endif;

	$last_range = array_pop(explode('.', $this->ip));
	$last_range_votes = 0;
	for ( $current = 0; $current <= $poll->limitations->ip_range->width; $current++ ):
	    if ( ($last_range - $current) >= 0 ):
		$last_range_votes += (get_transient($this->ip_range . ($last_range - $current) . '-' . $poll->special_id, false) === false) ? 0 : 1;
	    endif;
	    if ( ($last_range + $current) <= 255 ):
		$last_range_votes += (get_transient($this->ip_range . ($last_range + $current) . '-' . $poll->special_id, false) === false) ? 0 : 1;
	    endif;
	endfor;

	if ( $last_range_votes != 0 && $last_range_votes >= $poll->limitations->ip_range->limits ):
	    return apply_tp_filters('tp_security_check_ip_range_layer', true, $poll->special_id);
	endif;

	/**
	 * IP range layer check.
	 * 
	 * @since 2.0.0
	 * @filter tp_check_ip_range_layer
	 * @param State
	 * @param Special ID
	 */
	return apply_tp_filters('tp_security_check_ip_range_layer', false, $poll->special_id);
    }

}
