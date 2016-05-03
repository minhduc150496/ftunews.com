<?php if (!defined('ABSPATH')) exit; // Shhh     ?>
<ul class="tp-results">
    <?php foreach (get_poll_choices() as $choice): ?>
        <li>
            <label>
                <div class="choice-content">
                    <?php if ($choice->type == 'text'): ?>
                        <?php echo $choice->text; ?>
                    <?php elseif ($choice->type == 'image'): ?>
                        <p><?php echo $choice->label; ?></p>
                        <img src="<?php echo $choice->image; ?>"/>
                    <?php elseif ($choice->type == 'video'): ?>
                        <p><?php echo $choice->label; ?></p>
                        <?php echo $choice->video; ?>
                    <?php elseif ($choice->type == 'link'): ?>
                        <a href="<?php echo $choice->url; ?>" target="_blank"><?php echo $choice->label; ?></a>
                    <?php elseif ($choice->type == 'html'): ?>
                        <?php echo $choice->html; ?>
                    <?php endif; ?>
                    <?php poll_choice_result_rendered($choice); ?>


                    <?php if (diplay_poll_results_as('number')): ?>
                        &nbsp;&bull;&nbsp;<?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?>
                    <?php elseif (diplay_poll_results_as('percentage')): ?>
                        &nbsp;&bull;&nbsp;<?php echo $choice->votes_percentage; ?>%
                    <?php elseif (diplay_poll_results_as('both')): ?>
                        &nbsp;&bull;&nbsp;<?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?> (<?php echo $choice->votes_percentage; ?>%)
                    <?php endif; ?>
                </div>
                <div class="votes-bar">
                    <div class="current-score" data-animate-width="<?php echo $choice->votes_percentage; ?>%"
                         data-animate-duration="<?php echo tp_preset_options('general', 'animationDuration'); ?>"></div>
                    <noscript>
                        <div class="current-score" style="width: <?php echo $choice->votes_percentage; ?>%"></div>
                    </noscript>
                </div>
            </label>
        </li>
    <?php endforeach; ?>
</ul>
<div class="tp-buttons">
    <?php if (display_poll_buttons()): ?>
        <form method="post">
            <?php other_poll_buttons(); ?>
            <input type="hidden" name="tp_poll_id" value="<?php echo get_poll_id(); ?>">
            <?php if (user_can_vote()): ?>
                <!-- button Back -->
                <button name="tp_action" value="back" class="tp-btn tp-back-btn"><?php _e('Back', TP_TD); ?></button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>