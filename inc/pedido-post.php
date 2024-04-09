<?php


function order_meta_boxes(){
    add_meta_box(
        'orderdetails', 
        _( 'Detalles' ),
        'order_meta_boxes_callback',
        'pedido'
    );
}
add_action('add_meta_boxes', 'order_meta_boxes');


// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => "https://api.sandbox.paypal.com/v2/checkout/orders/5O190127TN364715T",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_HTTPHEADER => array(
//     'authorization: Bearer A21AAFJ9eoorbnbVH3fTJrCTl2o7-P_1T6q8vdYB_QwBB9Ais5ZZmJD4BsNjIiOh8j8OyOcfzLO1BKcgKe0pK-mntpk6jOm-',
//     'content-type: application/json'
//   ),
// ));

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }
function html_order($rows, $data){
    $client = $data->client->payer;
    $compra = $data->client->purchase_units[0];
    $address = $compra->shipping->address;
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    ?>
    <div>
        <h4 style="margin-bottom:8px">Mas información del pedido</h4>
        <?=apply_filters('the_content',$data->comment)?>
    </div>
    <hr>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>
                   ID orden
                </th>
                <th> Forma de pago </th>
                <th>Correo</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$data->client->id?></td>
                <td><?=$client->name->given_name.' '.$client->name->surname?></td>
                <td><?=$client->email_address?></td>
                <td><?=$compra->amount->value.' '.$compra->amount->currency_code?></td>
            </tr>
        </tbody>
    </table><br>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>
                    Calles
                </th>
                <th> Calle 2 </th>
                <th>Estado</th>
                <th>Código postal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> <?= $address->address_line_1?></td>
                <td><?= $address->address_line_2?></td>
                <td> <?= $address->admin_area_2?></td>
                <td><?= $address->postal_code?></td>
            </tr>
        </tbody>
    </table><br>
    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th>
                   #
                </th>
                <th> Producto </th>
                <th> Precio </th>
                <th> Cantidad </th>
            </tr>
        </thead>
        <tbody>
            <?=$rows?>
        </tbody>
    </table>
    <?php 
}
function order_meta_boxes_callback(){
    global $post;
    if( isset($post->ID) ){
        $data = json_decode($post->post_content);
        $html = '';

        foreach( $data->car as $o ){
            $html .= "<tr>
                        <td><img src='{$o->image}' width='100'/></td>
                        <td>{$o->title}</td>
                        <td>{$o->price}</td>
                        <td>{$o->qty}</td>
                    </tr>";
        }
        html_order($html, $data);
    }
}