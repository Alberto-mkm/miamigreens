<?php /* Template Name: How To Order */ ?>
<?php get_header() ?>
<?php 
    $terms = _get_terms();
    
?>

<?php foreach($terms as $t): ?>
    
    <?php 
        $term_image = z_taxonomy_image_url( $t->term_id );
        $products = get_products($t->term_id);
    ?>

    <section>
        <div id="cat-<?=$t->slug?>" class="header-section d-flex justify-content-between bg-custom-secondary">
            <h2><?=$t->name?></h2>
            <img src="<?=$term_image?>" alt="<?=$t->name?>">
        </div>
        
        <div class="container">
            <div class="row">
                <?php foreach(products as $o): ?>
                    <div class="col">  
                        <img src="<?=imgUrl( $o->ID )?>" alt="<?=$o->post_title?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>