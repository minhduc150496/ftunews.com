<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Question image
      Description: Add an image with your question easily.
      Addon URI: http://wpsto.re/addons/downloads/question-image
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Bulk Image Insertion.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\QuestionImage
 */
Class TP_Question_Image_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Textdomain
        load_plugin_textdomain('tp-qi-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Button
        add_action('tp_admin_editor_after_question_content', array( $this, 'field' ));
        // Assets
        add_action('tp_admin_editor_enqueue_scripts', array( $this, 'assets' ));
        // Image
        add_filter('tp_template_get_part_header.php', array( $this, 'image' ));
    }

    /**
     * Enqueue assets.
     * 
     * @since 1.0.0
     * @return void
     */
    public function assets()
    {
        wp_enqueue_media();
        wp_enqueue_script('tp-qi-addon', TP_ADDONS_URL . basename(dirname(__FILE__)) . '/question-image.min.js', array( 'jquery' ), TP_VERSION);
    }

    /**
     * Button.
     * 
     * @since 1.0.0
     * @return void
     */
    public function field($options)
    {
        include_once('insert.php');
    }

    /**
     * Image.
     * 
     * @since 1.0.0
     * @param string $content
     * @return string
     */
    public function image($content)
    {
        global $poll;

        if ( !empty($poll->question_image) ):
            $content = sprintf('%s<img src="%s" style="display: block;margin-right: auto;margin-left: auto;">', $content, esc_attr($poll->question_image));
        endif;

        return $content;
    }

}

// Bootstrap
new TP_Question_Image_Addon();
