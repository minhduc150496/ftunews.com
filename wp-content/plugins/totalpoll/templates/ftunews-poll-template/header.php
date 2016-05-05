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

    .rule div {
        font-size: 16px !important;
    }

    .poll-body {
        background: #111111;
        color: white;
    }

    .poll-body .choice-content .name {
        text-transform: uppercase;
    }
    .poll-result i.fa {
        position: relative;
        top: 10px;
        margin-right: 15px;
    }
    .poll-result i.fa,
    .poll-body .choice-content i.fa {
        font-size: 40px;
    }

    .tp-choices > li:not(:first-child) {
        margin-top: 80px;
    }

    .choice-info {
        text-align: left;
    }
    label {
        display:inline-block;
        width: 40px;
        position: relative;
        top: 10px;
        margin-right: 15px;
    }

    label > input {
        display: none;
    }
    label > button {
        height: 0;
    }
    label .fa:hover {
        cursor: pointer;
    }
    label .fa.fa-heart {
        color: rgb(254, 0, 60);
        position: absolute;
        top: 0;
        left: 0;
        visibility: hidden;
    }

    label > input:checked ~ .fa-heart-o {
        visibility: hidden;
    }
    label > input:checked ~ .fa-heart {
        visibility: visible;
    }

    .btn-vote {
        width: 53px;
        background: none;
        border: none;
        outline: none;
    }
    .modal button.btn-link {
        font-weight: bold;
        color: black;
    }
    .modal-dialog {
        max-width: 480px;
        max-height: 250px;
        margin: 10px auto;
    }
    .modal-content {
        color: black;
        text-align: left;
        padding-left: 25px;
        padding-right: 25px;
        font-size: 16px;
    }
    .modal-body {
        padding: 0;
        margin-top: 50px;
        margin-bottom: 20px;
    }
    .modal-footer {
        padding: 0;
        padding-bottom: 10px;
        border: none;
    }
    .modal-footer .btn-link {
        font-size: 18px;
    }
    .tp-buttons {
        margin-top: 60px;
        text-align: center;
    }

    .modal-footer .btn:not(:first-child) {
        margin-left: 15px;
    }
    .modal-footer .btn:last-child {
        padding-right: 0;
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
        <div>
            Thể lệ:<br>
            Trải qua hai vòng thi sơ kết và bán kết cùng chuỗi hoạt động bên lề, cuộc thi đã tìm ra 16 gương mặt xinh
            xắn nhất sẽ khoe sắc, tranh tài trong đêm chung kết tại nhà hát Bến Thành vào ngày 3/5 sắp tới. Tuy vậy ngay
            từ bây giờ, teen đã có thể cùng tham gia bình chọn cho các thí sinh trên trang tin iOne.net. Hoạt động bình
            chọn sẽ bắt đầu từ hôm nay 26/4 đến 17h ngày 3/5/2012 teen nhé. Thí sinh nhận được nhiều vote nhất từ chính
            các bạn sẽ được trao danh hiệu Hoa khôi được yêu thích nhất trong đêm chung kết đấy! Duyên dáng Ngoại thương
            2012 được trang tin iOne.net và trang ftunews.com của trường Ngoại Thương bảo trợ thông tin.
        </div>
    </div>
    <img class="separator-2" src="<?php echo TP_ASSETS_URL ?>images/separator-2.svg">
</div>