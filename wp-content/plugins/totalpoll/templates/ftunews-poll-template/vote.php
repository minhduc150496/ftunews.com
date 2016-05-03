<?php if (!defined('ABSPATH')) exit; // Shhh      ?>

<div class="poll-body">
    <div class="container">
        <form method="post">
            <ul class="row tp-choices">
                <?php foreach (get_poll_choices() as $choice): ?>
                    <li class="col-md-12">
                        <div class="choice-content row">
                            <div class="col-md-8">
                                <label>
                                    <input type="<?php echo is_poll_multianswer() ? 'checkbox' : 'radio'; ?>"
                                           name="tp_choices<?php echo is_poll_multianswer() ? '[]' : ''; ?>"
                                           value="<?php echo $choice->id ?>" <?php disabled(false, display_poll_buttons()); ?> />
                                    <img width="100%" class="active"
                                         src="<?php echo $choice->image ?>">
                                </label>
                            </div>
                            <div class="col-md-4" style="">
                                <div>
                                    <h2><?php echo $choice->label ?></h2>
                                    <p>
                                        <?php if (diplay_poll_results_as('number')): ?>
                                            &nbsp;&bull;&nbsp;<?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?>
                                        <?php elseif (diplay_poll_results_as('percentage')): ?>
                                            &nbsp;&bull;&nbsp;<?php echo $choice->votes_percentage; ?>%
                                        <?php elseif (diplay_poll_results_as('both')): ?>
                                            &nbsp;&bull;&nbsp;<?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?> (<?php echo $choice->votes_percentage; ?>%)
                                        <?php endif; ?>
                                    </p>
                                    <p>
                                        <?php echo $choice->html ?>
                                    </p>
                                </div>
                            </div>
                            <?php poll_choice_vote_rendered($choice); ?>
                        </div>
                        <!--
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
                    <?php poll_choice_vote_rendered($choice); ?>
                </div>
                -->
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="tp-buttons">
                <input type="hidden" name="tp_poll_id" value="<?php echo get_poll_id(); ?>">
                <?php if (display_poll_buttons()): ?>
                    <?php other_poll_buttons(); ?>
                    <?php if (is_poll_results_locked()): ?>

                        <!-- button -->
                        <button name="tp_action" value="vote" class="tp-btn tp-btn-disabled"
                                disabled=""><?php _e('Vote to see results', TP_TD); ?></button>

                    <?php endif; ?>

                    <!-- button Vote -->
                    <button name="tp_action" value="vote"
                            class="tp-btn tp-vote-btn tp-primary-btn btn btn-success"><?php _e('Vote', TP_TD); ?></button>

                <?php endif; ?>
            </div>
        </form>
    </div>
</div>