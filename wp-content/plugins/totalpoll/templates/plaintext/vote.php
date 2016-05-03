<?php if ( !defined('ABSPATH') ) exit; // Shhh       ?>
<form method="post">
    <ul class="tp-choices">
	<?php foreach ( get_poll_choices() as $choice ): ?>
    	<li>
    	    <label>
    		<div class="input">
    		    <input type="<?php echo is_poll_multianswer() ? 'checkbox' : 'radio'; ?>" name="tp_choices<?php echo is_poll_multianswer() ? '[]' : ''; ?>" value="<?php echo $choice->id ?>" <?php disabled(false, display_poll_buttons()); ?> />
    		</div>
    		<div class="choice-content">
		    <?php if ( $choice->type == 'text' ): ?>
			<?php echo $choice->text; ?>
		    <?php elseif ( $choice->type == 'image' ): ?>
			<p><?php echo $choice->label; ?></p>
			<img src="<?php echo $choice->image; ?>" />
		    <?php elseif ( $choice->type == 'video' ): ?>
			<p><?php echo $choice->label; ?></p>
			<?php echo $choice->video; ?>
		    <?php elseif ( $choice->type == 'link' ): ?>
			<a href="<?php echo $choice->url; ?>" target="_blank"><?php echo $choice->label; ?></a>
		    <?php elseif ( $choice->type == 'html' ): ?>
			<?php echo $choice->html; ?>
		    <?php endif; ?>
		    <?php poll_choice_vote_rendered($choice); ?>
    		</div>
    	    </label>
    	</li>
	<?php endforeach; ?>
    </ul>
    <div class="tp-buttons">
        <input type="hidden" name="tp_poll_id" value="<?php echo get_poll_id(); ?>">
	<?php if ( display_poll_buttons() ): ?>
	    <?php other_poll_buttons(); ?>
	    <?php if ( is_poll_results_locked() ): ?>
		<button name="tp_action" value="vote" class="tp-btn tp-btn-disabled" disabled=""><?php _e('Vote to see results', TP_TD); ?></button>
	    <?php else: ?>
		<button name="tp_action" value="results" class="tp-btn tp-results-btn"><?php _e('Results', TP_TD); ?></button>
	    <?php endif; ?>
    	<button name="tp_action" value="vote" class="tp-btn tp-vote-btn tp-primary-btn"><?php _e('Vote', TP_TD); ?></button>
	<?php endif; ?>
    </div>
</form>