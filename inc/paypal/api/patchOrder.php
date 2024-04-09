<?php 

// include_once('Config/Config.php');
// include_once('Helpers/PayPalHelper.php');
function patchOrder($request){
  $paypalHelper = new PayPalHelper;

  $orderData = array();

  if(array_key_exists('updated_shipping', $request)) {
      $finalTotal = floatval($request['total_amt']) + (floatval($request['updated_shipping']) - floatval($request['current_shipping']));

      $orderData = '[ {
                "op" : "replace",
                "path" : "/purchase_units/@reference_id==\'PU1\'/amount",
                "value" : {
                  "currency_code" : "'.$request['currency'].'",
                  "value" : "'.$finalTotal.'",
                  "breakdown" : {
                    "item_total" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['item_amt'].'"
                    },
                    "shipping" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['updated_shipping'].'"
                    },
                    "tax_total" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['tax_amt'].'"
                    },
                    "shipping_discount" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['shipping_discount'].'"
                    },
                    "handling" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['handling_fee'].'"
                    },
                    "insurance" : {
                      "currency_code" : "'.$request['currency'].'",
                      "value" : "'.$request['insurance_fee'].'"
                    }
                  }
                }       
              }]';
  }

  header('Content-Type: application/json');
  echo json_encode($orderData);
  // echo json_encode($paypalHelper->orderPatch($orderData));
  exit();
}