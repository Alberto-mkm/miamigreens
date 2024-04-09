<?php get_header() ?>
<?php /* Template Name: Contact */ ?>
<?php $lang = get_lang(); ?>
<div class="bg-site contact">
<div class="container-fluid form-single contact px-0">
    <form id="commentForm" action="" class="form-horizontal">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <div class="row justify-content-center mx-0">
                    <div class="col-8 pt-5">
                        <div>
                            <h2 class="text-left title-contact">
                                <?=( 
                                    $lang == 'es' ? 
                                    '¿Necesitas algo aún más personalizado?'
                                    : 'Need something even more special?.'
                                )?>
                            </h2>
                             <p><?=( $lang == 'en' ? 'Send us a message; we\'ll get back to you promptly.': 'Envíanos un mensaje, te atenderemos a la brevedad.' )?></p>
                        </div>
                        <label for="name" class="py-2">
                            <input id="name" name="name" type="text" placeholder="">
                            <span><?=( $lang == 'en' ? 'Name': 'Nombre')?></span>
                        </label>
                        
                        <label for="number" class="py-2">
                            <input id="number" name="number" type="text" placeholder="">
                            <span><?=( $lang == 'en' ? 'Telephone': 'Número de teléfono')?></span>
                        </label>
                        
                        <label for="email" class="py-2">
                            <input id="email" name="email" type="email" placeholder="">
                            <span><?=( $lang == 'en' ? 'Email': 'Correo electrónico')?></span>
                        </label>
                        
                        <label for="message" class="py-2">
                            <input for="id" name="message" type="text" placeholder="">
                            <span><?=( $lang == 'en' ? 'Message': 'Mensaje')?></span>
                        </label>
                        <div class="col-12 text-center">
                        <button class="my-3"><?=( $lang == 'en' ? 'SEND': 'ENVIAR')?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 px-0 py-0 d-flex justify-content-end d-none d-md-block d-lg-block">
                <img src="<?=imgUrl($post->ID)?>" alt="Contact" class="img-fluid">
            </div>
        </div>
    </form>
</div>
</div>
<?php get_footer() ?>