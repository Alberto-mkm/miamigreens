<?php /* Template Name: Our food */ ?>
<?php get_header() ?>
<?php 
    $list = [];
    $terms = _get_terms();
    $lang = get_lang();
?>
<?php if (LANG == 'en'): ?>
    <style>
    #sect4 .item-margen:nth-child(1){
        order:2
    }
    #sect4 .item-margen:nth-child(2){
        order:3
    } 
</style>
<?php endif; ?>
<?php if (LANG == 'es'): ?>
    <style>
    #sect4 .item-margen:nth-child(1){
        order:3
    }

    #sect4 .item-margen:nth-child(2) {
        order: 2;
    }
</style>
<?php endif; ?>
<style>
    @media(min-width:768px){
        .item-margen{
            margin-left: 30px;
            margin-right: 30px;
        }
    }
</style>
<?php foreach($terms as $t): ?>
    
    <?php 
        $term_image = z_taxonomy_image_url( $t->term_id );
        $products = get_products($t->term_id);
    ?>

    <section id="sect<?=$t->term_id?>">
        <div id="cat-<?=$t->slug?>" class="header-section bg-custom-secondary">
            <div class="container-fluid">
                <div class="row d-flex align-items-center">
                    <div class="col-6">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <span><?=( $lang == 'en' ? 'For you' : 'Para tí' )?></span><br>
                                <h2><?=$t->name?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <img src="<?=$t->description?>" alt="<?=$t->name?>" class="img">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container py-5">
            <div class="row list-products py-5 justify-content-center">
                <?php foreach($products as $o): ?>
                    <?php $metas = get_post_meta($o->ID); ?>
                    <?php $image = imgUrl( $o->ID ) ?>
                    <?php array_push( $list, ['id'=>$o->ID,'image'=>$image,'title'=>$o->post_title, 'price'=> $metas['price'][0]] ); ?>
                    <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3 text-center item-margen">
                        <div class="pb-5">
                            <a href="<?=get_the_permalink($o->ID)?>" class="d-block my-2">
                                <img src="<?=$image?>" alt="<?=$o->post_title?>">
                            </a>
                            <h5 class="my-4"><?=$o->post_title?></h5>
                            <span class="d-block mb-4"><?=$metas['label_price'][0]?></span>
                            <div class="wrap-controls">
                                <div id="controls-<?=$o->ID?>" class="btn-group controls" role="group" aria-label="Basic outlined example" style="display:none">
                                    <span class="btn btn-outline-primary" data-id="<?=$o->ID?>">-</span>
                                    <span class="btn btn-outline-primary count" data-id="<?=$o->ID?>">1</span>
                                    <span class="btn btn-outline-primary" data-id="<?=$o->ID?>">+</span>
                                </div>
                                <a 
                                    id="addItem-<?=$o->ID?>"
                                    class="btn btn-outline-secondary border rounded-4 border-dark px-5 addItem" 
                                    href="<?=get_the_permalink($o->ID)?>"

                                ><?=( $lang == 'en' ? 'Lets start' : 'Empezar' )?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>
<script>
    const products = JSON.parse('<?=json_encode($list)?>');
</script>

<?php get_footer() ?>