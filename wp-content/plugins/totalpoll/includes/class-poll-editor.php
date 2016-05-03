<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Poll editor (admin).
 * 
 * @since 2.0.0
 * @package TotalPoll\Editor
 */

Class TP_Poll_Editor {

    /**
     * Initializing (regiser hooks, menus).
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        add_action('admin_init', array( $this, 'init' ));
        add_action('admin_notices', array( $this, 'logs_reset' ));
        add_action('save_post', array( $this, 'save' ));
    }

    /**
     * Editor init.
     * 
     * @global string $wp_version WP Version
     * @param string $title Title
     * @return string
     */
    public function init()
    {
        global $wp_version, $pagenow, $post;

        if ( in_array($pagenow, array( 'post-new.php', 'post.php' )) ):
            $load_editor = false;
            $which_hook = version_compare($wp_version, '3.5', '<') ? 'edit_form_advanced'
                        : 'edit_form_after_title';
            // For new
            if ( !isset($_GET['post']) && isset($_GET['post_type']) && $_GET['post_type'] == 'poll' ):
                // Load default template functionalities
                TotalPoll('template')->load_functions('default');
                $load_editor = true;
            // For existing
            elseif ( isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit' ):
                $load_editor = TotalPoll('poll')->load($_GET['post']);
                if ( !$load_editor && get_post_type($_GET['post']) == 'poll' ):
                    add_action($which_hook, array( $this, 'incompatible_poll' ));
                endif;
            endif;

            if ( $load_editor ):
                add_action('admin_enqueue_scripts', array( $this, 'enqueue_assets' ));
                add_action($which_hook, array( $this, 'editor' ));
            endif;

        endif;
    }

    /**
     * Enqueue assets.
     * 
     * @param string $current
     * @return void
     */
    public function enqueue_assets($current)
    {
        // Minimized or not ?
        $minimized = defined('WP_DEBUG') && WP_DEBUG === true ? '' : '.min';

        // Datepicker
        wp_enqueue_style('jquery-ui-smoothness', "//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui$minimized.css", false);
        wp_enqueue_script('jquery-ui-datepicker');

        // Color picker
        if ( wp_script_is('wp-color-picker', 'registered') ):
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        else:
            // Fallback
            wp_enqueue_style('farbtastic');
            wp_enqueue_script('farbtastic');
        endif;

        // Thickbox
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');

        // WP Media
        wp_enqueue_media();

        // TotalPoll
        wp_enqueue_script('tp-admin', TP_JS_ASSETS . "admin-main$minimized.js", array( 'media-views' ), TP_VERSION);
        wp_enqueue_style('tp-admin', TP_CSS_ASSETS . "admin-style$minimized.css", array(), TP_VERSION);

        /**
         * Enqueue scripts and styles.
         * 
         * @since 2.0.0
         * @action tp_admin_enqueue_scripts
         */
        do_tp_action('tp_admin_editor_enqueue_scripts', $current);
    }

    /**
     * Poll editor.
     * 
     * @since 2.0.0
     * @global object $poll
     * @return void
     */
    public function editor()
    {
        global $poll;

        // Load poll
        TotalPoll('poll')->load();

        // Special loading for new polls
        if ( !is_object($poll) && get_current_screen()->action == 'add' ):
            $defaults = array(
                'id' => get_the_ID(),
                'choices' => array(
                    array(
                        'type' => 'text',
                        'votes' => 0,
                        'text' => __('Choice sample', TP_TD)
                    )
                ),
                'template' => array(
                    'name' => 'default',
                    'preset' => array( 'name' => 'default' )
                ),
                'limitations' => array(
                    'revote' => array(
                        'session' => 1,
                        'cookies' => 1
                    ),
                ),
                'misc' => array(
                    'orderby_votes_direction' => 'asc',
                    'show_results' => 'number'
                )
            );
            $poll = json_decode(json_encode($defaults), false);
            TotalPoll('template')->load();
        endif;

        // Save opened tabs
        $last_opened_tabs = wp_parse_args(isset($_GET['tp_opened_tabs']) ? $_GET['tp_opened_tabs']
                            : array(), array( 'shortcode' => '1', 'question' => '1' ));

        // Nonce field
        wp_nonce_field(plugin_basename(__FILE__), 'tp_options_nonce');

        // Poll options
        $options = $poll;

        /**
         * Before editor.
         * 
         * @since 2.0.0
         * @action tp_admin_editor_before
         * @param Options
         */
        do_tp_action('tp_admin_editor_before', $options);

        // Sections
        $sections = array( 'header', 'shortcode', 'question', 'choices', 'design', 'limitations', 'misc', 'sharing', 'logs', 'footer' );
        foreach ( $sections as $editor_section ):
            /**
             * Before section.
             * 
             * @since 2.0.0
             * @action tp_admin_editor_before_{$section}
             * @param Options
             */
            do_tp_action("tp_admin_editor_before_{$editor_section}_section", $options);
            /**
             * Include section.
             */
            include_once( TP_PATH . "includes/admin/editor/$editor_section.php" );
            /**
             * After section.
             * 
             * @since 2.0.0
             * @action tp_admin_editor_after_{$editor_section}
             * @param Options
             */
            do_tp_action("tp_admin_editor_after_{$editor_section}_section", $options);
        endforeach;
        /**
         * After editor.
         * 
         * @since 2.0.0
         * @action tp_admin_editor_after
         * @param Options
         */
        do_tp_action('tp_admin_editor_after', $options);
    }

    /**
     * Save poll.
     * 
     * @param string $poll_id
     * @return void
     */
    public function save($poll_id)
    {

        // Is an auto save
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE && isset($_POST['data']['wp_autosave']['post_type']) && 'poll' == $_POST['data']['wp_autosave']['post_type'] && current_user_can('edit_post', $_POST['data']['wp_autosave']['post_id'])
        ):
            $options = array(
                'question' => __('A question', TP_TD),
                'choices' => array( array( 'type' => 'text', 'votes' => 0, 'text' => __('Choice sample', TP_TD) ) ),
                'limitations' => array(
                    'revote' => array( 'session' => 1, 'cookies' => 1 ),
                    'cookies' => array( 'timeout' => 1440 ),
                    'ip' => array(),
                    'ip_range' => array(),
                    'quota' => 0,
                    'date' => array()
                ),
                'misc' => array(
                    'show_results' => 'number',
                    'orderby_votes_direction' => 'asc'
                ),
                'template' => array(
                    'name' => 'default',
                    'preset' => array(
                        'name' => 'default',
                        'settings' => array()
                    )
                )
            );

        // Is a normal save
        else:

            // Quick edits
            if ( defined('DOING_AJAX') && DOING_AJAX )
                return;

            // Check current post type and permissions
            if ( isset($_POST['post_type']) && 'poll' == $_POST['post_type'] ):
                if ( !current_user_can('edit_post', $poll_id) )
                    return;
            endif;

            // Check nonce
            if ( !isset($_POST['tp_options_nonce']) || !wp_verify_nonce($_POST['tp_options_nonce'], plugin_basename(__FILE__)) ):
                return;
            endif;

            // Logs
            if ( isset($_POST['download_logs']) ):
                // Launch download
                TotalPoll('logs')->download($poll_id);
            elseif ( isset($_POST['download_logs_txt']) ):
                // Launch download of deprecated logs
                TotalPoll('logs')->download($poll_id, true);
            elseif ( isset($_POST['reset_logs']) ):
                // Clear logs
                TotalPoll('logs')->clear($poll_id);
                // Show reset message
                add_filter('redirect_post_location', array( $this, 'reset_done' ));
            endif;

            // Options
            $options = (array) $_POST['tp_options'];

            // Old options
            $original = tp_get_poll_options($poll_id);

            // Fix a rare issue which converts choices array to object

            if ( $original->choices instanceof stdClass ):
                $original->choices = json_decode(json_encode($original->choices), true);
                foreach ( $original->choices as $key => $choice ):
                    $original->choices[$key] = json_decode(json_encode($choice));
                endforeach;
            endif;

            // Sync between new and old votes
            foreach ( $options['choices'] as $index => $choice ): $index = intval($index);
                // Check last known index
                if ( isset($choice['last_index']) ):
                    if ( $original && !isset($options['override_votes']) ):
                        // Copy past votes

                        $options['choices'][$index]['votes'] = intval($original->choices[intval($choice['last_index'])]->votes);
                    endif;
                    // Remove last_index from choices
                    unset($options['choices'][$index]['last_index']);
                endif;

                // Fallback when votes field is empty
                $options['choices'][$index]['votes'] = intval($options['choices'][$index]['votes']);

            endforeach;

            // Remove override_votes from options
            unset($options['override_votes']);

            // Add a sample
            if ( empty($options['choices']) ):
                $options['choices'][] = array( 'type' => 'text', 'votes' => 0, 'text' => __('Choice sample', TP_TD) );
            endif;

            // Limitations filters
            if ( isset($options['limitations']['revote']['ip_range']) ):

                // Check for valid values
                if ( $options['limitations']['ip_range']['limits'] < 1 )
                    $options['limitations']['ip_range']['limits'] = 5;

                // Check for valid values
                if ( $options['limitations']['ip_range']['width'] < 1 || $options['ip_range']['width'] > 10 )
                    $options['limitations']['ip_range']['width'] = 5;

            endif;

            if ( isset($options['limitations']['revote']['ip']) ):

                // Check for valid values
                if ( $options['limitations']['ip']['timeout'] < 0 )
                    $options['limitations']['ip']['timeout'] = absint($options['limitations']['ip']['timeout']);

                // Check for valid values
                if ( $options['limitations']['quota'] < 1 )
                    $options['limitations']['quota'] = 0;

            endif;

            if ( isset($options['limitations']['revote']['cookies']) ):

                // Check for valid values
                if ( $options['limitations']['cookies']['timeout'] < 1 )
                    $options['limitations']['cookies']['timeout'] = 1;

            endif;

            // Date
            if ( isset($options['limitations']['date']['start']) ):

                // First check date validity before pass it to strtotime function
                $start_date_timestamp = '';
                $start_date = explode('/', $options['limitations']['date']['start']);

                if ( count($start_date) == 3 && checkdate($start_date[0], $start_date[1], $start_date[2]) ):
                    // Convert date to timestamp
                    $start_date_timestamp = strtotime($options['limitations']['date']['start']);
                endif;

                // Check timestamp
                if ( !empty($start_date_timestamp) ):
                    $options['limitations']['date']['start_timestamp'] = $start_date_timestamp;
                else:
                    // Reset both
                    $options['limitations']['date']['start'] = '';
                    $options['limitations']['date']['start_timestamp'] = '';
                endif;

            endif;

            if ( isset($options['limitations']['date']['end']) ):

                $end_date_timestamp = '';
                $end_date = explode('/', $options['limitations']['date']['end']);

                if ( count($end_date) == 3 && checkdate($end_date[0], $end_date[1], $end_date[2]) ):
                    $end_date_timestamp = strtotime($options['limitations']['date']['end']);
                endif;

                if ( !empty($end_date_timestamp) ):
                    $options['limitations']['date']['end_timestamp'] = $end_date_timestamp;
                else:
                    $options['limitations']['date']['end'] = '';
                    $options['limitations']['date']['end_timestamp'] = '';
                endif;

            endif;

            // Template & preset
            $template_preset = explode('-preset-', $options['template']['preset']['name']);

            // Template name
            $options['template']['name'] = $template_preset[0];

            // Preset name
            $options['template']['preset']['name'] = $template_preset[1];

            // Fetch template
            TotalPoll('template')->fetch($options['template']['name']);

            // Load the template functions
            TotalPoll('template')->load_functions($options['template']['name']);

            // Delete, load or save a new preset
            if ( isset($options['template']['preset']['delete']) ):
                // Delete the preset
                TotalPoll('template')->delete_preset($template_preset[0], $template_preset[1]);
                // Reset to default
                $has_defined_preset = isset(TotalPoll('template')->available[$options['template']['name']]->presets[$options['template']['preset']['name']]);
                $options['template']['preset']['name'] = $has_defined_preset ? $options['template']['preset']['name']
                            : 'default';
            elseif ( empty($options['template']['preset']['load']) ):

                // Override preset name
                if ( isset($options['template']['preset']['new']) ):
                    $new = true;
                    $template_preset[1] = $options['template']['preset']['new'];
                endif;

                // Preset separation
                $preset = $options['template']['preset']['settings'];
                $options['template']['preset']['name'] = TotalPoll('template')->save_preset($template_preset[0], $template_preset[1], $preset, isset($new));

            endif;

            /**
             * Remove preset settings from template object because presets are saved in another object
             */
            unset($options['template']['preset']['settings']);

        endif;

        /**
         * Poll options to be saved.
         * 
         * @since 2.0.0
         * @filter tp_admin_save_poll_options
         * @param Options
         */
        $options = apply_filters('tp_admin_editor_save_poll_options', $options);

        // Save new options
        update_post_meta($poll_id, '_tp_options', $options);

        /**
         * After poll save
         * 
         * @since 2.0.0
         * @action tp_admin_after_save_poll
         * @param Poll id
         * @param Options
         */
        do_action('tp_admin_editor_after_save_poll', $poll_id, $options);

        // Restore opened tabs
        add_filter('redirect_post_location', array( $this, 'reopen_last_tabs' ));
    }

    /**
     * Reset done.
     * 
     * @param string $location
     * @return string
     */
    public function reset_done($location)
    {
        return esc_url_raw( add_query_arg('reset_done', 1, $location) );
    }

    /**
     * Logs reset message.
     * 
     * @since 2.0.0
     * @return void
     */
    public function logs_reset()
    {
        if ( isset($_GET['reset_done']) ):
            include_once( TP_PATH . 'includes/admin/editor/reset.php' );
        endif;
    }

    /**
     * Incompatibility message.
     * 
     * @since 2.0.0
     * @return void
     */
    public function incompatible_poll()
    {
        include_once( TP_PATH . 'includes/admin/editor/incompatible.php' );
    }

    /**
     * Reopen last tabs.
     * 
     * @param string $location
     * @return string
     */
    public function reopen_last_tabs($location)
    {
        return esc_url_raw( add_query_arg(array( 'tp_opened_tabs' => $_POST['tp_opened_tabs'] ), $location) );
    }

}
