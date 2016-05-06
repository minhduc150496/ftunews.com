<?php if (!defined('ABSPATH')) exit; // Shhh    ?>
<link href="<?php echo TP_ASSETS_URL ?>css/ftunews-poll.css" type="text/css" rel="stylesheet">

<div class="poll-header">
    <div class="ratio-wrapper">
        <div class="ratio-content top-image">
            <img width="100%" src="<?php echo TP_ASSETS_URL ?>images/girl.jpg">
            <div class="separator-1" style="width: 100%; overflow: hidden">
                <div style="margin: -5px;">
                    <img width="100%" height="auto" src="<?php echo TP_ASSETS_URL ?>images/separator-1.svg">
                </div>
            </div>
        </div>
    </div>
    <div class="rule">
        <?php $running = true;?>
        <?php if (is_poll_quota_exceeded()): ?>
            <div><?php _e('Closed due to the exceeded votes quota', TP_TD); ?></div>
        <?php $running = false;
        endif; ?>

        <?php if (!is_poll_started()): ?>
            <div><?php _e("This poll isn't started yet.", TP_TD); ?></div>
            <?php $running = false;
        endif; ?>

        <?php if (is_poll_finished()): ?>
            <div><?php _e('This poll has been closed.', TP_TD); ?></div>
            <?php $running = false;
        endif; ?>

        <?php if ($running):?>
        <h2>BÌNH CHỌN NỮ SINH DUYÊN DÁNG NHẤT ĐẠI HỌC NGOẠI THƯƠNG</h2>
        <div>
            Thể lệ:<br>
            Trải qua hai vòng thi sơ kết và bán kết cùng chuỗi hoạt động bên lề, cuộc thi đã tìm ra 16 gương mặt xinh
            xắn nhất sẽ khoe sắc, tranh tài trong đêm chung kết tại nhà hát Bến Thành vào ngày 3/5 sắp tới. Tuy vậy ngay
            từ bây giờ, teen đã có thể cùng tham gia bình chọn cho các thí sinh trên trang tin iOne.net. Hoạt động bình
            chọn sẽ bắt đầu từ hôm nay 26/4 đến 17h ngày 3/5/2012 teen nhé. Thí sinh nhận được nhiều vote nhất từ chính
            các bạn sẽ được trao danh hiệu Hoa khôi được yêu thích nhất trong đêm chung kết đấy! Duyên dáng Ngoại thương
            2012 được trang tin iOne.net và trang ftunews.com của trường Ngoại Thương bảo trợ thông tin.
        </div>
        <?php endif;?>
    </div>
    <div style="width: 100%; overflow: hidden">
        <div style="margin: -5px;">
            <img width="100%" height="auto" class="separator-2" src="<?php echo TP_ASSETS_URL ?>images/separator-2.svg">
        </div>
    </div>
</div>