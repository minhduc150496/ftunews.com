<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Logs.
 * 
 * @since 2.0.0
 * @package TotalPoll\Logs
 */

Class TP_Logs {

    /**
     * Logs container.
     * 
     * @since 2.0.0
     * @access private
     * @type array
     */
    private $logs = array();

    /**
     * Save logs.
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        add_action('shutdown', array( $this, 'save' ));
    }

    /**
     * Log message.
     * 
     * @since 2.0.0
     * @param string $message Message to log
     * @return void
     */
    public function log($log)
    {
        $this->logs[] = array( 'time' => time(), 'args' => $log );
    }

    /**
     * Reset logs.
     * 
     * @since 2.0.0
     * @return void
     */
    public function reset()
    {
        $this->logs = array();
    }

    /**
     * Clear logs.
     * 
     * @since 2.0.0
     * @global object $poll Current poll object
     * @return bool
     */
    public function clear($poll_id)
    {
        return delete_post_meta($poll_id, '_tp_logs_csv');
    }

    /**
     * Get logs.
     * 
     * @since 2.0.0
     * @global $poll Current poll object
     * @return array logs messages
     */
    public function get($poll_id, $txt = false)
    {
        if ( $logs = get_post_meta($poll_id, ($txt ? '_tp_logs' : '_tp_logs_csv')) )
            return $logs;

        return array();
    }

    /**
     * Save logs.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function save()
    {
        global $poll;

        if ( empty($this->logs) || !isset($poll->id) || !isset($poll->logs) ):
            return;
        endif;

        foreach ( $this->logs as $log ):
            add_post_meta($poll->id, '_tp_logs_csv', $log);
        endforeach;

        return;
    }

    /**
     * Download logs.
     * 
     * @since 2.0.0
     * @return void
     */
    public function download($poll_id, $txt = false)
    {

        // Give a name
        $filename = 'poll-logs-' . time() . ($txt ? '.txt' : '.csv');

        // Send download headers
        header("Content-Disposition: attachment; filename=$filename");
        header('Content-type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');

        $logs = array_filter($this->get($poll_id, $txt));
        $time_format = get_site_option('time_format');
        $date_format = get_site_option('date_format');
        
        // Txt or csv
        if ( $txt ):

            foreach ( $logs as $log ):
                printf("%s - %s\r\n", date($date_format . ' ' . $time_format, $log[0]), $log[1]);
            endforeach;

        else:

            self::csv_row(array(
                __('Date', TP_TD),
                __('Time', TP_TD),
                __('Status', TP_TD),
                __('Choice', TP_TD),
                __('IP', TP_TD),
                __('User Agent', TP_TD),
                __('Extra', TP_TD)
            ));
            foreach ( $logs as $log ):
                self::csv_row(array(
                    date($date_format, $log['time']), // Date
                    date($time_format, $log['time']), // Time
                    $log['args']['status'], // Status
                    implode(', ', $log['args']['choices']), // Choices
                    $log['args']['ip'], // IP
                    $log['args']['useragent'], // User agent
                    $log['args']['extra'] // Extra
                ));
            endforeach;

        endif;
        // That's all, folks!
        exit;
    }

    /**
     * Generate a csv row from an array.
     * 
     * @since 2.7.0
     * @static
     * @return void
     */
    public static function csv_row($columns, $separator = ';', $enconding = 'UTF-16LE')
    {
        if ( empty($columns) ):
            return;
        endif;

        foreach ( $columns as $column ):
            printf('"%s"' . $separator, mb_convert_encoding(str_replace('"', '""', $column), $enconding));
        endforeach;

        echo "\r\n";
    }

}
