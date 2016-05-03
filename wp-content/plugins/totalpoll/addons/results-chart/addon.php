<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Results chart meta box
      Description: Fancy results chart meta box
      Addon URI: http://wpsto.re/addons/downloads/results-chart
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Shortcode choice addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\ResultsChart
 */
Class TP_Results_Chart {

    /**
     * Register some hooks.
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Text domain
        load_plugin_textdomain('tp-results-chart', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Assets
        add_action('tp_admin_editor_enqueue_scripts', array( $this, 'enqueue_assets' ));
        // Metabox
        add_action('add_meta_boxes', array( $this, 'metabox' ));
    }

    /**
     * Enqueue assets.
     * 
     * @since 1.0.0
     * @return void
     */
    public function enqueue_assets()
    {
        wp_enqueue_script('chart-js', TP_ADDONS_URL . basename(dirname(__FILE__)) . '/chart.min.js', array( 'jquery' ), TP_VERSION);
    }

    /**
     * Register metabox
     * 
     * @since 1.0.0
     * @return void
     */
    public function metabox($post_type)
    {
        if ( $post_type == 'poll' ):
            add_meta_box(
                    'tp_results_chart', __('Results', 'tp-results-chart'), array( $this, 'render' ), $post_type, 'side', 'core'
            );
        endif;
    }

    /**
     * Render metabox
     * 
     * @since 1.0.0
     * @return void
     */
    public function render()
    {
        require_once('metabox.php');
    }

}

// Bootstrap
new TP_Results_Chart();
