<?php

function construct()
{
      load_model('index');
}

function indexAction()
{
      if (is_auth()) {
            $notifications = get_notification();
            load_view('index', [
                  "notifications" => $notifications
            ]);
      } else {
            header('location:/du_an_1_Nhom3/?role=client&mod=auth');
            
      }
}
function indexPostAction()
{
      if (is_auth()) {
            $id = $_POST['id'];
            $check_in_date = $_POST['check_in_date'];
            $check_out_date = $_POST['check_out_date'];
            $production = get_one_production($id);

            $data['productions'] = $production;
            if (!isset($_SESSION['cart'])) {
                  $_SESSION['cart'] = [];
            }
            
            $production['check_in_date'] = $check_in_date;
            $production['check_out_date'] = $check_out_date;
            // echo "<pre>";
            //       var_dump(  $_SESSION['cart']);die();
            $check = false;
            foreach ($_SESSION['cart'] as $key => $value) {
                  if ($production['id'] == $value['id']) {
                        $check = true;
                        break;
                  }
            }
            if ($check) {
                  echo "<script> alert('Phòng đã được tồn tại trong giỏ hàng.Vui lòng vào giỏ hàng để hoàn thành các bước thanh toán!!!') </script>";
                  header("Refresh: 0.5; URL=/du_an_1_Nhom3/?role=client&mod=product_details&action=index&id=$id");
                  //header("Refresh: 0.5; URL=/du_an_1_Nhom3/?role=client&mod=bill&action=index");
                 
                  die();
            } else {
                  $_SESSION['cart'][] = $production;
            }

            load_view('index');
      } else {
            header('location:/du_an_1_Nhom3/?role=client&mod=auth');
      }
}


function insertBillPostAction()
{
      require_once "./mail/index.php";

      $full_name = $_POST['full_name'];
      $addressMail = $_POST['email'];
      $numberphone = $_POST['numberphone'];
      $address = $_POST['address'];
      $created_id = $_SESSION['auth']['id'];
     
      $bill = get_list_bill();
      $check = false;
      foreach ($bill as $item) {
            if ($item['created_id'] == $created_id) {
                  $check = true;
                  break;
            }
      }
      if ($check == false) {
            insert_bill(["created_id" => $created_id]);
      }

      $check2 = false;
      $bill_detail = get_list_bill_detail();
      // echo "<pre>";
      // var_dump($_SESSION['cart']);
      // var_dump($bill_detail);
      // die();
      $get_one_bill = get_one_bill($_SESSION['auth']['id']);
      foreach ($_SESSION['cart'] as $cart) {
            // Khởi tạo lại biến $check2 trước mỗi vòng lặp
            $check2 = false;  
            
            // Kiểm tra nếu giỏ hàng không trống
            if (count($bill_detail) > 0) {
                foreach ($bill_detail as $item) {
                    if ($cart['id'] == $item['product_id'] && (strtotime($cart['check_in_date']) - strtotime($item['check_out_date']) < 0)) {
                        $check2 = true;
                        break;
                    }
                }
            }
        
            // Kiểm tra nếu ngày trả phòng nhỏ hơn ngày nhận phòng
            if (strtotime($cart['check_in_date']) && strtotime($cart['check_out_date'])) {
                if (strtotime($cart['check_in_date']) > strtotime($cart['check_out_date'])) {
                    echo "<script> alert('Phòng ".$cart['name']." không thể đặt. Do có ngày trả nhỏ hơn ngày nhận!!!') </script>";
                    header("Refresh: 0.5; URL=/du_an_1_Nhom3/?role=client&mod=bill&action=index");
                    die();  // Dừng chương trình tại đây
                }
        
                // Tính toán số ngày thuê phòng
                $check_in_timestamp = strtotime($cart['check_in_date']);
                $check_out_timestamp = strtotime($cart['check_out_date']);
                $duration_in_days = ($check_out_timestamp - $check_in_timestamp) / (60 * 60 * 24);  // Số ngày giữa hai ngày
                $price = $duration_in_days * $cart['price'];
            } else {
                echo "<script>alert('Ngày tháng không hợp lệ!');</script>";
                exit;  // Dừng chương trình nếu ngày tháng không hợp lệ
            }
        
            // Nếu không có vấn đề gì với ngày tháng, tiến hành insert vào chi tiết hóa đơn
            if ($check2 == false || count($bill_detail) == 0) {
                insert_bill_detail([
                    "bill_id" => $get_one_bill['id'],
                    "product_id" => $cart['id'],
                    "full_name" => $full_name,
                    "email" => $addressMail,
                    "numberphone" => $numberphone,
                    "address" => $address,
                    "total_money" => $price,
                    "check_in_date" => $cart['check_in_date'],
                    "check_out_date" => $cart['check_out_date']
                ]);
        
                // Thông báo qua email
                $title = "Thông báo dịch vụ đặt phòng tại Hotel AYBITI";
                $content = "<p>Xin chào,<b>$full_name</b></p>
                            <p>Cảm ơn <b>$full_name</b> đã sử dụng dịch vụ khách sạn của chúng tôi - khách sạn Poly's Hotel.</p>
                            <p>Như lời yêu cầu, chúng tôi đã đặt phòng cho <b>$full_name</b> với tên phòng là ".$cart['name']." từ ngày ".$cart['check_in_date']." đến ngày ".$cart['check_out_date'].". Tổng tiền là: $$price.</p>
                            <p>Chúng tôi rất mong đợi chuyến thăm của <b>$full_name</b></p>
                            Trân trọng.";
                GuiMail($title, $content, $addressMail);
        
                // Chuyển hướng người dùng sau khi thực hiện xong
                header("Refresh: 0.1; URL=/du_an_1_Nhom3/?role=client&mod=bill&action=index");
                echo "<script> alert('Đặt phòng ".$cart['name']." thành công!!!') </script>";
                exit;  // Dừng chương trình sau khi thực hiện thành công
            } else {
                $check2 = false;  // Đặt lại check2 nếu có vấn đề
                echo "<script> alert('Phòng ".$cart['name']." không thể đặt. Do có người đặt trước đó rồi!!!') </script>";
                header("Refresh: 0.5; URL=/du_an_1_Nhom3/?role=client&mod=bill&action=index");
                exit;  // Dừng chương trình nếu không thể đặt phòng
            }
        }
        
      //header('location://http://localhost:3000/du_an_1_Nhom3/?role=client');
      unset($_SESSION['cart']);
      
}

function listAction()
{
      if (is_auth()) {
            $created_id = $_SESSION['auth']['id'];
            $data['bill_details'] = get_bill_detail_by_id($created_id);
            load_view('list', $data);
      } else {
            header('location:/du_an_1_Nhom3/?role=client&mod=auth');
      }
}

function deleteAction()
{
      
      if (isset($_GET['id'])) {
           unset($_SESSION['cart'][$_GET['id']]);
        } else {
            $_SESSION['cart'] = [];
        }


     
      header('location:/du_an_1_Nhom3/?role=client&mod=bill&action=index');
 

}

