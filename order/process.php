<?php
  include('ldfood_fns.php');
  // The shopping cart needs sessions, so start one
  session_start(); // 会话启动

  do_html_header('Checkout'); // 输出头部html代码

  $card_type = $_POST['card_type'];
  $card_number = $_POST['card_number'];
  $card_name = $_POST['card_name'];

  if ($_SESSION['cart']) {
    // display cart, not allowing changes and without pictures
    display_cart($_SESSION['cart'], false, 0); // 输出购物车html代码

    display_shipping(calculate_shipping_cost()); // 输出支付价格信息

    if ($card_type == 'BankCard' && $card_number && $card_name) {
      // 处理银行卡支付
      if (process_card($_POST)) { // 支付
        // empty shopping cart
        unset($_SESSION['items']);
        unset($_SESSION['total_price']);
        unset($_SESSION['cart']);
        echo "<h2>你的订单已经完成，谢谢惠顾，欢迎再次下单。</h2>";
        display_button("user_main.php", "cart", "Continue Shopping"); // 跳转按钮
      } else {
        echo "<h2>无法完成支付，请重试。</h2>";
        display_button("purchase.php", "last", "Back"); // 跳转按钮
      }
    } elseif ($card_type == 'Wechatpay' || $card_type == 'Alipay') {
      // 处理二维码支付
      echo "<h2>请使用二维码完成支付。</h2>";
      if ($card_type == 'Wechatpay') {
        echo '<img src="path_to_wechat_qr_code.jpg" alt="WeChat Pay QR Code">';
      } elseif ($card_type == 'Alipay') {
        echo '<img src="path_to_alipay_qr_code.jpg" alt="Alipay QR Code">';
      }
      // 这里可以添加更多处理二维码支付成功后的代码
      display_button("user_main.php", "cart", "Continue Shopping"); // 跳转按钮
    } else {
      echo "<h2>你未按要求填写表单，请重试。</h2><hr />";
      display_button("purchase.php", "last", "Back"); // 跳转按钮
    }
  } else {
    echo "<h2>你的购物车为空。</h2><hr />";
    display_button("user_main.php", "cart", "Continue Shopping"); // 跳转按钮
  }

  do_html_footer();
?>

