<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Export results
      Description: Export results of all polls.
      Addon URI: http://wpsto.re/addons/downloads/export-results/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

Class TP_Export_Results_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Textdomain
        load_plugin_textdomain('tp-export-results-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Menu
        add_action('admin_menu', array( $this, 'menu' ));
        add_action('admin_init', array( $this, 'check_for_export' ));
    }

    /**
     * Register menu
     * 
     * @since 1.0.0
     * @return void
     */
    public function menu()
    {
        global $submenu;
        $submenu['edit.php?post_type=poll'][14] = array( __('Export results', 'tp-export-results-addon'), 'install_themes', 'edit.php?post_type=poll&export-polls=1' );
    }

    /**
     * Check for export request
     * 
     * @since 1.0.0
     * @return void
     */
    public function check_for_export()
    {
        // Export
        if ( !empty($_REQUEST['post_type']) && !empty($_REQUEST['export-polls']) && $_REQUEST['post_type'] == 'poll' && current_user_can('install_themes') ):
            $this->export();
        endif;
    }

    /**
     * Export polls results (csv)
     * 
     * @since 1.0.0
     * @global array $wp_filter
     * @global array $wp_actions
     * @global array $merged_filters
     * @global object $poll
     */
    private function export()
    {
        global $wp_filter, $wp_actions, $merged_filters, $poll;

        /**
         * Disabled filters & actions temporary
         */
        $wp_filter = array();
        $wp_actions = array();
        $merged_filters = array();

        // Get polls
        $polls = get_posts('post_type=poll&post_status=any&numberposts=-1');

        // Give a name
        $filename = 'polls-results-' . time() . '.csv';

        // Send download headers
        header('Content-type: application/csv');
        header("Content-Disposition: attachment; filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "\xEF\xBB\xBF"; // UTF8 Support in MS Office (Byte Order Mark).

        foreach ( $polls as $poll ):
            $choices = array();
            $results = array();

            printf("\"%s :\";\"%s\"\r\n", __('Poll title', 'tp-export-results-addon'), $poll->post_title);
            TotalPoll('poll')->load($poll->ID);
            printf("\"%s :\";\"%s\"\r\n", __('Poll quetion', 'tp-export-results-addon'), $poll->question);
            foreach ( $poll->choices as $index => $choice ):
                $label = empty($choice->text) ? empty($choice->label) ? '' : $choice->label
                            : $choice->text;
                $choices[] = sprintf('"#%s %s"', $index + 1, str_replace('"', '""', $label));
                $results['number'][] = sprintf('"%s"', $choice->votes);
                $results['percentage'][] = sprintf('"%s%%"', $choice->votes_percentage);
            endforeach;

            printf("\"%s\";%s\r\n", __('Choices', 'tp-export-results-addon'), implode(';', $choices));
            printf("\"%s\";%s\r\n", __('Votes', 'tp-export-results-addon'), implode(';', $results['number']));
            printf("\"%s\";%s\r\n", __('Percentage', 'tp-export-results-addon'), implode(';', $results['percentage']));
            printf("\"%s\";\"%s\"\r\n", __('Total Voters', 'tp-export-results-addon'), $poll->total_votes);
            echo("\"-----------------------------------------------------------------\"\r\n");

            $poll = new stdClass();
        endforeach;

        // That's all, folks!
        exit;
    }

}

// Bootstrap it
new TP_Export_Results_Addon();
