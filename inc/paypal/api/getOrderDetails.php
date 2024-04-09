<?php

// include_once('Config/Config.php');
// include_once('Helpers/PayPalHelper.php');
function getOrderDetails(){
    $body = '';
    $paypalHelper = new PayPalHelper;
    $paypal = $paypalHelper->orderGet();
    
    global $wpdb;
    
    $title = 'Order-'.$paypal['data']['id'];
    $mypost = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_title = %s", $title ) );
    if( isset( $mypost->ID ) ){
        $content = json_decode($mypost->post_content, true );
        $content['client'] = $paypal['data'];
        $post_content = json_encode($content);
        
        $post_id = wp_update_post([
            'ID'           => $mypost->ID,
            'post_content' => $post_content
        ]);

        //$data = json_decode($mypost );
        // get_template_email_order( $post_id  );
        $order = get_post($post_id);
    
        $data = json_decode($order->post_content);
        
        $body .= html_order_mail( $data );
    
        $html = "
            <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'><head>
            <head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <meta name='x-apple-disable-message-reformatting'>
                <title>Miami green - {$order->title} </title>
            </head>
                <body style='font-family:monospace;margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000'>
                {$body}
                </body>
            </html>
        ";

        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Miamigreen<contacto@miamigreens.com>');
        $client = $data->client->payer;
        if( wp_mail([$client->email_address,'miamieatsgreen@gmail.com'], 'Orden de compra', $html, $headers ) ){
            header('Content-Type: application/json');
            echo json_encode([$paypal,$mypost->post_name]);
            exit();
        }

    }
    header('Content-Type: application/json');
    echo json_encode([$paypal,$mypost->post_name]);
    exit();
    
}
function get_complement($complements){
    $string = '';
    foreach( $complements as $val ){
        $string .= "<li>{$val}</li>";
    }
    return "<td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'>
            <ol style='margin:2px 0px'>
                {$string}
            </ol>
        </td>";
}
function get_extras($extras, $price){
    $string = '';
    foreach( $extras as $val ){
        $string .= "<li>{$val} - $ {$price} USD</li>";
    }
    return "<td colspan='2' style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'>
            <ol style='margin:2px 10px'>
                {$string}
            </ol>
        </td>";
}
function html_order_mail($data){
    $client = $data->client->payer;
    $compra = $data->client->purchase_units[0];
    $address = $compra->shipping->address;
    $tbody = ''; $option = '';
    foreach( $data->car as $o ){
        if( isset($o->option) ){
            $option = ' | '.$o->option;
        }
        $tbody .= "
            <tr>
                <td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'><img src='{$o->image}' width='100' /></td>
                <td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'>
                    {$o->title} {$option}
                </td>
                <td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'>$ {$o->price} USD</td>
                <td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'>{$o->qty}</td>
                <td style='padding: 8px;background: #f1efe7;border-bottom: 1px solid #fff;color: #444;border-top: 1px solid transparent;'></td>
            </tr>
            <tr><td colspan='5'>
                <table style='font-family:monospace;font-size: 12px; width: 600px; text-align: left;border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>".( LANG == 'es' ? 'Conservas':'Canned food')."</th>
                            <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>".( LANG == 'es' ? 'Aderezos':'Dressings')."</th>
                            <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>Greens</th>
                            <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>Toppings</th>
                            <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>Veggies</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            ".get_complement($o->complements->cannedfood->items)."
                            ".get_complement($o->complements->dressings->items)."
                            ".get_complement($o->complements->green->items)."
                            ".get_complement($o->complements->toppings->items)."
                            ".get_complement($o->complements->veggies->items)."
                        </tr>
                    </tbody>
                </table><td>
            </tr>
            <tr><td colspan='5'>
                <table style='font-family:monospace;font-size: 12px; width: 600px; text-align: left;border-collapse: collapse;'>
                    <thead>
                        <tr>
                            <th colspan='2' style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>".( LANG == 'es' ? 'Granos':'Grains')."</th>
                            <th colspan='2' style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>".( LANG == 'es' ? 'Proteina':'Protein')."</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            ".get_extras($o->extras->grains->items, $o->extras->grains->p)."
                            ".get_extras($o->extras->protein->items, $o->extras->protein->p)."
                        </tr>
                    </tbody>
                </table><td>
            </tr>
        ";
        $option = '';
    }
    
    $lang['order'] = LANG == 'es' ? "Id orden":"Order id";
    $lang['title_order'] = LANG == 'es' ? "Mas información del pedido": "More information about the order";
    $lang['forma_pago'] = LANG == 'es' ? "Forma de pago":"Way to pay";
    $lang['email'] = LANG == 'es' ? "Correo":"Email";

    $lang['street'] = LANG == 'es' ? "Calles":"Street";
    $lang['street2'] = LANG == 'es' ? "Calles 2":"Street 2";
    $lang['state'] = LANG == 'es' ? "Estado":"State";
    $lang['zip'] = LANG == 'es' ? "Código postal":"Zip";
    
    $lang['product'] = LANG == 'es' ? "Producto":"Product";
    $lang['qty'] = LANG == 'es' ? "Cantidad":"Qty";
    $lang['price'] = LANG == 'es' ? "Precio":"Price";
    

    $html = "
        <div>
            <h4 style='margin-bottom:8px'>{$lang['title_order']}</h4>
            ".apply_filters('the_content',$data->comment)."
        </div>
        <hr>
        <table style='font-family:monospace;font-size: 12px; width: 600px; text-align: left;border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>
                        {$lang['order']}
                    </th>
                    <th>{$lang['forma_pago']}</th>
                    <th>{$lang['email']}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{$data->client->id}</td>
                    <td>{$client->name->given_name} {$client->name->surname}</td>
                    <td>{$client->email_address}</td>
                    <td>{$compra->amount->value} {$compra->amount->currency_code}</td>
                </tr>
            </tbody>
        </table><br>
        <table style='font-family:monospace;font-size: 12px; width: 600px; text-align: left;border-collapse: collapse;'>
            <thead>
                <tr>
                    <th>
                        {$lang['street']}
                    </th>
                    <th> {$lang['street2']} </th>
                    <th>{$lang['state']}</th>
                    <th>{$lang['zip']}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> {$address->address_line_1}</td>
                    <td>{$address->address_line_2}</td>
                    <td> {$address->admin_area_2}</td>
                    <td>{$address->postal_code}</td>
                </tr>
            </tbody>
        </table><br>
        <table style='font-family:monospace;font-size: 12px; width: 600px; text-align: left;border-collapse: collapse;'>
            <thead>
                <tr>
                    <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>
                    #
                    </th>
                    <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'> 
                        {$lang['product']}
                    </th>
                    <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'> 
                        {$lang['price']}
                    </th>
                    <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'> 
                        {$lang['qty']}
                    </th>
                    <th style='font-size: 13px;font-weight: normal;padding: 8px;background: #777;border-top: 4px solid #aabcfe;border-bottom: 1px solid #fff;color: #fff;'>#</th>
                </tr>
            </thead>
            <tbody>
                {$tbody}
            </tbody>
        </table>
    ";
    return $html;
}

add_action( 'wp_ajax_get_template_email_order', 'get_template_email_order' );
add_action( 'wp_ajax_nopriv_get_template_email_order', 'get_template_email_order' );

function get_template_email_order( $post_id ){
    $lang['subject'] = 'Orden de compra -> '.$order->title;

    if( LANG == 'en' ){
        $lang['subject'] = $order->title;
    }
    $postID = $post_id;
    if( ! is_numeric($post_id) ){
        $postID = 267;
    }

    $order = get_post($postID);
    
    $data = json_decode($order->post_content);
    // echo "<pre>";
    // var_dump( $data->car[0]->complements->green ); 
    // echo "</pre>";
    // die();
    $body .= html_order_mail( $data );
   
	$html = "
		<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'><head>
		<head>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <meta name='x-apple-disable-message-reformatting'>
            <title>Miami green - {$order->title} </title>
		</head>
			<body style='font-family:monospace;margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000'>
            {$body}
			</body>
		</html>
	";
    // die($html);
	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Miamigreen<contacto@miamigreens.com>');
    $client = $data->client->payer;
	wp_mail([$client->email_address,'miamieatsgreen@gmail.com'], $lang['subject'], $html, $headers );
    // $multiple_recipients = [
    //     // 
    //     // 'Muss_1925@hotmail.com',
    //     // '', 
    //     // 'alberto_mkm@hotmail.com',
    //     // 'jessicapsanchezc@gmail.com',
    //     // 'nevisolmb@gmail.com'
    // ];
    // wp_mail('nevisolmb@gmail.com', $lang['subject'], $html, $headers );
    
}//.', miamieatsgreen@gmail.com' Envía a jessicapsanchezc@gmail.com nevisolmb@gmail.com