<?php /* Template Name: checkout */ ?>
<?php get_header() ?>

<?php 
    $baseUrl = site_url();
    $delivery = get_option( 'delivery','' );
    $tax = get_option( 'tax', '' );
    $horarios = get_post_meta($post->ID, 'horario_de_entregas', true);
    $horarios = explode(PHP_EOL,$horarios);
    $horarios2 = $horarios; $ic = 0;
    foreach($horarios2 as $h2){
        $horarios[$ic] = trim($h2);
        $ic++;
    }
    // var_dump($horarios); die();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div hidden id="tax" data-tax="<?=$tax?>"></div>
<style>
    .closeModal{
        background-color: transparent !important;
    }
    .relative{
        position: relative;
    }
    #paypal-button-container{
        text-align: right;
        
    }
    #paypal-button-container > div{
        max-width:100%;
        margin: auto 0 auto auto;
    }
    .wrap-paypal{
        position: relative;
    }
    .wrap-paypal.disabled::before{
        content: '';
        height: 100%;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index:1002;
    }

    .box-import{
        transition: padding 0.5s;
    }
    .bounce{
        border: 2px solid #f55;
        padding: 5px;
        border-radius: 3px;
    }
</style>
<style>
    :root {
    --light-grey: #F6F9FC;
    --dark-terminal-color: #0A2540;
    --accent-color: #635BFF;
    --radius: 3px;
    }

    form.stripe > * {
        margin: 10px 0;
    }

    form.stripe button {
        background-color: var(--accent-color);
    }

    form.stripe button {
    background: var(--accent-color);
    border-radius: var(--radius);
    color: white;
    border: 0;
    padding: 12px 16px;
    margin-top: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: block;
    }
    form.stripe button:hover {
    filter: contrast(115%);
    }
    form.stripe button:active {
    transform: translateY(0px) scale(0.98);
    filter: brightness(0.9);
    }
    form.stripe button:disabled {
    opacity: 0.5;
    cursor: none;
    }

    form.stripe label {
    display: block;
    }

    form.stripe a {
    color: var(--accent-color);
    font-weight: 900;
    }

    form.stripe small {
    font-size: .6em;
    }

    form.stripe fieldset, 
    form.stripe input, 
    form.stripe select {
    border: 1px solid #efefef;
    }

    #payment-form {
    border: #F6F9FC solid 1px;
    border-radius: var(--radius);
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 30px 50px -20px rgb(50 50 93 / 25%), 0 30px 60px -30px rgb(0 0 0 / 30%);
    }

    #messages {
    font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New';
    display: none; /* hide initially, then show once the first message arrives */
    background-color: #0A253C;
    color: #00D924;
    padding: 20px;
    margin: 20px 0;
    border-radius: var(--radius);
    font-size:0.7em;
    overflow: scroll;
    }
</style>
<div class="container pt-4">
    <div class="row pt-4">
        <div class="col">
            <h2><?=( LANG == 'es' ? 'Resumen de compra':'Checkout')?></h2>
        </div>
    </div>
    <div class="row pt-4">
        <div class="col-12 col-sm-12 col-md-12 col-lg-9">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th><?=( LANG == 'es' ? 'Nombre':'Name')?></th>
                            <th class="text-center"><?=( LANG == 'es' ? 'Cantidad':'Qty')?></th>
                            <th class="text-center"><?=( LANG == 'es' ? 'Precio':'Price')?></th>
                            <th class="text-center"><?=( LANG == 'es' ? 'Extras':'Extras')?></th>
                        </tr>
                    </thead>
                    <tbody id="listProducts"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <p class="text-bold mt-2">
                                    <?=( LANG == 'es' ? 'Compártenos detalles de tu evento, Fecha, Hora, Lugar':'Let us know more details about your special event' )?>:
                                </p>
                                <textarea name="" id="commentcheckout" cols="10" rows="5" class="resize rounded-md"></textarea>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-3 relative">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Subtotal: </th><td><span id="totalCheckout"></span></td>
                </tr>
                <tr>
                    <th>Extras: </th><td>$<span id="extrasCheckout">0.00</span></td>
                </tr>
                <tr>
                    <th><?=( LANG == 'es' ? 'Impuestos':'Taxes')?>: </th><td>$<span id="taxCheckout">0.00</span></td>
                </tr>
                <tr>
                    <th><?=( LANG == 'es' ? 'Envio':'Delivery')?>: </th><td>$<span id="delivery" data-delivery="<?=$delivery?>"><?=$delivery?><span> USD</td>
                </tr>
                <tr>
                    <th>Total: </th><td>$<span id="ftotalCheckout"></span></td>
                </tr>
            </table>
            <div class="box-import rainbow">
                <p class="strong mb-2">
                    <?=(LANG == 'es' ? 'Seleccione una fecha y hora de entrega':
                    'Select a delivery time' )?>
                </p>
                <div>
                    <input class="datepicker rounded-2" style="width:160px"/>
                    <span id="setHour" class="ps-2 d-inline-block"></span>
                </div>
            
            </div>
            <form id="payment-form" class="stripe">
                <div>
                    <label for="name">Nombre completo</label>
                    <input id="name" name="full_name" placeholder="" value="" required  class="form-control"/>
                </div>
                <div>
                    <label for="street">Calle</label>
                    <input id="street" name="[address][street]" value="" placeholder="" required class="form-control"/>
                </div>
                <div>
                    <label for="location">Colonia</label>
                    <input id="location" name="[address][location]" value="" placeholder="" required class="form-control"/>
                </div>
                <div>
                    <label for="city">Ciudad</label>
                    <input id="city" name="[address][city]" value="" placeholder="" required class="form-control"/>
                </div>
                <div>
                    <label for="state">Estado</label>
                    <input id="state" name="[address][state]" value="" placeholder="" required  class="form-control"/>
                </div>
                <div>
                    <label for="postal_code">Código postal</label>
                    <input id="postal_code" name="[address][postal_code]" value="" placeholder="" required class="form-control"/>
                </div>
                <div>
                    <label for="card-element">
                        Card
                    </label>
                    <div id="card-element">
                        <!-- Elements will create input elements here -->
                    </div>
                    <!-- We'll put the error messages in this element -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <div>
                    <button id="submit">Pay</button>
                </div>
            </form>
            <div id="messages" role="alert" style="display: none;"></div>

            <div class="wrap-paypal disabled">
                <div id="paypal-button-container" class="mt-1"></div>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
<div id="mymodal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
            <?=( LANG == 'es' ? 'Seleccione una horario de entrega' : 'Select a delivery time' )?>
        </h5>
        <button type="button" class="close closeModal bcloseModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
        <?=(LANG == 'es' ? 
                    '<p class="mt-1"><strong>Nota:</strong> Servicio de Delivery exclusivo en Miami Beach</p>'
                    :
                    '<p class="mt-1"><strong>Note:</strong> Delivery only in Miami Beach </p>' 
                )?>
            <?php $cont = 0;?>
            <ul id="horasEntrega" class="list-group list-group-flush mx-0">
                <?php foreach($horarios as $h): ?>
                    <li class="list-group-item">
                        <label for="horario<?=$cont?>">
                            <input id="horario<?=$cont?>" type="radio" name="horario" value="<?=$h?>">
                            <?=trim($h)?>
                        </label>
                    </li>
                    <?php $cont ++; ?>
                <?php endforeach; ?>
            </ul>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bcloseModal"><?=(LANG == 'en' ? 'Accept':'Aceptar')?></button>
        <button type="button" class="btn btn-secondary bcloseModal"><?=(LANG == 'en' ? 'Close':'Cerrar')?></button>
      </div>
    </div>
  </div>
</div>
<?php //var_dump(PAYPAL_CREDENTIALS[PAYPAL_ENVIRONMENT]['client_id'])?>
<!-- Javascript Import -->
<!-- &vault=false&commit=true&debug=true<?php echo isset($_GET['buyer-country']) ? "&buyer-country=" . $_GET['buyer-country'] : "" ?> -->
<!-- <script src="https://www.paypal.com/sdk/js?client-id=<?=PAYPAL_CREDENTIALS[PAYPAL_ENVIRONMENT]['client_id']?>&currency=USD&intent=capture"></script> -->
<script src="https://www.paypal.com/sdk/js?client-id=<?=PAYPAL_CREDENTIALS[PAYPAL_ENVIRONMENT]['client_id']?>&intent=capture&vault=false&currency=USD&commit=true"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<!-- PayPal In-Context Checkout script -->
<script type="text/javascript">
    var date_delivery;
    
    window.onload = function(){
        jQuery('#horasEntrega').on('click','input',function(){
            jQuery('#setHour').html( jQuery(this).val() );
            jQuery('.wrap-paypal').removeClass('disabled')
            jQuery('.box-import').removeClass('bounce')
        })
        jQuery('.bcloseModal').click(()=>{
            jQuery('#mymodal').modal('toggle')
        });

        jQuery('.wrap-paypal').click(()=>{
            if( jQuery('#setHour').html() != "" ){
                jQuery('.wrap-paypal').removeClass('disabled')
            }else{
                jQuery('.box-import').addClass('bounce')
            }
        });
        const today = new Date();
        var tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000);
        <?php if( LANG == 'es' ) { ?>
            flatpickr.localize(flatpickr.l10ns.es);
        <?php } ?>
        flatpickr(".datepicker", {
            minDate: tomorrow,
            locale: "<?=(LANG == 'es' ? 'es':'en')?>",
            // disable: [
            //     function(date) {
            //         // return true to disable
            //         return (date.getDay() === 0 || date.getDay() === 6);
            //     }
            // ],
            "locale": {
                "firstDayOfWeek": 1 // start week on Monday
            },
            onChange : (_, dateStr, i) =>{
                date_delivery = dateStr;
                jQuery('#mymodal').modal('toggle');
            }
        });
    };

    paypal.Buttons({
        // Set your environment
        env: '<?= PAYPAL_ENVIRONMENT ?>',
        // Set style of buttons
        style: {
            layout: 'vertical',   // horizontal | vertical
            size:   'responsive',   // medium | large | responsive
            shape:  'pill',         // pill | rect
            color:  'gold',         // gold | blue | silver | black,
            fundingicons: false,    // true | false,
            tagline: false          // true | false,
        },
        // Wait for the PayPal button to be clicked
        createOrder: function() {
            
            const subtotal = carrito.getSubtotal();
            const subtotalItems = subtotal.total;
            
            const {number} = carrito.total();
            const total = number;
            

            const formData = {
                item_amt: subtotalItems,
                tax_amt: carrito.tax(),
                handling_fee: 0,
                insurance_fee: 0,
                shipping_amt: jQuery('#delivery').data('delivery'),
                shipping_discount:0,
                total_amt : total,
                currency: 'USD',
                return_url :  '<?= $baseUrl.URL["redirectUrls"]["returnUrl"]?>',
                cancel_url : '<?= $baseUrl.URL["redirectUrls"]["cancelUrl"]?>',
                cart : carrito.items,
                comment : document.getElementById('commentcheckout').value,
                hour_delivery : jQuery('#setHour').html(),
                date_delivery : date_delivery,

            }
            jQuery('.loader-section').show();
            return fetch(
                '<?= $baseUrl.URL['services']['orderCreate']?>',
                {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
				    },
				    body: JSON.stringify(formData)
                }
            ).then(function(response) {
                return response.json();
            }).then(function(resJson) {
                // carrito.empty();
                jQuery('.loader-section').hide();
                return resJson.data.id;
            });
        },

        // Wait for the payment to be authorized by the customer
        onApprove: function(data, actions) {
            jQuery('.loader').show();
            return fetch(
                '<?=  $baseUrl.URL['services']['orderGet'] ?>',
                {
                    method: 'GET'
                }
            ).then(function(res) {
                return res.json();
            }).then(function(res) {
                window.location.href = window.location.origin = '/pedido/'+res[1];
            });
        }

    }).render('#paypal-button-container');

</script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://miamigreens.com/wp-content/themes/miami-green/js/stripe.js"></script>
<!-- Es 
Psw: Proyecto2024! -->