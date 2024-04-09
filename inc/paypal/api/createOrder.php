<?php 
function saveOrder(
    $order_id, $car, 
    $comment,
    $hour_delivery,
    $date_delivery
){
	$title = "Order-".$order_id;
	$data = [
		'car'			=> $car,
        'client' => [],
        'comment' => $comment,
        'hour_delivery' => $hour_delivery,
        'date_delivery' => $date_delivery
	];
	// Create post object
	$my_post = array(
		'post_title'    => wp_strip_all_tags( $title ),
		'post_content'  => json_encode($data),
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type'		=> 'pedido'
	);
	
	// Insert the post into the database
	wp_insert_post( $my_post );
}
function uuid4() {
    /* 32 random HEX + space for 4 hyphens */
    $out = bin2hex(random_bytes(18));

    $out[8]  = "-";
    $out[13] = "-";
    $out[18] = "-";
    $out[23] = "-";

    /* UUID v4 */
    $out[14] = "4";
    
    /* variant 1 - 10xx */
    $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

    return $out;
}
function createOrder($request){
    $post = $request->get_params();
    $paypalHelper = new PayPalHelper;
    $randNo = (string)rand(10000,20000);
    $carrito = [];
    foreach($post['cart'] as $o){
        array_push($carrito,[
            'name' => $o['title'],
            'description' => $o['title'],
            'sku' => "mg-".$o['id'],
            'unit_amount' => [
                'currency_code' => 'USD',
                'value' => $o['price']
            ],
            'quantity' => "{$o['qty']}",
            'category' => 'PHYSICAL_GOODS'
        ]);
    }
    $carrito = json_encode($carrito); 

    $orderData = '{
        "intent" : "CAPTURE",
        "application_context" : {
            "return_url" : "'.$post['return_url'].'",
            "cancel_url" : "'.$post['cancel_url'].'"
        },
        "purchase_units" : [ 
            {
                "reference_id" : "Miamigreens food '.$randNo.'",
                "description" : "Miamigreens food",
                "invoice_id" : "Miamigreens food-'.$randNo.'",
                "custom_id" : "Mfood-'.$randNo.'",
                "amount" : {
                    "currency_code" : "USD",
                    "value" : "'.$post['total_amt'].'",
                    "breakdown" : {
                        "item_total" : {
                            "currency_code" : "USD",
                            "value" : "'.$post['item_amt'].'"
                        },
                        "shipping" : {
                            "currency_code" : "'.$post['currency'].'",
                            "value" : "'.$post['shipping_amt'].'"
                        },
                        "tax_total" : {
                            "currency_code" : "'.$post['currency'].'",
                            "value" : "'.$post['tax_amt'].'"
                        },
                        "handling" : {
                            "currency_code" : "'.$post['currency'].'",
                            "value" : "'.$post['handling_fee'].'"
                        },
                        "shipping_discount" : {
                            "currency_code" : "'.$post['currency'].'",
                            "value" : "'.$post['shipping_discount'].'"
                        },
                        "insurance" : {
                            "currency_code" : "'.$post['currency'].'",
                            "value" : "'.$post['insurance_fee'].'"
                        }
                    }
                },
                "items" : '.$carrito.'
            }
        ]
    }';
    

    if(array_key_exists('shipping_country_code', $post)) {

        $orderDataArr = json_decode($orderData, true);
        $orderDataArr['application_context']['shipping_preference'] = "SET_PROVIDED_ADDRESS";
        $orderDataArr['application_context']['user_action'] = "PAY_NOW";
        
        $orderDataArr['purchase_units'][0]['shipping']['address']['address_line_1']= $post['shipping_line1'];
        $orderDataArr['purchase_units'][0]['shipping']['address']['address_line_2']= $post['shipping_line2'];
        $orderDataArr['purchase_units'][0]['shipping']['address']['admin_area_2']= $post['shipping_city'];
        $orderDataArr['purchase_units'][0]['shipping']['address']['admin_area_1']= $post['shipping_state'];
        $orderDataArr['purchase_units'][0]['shipping']['address']['postal_code']= $post['shipping_postal_code'];
        $orderDataArr['purchase_units'][0]['shipping']['address']['country_code']= $post['shipping_country_code'];

        $orderData = json_encode($orderDataArr);
    }

    header('Content-Type: application/json');
    $response = $paypalHelper->orderCreate($orderData);
    saveOrder(
        $response["data"]["id"], 
        $post['cart'], 
        $post['comment'],
        $post['hour_delivery'],
        $post['date_delivery']
    );

    echo json_encode($response);
    exit();
}