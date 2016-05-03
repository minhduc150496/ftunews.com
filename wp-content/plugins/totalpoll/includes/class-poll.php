<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Poll Loader.
 * 
 * @since 2.0.0
 * @package TotalPoll\Poll
 */

Class TP_Poll {

    /**
     * Register hooks to prepare and render poll.
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        // A simple cache layer
        global $cached_polls, $cached_rendered_polls;
        $cached_polls = array();
        $cached_rendered_polls = array();
    }

    /**
     * Setup poll when ID is present.
     * 
     * @since 2.0.0
     * @global object $post
     * @global array $shortcode_tags
     * @return void
     */
    public function setup()
    {
        /**
         * Load poll when it's a post
         */
        $this->load();
        add_filter('the_content', array( $this, 'single_post' ));
    }

    /**
     * Load poll.
     * 
     * @since 2.0.0
     * @global object $poll
     * @global array $cached_polls
     * @param int|bool $poll_id Poll ID
     * @return bool
     */
    public function load($poll_id = false)
    {
        global $poll, $cached_polls;

        // Init addons
        TotalPoll('addons');

        // Prepare ID
        $poll_id = $poll_id ? $poll_id : tp_get_poll_id();

        // Check if there is a cached copy
        if ( $poll_id && isset($cached_polls[$poll_id]) ):
            // Then set current object from the cached copy
            $poll = $cached_polls[$poll_id];
            return true;
        endif;

        // Get stored options
        $poll = tp_get_poll_options($poll_id);

        // Check validity
        if ( !is_object($poll) )
            return false;

        // Load template
        TotalPoll('template')->load();

        // Assign id and special id (for security reasons)
        $poll->id = $poll_id;

        /**
         * Filter the special ID.
         * 
         * @since 2.0.0
         * @filter tp_poll_generate_special_id
         * @param Current special ID
         * @param Poll object
         */
        $poll->special_id = apply_tp_filters('tp_poll_generate_special_id', "tpvb-{$poll->id}", $poll);

        // Prepare choices
        tp_prepare_poll_choices($poll);

        // Cache poll
        $cached_polls[$poll_id] = $poll;

        // Enqueue assets
        $minimized = defined('WP_DEBUG') && WP_DEBUG === true ? '' : '.min';
        wp_enqueue_script('fastclick', TP_JS_ASSETS . "fastclick$minimized.js", TP_VERSION);
        wp_enqueue_script('totalpoll', TP_JS_ASSETS . "totalpoll$minimized.js", array( 'jquery', 'fastclick' ), TP_VERSION);
        /**
         * Enqueue assets.
         * 
         * @since 2.0.0
         * @action tp_poll_enqueue_assets
         * @param Minimized
         */
        do_tp_action('tp_poll_enqueue_assets', $minimized);

        return true;
    }

    /**
     * Unload current poll.
     * 
     * @since 2.0.0
     * @global object $poll
     * @global array $cached_polls
     * @return void
     */
    public function unload()
    {
        global $poll, $cached_polls, $cached_rendered_polls;
        unset($cached_polls[$poll->id], $cached_rendered_polls[$poll->id]);
        $poll = false;
    }

    /**
     * Render poll.
     * 
     * @global object $poll Poll object
     * @return string Rendered poll
     */
    public function get_render($skip_css = false)
    {
        global $poll, $cached_rendered_polls;

        // Check poll
        if ( !is_object($poll) )
            return;

        // Check if there is a cached copy
        if ( isset($cached_rendered_polls[$poll->id]) ):
            // Then set current object from the cached copy
            return $cached_rendered_polls[$poll->id];
        endif;

        /**
         * Before poll render
         * 
         * @since 2.0.0
         * @action tp_poll_before_render
         * @param Poll object
         */
        do_tp_action('tp_poll_before_render', $poll);

        // Start capture of content
        ob_start();

        $style = '';

        // Omit css if rendered before
        if ( !$skip_css && !isset(TotalPoll('template')->presets->{$poll->template->name}->{$poll->template->preset->name}->rendered) ):
            $style = sprintf('<style type="text/css">%s</style>', TotalPoll('template')->get_css());
            if ( is_object(TotalPoll('template')->presets->{$poll->template->name}->{$poll->template->preset->name}) ):
                TotalPoll('template')->presets->{$poll->template->name}->{$poll->template->preset->name}->rendered = true;
            endif;
        endif;

        $content_file = '';
        if ( (!isset($poll->skip_to_results) || $poll->skip_to_results === false ) && TotalPoll('security')->has_ability_to_vote() || !is_poll_started() ):
            $poll->showing_vote = true;
            $content_file = 'vote.php';
        else:
            $poll->showing_results = true;
            $content_file = 'results.php';
        endif;
        /**
         * File to render (vote.php or results.php).
         * 
         * @since 2.0.0
         * @filter tp_poll_render_file
         * @param File name
         */
        $content_file = apply_tp_filters('tp_poll_render_file', $content_file);

        echo TotalPoll('template')->get_part('header.php');
        echo TotalPoll('template')->get_part($content_file);
        echo TotalPoll('template')->get_part('footer.php');

        // Preset class
        $id = "tp-{$poll->template->name}-{$poll->template->preset->name}-preset";
        // Minify and wrap content
        $classes = apply_tp_filters('tp_poll_container_classes', array( 'tp-poll-container' ));
        $attributes = apply_tp_filters('tp_poll_container_attributes', '');
        $content = sprintf('<div id="%s" class="%s" %s>%s</div>', $id, implode(' ', $classes), $attributes, ob_get_clean());
        /**
         * Render poll.
         * 
         * @since 2.0.0
         * @filter tp_poll_render
         * @param Content
         */
        $cached_rendered_polls[$poll->id] = apply_tp_filters('tp_poll_render_with_style', tp_minify_html($style . apply_tp_filters('tp_poll_render', $content)));

        /**
         * After poll render
         * 
         * @since 2.0.0
         * @action tp_poll_after_render
         * @param Rendered content
         */
        do_tp_action('tp_poll_after_render', $cached_rendered_polls[$poll->id]);

        /**
         * Rendered poll
         * 
         * @since 2.0.0
         * @filter tp_poll_rendered
         * @param Rendered poll
         */
        return apply_tp_filters('tp_poll_rendered', $cached_rendered_polls[$poll->id]);
    }

    /**
     * Render poll to buffer.
     * 
     * @since 2.0.0
     * @return void
     */
    public function render()
    {
        echo $this->get_render();
    }

    /**
     * Shortcode.
     * @since 2.0.0
     * @param array $attrs
     * @return string
     */
    public function shortcode($attrs)
    {
        $this->load($attrs['id']);
        /**
         * Render poll by shortcode.
         * 
         * @since 2.0.0
         * @filter tp_render_shortcode
         * @param Content
         * @param Shortcode attributes
         */
        return apply_tp_filters('tp_poll_render_shortcode', $this->get_render(), $attrs);
    }

    /**
     * Post.
     * 
     * @since 2.0.0
     * @return string
     */
    public function single_post()
    {
        $this->load(get_the_ID());
        /**
         * Render poll.
         * 
         * @since 2.0.0
         * @filter tp_poll_render_post
         * @param Content
         * @param Shortcode attributes
         */
        return apply_tp_filters('tp_poll_render_post', $this->get_render());
    }

}
