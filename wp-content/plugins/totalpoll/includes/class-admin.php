<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Admin panel.
 * 
 * @since 2.0.0
 * @package TotalPoll\Admin
 */

Class TP_Admin {

    /**
     * Initializing (register menu, enqueue required scripts).
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        // Columns
        add_filter('manage_poll_posts_columns', array( $this, 'poll_columns' ));
        add_action('manage_poll_posts_custom_column', array( $this, 'poll_columns_content' ), 10, 2);
        // Update notification
        add_action('admin_notices', array( $this, 'check_for_updates' ));
        add_action('admin_init', array( $this, 'dismiss_update' ));
        // Register menus
        add_action('admin_menu', array( $this, 'register_menus' ));
        // Enqueue assets
        if ( isset($_GET['page']) && substr($_GET['page'], 0, 3) == 'tp-' ):
            add_action('admin_enqueue_scripts', array( $this, 'enqueue_assets' ));
        endif;
    }

    /**
     * Add shortcode to poll listing columns.
     * 
     * @since 2.5.0
     * @param array $columns
     * @return array
     */
    public function poll_columns($columns)
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'shortcode' => __('Shortcode', TP_TD),
            'date' => __('Date')
        );
    }

    public function poll_columns_content($column, $id)
    {
        if ( $column === 'shortcode' ):
            printf('<code>[total-poll id="%s"]</code>', $id);
        endif;
    }

    /**
     * Check for updates.
     * 
     * @since 2.0.0
     * @return void
     */
    public function check_for_updates()
    {

        // Check if we request last version the three last days
        $last_version = intval(get_transient('tp_last_version'));

        if ( !$last_version ):
            // Request last version from plugin website
            $last_version = intval(
                    wp_remote_retrieve_body(wp_remote_get(TP_WEBSITE . '?update'))
            );

            // Cache last version for three days
            set_transient('tp_last_version', $last_version, DAY_IN_SECONDS * 3);
        endif;

        // Compare versions
        if ( version_compare($last_version, TP_VERSION, '>') && current_user_can('edit_theme_options') &&
                !get_option("tp_dismiss_update_version_$last_version", false) ):
            include_once( TP_PATH . 'includes/admin/update-notification.php' );
        endif;
    }

    /**
     * Dismiss update.
     * 
     * @since 2.0.0
     * @return void
     */
    public function dismiss_update()
    {
        if ( isset($_GET['tp_dismiss_update']) && current_user_can('update_plugins') ):
            $update = intval($_GET['tp_dismiss_update']);
            update_option("tp_dismiss_update_version_$update", true);
            exit;
        endif;
    }

    /**
     * Enqueue assets.
     * 
     * @since 2.0.0
     * @return void
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('tp-admin', TP_CSS_ASSETS . 'admin-style.min.css', array(), TP_VERSION);
    }

    /**
     * Installer depenencies
     * 
     * @since 2.5.0
     * @return void
     */
    public function include_installer()
    {
        global $wp_version;

        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        if ( version_compare($wp_version, '3.7', '>') ):
            require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader-skins.php' );
        endif;

        require_once ( TP_PATH . 'includes' . DS . 'class-installer.php' );
        require_once ( TP_PATH . 'includes' . DS . 'class-installer-skin.php' );
    }

    /**
     * Register menus.
     * 
     * @since 2.0.0
     * @return void
     */
    public function register_menus()
    {
        // Addons
        add_submenu_page('edit.php?post_type=poll', __('Addons Manager', TP_TD), __('Addons', TP_TD), 'install_themes', 'tp-addons-manager', array( $this, 'addons' ));
        // Tempaltes
        add_submenu_page('edit.php?post_type=poll', __('Templates Manager', TP_TD), __('Templates', TP_TD), 'install_themes', 'tp-templates-manager', array( $this, 'templates' ));
        // Store
        add_submenu_page('edit.php?post_type=poll', __('Templates & Addons Store', TP_TD), __('Store', TP_TD), 'install_themes', 'tp-store', array( $this, 'store' ));
        // Tools
        add_submenu_page('edit.php?post_type=poll', __('TotalPoll Tools', TP_TD), __('Tools', TP_TD), 'edit_posts', 'tp-tools', array( $this, 'tools' ));
        // About
        add_submenu_page('edit.php?post_type=poll', __('About TotalPoll', TP_TD), __('About', TP_TD), 'edit_posts', 'tp-about', array( $this, 'about' ));
    }

    /**
     * Upgrade page.
     * 
     * @since 2.0.0
     * @return void
     */
    public function tools()
    {
        // Let's do some work
        $this->process_upgrade();
        /**
         * Include tools page.
         */
        include_once( TP_PATH . 'includes/admin/tools.php' );
    }

    /**
     * About page.
     * 
     * @since 2.0.0
     * @return void
     */
    public function about()
    {
        /**
         * Include about page.
         */
        include_once( TP_PATH . 'includes/admin/about.php' );
    }

    /**
     * Addons manager.
     * 
     * @since 2.0.0
     * @return void
     */
    public function addons()
    {
        // Let's do some work
        $this->process_addons();
        /**
         * Include addons page.
         */
        include_once( TP_PATH . 'includes/admin/addons.php' );
    }

    /**
     * Templates manager.
     * 
     * @since 2.0.0
     * @return void
     */
    public function templates()
    {
        // Let's do some work
        $this->process_templates();
        /**
         * Include templates page.
         */
        include_once( TP_PATH . 'includes/admin/templates.php' );
    }

    /**
     * Template & Addons Store.
     * 
     * @since 2.0.0
     * @return void
     */
    public function store()
    {
        /**
         * Include store page.
         */
        include_once( TP_PATH . 'includes/admin/store.php' );
    }

    /**
     * Process installation, activation and deletion of addons.
     * 
     * @since 2.0.0
     * @return void
     */
    public function process_addons()
    {
        if ( $_POST ):
            /**
             * Install addons
             */
            if ( $_FILES && wp_verify_nonce($_POST['upload-addon-nonce'], 'upload-addon') ):
                TotalPoll('addons')->install();
            endif;

            if ( isset($_POST['addons']) && is_array($addons = $_POST['addons']) && wp_verify_nonce($_POST['manage-addons-nonce'], 'manage-addons') ):

                /**
                 * Activation
                 */
                if ( isset($_POST['activate']) ):
                    update_site_option('tp_addons', array_unique(array_merge($addons, TotalPoll('addons')->activated)));
                endif;

                /**
                 * Deactivation
                 */
                if ( isset($_POST['deactivate']) && !empty(TotalPoll('addons')->activated) ):
                    update_site_option('tp_addons', array_diff(TotalPoll('addons')->activated, $addons));
                endif;


                /**
                 * Delete
                 */
                if ( isset($_POST['delete']) ):
                    foreach ( $addons as $addon ):
                        TotalPoll('addons')->delete($addon);
                    endforeach;
                endif;


                // Refetch all activated addons
                TotalPoll('addons')->fetch_activated();

            endif;

        endif;
    }

    /**
     * Process installation and deletion of templates.
     * 
     * @since 2.0.0
     * @return void
     */
    public function process_templates()
    {
        if ( $_POST ):

            /**
             * Install templates
             */
            if ( $_FILES && wp_verify_nonce($_POST['upload-template-nonce'], 'upload-template') ):
                TotalPoll('template')->install();
            endif;

            /**
             * Delete templates
             */
            if ( isset($_POST['template']) && is_array($templates = $_POST['template']) && wp_verify_nonce($_POST['delete-template-nonce'], 'delete-template') ):

                foreach ( $templates as $template ):
                    TotalPoll('template')->delete($template);
                endforeach;

                // Refresh the templates list
                TotalPoll('template')->fetch();

            endif;
        endif;
    }

    /**
     * Process upgrade from 1.x
     * 
     * @since 2.0.0
     * @return void
     */
    public function process_upgrade()
    {
        if ( !empty($_POST['upgrade']) && is_array($_POST['upgrade']) && wp_verify_nonce($_POST['upgrade_polls_nonce'], plugin_basename(TP_ROOT_FILE)) ):

            foreach ( $_POST['upgrade'] as $poll_id ):

                $new_options = array(
                    'question' => '',
                    'choices' => array(),
                    'limitations' => array(
                        'revote' => array(),
                        'cookies' => array(),
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

                // Old settings
                $poll_security_layers = json_decode(json_encode(get_post_meta($poll_id, 'poll_security_layers', true)), true);
                $poll_security_extra = json_decode(json_encode(get_post_meta($poll_id, 'poll_security_extras', true)), true);
                $poll_content = json_decode(json_encode(get_post_meta($poll_id, 'poll_content', true)), true);
                $poll_options = json_decode(json_encode(get_post_meta($poll_id, 'poll_options', true)), true);
                $poll_design = json_decode(json_encode(get_post_meta($poll_id, 'poll_design', true)), true);

                if ( empty($poll_content) || empty($poll_security_layers) ):
                    break;
                endif;

                // Check template
                if ( isset(TotalPoll('template')->available[$poll_design['template_name']]) ):
                    $new_options['template']['name'] = $poll_design['template_name'];
                endif;

                // Question
                if ( isset($poll_content['question']) ):
                    $new_options['question'] = $poll_content['question'];
                endif;


                // Choices
                if ( !empty($poll_content['choices']) && is_array($poll_content['choices']) ):

                    foreach ( $poll_content['choices'] as $index => $choice ):

                        if ( isset($choice['text']) )
                            $type = 'text';
                        if ( isset($choice['image']) )
                            $type = 'image';
                        if ( isset($choice['embed']) )
                            $type = 'video';
                        if ( isset($choice['url']) )
                            $type = 'link';
                        if ( isset($choice['html']) )
                            $type = 'html';

                        $new_options['choices'][$index] = array();

                        // Votes
                        $new_options['choices'][$index]['votes'] = intval($choice['votes']);

                        // Text
                        if ( isset($choice['text']) ):
                            $new_options['choices'][$index]['type'] = 'text';
                            $new_options['choices'][$index]['text'] = $choice['text'];
                        endif;

                        // Images
                        if ( isset($choice['image']['url']) ):
                            $new_options['choices'][$index]['type'] = 'image';
                            $new_options['choices'][$index]['image'] = $choice['image']['url'];
                            $new_options['choices'][$index]['full'] = $choice['image']['url'];
                        endif;

                        // Links
                        if ( isset($choice['link']['url']) ):
                            $new_options['choices'][$index]['type'] = 'link';
                            $new_options['choices'][$index]['url'] = $choice['link']['url'];
                        endif;

                        // Videos
                        if ( isset($choice['video']['embed']) ):
                            $new_options['choices'][$index]['type'] = 'video';
                            $new_options['choices'][$index]['video'] = $choice['video']['embed'];
                            $new_options['choices'][$index]['thumbnail'] = '';
                        endif;

                        // HTML
                        if ( isset($choice['html']['html']) ):
                            $new_options['choices'][$index]['type'] = 'html';
                            $new_options['choices'][$index]['html'] = $choice['html']['html'];
                        endif;

                        // Labels
                        if ( is_array($choice[$new_options['choices'][$index]['type']]) && isset($choice[$new_options['choices'][$index]['type']]['label']) ):
                            $new_options['choices'][$index]['label'] = $choice[$new_options['choices'][$index]['type']]['label'];
                        endif;

                    endforeach;

                endif;

                // Limitations
                foreach ( array( 'session', 'cookies', 'ip', 'ip_range' ) as $layer ):
                    if ( isset($poll_security_layers[$layer]) ):
                        $new_options['limitations']['revote'][$layer] = true;
                    endif;
                endforeach;

                // Cookies
                $new_options['limitations']['cookies']['timeout'] = 15;

                // IP Timeout
                $new_options['limitations']['ip']['timeout'] = isset($poll_security_extra['ip_timeout'])
                            ? $poll_security_extra['ip_timeout'] : 15;

                // IP Range
                $new_options['limitations']['ip_range']['width'] = isset($poll_security_extras['ip_range_limit'])
                            ? $poll_security_extras['ip_range_limit'] : 5;
                $new_options['limitations']['ip_range']['limits'] = isset($poll_security_extras['ip_range_votes_limit'])
                            ? $poll_security_extras['ip_range_votes_limit'] : 5;

                // Quota
                $new_options['limitations']['revote']['quota'] = isset($poll_security_extras['votes_quota']) && $poll_security_extras['votes_quota'] != 0;
                $new_options['limitations']['quota'] = isset($poll_security_extras['votes_quota'])
                            ? $poll_security_extras['votes_quota'] : 0;

                // Date
                if ( isset($poll_security_extras['start_date']) || isset($poll_security_extras['end_date']) ):
                    $new_options['limitations']['revote']['date'] = true;

                    if ( isset($poll_security_extras['start_date']) ):
                        $new_options['limitations']['date']['start'] = $poll_security_extras['start_date'];
                        $new_options['limitations']['date']['start_timestamp'] = strtotime($poll_security_extras['start_date']);
                    endif;

                    if ( isset($poll_security_extras['end_date']) ):
                        $new_options['limitations']['date']['end'] = $poll_security_extras['end_date'];
                        $new_options['limitations']['date']['end_timestamp'] = strtotime($poll_security_extras['end_date']);
                    endif;

                endif;

                // Vote to see results
                if ( isset($poll_security_extras['results_visibility']) ):
                    $new_options['limitations']['vote_for_results'] = true;
                endif;

                // Multiselection
                if ( isset($poll_options['selection_type']) && $poll_options['selection_type'] == 2 ):
                    $new_options['limitations']['multiselection'] = true;
                endif;

                // Shuffle
                if ( isset($poll_options['shuffle_order']) ):
                    $new_options['misc']['shuffle'] = true;
                endif;

                // Display as
                if ( isset($poll_options['display_type']) ):
                    $aliases = array( '1' => 'number', '2' => 'percentage', '3' => 'both', '4' => 'nothing' );
                    $new_options['misc']['show_results'] = $aliases[$poll_options['display_type']];
                endif;

                // Sharing
                if ( isset($poll_content['sharing']) ):
                    $new_options['sharing']['show'] = true;
                endif;

                if ( isset($poll_content['twitter_exp']) ):
                    $new_options['sharing']['expressions']['twitter'] = str_replace(
                            array( '%QUESTION%', '%CURRENT_LINK%' ), array( '{{question}}', '{{link}}' ), $poll_content['twitter_exp']
                    );
                endif;

                if ( isset($poll_content['facebook_exp']) ):
                    $new_options['sharing']['expressions']['facebook'] = str_replace(
                            array( '%QUESTION%', '%CURRENT_LINK%' ), array( '{{question}}', '{{link}}' ), $poll_content['facebook_exp']
                    );
                endif;

                if ( isset($poll_content['gplus_exp']) ):
                    $new_options['sharing']['expressions']['googleplus'] = str_replace(
                            array( '%QUESTION%', '%CURRENT_LINK%' ), array( '{{question}}', '{{link}}' ), $poll_content['gplus_exp']
                    );
                endif;

                // Logs
                if ( isset($poll_content['logs_enabled']) ):
                    $new_options['logs'] = true;
                endif;

                // Set new settings
                update_post_meta($poll_id, '_tp_options', $new_options);

                // Delete old settings
                delete_post_meta($poll_id, 'poll_security_layers');
                delete_post_meta($poll_id, 'poll_security_extras');
                delete_post_meta($poll_id, 'poll_content');
                delete_post_meta($poll_id, 'poll_options');
                delete_post_meta($poll_id, 'poll_design');

            endforeach;
        endif;
    }

}
