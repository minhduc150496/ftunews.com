<?php

if ( !defined('ABSPATH') )
    exit; // Shhh


/**
 * Process vote, results and preview requests.
 * 
 * @since 2.0.0
 * @package TotalPoll\Request
 */

Class TP_Request {

    /**
     * Preview mode.
     * 
     * @since 2.0.0
     * @access public
     * @type bool
     */
    public $preview_mode;

    /**
     * Capture actions.
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        TotalPoll('addons');
        
	add_action('tp_capture_ajax_vote', array( $this, 'ajax_vote' ));
	add_action('tp_capture_ajax_back', array( $this, 'ajax_back' ));
	add_action('tp_capture_ajax_results', array( $this, 'ajax_results' ));

	add_action('tp_capture_post_results', array( $this, 'results' ));
	add_action('tp_capture_post_vote', array( $this, 'vote' ));
	add_action('tp_capture_get_preview', array( $this, 'preview' ));

	$this->preview_mode = isset($_GET['tp_action']) && $_GET['tp_action'] === 'preview';

	// Enable fake votes
	if ( $this->preview_mode ):
	    add_filter('tp_security_vote_ability', '__return_true');
	    add_filter('tp_display_poll_buttons', '__return_true');
	endif;
    }

    /**
     * Preview.
     * 
     * @since 2.0.0
     * @return void
     */
    public function preview()
    {
	// Prevent preview from unpreviliged users
	if ( !current_user_can('edit_posts') )
	    wp_die(__('You cannot preview this poll. Login first.', TP_TD));

	TotalPoll('poll')->load();
	exit(include_once( TP_PATH . 'includes/admin/editor/preview.php' ));
    }

    /**
     * Vote.
     * 
     * @since 2.0.0
     * @return void
     */
    public function vote()
    {
	global $poll;

	if ( TotalPoll('poll')->load($_REQUEST['tp_poll_id']) ):

	    if ( $this->preview_mode && current_user_can('edit_posts') ):
		$poll->skip_to_results = true;
	    else:
		if ( TotalPoll('security')->has_ability_to_vote() && !empty($_POST['tp_choices']) ):
		    $log = array(
			'status' => '',
			'choices' => array(),
			'ip' => '',
			'useragent' => '',
			'extra' => ''
		    );
		    $voted_for = (array) $_POST['tp_choices'];
		    if ( !isset($poll->limitations->multiselection) && count($voted_for) > 1 ):
			$voted_for = (array) current($voted_for);
		    endif;

		    // Save votes
		    if ( tp_save_poll_votes($poll, $voted_for) ):
			// Refresh choices
			tp_prepare_poll_choices($poll);
			// Lock vote ability
			TotalPoll('security')->lock_vote_ability();
			// Status for logs
			$log['status'] = __('Accepted', TP_TD);
		    else:
			$log['status'] = __('Denied', TP_TD);
		    endif;

		else:
		    // Status for logs
		    $log['status'] = __('Denied', TP_TD);
		endif;

		$poll->skip_to_results = !empty($voted_for);

		if ( !empty($log['status']) && !empty($voted_for) ):

		    foreach ( $poll->choices as $index => $choice ):
			if ( in_array($choice->id, $voted_for) )
			    $log['choices'][] = sprintf("#%s %s", $index + 1, empty($choice->text) ? empty($choice->label) ? '' : $choice->label : $choice->text);
		    endforeach;

		    $log['ip'] = TotalPoll('security')->ip;
		    $log['useragent'] = $_SERVER['HTTP_USER_AGENT'];
		    $log['extra'] = apply_tp_filters('tp_request_log_extra', '', $log);

		    TotalPoll('logs')->log($log);

		endif;

	    endif;

	endif;
    }

    /**
     * Results.
     * 
     * @since 2.0.0
     * @return void
     */
    public function results()
    {
	global $poll;

	if ( TotalPoll('poll')->load($_POST['tp_poll_id']) ):

	    if ( $this->preview_mode && current_user_can('edit_posts') ):

		$poll->skip_to_results = true;

	    else:

		if ( is_poll_started() && !is_poll_results_locked() ):
		    $poll->skip_to_results = true;
		endif;

	    endif;

	endif;
    }

    /**
     * Ajax vote.
     * 
     * @since 2.0.0
     * @return void
     */
    public function ajax_vote()
    {
	$this->vote();
	exit(TotalPoll('poll')->get_render(true));
    }

    /**
     * Ajax results.
     * 
     * @since 2.0.0
     * @return void
     */
    public function ajax_results()
    {
	$this->results();
	exit(TotalPoll('poll')->get_render(true));
    }

    /**
     * Ajax back.
     * 
     * @since 2.0.0
     * @return void
     */
    public function ajax_back()
    {
	if ( TotalPoll('poll')->load($_REQUEST['tp_poll_id']) ):
	    exit(TotalPoll('poll')->get_render(true));
	endif;
    }

}
