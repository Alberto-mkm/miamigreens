<?php $lang = get_lang() ?>
<div class="container text-center form-single">
    <div class="row">
        <?php if( $lang == 'en'){ ?>
            <h2>
                Need something even more special?
            </h2>
            <p>Send us a message; we'll get back to you promptly.</p>
        <?php }else{?>
            <h2>
            ¿Necesitas algo aún más personalizado?
            </h2>
            <p>Envíanos un mensaje, te atenderemos a la brevedad.</p>
        <?php } ?>
    </div>
    <form id="commentForm" action="" class="form-horizontal row d-flex justify-content-center">
        <div class="col-xs-12 col-sm-11 col-md-10 col-lg-9">
            <div class="row">
                <div class="col-6">
                    <label for="name">
                        <input id="name" type="text" name="name" placeholder="">
                        <span><?=( $lang == 'en' ? 'Name': 'Nombre')?></span>
                    </label>
                </div>
                <div class="col-6">
                    <label for="number">
                        <input id="number" type="text" name="number" placeholder="">
                        <span><?=( $lang == 'en' ? 'Telephone': 'Número de teléfono')?></span>
                    </label>
                </div>
                <div class="col-6">
                    <label for="email">
                        <input id="email" type="email" name="email" placeholder="">
                        <span><?=( $lang == 'en' ? 'Email': 'Correo electrónico')?></span>
                    </label>
                </div>
                <div class="col-6">
                    <label for="message">
                        <input for="id" type="text" name="message" placeholder="">
                        <span><?=( $lang == 'en' ? 'Message': 'Mensaje')?></span>
                    </label>
                </div>
                <div class="col-12 text-center">
                    <button class="my-3"><?=( $lang == 'en' ? 'SEND': 'ENVIAR')?></button>
                </div>
            </div>
        </div>
    </form>
</div>