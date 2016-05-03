<?php
/* Plugin Name: Editor Choice
 * Plugin URI: http://www.binarynote.com
 * Description: Ads a Widget to show Editor selected Articles from all your website
 * Version: 1.0
 * Author: Rakesh Kumar
 * Author URI: http://www.binarynote.com
 * License: GPL2 
 */defined('ABSPATH') or die("No script kiddies please!");
function post_choice($tagname = 'rakesh',$postcount = 20) {
query_posts(array('tag' => $tagname, 'showposts' => $postcount));
echo '<div style="margin-top:10px;margin-left:10px; margin-bottom:10px;">';
if (have_posts()) while (have_posts()) : the_post(); ?>
<ul>
	<li>	
		<a href="<?php the_permalink() ?>"><?php  the_title(); ?></a>
	</li>	
</ul>
<?php endwhile; 
echo '</div>';
}
add_action( 'widgets_init', 'rsa_editor_choice_load_widgets' );
function rsa_editor_choice_load_widgets() {
	register_widget( 'rsa_editor_choice');
}
class rsa_editor_choice extends WP_Widget {
	function rsa_editor_choice() {
		$widget_ops = array( 'classname' => 'rsa_editor_choice', 'description' => __('A widget to show Editor selected Post for the readers and OFF COURSE for SEARCH ENGINE.', 'choice') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'rsa-editor-choice-widget' );
		$this->WP_Widget( 'rsa-editor-choice-widget', __('editor_choice_Widget', 'rsa_editor_choice'), $widget_ops, $control_ops );
	}
function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$postcount = $instance['postcount'];
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		post_choice('rakesh',$postcount);
		echo $after_widget;
	}
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tagname'] = strip_tags( $new_instance['tagname'] );
		$instance['postcount'] = strip_tags( $new_instance['postcount'] );
		return $instance;
	}
function form( $instance ) {
	$defaults = array( 'title' => __('Editor Choice', 'Choice') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tagname' ); ?>"><?php _e('Tag Name to Search', 'Choice'); ?></label>
			<input id="<?php echo $this->get_field_id( 'tagname' ); ?>" name="<?php echo $this->get_field_name( 'tagname' ); ?>" value="<?php echo $instance['tagname']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('No of Post:', 'Random'); ?></label>
			<input id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}
?>