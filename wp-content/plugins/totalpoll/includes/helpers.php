<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Minify CSS.
 * 
 * @package TotalPoll\Helpers\Minify
 * @since 2.0.0
 * @param string $css
 * @return string
 */
function tp_minify_css($css)
{
    if ( defined('WP_DEBUG') && WP_DEBUG === true )
	return $css;

    $minify_patterns = array(
	'#/\*.*?\*/#s' => '', // Remove comments
	'/\s*([{}|:;,])\s+/' => '$1', // Remove whitespace
	'/\s\s+(.*)/' => '$1', // Remove trailing whitespace at the start
	'/\;\}/' => '}', // Remove unnecesairy ;
    );

    return preg_replace(array_keys($minify_patterns), array_values($minify_patterns), $css);
}

/**
 * Minify HTML.
 * 
 * @package TotalPoll\Helpers\Minify
 * @since 2.0.0
 * @param string $html
 * @return string
 */
function tp_minify_html($html)
{
    if ( defined('WP_DEBUG') && WP_DEBUG === true )
	return $html;

    return preg_replace("/\n\r|\r\n|\n|\r|\t| {2}/", '', $html);
}

/**
 * Same as add_filter but for current template only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @return mixed
 */
function add_filter_to_current_template($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $poll;
    return add_filter('__' . $poll->template->name . '__' . $tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Same as add_filter but for current poll only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @return mixed
 */
function add_filter_to_current_poll($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $poll;
    if ( isset($poll->id) ):
	return add_filter('__poll_' . $poll->id . '__' . $tag, $function_to_add, $priority, $accepted_args);
    endif;
    return false;
}

/**
 * Same as apply_filters but for current template & poll filters only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @global object $poll Poll object
 * @param string $tag
 * @param mixed $value
 * @return mixed
 */
function apply_tp_filters($tag, $value)
{
    global $poll;
    $args = (array) func_get_args();
    array_shift($args);
    if ( isset($poll->template->name) ):
	$args[0] = apply_filters_ref_array('__poll_' . $poll->id . '__' . $tag, $args);
	$args[0] = apply_filters_ref_array('__' . $poll->template->name . '__' . $tag, $args);
    endif;
    return apply_filters_ref_array($tag, $args);
}

/**
 * Same as has_filter but for current template & poll filters only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @param string $tag
 * @param callback $function_to_check
 * @return mixed
 */
function has_tp_filter($tag, $function_to_check = false)
{
    global $poll;
    if ( isset($poll->template->name) ):
	return has_filter('__' . $poll->template->name . '__' . $tag, $function_to_check) ||
		has_filter('__poll_' . $poll->id . '__' . $tag, $function_to_check);
    endif;
    return false;
}

/**
 * Same as remove_filter but for current template & poll filters only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @global object $poll
 * @param string $tag
 * @param callback $function_to_remove
 * @param int $priority
 * @return bool
 */
function remove_tp_filter($tag, $function_to_remove, $priority = 10)
{
    global $poll;
    return remove_filter('__poll_' . $poll->id . '__' . $tag, $function_to_remove, $priority) ||
	    remove_filter('__' . $poll->template->name . '__' . $tag, $function_to_remove, $priority);
}

/**
 * Same as remove_all_filters but for current template & poll filters only.
 * 
 * @package TotalPoll\Helpers\Filters
 * @since 2.0.0
 * @global object $poll
 * @param string $tag
 * @param int $priority
 * @return bool
 */
function remove_all_tp_filters($tag, $priority = false)
{
    global $poll;
    return remove_all_filters('__poll_' . $poll->id . '__' . $tag, $priority) ||
	    remove_all_filters('__' . $poll->template->name . '__' . $tag, $priority);
}

/**
 * Same as add_action but for current template only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @global object $poll
 * @param string $tag
 * @param callback $function_to_add
 * @param int $priority
 * @param int $accepted_args
 * @return bool
 */
function add_action_to_current_template($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $poll;
    return add_filter('__' . $poll->template->name . '__' . $tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Same as add_action but for current poll only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @global object $poll
 * @param string $tag
 * @param callback $function_to_add
 * @param int $priority
 * @param int $accepted_args
 * @return bool
 */
function add_action_to_current_poll($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $poll;
    return add_filter('__poll_' . $poll->id . '__' . $tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Same as do_action but for current template & poll actions only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @global object $poll
 * @param string $tag
 * @param mixed $arg
 * @return mixed
 */
function do_tp_action($tag, $arg = '')
{
    global $poll;
    $args = (array) func_get_args();
    array_shift($args);

    if ( isset($poll->template->name) ):
	do_action_ref_array('__poll_' . $poll->id . '__' . $tag, $args);
	do_action_ref_array('__' . $poll->template->name . '__' . $tag, $args);
    endif;

    return do_action_ref_array($tag, $args);
}

/**
 * Same as has_action but for current template & poll actions only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @param string $tag
 * @param callback $function_to_check
 * @return mixed
 */
function has_tp_action($tag, $function_to_check = false)
{
    return has_tp_filter($tag, $function_to_check);
}

/**
 * Same as remove_action but for current template & poll actions only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @param string $tag
 * @param callback $function_to_remove
 * @param int $priority
 * @return bool
 */
function remove_tp_action($tag, $function_to_remove, $priority = 10)
{
    return remove_tp_filter($tag, $function_to_remove, $priority);
}

/**
 * Same as remove_all_actions but for current template & poll actions only.
 * 
 * @package TotalPoll\Helpers\Actions
 * @since 2.0.0
 * @param type $tag
 * @param type $priority
 * @return bool
 */
function remove_all_tp_actions($tag, $priority = false)
{
    return remove_all_tp_filters($tag, $priority);
}

/**
 * Preset setting getter.
 * 
 * @package TotalPoll\Helpers\Template
 * @since 2.0.0
 * @param string $section
 * @param string|bool $field
 * @param string|bool $states
 * @param string|array|bool $arrays
 * @param string|bool $default
 * @return mixed
 */
function tp_preset_options($section, $field = false, $states = false, $arrays = false, $default = false)
{
    return TotalPoll('template')->retrieve_options($section, $field, $states, $arrays, $default);
}

/**
 * Get current template url.
 * 
 * @package TotalPoll\Helpers\Template
 * @global object $poll Current poll
 * @param string $with With additional path
 * @return string Url
 */
function tp_get_template_url($with = '')
{
    global $poll;
    return TP_TEMPLATES_URL . $poll->template->name . '/' . $with;
}
