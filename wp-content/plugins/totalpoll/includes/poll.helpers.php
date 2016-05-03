<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Prepare poll choices.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param object $poll Poll object
 * @return void
 */

function tp_prepare_poll_choices($poll)
{
    if ( !isset($poll->choices) || empty($poll->choices) )
        return;

    if ( !is_admin() ):
        // Check for custom choice types
        $refresh_choices = false;
        $builtin_types = array( 'text', 'link', 'image', 'video', 'html' );
        foreach ( $poll->choices as $index => $choice ):
            if ( !in_array($choice->type, $builtin_types) &&
                    (!has_filter("tp_render_{$choice->type}_choice_vote") || !has_filter("tp_render_{$choice->type}_choice_result")) ):
                unset($poll->choices[$index]);
                $refresh_choices = true;
            endif;
        endforeach;

        if ( $refresh_choices ):
            $poll->choices = array_values($poll->choices);
        endif;
    endif;

    // Generate an unique ID for each choice
    array_walk($poll->choices, 'tp_generate_choice_id', $poll->special_id);

    // Count total votes
    $poll->total_votes = 0;
    array_walk($poll->choices, 'tp_count_choices_total_votes', $poll);
    array_walk($poll->choices, 'tp_calc_choice_percentages', $poll->total_votes);

    // Strip slashes
    array_walk($poll->choices, 'tp_prepare_choice_html');

    /**
     * Prepare poll choices
     * 
     * @since 2.0.0
     * @action tp_poll_prepare_choices
     * @param Poll choices
     * @param Poll object
     */
    do_tp_action('tp_poll_prepare_choices', $poll->choices, $poll);
}

/**
 * Get current poll ID.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @global object $post Post object
 * @return int|bool Current poll id, false otherwise
 */
function tp_get_poll_id()
{
    global $post;

    $poll_id = false;

    // From request
    if ( isset($_REQUEST['tp_poll_id']) )
        $poll_id = $_REQUEST['tp_poll_id'];

    // From post object
    if ( isset($post) && $post->post_type === 'poll' )
        $poll_id = $post->ID;

    /**
     * Get poll ID
     * 
     * @since 2.0.0
     * @filter tp_poll_get_id
     * @param Poll ID
     */
    return apply_filters('tp_poll_get_id', $poll_id);
}

/**
 * Get poll options.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param int $poll_id Poll ID
 * @return boolean
 */
function tp_get_poll_options($poll_id = false)
{
    if ( !$poll_id ):
        if ( !($poll_id = tp_get_poll_id()) )
            return false;
    endif;
    // Get stored options
    $options = get_post_meta($poll_id, '_tp_options', true);

    /**
     * Get poll options
     * 
     * @since 2.0.0
     * @filter tp_poll_get_options
     * @param Options
     * @param Poll ID
     */
    $filtered_options = apply_filters('tp_poll_get_options', $options, $poll_id);
    if ( empty($filtered_options) ):
        return false;
    endif;
    // Return options object
    return json_decode(json_encode($filtered_options));
}

/**
 * Save votes.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param $poll Poll object
 * @param array $votes Choices IDs
 * @return boolean
 */
function tp_save_poll_votes($poll, $votes = array())
{

    // Check there are vote
    if ( empty($votes) || !is_object($poll) )
        return false;

    // Get a "raw" copy
    $new = tp_get_poll_options($poll->id);

    // Add votes to choices
    foreach ( $poll->choices as $index => $choice ):
        if ( in_array($choice->id, $votes) ):
            $new->choices[$index]->votes++;
            $poll->choices[$index]->votes++;
        endif;
    endforeach;

    /**
     * Filter saved votes.
     * 
     * @since 2.0.0
     * @filter tp_poll_save_votes
     * @param New choices object (with counted votes)
     * @param Votes
     */
    $new = apply_tp_filters('tp_poll_save_votes', $new, $votes);

    // Update options object
    return update_post_meta($poll->id, '_tp_options', json_decode(json_encode($new), true));
}

/**
 * Generate unique ID for each choice.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param object $choice Poll choice object
 * @param int $index Poll choice index
 * @param string $special_id Poll special id
 * @return void
 */
function tp_generate_choice_id($choice, $index, $special_id)
{
    // Generate a unique ID
    $id = md5(session_id() . $index . $special_id . $choice->type);
    /**
     * Choice ID
     * 
     * @since 2.0.0
     * @filter tp_poll_generate_choice_id
     * @param ID
     * @param Choice object
     * @param Choice index
     */
    $choice->id = apply_tp_filters('tp_poll_generate_choice_id', $id, $choice, $index);
}

/**
 * Count total votes.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param object $choice Poll choice object
 * @param object $poll Poll object
 * @return void
 */
function tp_count_choices_total_votes($choice, $index, $poll)
{
    // Cumulate votes
    $poll->total_votes += (int) $choice->votes;
}

/**
 * Calc percentages.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param object $choice Poll choice object
 * @param int $index Poll choice index
 * @param int $total_votes Poll total votes
 * @return void
 */
function tp_calc_choice_percentages($choice, $index, $total_votes)
{
    /**
     * Filter votes percentage precision
     * 
     * @since 2.0.0
     * @filter tp_poll_percentage_precision
     * @param Precision
     */
    $precision = apply_tp_filters('tp_poll_percentage_precision', 3);
    $choice->votes_percentage = ($total_votes === 0) ? 0 : round($choice->votes / $total_votes, $precision) * 100;
}

/**
 * Order choices by votes (sort callback).
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param $current Current choice
 * @param $next Next Choice
 * @return int
 */
function tp_order_choices_by_votes($current, $next)
{
    if ( $current->votes === $next->votes ) {
        return 0;
    }
    return ($current->votes < $next->votes) ? -1 : 1;
}

/**
 * Strip slashes from HTML.
 * 
 * @package TotalPoll\Helpers\Poll
 * @since 2.0.0
 * @param object $choice Poll choice object
 * @return void
 */
function tp_prepare_choice_html($choice)
{
    // Stripe slashes from html choices
    if ( $choice->type === 'html' ):
        /**
         * Prepare html choice
         * 
         * @since 2.0.0
         * @filter tp_poll_prepare_choice_html
         * @param HTML
         */
        $html = stripslashes($choice->html);
        if ( !is_admin() ):
            $html = do_shortcode($html);
        endif;
        $choice->html = apply_tp_filters('tp_poll_prepare_choice_html', $html);
    endif;
}
