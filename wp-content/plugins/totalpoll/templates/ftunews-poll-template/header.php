<?php if (!defined('ABSPATH')) exit; // Shhh    ?>
<?php if (is_poll_quota_exceeded()): ?>
    <p class="tp-warning"><?php _e('Closed due to the exceeded votes quota', TP_TD); ?></p>
<?php endif; ?>

<?php if (!is_poll_started()): ?>
    <p class="tp-warning"><?php _e("This poll isn't started yet.", TP_TD); ?></p>
<?php endif; ?>

<?php if (is_poll_finished()): ?>
    <p class="tp-warning"><?php _e('This poll has been closed.', TP_TD); ?></p>
<?php endif; ?>

<style>
    .main {
        background: #111111;
    }

    .poll-header {
        background: white;
    }

    .ratio-wrapper:hover .ratio-content {
        transform: none;
    }

    .top-image {
        position: relative;
        overflow: hidden;
    }

    .separator-1 {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: auto;
        width: 100%;
    }

    .separator-2 {
        height: auto;
        width: 100%;
    }

    .rule {
        padding: 0 15px 160px 15px;
        margin: 0 auto !important;
        max-width: 970px;
        color: #111111;
    }

    .rule h2 {
        font-size: 34px !important;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        color: rgb(144, 0, 183);
    }

    .rule p {
        font-size: 16px !important;
    }

    .poll-body {
        background: #111111;
        color: white;
    }

    .poll-result {
        text-align: center;
        font-size: 26px;
        color: white;
    }

    .tp-choices > li:not(:first-child) {
        margin-top: 80px;
    }

    .tp-choices .choice-content img.active {
        /*border: solid 5px rgb(144,0,183);*/
    }

    label {
        width: 100%;
    }

    label > input {
        display: none;
    }

    label > img {
        cursor: pointer;
        border: 5px solid transparent;
    }

    label > input:checked + img {
        border-color: rgb(144, 0, 183);
    }

    .tp-buttons {
        margin-top: 60px;
        text-align: center;
    }

    .tp-buttons .btn:not(:first-child) {
        margin-left: 15px;
    }
</style>

<div class="poll-header">
    <div class="ratio-wrapper">
        <div class="ratio-content top-image">
            <img width="100%" src="<?php echo TP_ASSETS_URL ?>images/girl.jpg">
            <img class="separator-1" src="<?php echo TP_ASSETS_URL ?>images/separator-1.svg">
        </div>
    </div>
    <div class="rule">
        <h2>BÌNH CHỌN NỮ SINH DUYÊN DÁNG NHẤT ĐẠI HỌC NGOẠI THƯƠNG</h2>
        <p>
            Thể lệ:<br>
            Trải qua hai vòng thi sơ kết và bán kết cùng chuỗi hoạt động bên lề, cuộc thi đã tìm ra 16 gương mặt xinh
            xắn nhất sẽ khoe sắc, tranh tài trong đêm chung kết tại nhà hát Bến Thành vào ngày 3/5 sắp tới. Tuy vậy ngay
            từ bây giờ, teen đã có thể cùng tham gia bình chọn cho các thí sinh trên trang tin iOne.net. Hoạt động bình
            chọn sẽ bắt đầu từ hôm nay 26/4 đến 17h ngày 3/5/2012 teen nhé. Thí sinh nhận được nhiều vote nhất từ chính
            các bạn sẽ được trao danh hiệu Hoa khôi được yêu thích nhất trong đêm chung kết đấy! Duyên dáng Ngoại thương
            2012 được trang tin iOne.net và trang ftunews.com của trường Ngoại Thương bảo trợ thông tin.
        </p>
    </div>
    <img class="separator-2" src="<?php echo TP_ASSETS_URL ?>images/separator-2.svg">
</div>