<?php if (!defined('ABSPATH')) exit; // Shhh    ?>
<link href="<?php echo TP_ASSETS_URL ?>css/ftunews-poll.css" type="text/css" rel="stylesheet">

<div class="poll-header">
    <div class="ratio-wrapper">
        <div class="ratio-content top-image">
            <img width="100%" style="margin-top: -18%" src="<?php echo TP_ASSETS_URL ?>images/girl.jpg">
            <div class="separator-1" style="width: 100%; overflow: hidden">
                <div style="margin: -0.1%;">
                    <img width="100%" height="auto"
                         src="<?php echo TP_ASSETS_URL ?>images/separator-1.svg">
                </div>
            </div>
        </div>
    </div>
    <div class="rule">


        <h2>BÌNH CHỌN HOA KHÔI ĐƯỢC YÊU THÍCH NHẤT FTUCHARM 2016</h2>
        <div style='text-align: justify;'>
            <p>Nhằm tìm ra và tôn vinh gương mặt Top 15 nhận được sự quan tâm và yêu thích nhất,
                FTUNEWS phối hợp cùng BTC cuộc thi Duyên dáng Ngoại thương –
                FTUCharm tổ chức cuộc thi bình chọn danh hiệu "Hoa khôi được yêu thích nhất".</p>
            <p><b>Thời gian bình chọn:</b> Từ 21h ngày 18/5/2016 đến 17h ngày 28/5/2016</p>
            <p><b>Cách thức bình chọn:</b> Bên phải mỗi tấm ảnh có hiển thị thông tin cá nhân và nút bình chọn.
                Người tham gia chỉ được bình chọn cho 1 thí sinh trong mỗi lần vote,
                hệ thống cho phép bình chọn 1 lần/ngày đối với mỗi người dùng.</p>
            <p>Danh hiệu "Hoa khôi được yêu thích nhất" được trao hoàn toàn dựa trên kết quả bình chọn!</p>
            <p>Kết quả bình chọn sẽ được công bố vào đêm Chung kết FTUCharm 2016,
                diễn ra tại Hội trường A5 Đại học Bách Khoa TP.HCM ngày 28/5/2016.</p>
            <p><b>Lưu ý:</b> Khi có bất cứ thắc mắc, khiếu nại nào xảy ra, BTC sẽ là người đưa ra quyết định cuối cùng.</p>
            <p class="text-red">Ban tổ chức đã tiến hành kiểm tra và loại các bình chọn gian lận.</p>
            <?php if (!is_poll_finished()):?>
                <p class="text-red" id="remaining-time-box">Còn <span id="remaining-time"></span> nữa.</p>
            <?php endif ?>
        </div>

        <?php if (is_poll_quota_exceeded()): ?>
            <p class="text-red"><?php _e('Closed due to the exceeded votes quota', TP_TD); ?></p>
        <?php endif; ?>

        <?php if (!is_poll_started()): ?>
            <p class="text-red"><?php _e("Cuộc bình chọn chưa bắt đầu.", TP_TD); ?></p>
        <?php endif; ?>

        <?php if (is_poll_finished()): ?>
            <p class="text-red"><?php _e('Cuộc bình chọn đã kết thúc!', TP_TD); ?></p>
        <?php endif; ?>

    </div>
    <div style="width: 100%; overflow: hidden">
        <div style="margin: -5px;">
            <img width="100%" height="auto" class="separator-2" src="<?php echo TP_ASSETS_URL ?>images/separator-2.svg">
        </div>
    </div>
</div>