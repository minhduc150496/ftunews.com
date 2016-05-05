<?php if (!defined('ABSPATH')) exit; // Shhh      ?>

<div class="poll-body">
    <div class="container">
        <form method="post" id="myForm">
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
                                        <label>
                                            <input type="<?php echo is_poll_multianswer() ? 'checkbox' : 'radio'; ?>"
                                                   name="tp_choices<?php echo is_poll_multianswer() ? '[]' : ''; ?>"
                                                   value="<?php echo $choice->id ?>" <?php disabled(false, display_poll_buttons()); ?> />
                                                <i data-toggle="modal" data-target="#myModal" class="fa fa-heart-o"></i>
                                                <i data-toggle="modal" data-target="#myModal" class="fa fa-heart"></i>
                                        </label>
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
            </ul>

            <!-- Modal -->
            <div id="myModal" class="tp-buttons modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-body">
                            <div><b>Bạn có chắc chắn với quyết định của mình?</b></div>
                            <div style="font-style: italic">
                                (Lưu ý: Sau khi bấm nút "Có, tôi chắc chắn" bạn sẽ không thể bình chọn lại hoặc tiếp tục bình chọn trong hôm nay)
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="tp_poll_id" value="<?php echo get_poll_id(); ?>">
                            <button type="reset" class="btn btn-link" data-dismiss="modal" onclick="document.getElementById('myForm').reset()">Hủy</button>
                            <!-- button Vote -->
                            <button name="tp_action" value="vote"
                                    class="tp-btn tp-vote-btn tp-primary-btn btn btn-link"
                                    data-dismiss="modal">Có, tôi chắc chắn
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /Modal -->
        </form>
    </div>
</div>
