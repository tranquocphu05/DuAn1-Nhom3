<?php require "layout/client/header_client.php" ?>
<div class="user">
    <div class="user1">
        <div class="item1">
            <!-- <img src="public/image/addfb.png" alt="" style="width:200px; margin-left:10px;"> -->
            <p style="margin-left:5px;"><?= $_SESSION["auth"]['full_name'] ?></p>
        </div>
        <div class="item2">
            <?php
            if (($_SESSION['auth']['role']) == 2) { ?>
                <a href="/du_an_1_poly_hotel/?role=admin">Đăng nhập trang quản trị</a>
            <?php } ?>
        </div>

        <div class="item3">
            <a href="/du_an_1_poly_hotel/?role=client&mod=bill&action=list">Danh sách đặt lịch</a>
        </div>

        <div class="item2">
            <a href="/du_an_1_poly_hotel/?role=client&mod=auth&action=forgotPassword">Quên mật khẩu</a>
        </div>
        <div class="item5">
            <a href="/du_an_1_poly_hotel/?role=client&mod=auth&action=logout">Đăng xuất</a>
        </div>
    </div>

    <div class="user2">
        <p style="font-size:25px;color:red;text-align:center;">DANH SÁCH ĐẶT LỊCH</p>
        <form action="" method="post" class="w3-container">
            <div class="row">

                <table class="table">
                    <thead>
                        <th>Mã hóa đơn</th>
                        <th>Tên phòng</th>
                        <th>Ngày nhận phòng</th>
                        <th>Ngày trả phòng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng Thái</th>
                    </thead>

                    <tbody>
                        <?php foreach ($bill_details as $value) { ?>
                            <tr>
                                <td><?= $value['id'] ?></td>
                                <td><?= $value['name'] ?></td>
                                <td><?= $value['check_in_date'] ?></td>
                                <td><?= $value['check_out_date'] ?></td>
                                <td><?= $value['price'] ?></td>
                                <td>
                                    <?= $value['status'] ?? '' ?>
                                    <h5>Đã Đặt Thành Công</h5>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </form>

    </div>
</div>

<?php require "layout/client/footer_client.php" ?>
