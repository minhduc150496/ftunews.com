<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Templates & presets loader and manager.
 * 
 * @since 2.0.0
 * @package TotalPoll\Template
 */

Class TP_Template {

    /**
     * Available templates.
     * 
     * @since 2.0.0
     * @access public
     * @type array
     */
    public $available = array();

    /**
     * Available presets.
     * 
     * @since 2.0.0
     * @access public
     * @type array
     */
    public $presets;

    /**
     * Initializing.
     * 
     * @since 2.0.0
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Fetch templates.
     * 
     * @since 2.0.0
     * @return void
     */
    public function fetch($template = null)
    {
        // Fetch all template
        if ( is_null($template) ):

            $templates = glob(TP_TEMPLATES_PATH . '*', GLOB_ONLYDIR);
            foreach ( $templates as $template_dir ):
                $template_name = basename($template_dir);
                $this->fetch($template_name);
            endforeach;

            return $this->available;
        endif;

        // Return if exists
        if ( isset($this->available[$template]) ):
            return $this->available[$template];
        endif;

        // Load template (just when needed)
        if ( !$this->presets ):
            $this->presets = ( object ) json_decode(json_encode(get_option('tp_presets', array())), false);
        endif;

        // Template headers defaults
        $defaults = array(
            'name' => 'Template Name',
            'website' => 'Template URI',
            'version' => 'Version',
            'description' => 'Description',
            'author' => 'Author',
            'authorURI' => 'Author URI'
        );

        $template_dir = TP_TEMPLATES_PATH . DS . $template;
        $stylesheet_file = $template_dir . DS . 'style.css';
        $settings_file = $template_dir . DS . 'settings.php';
        if ( file_exists($stylesheet_file) ):

            $template_info = get_file_data($stylesheet_file, $defaults);
            $this->available[$template] = ( object ) $template_info;
            $this->available[$template]->presets = array( 'default' => array() );

            // Init preset object
            if ( !isset($this->presets->{$template}) ):
                $this->presets->{$template} = new stdClass();
            endif;

            // Set a default preset
            if ( !isset($this->presets->{$template}->default) ):
                $this->presets->{$template}->default = new stdClass();
            endif;

            // Settings
            if ( file_exists($settings_file) ):
                /**
                 * Include template's settings.php file.
                 */
                include( $settings_file );
                if ( isset($settings) ):
                    $this->available[$template]->settings = ( array ) $settings;
                    unset($settings);
                endif;

                if ( isset($presets) && !empty($presets) && is_array($presets) ):

                    $this->available[$template]->presets = array_merge(( array ) $presets, $this->available[$template]->presets);
                    foreach ( $presets as $id => $preset ):

                        if ( !isset($this->presets->{$template}->$id) ):
                            $this->presets->{$template}->$id = ( object ) json_decode(json_encode($preset), false);
                        endif;

                    endforeach;
                    unset($presets);

                endif;

            endif;
        else:
            return false;
        endif;

        return $this->available[$template];
    }

    /**
     * Load template functionalities and settings.
     * 
     * @since 2.0.0
     * @global object $poll Poll object
     * @return void
     */
    public function load()
    {
        global $poll;

        if ( is_object($poll) ):

            // Play nice when template does not exists
            if ( !$this->fetch($poll->template->name) ):
                $this->fetch('default');
                $poll->template->name = 'default';
            endif;

            // Or preset does not exists
            if ( !isset($this->presets->{$poll->template->name}->{$poll->template->preset->name}) ):
                $poll->template->preset->name = 'default';
            endif;

            // Preset settings
            $preset = ( object ) $this->presets->{$poll->template->name}->{$poll->template->preset->name};
            $poll->template->preset->settings = $preset;

            $this->load_functions($poll->template->name);

            // Set settings
            if ( isset($this->fetch($poll->template->name)->settings) ):
                $poll->template->settings = $this->fetch($poll->template->name)->settings;
            endif;
            // Set presets
            if ( isset($this->fetch($poll->template->name)->presets) ):
                $poll->template->presets = $this->fetch($poll->template->name)->presets;
            endif;


        endif;
    }

    /**
     * Get Stylesheet.
     * 
     * @since 2.0.0
     * @global object $poll;
     * @global array $global_blocks;
     * @return string
     */
    public function get_css()
    {
        return;
        global $poll;
        // Replace dot notation
        $css = tp_minify_css($this->get_part('style.css'));
        $css = preg_replace_callback("/\{\{(.*?)\}\}/sim", array( $this, 'replace_variables' ), $css);

        global $global_blocks;
        $global_blocks = array();

        // A workaround for @global blocks
        $css = preg_replace_callback('/@global\s*\{(.*?\})\s*\}/sim', array( $this, 'tokeniz_globals' ), $css);

        // Prefix selectors with preset name
        $css = preg_replace_callback("/([^\r\n,{}]+)(,(?=[^}]*{)|\s*{)/sim", array( $this, 'prefix_selectors' ), $css);

        // Restore omitted @global blocks
        $css = preg_replace_callback('/\{\{global\(([0-9]+)\)\}\}/sim', array( $this, 'restore_globals' ), $css);
        unset($global_blocks);

        // Fix urls in css
        $css = preg_replace_callback("/url\(['\"\s]*+(?!\/\/)([^):]*?)['\"\s]*+\)/sim", array( $this, 'fix_css_urls' ), $css);
        /**
         * Get css.
         * 
         * @since 2.0.0
         * @filter tp_template_get_css
         * @param Css
         */
        return apply_tp_filters('tp_template_get_css', $css);
    }

    /**
     * Fix CSS Urls.
     * 
     * @since 2.0.0
     * @param array $match
     * @return string
     */
    public function fix_css_urls($match)
    {
        $url = tp_get_template_url($match[1]);
        return sprintf('url(%s)', $url);
    }

    /**
     * Replace @global blocks with a token to avoid prefixing.
     * 
     * @since 2.0.0
     * @global array $global_blocks
     * @param array $match
     * @return string
     */
    public function tokeniz_globals($match)
    {
        global $global_blocks;
        $global_blocks[] = $match[1];

        return sprintf('{{global(%s)}}', count($global_blocks) - 1);
    }

    /**
     * Resotre omitted @global blocks.
     * 
     * @since 2.0.0
     * @global array $global_blocks
     * @param array $match
     * @return string
     */
    public function restore_globals($match)
    {
        global $global_blocks;

        if ( isset($match[1]) && isset($global_blocks[$match[1]]) ):
            return $global_blocks[$match[1]];
        endif;

        return;
    }

    /**
     * Replace dot notation variables.
     * 
     * @since 2.0.0
     * @global object $poll
     * @param array $match
     * @return string
     */
    public function replace_variables($match, $default = 'inherit')
    {
        // Exploding the expression
        $section_field = ( array ) explode('.', $match[1]);

        // Get section
        $section = $section_field[0];

        if ( isset($section_field[1]) ) :
            // Get field and states
            if ( preg_match("/^([^\[:]+):([^\[]+)/", $section_field[1], $field_states) ):
                $field = $field_states[1];
                $states = $field_states[2];
            else:
                preg_match("/^([^\[:]+)/", $section_field[1], $field_match);
                $field = $field_match[1];
            endif;

            preg_match_all('/\[([^\]]+)\]/', $section_field[1], $arrays_matches);
            if ( !empty($arrays_matches[1]) ):
                $arrays = $arrays_matches[1];
            endif;
        endif;

        if ( !isset($field) )
            $field = false;

        if ( !isset($states) )
            $states = false;

        if ( !isset($arrays) )
            $arrays = false;
        return $this->retrieve_options($section, $field, $states, $arrays, $default);
    }

    /*
     * Retrieves a preset option or its default as a fallback.
     * 
     * @since 2.0.0
     * @global object $poll
     * @param string $section
     * @param bool|string $field
     * @param bool|string $states
     * @param bool|array $arrays
     * @param mixed $default
     * @return mixed
     */

    public function retrieve_options($section, $field = false, $states = false, $arrays = false, $default = false)
    {
        global $poll;
        // Define a start path (which is the preset settings object)
        $full_path = $poll->template->preset->settings;
        $default_path = $this->fetch($poll->template->name)->settings;
        $default_way = false;
        // Lets Drive!
        // Section
        if ( isset($full_path->{$section}) ):
            $option = $full_path->{$section};
        elseif ( isset($default_path['sections'][$section]) ):
            $option = $default_path['sections'][$section];
            $default_way = true;
        else:
            $option = $default;
        endif;

        if ( $field && $option !== $default ):
            // Field and states
            if ( $states ):
                if ( isset($option->{$field . ':' . $states}) && !$default_way ):
                    $option = $option->{$field . ':' . $states};
                else:
                    if ( !$default_way )
                        $option = isset($default_path['sections'][$section]) ? $default_path['sections'][$section]
                                    : $default;

                    $option = $option !== $default && isset($option['fields'][$field]['states'][$states])
                                ? $option['fields'][$field]['states'][$states] : $default;
                    $default_way = true;
                endif;
            else:
                if ( isset($option->{$field}) && !$default_way ):
                    $option = $option->{$field};
                else:
                    if ( !$default_way )
                        $option = isset($default_path['sections'][$section]) ? $default_path['sections'][$section]
                                    : $default;

                    $option = $option !== $default && isset($option['fields'][$field])
                                ? $option['fields'][$field] : $default;
                    $default_way = true;
                endif;
            endif;

            // Field and states with arrays
            if ( $arrays !== false && $option !== $default ):
                // Array check safety
                if ( is_array($arrays) ):
                    foreach ( $arrays as $element ):
                        if ( !is_array($option) || !array_key_exists($element, $option) ):
                            $option = $default;
                            continue;
                        else:
                            $option = $option[$element];
                        endif;
                    endforeach;
                else:
                    if ( isset($option[$arrays]) ):
                        $option = $option[$arrays];
                    else:
                        $option = $default;
                    endif;
                endif;
            endif;

            // Get the default key
            if ( !$default_way ):
                $option = $option;
            elseif ( is_array($option) && array_key_exists('default', $option) && $option !== $default ):
                $option = $option['default'];
            else:
                $option = $default;
            endif;
        endif;

        if ( is_string($option) ):
            $option = stripslashes($option);
        endif;

        return $option;
    }

    /**
     * Prefix CSS selectors.
     * 
     * @since 2.0.0
     * @global $poll
     * @param array $match
     * @return string
     */
    private function prefix_selectors($match)
    {
        global $poll;

        /**
         * Ignore some selectors from beign prefixed.
         * 
         * @since 2.0.0
         * @filter tp_template_prefix_ignore
         * @param Array of expressions
         * @param Selector
         */
        $ignored = array( '@media' => $match[0] );
        foreach ( $ignored as $key => $new ):
            if ( strstr($match[0], $key) !== false )
                return $new;
        endforeach;

        $id = "#tp-{$poll->template->name}-{$poll->template->preset->name}-preset {$match[0]}";

        /**
         * Ignore some selectors from beign prefixed
         * 
         * @since 2.0.0
         * @filter tp_template_prefix_id
         * @param Prefixed selector
         * @param Original selector
         */
        return $id;
    }

    /**
     * Load functions.php file.
     * 
     * @since 2.0.0
     * @param type $template Template name
     * @return void
     */
    public function load_functions($template)
    {

        // Files to load
        $functions_file = TP_TEMPLATES_PATH . basename($template) . DS . 'functions.php';

        if ( file_exists($functions_file) ):
            /**
             * Include functions.php file.
             */
            include_once( $functions_file );
        endif;
    }

    /**
     * Load a template file.
     * 
     * @since 2.0.0
     * @global object $poll
     * @param $filename
     * @param array $variables
     * @return void
     */
    public function load_part($filename, $variables = array())
    {
        global $poll;
        if ( file_exists($path = TP_TEMPLATES_PATH . $poll->template->name . DS . $filename) ):
            /**
             * Template file path
             * 
             * @since 2.0.0
             * @filter tp_template_load_part_path
             * @param Path
             * @param Filename
             */
            $path = apply_tp_filters('tp_template_load_part_path', $path, $filename);
            /**
             * Passed variables to template file
             * 
             * @since 2.0.0
             * @filter tp_template_load_part_variables
             * @param Variables
             */
            $variables = apply_tp_filters('tp_template_load_part_variables', $variables);

            extract($variables);
            include($path);
        endif;
    }

    /**
     * Get a template file content.
     * 
     * @since 2.0.0
     * @param string $filename
     * @param array $variables
     * @return string
     */
    public function get_part($filename, $variables = array())
    {
        ob_start();
        $this->load_part($filename, $variables);
        /**
         * Retrieve content from buffer and mifify it
         * 
         * @since 2.0.0
         * @filter tp_template_get_part_$filename
         * @param Content of file
         * @param Variables
         */
        return apply_tp_filters("tp_template_get_part_$filename", tp_minify_html(ob_get_clean()), $variables);
    }

    /**
     * Save preset.
     * 
     * @since 2.0.0
     * @param string $template
     * @param string $name
     * @param array $settings
     * @param bool $new
     * @return string
     */
    public function save_preset($template, $name, $settings, $new = false)
    {
        if ( $new ):
            // Base name
            $name = str_replace('-preset-', '', urldecode(sanitize_title_with_dashes($name)));
            // Alternative name
            if ( empty($name) ):
                $name = 'default-' . time();
            elseif ( isset($this->presets->{$template}->{$name}) ):
                $name = $name . '-' . time();
            endif;

        endif;
        // Update presets
        $this->presets->{$template}->{$name} = ( array ) $settings;
        update_option('tp_presets', $this->presets);

        return $name;
    }

    /**
     * Delete preset.
     * 
     * @since 2.0.0
     * @param type $template
     * @param type $name
     * @return bool
     */
    public function delete_preset($template, $name)
    {
        unset($this->presets->{$template}->{$name});
        return update_option('tp_presets', $this->presets);
    }

    /**
     * Install template.
     * 
     * @since 2.0.0
     * @return void
     */
    public function install()
    {

        TotalPoll('admin')->include_installer();

        $file_upload = new File_Upload_Upgrader('templatezip', 'package');

        $title = __('Upload Template', TP_TD);
        $parent_file = 'edit.php?post_type=poll';
        $submenu_file = 'edit.php?post_type=poll&page=tp-templates-manager';

        $title = sprintf(__('Installing Template from uploaded file: %s', TP_TD), esc_html(basename($file_upload->filename)));
        $nonce = 'upload-template';
        $url = add_query_arg(array( 'package' => $file_upload->id ), 'edit.php?post_type=poll&page=tp-templates-manager');
        $type = 'upload';

        $upgrader = new TP_Installer(new TP_Installer_Skin(compact('type', 'title', 'nonce', 'url')));
        $result = $upgrader->install($file_upload->package);

        if ( $result || is_wp_error($result) )
            $file_upload->cleanup();

        exit;
    }

    /**
     * Delete template.
     * 
     * @since 2.0.0
     * @global instance $wp_filesystem
     * @param string $stylesheet
     * @param string $redirect
     * @return \WP_Error|boolean
     */
    public function delete($stylesheet, $redirect = '')
    {
        global $wp_filesystem;

        if ( empty($stylesheet) )
            return false;

        ob_start();
        if ( empty($redirect) )
            $redirect = wp_nonce_url('edit.php?post_type=poll&page=tp-templates-manager', 'delete-template_' . $stylesheet);
        if ( false === ($credentials = request_filesystem_credentials($redirect)) ) {
            $data = ob_get_contents();
            ob_end_clean();
            if ( !empty($data) ) {
                include_once( ABSPATH . 'wp-admin/admin-header.php');
                echo $data;
                include( ABSPATH . 'wp-admin/admin-footer.php');
                exit;
            }
            return;
        }

        if ( !WP_Filesystem($credentials) ) {
            request_filesystem_credentials($redirect, '', true); // Failed to connect, Error and request again
            $data = ob_get_contents();
            ob_end_clean();
            if ( !empty($data) ) {
                include_once( ABSPATH . 'wp-admin/admin-header.php');
                echo $data;
                include( ABSPATH . 'wp-admin/admin-footer.php');
                exit;
            }
            return;
        }

        if ( !is_object($wp_filesystem) )
            return new WP_Error('fs_unavailable', __('Could not access filesystem.', TP_TD));

        if ( is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->get_error_code() )
            return new WP_Error('fs_error', __('Filesystem error.', TP_TD), $wp_filesystem->errors);

        //Get the base templates folder
        $templates_dir = TP_TEMPLATES_PATH;
        if ( empty($templates_dir) )
            return new WP_Error('fs_no_templates_dir', __('Unable to locate TotalPoll templates directory.', TP_TD));

        $templates_dir = trailingslashit($templates_dir);
        $template_dir = trailingslashit($templates_dir . $stylesheet);
        $deleted = $wp_filesystem->delete($template_dir, true);

        if ( !$deleted )
            return new WP_Error('could_not_remove_template', sprintf(__('Could not fully remove the template %s.', TP_TD), $stylesheet));

        return true;
    }

}
