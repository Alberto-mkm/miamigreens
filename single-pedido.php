<?php wp_head() ?>
<?php 
    $order = json_decode($post->post_content);
    $client = $order->client->payer;
    $compra = $order->client->purchase_units[0]->amount;
    $address = $order->client->purchase_units[0]->shipping->address;
    $buyer = $order->client->purchase_units[0]->shipping->name;
    
    // echo "<pre>";
    // var_dump($buyer->full_name);
    // echo "</pre>";
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Overpass&display=swap" rel="stylesheet">
<style>
.relative{
    position:relative;
}
.form-horizontal span{
    position:relative;
}
</style>
<div class="jumbotron jumbotron-fluid gradient" style="background:#189027;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-8">
                <h5 class="text-white"><?=( LANG == 'en' ? 'Your payment is complete':'Pago completado')?></h5>
                <p class="text-white"><?=$buyer->full_name?>, <?=( LANG == 'en' ? 'Thank you for your Order':'Gracias por tu compra')?></p>
            </div>
        </div>
    </div>
</div>
<div class="container my-4">
    <div class="row d-flex justify-content-center">
        <div class="col-8">
        <div class="card">
            <div class="card-header">
                <!-- <h4 class="card-title"><?=$post->post_title?></h4> -->
                <h5><?=( LANG == 'en' ? 'Shipping Details':'Datos del cliente')?></h5>
            </div>
            <div class="card-body">
                <form id="orderView" class="form">
                    <div class="col-sm-7">
                        <p id="viewRecipientName"><?= $buyer->full_name?></p>
                        <p id="viewAddressLine1"><?= $address->address_line_1?></p>
                        <p id="viewAddressLine2"><?= $address->address_line_2?></p>
                        <p class="relative">
                            <span id="viewCity"><?=$address->admin_area_2?></span>,
                            <span id="viewState"><?=$address->admin_area_1?></span> - <span id="viewPostalCode"><?=$address->postal_code?></span>
                        </p>
                    </div>
                    <hr>
                    <h5 class="col-sm-5">
                        <?=( LANG == 'en' ? 'Transaction Details':'Detalles de pago')?>
                    </h5>
                    <hr>
                    <div class="col-sm-7">
                        <p class="relative">
                            <?=( LANG == 'en' ? 'Transaction ID':'Id de orden')?>:
                            <span class="viewTransactionID"><?=$order->client->id?></span></p>
                        <p class="relative">
                            <?=( LANG == 'en' ? 'Payment Total Amount':'Monto de la compra')?>:
                            <span class="viewFinalAmount"><?=$compra->value?></span> </p>
                        <p class="relative">
                            <?=( LANG == 'en' ? 'Currency Code':'Moneda')?>:
                            <span class="viewCurrency"><?=$compra->currency_code?></span></p>
                    </div>
                        
                    <hr>
                    <h3> Click <a href='<?=site_url()?>'><?=( LANG == 'en' ? 'here':'aquí')?> </a> <?=( LANG == 'en' ? 'to return to Home Page':' para regresar al inicio')?></h3>
                </form>
            </div>
        </div>
    </div>
</div></div>
<?php wp_footer() ?>