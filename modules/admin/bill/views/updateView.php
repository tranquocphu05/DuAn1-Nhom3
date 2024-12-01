<!-- In update.php -->
<form method="POST" action="/du_an_1_Nhom3/?role=admin&mod=bill&action=update&id=<?php echo $data['bill_detail']['id']; ?>">
    <label for="full_name">Họ và tên:</label>
    <input type="text" id="full_name" name="full_name" value="<?php echo $data['bill_detail']['full_name']; ?>" required />

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $data['bill_detail']['email']; ?>" required />

    <label for="numberphone">Số điện thoại:</label>
    <input type="text" id="numberphone" name="numberphone" value="<?php echo $data['bill_detail']['numberphone']; ?>" required />

    <label for="address">Địa chỉ:</label>
    <input type="text" id="address" name="address" value="<?php echo $data['bill_detail']['address']; ?>" required />

    <label for="room">Tên phòng:</label>
    <input type="text" id="room" name="room" value="<?php echo $data['bill_detail']['room']; ?>" required />

    <label for="check_in_date">Ngày nhận phòng:</label>
    <input type="text" id="check_in_date" name="check_in_date" value="<?php echo $data['bill_detail']['check_in_date']; ?>" required />

    <label for="check_out_date">Ngày trả phòng:</label>
    <input type="text" id="check_out_date" name="check_out_date" value="<?php echo $data['bill_detail']['check_out_date']; ?>" required />

    <label for="total_money">Tổng tiền:</label>
    <input type="number" id="total_money" name="total_money" value="<?php echo $data['bill_detail']['total_money']; ?>" required />

    <label for="check_in">Đã check-in:</label>
    <input type="checkbox" id="check_in" name="check_in" <?php echo $data['bill_detail']['check_in'] ? 'checked' : ''; ?> />

    <label for="cancel_room">Đã hủy phòng:</label>
    <input type="checkbox" id="cancel_room" name="cancel_room" <?php echo $data['bill_detail']['cancel_room'] ? 'checked' : ''; ?> />

    <label for="confirm_room">Đã xác nhận:</label>
    <input type="checkbox" id="confirm_room" name="confirm_room" <?php echo $data['bill_detail']['confirm_room'] ? 'checked' : ''; ?> />

    <button type="submit">Cập nhật</button>
</form>
