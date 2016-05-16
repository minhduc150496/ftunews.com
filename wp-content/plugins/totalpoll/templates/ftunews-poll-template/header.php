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
            <div><?php _e("Cuộc bình chọn chưa bắt đầu.", TP_TD); ?></div>
            <?php $running = false;
        endif; ?>

        <?php if (is_poll_finished()): ?>
            <div><?php _e('Cuộc bình chọn đã kết thúc.', TP_TD); ?></div>
            <?php $running = false;
        endif; ?>

        <?php if ($running):?>
        <h2>BÌNH CHỌN NỮ SINH DUYÊN DÁNG NHẤT ĐẠI HỌC NGOẠI THƯƠNG</h2>
        <div>
            <p>Nhằm tìm ra và tôn vinh gương mặt Top 15 nhận được sự quan tâm và yêu thích nhất, FTUNEWS phối hợp cùng BTC cuộc thi Duyên dáng Ngoại thương – FTUCharm tổ chức cuộc thi bình chọn danh hiệu "Hoa khôi được yêu thích nhất".</p>
            <p><b>Thời gian bình chọn:</b> Từ 17h ngày 18/5/2016 đến 17h ngày 28/5/2016</p>
            <p><b>Cách thức bình chọn:</b> Bên phải mỗi tấm ảnh có hiển thị thông tin cá nhân và nút bình chọn. Người tham gia chỉ được bình chọn cho 1 thí sinh trong mỗi lần vote, hệ thống cho phép bình chọn 1 lần/ngày đối với mỗi người dùng. </p>
            <p>Danh hiệu "Hoa khôi được yêu thích nhất" được trao hoàn toàn dựa trên kết quả bình chọn!</p>
            <p>Kết quả bình chọn sẽ được công bố vào đêm Chung kết FTUCharm 2016, diễn ra tại Hội trường A5 Đại học Bách Khoa TP.HCM ngày 28.5.2016. </p>
            <p>Lưu ý: Khi có bất cứ thắc mắc, khiếu nại nào xảy ra, BTC sẽ là người đưa ra quyết định cuối cùng.</p>
        </div>
        <?php endif;?>
    </div>
    <div style="width: 100%; overflow: hidden">
        <div style="margin: -5px;">
            <img width="100%" height="auto" class="separator-2" src="<?php echo TP_ASSETS_URL ?>images/separator-2.svg">
        </div>
    </div>
</div>