<?php if (!defined('ABSPATH')) exit; // Shhh     ?>
<div class="poll-body poll-result">
    <div class="container">
        <ul class="row tp-choices">
            <?php foreach (get_poll_choices() as $choice): ?>
                <li class="col-md-12">
                    <div class="choice-content row">
                        <div class="col-md-8">
                            <img width="100%" class="active"
                                 src="<?php echo $choice->image ?>">
                        </div>
                        <div class="col-md-4">
                            <div class="choice-info">
                                <h2 class="name"><?php echo $choice->label ?></h2>
                                <p>
                                    <?php echo $choice->html ?>
                                </p>
                                <p class="intro">
                                    <i data-toggle="modal" data-target="#myModal" class="fa fa-heart-o btn-vote-static"></i>
                                    <?php if (diplay_poll_results_as('number')): ?>
                                        <?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?>
                                    <?php elseif (diplay_poll_results_as('percentage')): ?>
                                        <?php echo $choice->votes_percentage; ?>%
                                    <?php elseif (diplay_poll_results_as('both')): ?>
                                        <?php printf(_n('%s Vote', '%s Votes', $choice->votes, TP_TD), $choice->votes); ?> (<?php echo $choice->votes_percentage; ?>%)
                                    <?php endif; ?>
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
        </ul>    </div>
</div>