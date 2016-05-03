<?php
if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Pager
      Description: Simple pagination for huge polls.
      Addon URI: http://wpsto.re/addons/downloads/pager/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Pagination addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\Pager
 */
Class TP_Pager_Addon {

    /**
     * Register some hooks.
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Text domain
        load_plugin_textdomain('tp-pager-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // Options
        add_action('tp_admin_editor_after_misc_content', array( $this, 'settings' ));
        add_filter('tp_admin_editor_save_poll_options', array( $this, 'save' ));

        // Slice, Css& Buttons hooks
        add_filter('tp_template_get_part_header.php', array( $this, 'slice' ));
        add_filter('tp_template_get_part_style.css', array( $this, 'css' ));

        // Prev & Next Ajax Load
        add_action('tp_capture_ajax_next_page', array( $this, 'ajax_page' ));
        add_action('tp_capture_ajax_prev_page', array( $this, 'ajax_page' ));

        // Prev & Next Normal Load
        add_action('tp_capture_post_next_page', array( $this, 'page' ));
        add_action('tp_capture_post_prev_page', array( $this, 'page' ));
    }

    /**
     * Pagination buttons
     * 
     * @global object $poll
     * @return void
     */
    public function buttons()
    {
        global $poll;
        ?>
        <div class="tp-pagination">

            <input type="hidden" name="tp_page" value="<?php echo esc_attr($poll->current_page); ?>">
            <?php
            if ( (isset($_REQUEST['tp_choices']) && $_REQUEST['tp_previous_choices'] = $_REQUEST['tp_choices']) || isset($_REQUEST['tp_previous_choices']) ):
                $previous_choices = implode(',', (array) $_REQUEST['tp_previous_choices']);
                $previous_choices_input_id = md5($previous_choices);
            ?>
                <input id="<?php echo $previous_choices_input_id; ?>" type="hidden" name="tp_previous_choices" value="<?php echo esc_attr($previous_choices); ?>">
                <script type="text/javascript">
                    (function(){
                        var choices = jQuery('#<?php echo $previous_choices_input_id ?>').val().split(',');
                        jQuery.each(choices, function(){
                            jQuery('input[name^="tp_choices"][value="' + this + '"]').attr('checked', '');
                        });
                    })();
                </script>
            <?php endif; ?>
            <input type="hidden" name="tp_showing" value="<?php echo isset($poll->showing_results) ? 'results' : 'vote';
            ?>">
            <button type="submit" class="tp-btn tp-primary-btn tp-pagination-prev" name="tp_action" value="prev_page" <?php disabled(false, isset($poll->prev_page) && $poll->prev_page) ?>>
                <?php _e('Previous page', 'tp-pager-addon'); ?>
            </button>
            <?php
            if ( isset($poll->misc->display_pages) ):
                for ( $page = 1; $page <= $poll->total_pages; $page++ ):
                    ?>
                    <button type="submit" class="tp-btn" name="tp_action" value="next_page" <?php disabled($poll->current_page, $page); ?> onclick="jQuery(this).parent().find('[name=\'tp_page\']').val(<?php echo $page - 1; ?>)">
                        <?php echo $page; ?>
                    </button>
                    <?php
                endfor;
            endif;
            ?>
            <button type="submit" class="tp-btn tp-primary-btn tp-pagination-next" name="tp_action" value="next_page" <?php disabled(false, isset($poll->next_page) && $poll->next_page) ?>>
                <?php _e('Next page', 'tp-pager-addon'); ?>
            </button>
        </div>
        <?php
    }

    /**
     * Pagination CSS.
     * 
     * @since 1.0.0
     * @param string $css
     * @return string
     */
    public function css($css)
    {
        ob_start();
        ?>
        .tp-pagination {display: block;text-align: center;margin: 1em 0;padding: 1em;border: 1px solid {{buttons.borderColor}};}
        .tp-pagination:after {content: "";display: table;clear: both;}
        .tp-pagination .tp-pagination-next {float: right;margin-left: 0;}
        .tp-pagination .tp-pagination-prev {float: left;margin-left: 0;}
        <?php
        return $css . ob_get_clean();
    }

    /**
     * Slice choices
     * 
     * @since 1.0.0
     * @global object $poll
     * @param string $content
     * @return string
     */
    public function slice($content)
    {
        global $poll;
        if ( isset($poll->misc->pagination) ):

            $poll->choices = get_poll_choices();

            $poll->total_pages = ceil(count($poll->choices) / $poll->misc->per_page);
            $poll->current_page = 1;

            if ( $poll->total_pages > 1 ):

                add_action_to_current_poll('tp_poll_other_buttons', array( $this, 'buttons' ));

                $requested = isset($_REQUEST['tp_page']) ? intval($_REQUEST['tp_page']) : 1;

                $requested = isset($_REQUEST['tp_action']) &&
                        $_REQUEST['tp_action'] == 'prev_page' ? $requested - 1 : $requested;
                $requested = isset($_REQUEST['tp_action']) &&
                        $_REQUEST['tp_action'] == 'next_page' ? $requested + 1 : $requested;

                if ( $requested > 0 && $requested <= $poll->total_pages ):
                    $poll->current_page = $requested;
                endif;

                $poll->next_page = $poll->current_page + 1 <= $poll->total_pages;
                $poll->prev_page = $poll->current_page - 1 != 0 &&
                        $poll->current_page - 1 <= $poll->total_pages;

                $poll->choices = array_slice($poll->choices, $poll->current_page == 1 ? 0 :
                                ($poll->current_page - 1) * $poll->misc->per_page, $poll->misc->per_page);

            endif;

        endif;
        return $content;
    }

    /**
     * Load Page.
     * 
     * @since 1.0.0
     * @global object $poll
     */
    public function page()
    {
        global $poll;
        TotalPoll('poll')->load($_REQUEST['tp_poll_id']);
        if ( isset($_REQUEST['tp_showing']) && $_REQUEST['tp_showing'] == 'results' ):
            $poll->skip_to_results = is_poll_started() && !is_poll_results_locked();
        endif;
    }

    /**
     * Ajax loaded page.
     * 
     * @since 1.0.0
     * @return void
     */
    public function ajax_page()
    {
        $this->page();
        exit(TotalPoll('poll')->get_render(true));
    }

    /**
     * Settings page.
     * 
     * @since 1.0.0
     * @return void
     */
    public function settings($options)
    {
        /**
         * Include settings
         */
        include_once('poll-settings.php');
    }

    /**
     * Save settings checks.
     * 
     * @since 1.0.0
     * @param array $options
     * @return array
     */
    public function save($options)
    {
        // Check pagination settings before saving
        if ( isset($options['misc']['pagination']) ):
            $options['misc']['per_page'] = intval($options['misc']['per_page']) <= 0 ? 5 : intval($options['misc']['per_page']);
        endif;

        return $options;
    }

}

// Bootstrap it
new TP_Pager_Addon();
