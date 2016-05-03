<?php
if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Poll Widget.
 * 
 * @since 2.0.0
 * @package TotalPoll\Widgets\Single
 */
if ( !class_exists('TP_Widget') ):

    Class TP_Widget extends WP_Widget {

        /**
         * Register widget with WordPress.
         * 
         * @since 2.0.0
         * @return void
         */
        public function __construct()
        {
            parent::__construct(
                    'totalpoll', // Base ID
                    __('Poll - TotalPoll', TP_TD), // Name
                    array( 'description' => __('Poll widget', TP_TD), ) // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         * @since 2.0.0
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         * @return void
         */
        public function widget($args, $instance)
        {
            if ( TotalPoll('poll')->load($instance['poll_id']) ):
                /**
                 * Filter widget title
                 * 
                 * @since 2.0.0
                 * @filter widget_title
                 * @param Widget title
                 */
                $title = apply_tp_filters('widget_title', $instance['title']);

                $args = apply_tp_filters('tp_widget_args', $args, $instance);

                echo $args['before_widget'];
                /**
                 * Before widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_before_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_before_content', $args, $instance);

                if ( !empty($title) ) {
                    echo $args['before_title'] . $title . $args['after_title'];
                }

                TotalPoll('poll')->render();
                /**
                 * After widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_after_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_after_content', $args, $instance);
                echo $args['after_widget'];
            endif;
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form($instance)
        {
            $defaults = array( 'title' => __('Poll', TP_TD), 'poll_id' => 0 );
            $instance = wp_parse_args($instance, $defaults);

            TotalPoll('poll')->load($instance['poll_id']);
            /**
             * Before widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_before_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_before_form', $instance, $this);
            ?>
            <p>
                <label><?php _e('Title:', TP_TD); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
                </label>
            </p>
            <p>
                <label for="poll_id"><?php _e('Poll', TP_TD); ?></label>
                <br>
                <select name="<?php echo $this->get_field_name('poll_id'); ?>" class="widefat">
                    <?php foreach ( (array) get_posts('post_type=poll&posts_per_page=-1') as $index => $poll ): ?>
                        <option value="<?php echo $poll->ID; ?>" <?php selected($instance['poll_id'], $poll->ID); ?>>
                            <?php echo $poll->post_title; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <?php
            /**
             * After widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_after_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_after_form', $instance, $this);
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();

            TotalPoll('poll')->load($instance['poll_id']);

            $instance['title'] = strip_tags($new_instance['title']);
            $instance['poll_id'] = (int) strip_tags($new_instance['poll_id']);
            /**
             * Update widget options
             * 
             * @since 2.0.0
             * @filter tp_widget_update
             * @param Instance
             * @param New instance
             * @param Old instance
             */
            return apply_tp_filters('tp_widget_update', $instance, $new_instance, $old_instance);
        }

    }

endif;

/**
 * Latest Poll Widget.
 * 
 * @since 2.7.0
 * @package TotalPoll\Widgets\Latest
 */
if ( !class_exists('TP_Latest_Widget') ):

    Class TP_Latest_Widget extends WP_Widget {

        /**
         * Register widget with WordPress.
         * 
         * @since 2.0.0
         * @return void
         */
        public function __construct()
        {
            parent::__construct(
                    'latest-totalpoll', // Base ID
                    __('Latest Poll - TotalPoll', TP_TD), // Name
                    array( 'description' => __('Poll widget', TP_TD), ) // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         * @since 2.0.0
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         * @return void
         */
        public function widget($args, $instance)
        {
            $poll = get_posts('post_type=poll&posts_per_page=1');
            if ( !empty($poll) && TotalPoll('poll')->load(current($poll)->ID) ):
                /**
                 * Filter widget title
                 * 
                 * @since 2.0.0
                 * @filter widget_title
                 * @param Widget title
                 */
                $title = apply_tp_filters('widget_title', $instance['title']);

                $args = apply_tp_filters('tp_widget_args', $args, $instance);

                echo $args['before_widget'];
                /**
                 * Before widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_before_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_before_content', $args, $instance);

                if ( !empty($title) ) {
                    echo $args['before_title'] . $title . $args['after_title'];
                }

                TotalPoll('poll')->render();
                /**
                 * After widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_after_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_after_content', $args, $instance);
                echo $args['after_widget'];
            endif;
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form($instance)
        {
            $defaults = array( 'title' => __('Poll', TP_TD), 'poll_id' => 0 );
            $instance = wp_parse_args($instance, $defaults);

            /**
             * Before widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_before_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_before_form', $instance, $this);
            ?>
            <p>
                <label><?php _e('Title:', TP_TD); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
                </label>
            </p>
            <?php
            /**
             * After widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_after_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_after_form', $instance, $this);
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();

            $instance['title'] = strip_tags($new_instance['title']);
            /**
             * Update widget options
             * 
             * @since 2.0.0
             * @filter tp_widget_update
             * @param Instance
             * @param New instance
             * @param Old instance
             */
            return apply_tp_filters('tp_widget_update', $instance, $new_instance, $old_instance);
        }

    }

endif;
/**
 * Random Poll Widget.
 * 
 * @since 2.7.0
 * @package TotalPoll\Widgets\Random
 */
if ( !class_exists('TP_Random_Widget') ):

    Class TP_Random_Widget extends WP_Widget {

        /**
         * Register widget with WordPress.
         * 
         * @since 2.0.0
         * @return void
         */
        public function __construct()
        {
            parent::__construct(
                    'random-totalpoll', // Base ID
                    __('Random Poll - TotalPoll', TP_TD), // Name
                    array( 'description' => __('Poll widget', TP_TD), ) // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         * @since 2.0.0
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         * @return void
         */
        public function widget($args, $instance)
        {
            $poll = get_posts('post_type=poll&posts_per_page=1&orderby=rand');
            if ( !empty($poll) && TotalPoll('poll')->load(current($poll)->ID) ):
                /**
                 * Filter widget title
                 * 
                 * @since 2.0.0
                 * @filter widget_title
                 * @param Widget title
                 */
                $title = apply_tp_filters('widget_title', $instance['title']);

                $args = apply_tp_filters('tp_widget_args', $args, $instance);

                echo $args['before_widget'];
                /**
                 * Before widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_before_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_before_content', $args, $instance);

                if ( !empty($title) ) {
                    echo $args['before_title'] . $title . $args['after_title'];
                }

                TotalPoll('poll')->render();
                /**
                 * After widget content
                 * 
                 * @since 2.0.0
                 * @action tp_widget_after_content
                 * @param Arguments
                 * @param Instance
                 */
                do_tp_action('tp_widget_after_content', $args, $instance);
                echo $args['after_widget'];
            endif;
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form($instance)
        {
            $defaults = array( 'title' => __('Poll', TP_TD), 'poll_id' => 0 );
            $instance = wp_parse_args($instance, $defaults);

            /**
             * Before widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_before_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_before_form', $instance, $this);
            ?>
            <p>
                <label><?php _e('Title:', TP_TD); ?>
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
                </label>
            </p>
            <?php
            /**
             * After widget form.
             * 
             * @since 2.0.0
             * @action tp_widget_after_form
             * @param Instance
             * @param Widget instance
             */
            do_tp_action('tp_widget_after_form', $instance, $this);
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();

            $instance['title'] = strip_tags($new_instance['title']);
            /**
             * Update widget options
             * 
             * @since 2.0.0
             * @filter tp_widget_update
             * @param Instance
             * @param New instance
             * @param Old instance
             */
            return apply_tp_filters('tp_widget_update', $instance, $new_instance, $old_instance);
        }

    }

    
endif;