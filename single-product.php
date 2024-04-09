<?php get_header() ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<?php 
    $images = get_post_meta($post->ID,'galeria');  
    $metas = get_post_meta( $post->ID);
    $complements = get_post_meta( $post->ID, 'complements', true );
    $extras = get_post_meta( $post->ID, 'extras', true );
    $extras = [$extras[1], $extras[0],$extras[2]];
    // var_dump($extras); die();
    $terms = get_the_terms($post->ID, 'accessories');
    $flag = true;// isset( $terms[0] ) ? $terms[0] : false;
    $precioExtra = array_pop($extras);
    $precioExtra = explode('-', $precioExtra);
    
    $titleExtras = ['Protein','Grains'];
    $titlComplements = ( 
        LANG == 'en' ? 
        ['Green', 'Veggies','Pickles','Toppings', 'Dressings']
        :
        ['Escoge tus greens', 'Escoge tus Veggies','escoge tus conservas','escoge tus toppings', 'escoge tus aderezos'] 
    );
    $titleComplements = ['Green', 'Veggies','Pickles','Toppings', 'Dressings'];
?>

<style>
    .swiper {
        width: 350px;
        height: 400px;
        display: inline-block;
        text-align: right;
        margin: 0 auto;
    }
    .content{
        position: relative;
        right: -40px;
        z-index: 23;
        color: #000;
        font-family: Oswald;
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
        text-transform: uppercase;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .swiper-button-next{
        bottom: 5px;
        transform: rotate(90deg);
        top: inherit;
        right: 10px;
        background-color: #000;
        border-radius:100%;
        width: 30px;
        height: 30px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .swiper-button-prev{
        top: 25px;
        right: 10px;
        transform: rotate(90deg);
        bottom: inherit;
        left: inherit;
        background-color: #000;
        border-radius:100%;
        width: 30px;
        height: 30px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .swiper-button-next::after,
    .swiper-button-prev::after{
        font-size: 20px;
        font-weight: bold;
    }
    .swiper-pagination-bullet{
        width: 10px;
        height: 10px;
    }
    .swiper-pagination-bullet-active{
        background: black;
    }
    .swiper-slide img {
      display: block;
      width: 100%;
      height: 400px;
      object-fit: cover;
    }
    .complement ul{
        display:none;
    }
    .cursor-pointer,
    .item-title{
        cursor: pointer;
    }
    li.list-group-item.cursor-pointer.select-item-extra.select::before,
    li.list-group-item.cursor-pointer.select-item.select::before {
        background: #27da4d;
        content: '';
        width: 16px;
        height: 16px;
        position: absolute;
        left: -7px;
        border-radius: 100%;
        box-shadow: 0px 0px 2px;
        top: 12px;
    }
    .item-title svg{
        transition: 0.4s ease-in-out;
        transform-origin: 50% 50%;
    }
    .item-title .select{
        transform:rotate(180deg)
    }
</style>
<section class="container py-5">
    <div class="row py-5 mb-5 text-uppercase">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center text-uppercase">
            <h3><?=$post->post_title?></h3><br><br>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4" style="text-align:center;">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="<?=imgUrl( $post->ID )?>" alt="<?=$post->post_title?>"></div>
                    <?php foreach ( $images as $image_id ): ?>
                        <div class="swiper-slide">
                            <img src="<?=wp_get_attachment_url( $image_id )?>" alt="<?=$post->post_title?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <?php if( ! isset( $terms[0]->slug) ){ ?>
            <div class="row px-0 mx-0">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <?php if( $flag ){ ?>
                        <ul class="list-group list-group-flush complementos mx-0 px-0">
                            <?php $cont1 = 0; foreach ($complements as $complement): ?>
                                <li class="list-group-item complement">
                                    <?php
                                        $items = explode(PHP_EOL, $complement );
                                        $limit = array_shift($items);
                                    ?>
                                    <strong class="item-title" style="display:block">
                                        <?=$titleComplements[$cont1]?> (<?=$limit?>)  
                                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none" style="float:right">
                                            <path d="M1 0.999999L6 6L11 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </strong>
                                    <ul class="wrap px-0 list-group list-group-flush mx-0">
                                            <?php foreach($items as $i):?>
                                                <li 
                                                    class="list-group-item cursor-pointer select-item"
                                                    data-limit="<?=$limit?>" data-cat="<?=strtolower($titleComplements[$cont1])?>"
                                                ><?=$i?></li>
                                            <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php $cont1++; endforeach; ?>
                        </ul>
                    <?php 
                        }else{ 
                            apply_filters('the_content', $post->post_content);
                        }   
                    ?>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="text-center"><h5>Extras </h5></div>
                    <ul class="list-group list-group-flush complementos extras mx-0 px-0">
                        <?php $cont = 0; $cont2 = 1;?>
                        <?php foreach ($extras as $extra): ?>
                        <li class="list-group-item complement cursor-pointer">
                            <?php
                                $items = explode(PHP_EOL, $extra );
                            ?>
                            <strong class="item-title">
                                <?=$titleExtras[$cont]?> +
                                $<?=$precioExtra[$cont2]?> <!--<(LANG == 'en' ? ' EA':'DLLS C/U.')?>-->
                                <svg width="12" height="7" viewBox="0 0 12 7" fill="none" style="float:right">
                                    <path 
                                        d="M1 0.999999L6 6L11 1" 
                                        stroke="black" 
                                        stroke-width="1.5" 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </strong>
                            <ul class="wrap list-group list-group-flush mx-0">
                                <?php foreach($items as $i):?>
                                    <li 
                                        class="list-group-item cursor-pointer select-item-extra" 
                                        data-price="<?=$precioExtra[$cont]?>"
                                        data-extra="<?=$titleExtras[$cont]?>"
                                    >
                                        <?=$i?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php $cont++; $cont2--; endforeach; ?>
                    </ul>
                </div>
                <div class="col-12 text-center">
                    <hr>
                    <p class="px-3"><strong>$<?=$metas['price'][0]?> USD</strong></p>
                    <div 
                        id="addItem-<?=$post->ID?>"
                        class="btn btn-outline-secondary border rounded-4 bg-dark text-white px-5 addItem" 
                        data-id="<?=$post->ID?>"
                    >
                        <?=(LANG == 'en'?'ADD TO CART':'AGREGAR AL CARRITO')?>
                    </div>
                </div>
            </div>
            <?php }else{ ?>
                <div class="text-uppercase">
                <?=apply_filters('the_content', $post->post_content)?>
                </div>
                <div class="col-12 text-center">
                    <!-- <hr> -->
                    <?php if( $post->ID == 43 || $post->ID == 196 ) { ?>
                        <label for="kib" class="text-uppercase mb-2"><input id="kib" type="checkbox" name="kib" value=""> <span id="valuekib"><?=( LANG == 'en' ? 'change kibis for Falafel (vegan)':'cambia los kibis por Falafel (vegano)')?></span></label>
                        
                    <?php } ?>
                    <p class="px-3"><strong>$<?=$metas['price'][0]?> USD</strong></p>
                    <div 
                        id="addItem-<?=$post->ID?>"
                        class="btn btn-outline-secondary border rounded-4 bg-dark text-white px-5 addItem" 
                        data-id="<?=$post->ID?>"
                    >
                    <?=(LANG == 'en'?'ADD TO CART':'AGREGAR AL CARRITO')?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-sm-12 col-md-6 col-lg-6 text-center d-flex justify-content-center">
                <div style="width:70%">
                    <?=apply_filters('the_content', $metas['mas_informacion'][0])?>
                    
                    <?php if( LANG == 'es' ){ ?>
                        <?php if( $post->ID == 192 ): ?> <a href="<?=get_the_permalink(176)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">IR AL BIG BOX</a> <?php endif ?>
                        <?php if( $post->ID == 176): ?> <a href="<?=get_the_permalink(192)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">IR AL SMALL BOX</a> <?php endif ?>
                        
                        <?php if( $post->ID == 196 ): ?> <a href="<?=get_the_permalink(195)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">ir al basket (personalizado)</a> <?php endif ?>
                        <?php if( $post->ID == 195 ): ?> <a href="<?=get_the_permalink(196)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">go to miami basket</a> <?php endif ?>
                    <?php }else{ ?>
                        <?php if( $post->ID == 48 ): ?> <a href="<?=get_the_permalink(190)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">GO TO BIG BOX</a> <?php endif ?>
                        <?php if( $post->ID == 190 ): ?> <a href="<?=get_the_permalink(48)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">GO TO SMALL BOX</a> <?php endif ?>
                            
                        <?php if( $post->ID == 43 ): ?> <a href="<?=get_the_permalink(177)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">go to basket (customizable)</a> <?php endif ?>
                        <?php if( $post->ID == 177 ): ?> <a href="<?=get_the_permalink(43)?>" class="text-uppercase btn btn-outline-secondary border rounded-4 bg-dark text-white px-5">go to miami basket</a> <?php endif ?>
                    <?php } ?>


                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-11 col-lg-10">
                        <img 
                            src="<?=wp_get_attachment_url( $metas['image_more_info'][0] )?>" 
                            alt="<?=$post->post_title?>" 
                            class="full-width"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                ?>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".mySwiper", {
        direction: "vertical",
        autoplay: {
            delay: 5000,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        }
    });
    (function($){
        $('.complementos').on('click','.item-title',function(){
            $(this).siblings('ul').slideToggle();
            $(this).children('svg').toggleClass('select');
        });
        
        $('.select-item-extra').click(function(){
            
            const value = $(this).html().trim();
            const data = $(this).data();
            const key_extra = data.extra.toLowerCase();
            if( extras[ key_extra ].items.includes(value) ){
                extras[key_extra].items = [...extras[key_extra].items.filter( item => item != value )]
                $(this).toggleClass('select');
            }else{
                extras[ key_extra ].p = data.price;
                extras[ key_extra ].items.push(value);
                $(this).toggleClass('select');
            }
            // console.log( extras )
        });

        $('.select-item').click(function(){
            const value = $(this).html().trim()
            const limit = parseInt( $(this).data('limit').trim() )
            const prop = $(this).data('cat').replace(' ','')
            // console.log( complements )
            complements[prop].qty = limit;
            if( 
                complements[prop].items.length < limit && 
                !complements[prop].items.includes( value ) 
            ){
                complements[prop].items.push( value );
                $(this).toggleClass('select');

            }else{
                if( complements[prop].items.includes( value ) ){
                    const newProp = complements[prop].items.filter( item => item != value );
                    complements[prop].items = [...newProp]
                    
                    $(this).toggleClass('select');
                }
            }
        })
    })(jQuery)
  </script>
<?php get_footer() ?>
