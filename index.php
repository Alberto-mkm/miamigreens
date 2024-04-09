<?php /* Template Name: Home */ ?>
<?php get_header() ?>
<style>
	.fruits{
		background-repeat: no-repeat!important;
		background-position: bottom right!important;
	}
	@media(max-width:768px){
		#about-us{
			text-align: center;
			height:600px
		}
	}
	
</style>
<?php 

	$terms = get_terms( array(
		'taxonomy'   => 'package',
		'orderby' => 'term_id',
		'order' => 'ASC',
		'hide_empty' => false,
	) );
	$metas = get_post_meta($post->ID);
	
	$translations = pll_the_languages( array( 'raw' => 1 ) );
	$lang = 'es';
	if( $translations['en']['current_lang'] ){
		$lang = 'en';
	}
?>
		<section id="section1" class="py-4">
			<div class="row d-flex justify-content-center mt-4 mb-4">
				<div class="col-lg-9 col-xl-8 text-center">
					<div><?=$post->post_content?></div>
					<img src="<?php echo imgUrl($post->ID) ?>" class="full-width" />
					<a href="#" class="btn bg-dark px-5 text-white rounded-pill build-your">
						<?=( $lang == 'es' ? 'HACER PEDIDO':'ORDER NOW' ) ?>
					</a>
				</div>
			</div>
		</section>
		<section id="how-to-order" class="pt-5 pt-4">
			<div class="container section_4">
				<div class="row d-flex align-items-center">
					<div class="col-sm-12 col-md-12 col-lg-12 text-center">
						<?=apply_filters('the_content', $metas['seccion_4_contenido'][0] )?>
					</div>
				</div>
			</div>
		</section>
		<section class="container mt-5 pt-4 mb-5 packages ">
			<div class="row">
				<?php foreach($terms as $t): ?>
					<?php 
						$term_meta = get_term_meta( $t->term_id );
						$term_image = z_taxonomy_image_url( $t->term_id ); 
					?>
					<div class="col-6 col-sm-4 col-md-4 col-lg-4 text-center">
						<img src="<?=$term_image?>" alt="<?=$t->name?>">
						<div class="my-4">
							<strong class="d-block my-3"><?=$t->name?></strong>
							<div class="mt-2 mb-4"><?=( $lang == 'es' ? 'DESDE':'FROM' ) ?> <?=$term_meta['price'][0]?></div>
							<a href="<?=site_url('/our-food#cat-'.$t->slug)?>" title="<?=$t->name?>" class="btn btn-outline-secondary border rounded-pill border-dark px-5">
								<?=( $lang == 'es' ? 'HACER PEDIDO':'LEST START' ) ?>
							</a>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</section>
		<section id="about-us" class="pt-4 pt-5 bg-custom-secondary fruits" style="background-image: url(<?=get_post($metas['imagen_c1'][0])->guid?>)">
			<div class="container-fluid">
				<div class="row d-flex align-items-center">
					<div class="col-sm-12 col-md-6 col-lg-7">
						<div class="row d-flex justify-content-center mx-0">
							<div class="col-sm-12 col-md-10 col-lg-6 content-title px-0">
								<?=apply_filters('the_content', $metas['contenido'][0])?><br>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-5 d-flex justify-content-end">
					</div>
				</div>
			</div>
		</section>
		<section class="py-5 preview-blog">
			<div class="container my-5">
				<div class="row d-flex align-items-center">
					<div class="col-sm-12 col-md-6 col-lg-6 wrap-button-outline">
						<?=apply_filters('the_content', $metas['contenido_seccion_5'][0])?>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-6">
						<img src="<?=get_post($metas['imagen_seccion_5'][0])->guid?>" style="max-width:100%"/>
					</div>
				</div>
			</div>
		</section>

<?php
get_footer();
