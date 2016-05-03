<?php if ( !defined('ABSPATH') ) exit; // Shhh    ?>
<?php if ( is_poll_quota_exceeded() ): ?>
    <p class="tp-warning"><?php _e('Closed due to the exceeded votes quota', TP_TD); ?></p>
<?php endif; ?>

<?php if ( !is_poll_started() ): ?>
    <p class="tp-warning"><?php _e("This poll isn't started yet.", TP_TD); ?></p>
<?php endif; ?>

<?php if ( is_poll_finished() ): ?>
    <p class="tp-warning"><?php _e('This poll has been closed.', TP_TD); ?></p>
<?php endif; ?>

<h3 class="tp-question"><?php the_poll_question(); ?></h3>