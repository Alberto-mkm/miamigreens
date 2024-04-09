<?php 
// require get_template_directory() .'/inc/paypal/api/Config/Config.php';
// require get_template_directory() .'/inc/paypal/api/Helpers/PayPalHelper.php';
function captureOrder(){
    $paypalHelper = new PayPalHelper;

    header('Content-Type: application/json');
    echo json_encode($paypalHelper->orderCapture());
    exit();
}