<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Miami_green
 */

require get_template_directory() . '/template-parts/icon.svg';
if( $post->ID != 20 && $post->ID != 106 && $post->ID != 179){
	require get_template_directory() . '/custom-templates/form-1.php';
}
	//echo is_page('Contact');
?>
	<?php if( ! is_page('Contact') ): ?>
		<div class="my-5">&nbsp;</div>
	<?php endif; ?>
	<footer id="colophon" class="container border-top border-dark <?=( ! is_page('Contact') ? 'mt-5':'')?> py-5">
		<div class="row mb-4">
			<div class="col-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-12 col-sm-12 col-md-6 col-lg-6 ul-1 <?=( wp_is_mobile() ? 'mobile-center pb-4':'' )?>">
						<h5>Menu</h5>
						<ul>
							<li>
								<a href="<?=site_url('/')?>"><?=( LANG == 'en' ? 'Home' : 'Inicio' )?></a>
							</li>
							<li>
								<a href="<?=site_url( ( LANG == 'en' ? 'our-food' : 'es/nuestra-comida' ) )?>"><?=( LANG == 'en' ? 'Our Food' : 'Nuestra comida' )?></a>
							</li>

							<li>
								<a href="<?=site_url( '/' )?>#about-us"><?=( LANG == 'en' ? 'About Us' : 'Nosotros')?></a>
							</li>
							
							<li>
								<a href="<?=site_url( ( LANG == 'en' ? 'contact' : 'es/contacto' ) )?>"><?=( LANG == 'en' ? 'Contact' : 'Contacto' )?></a>
							</li>
							<li>
								<a href="<?=site_url( '/' )?>#how-to-order"><?=( LANG == 'en' ? 'How to order' : 'Cómo ordenar' )?></a>
							</li>
							<!-- <li>
								<a href="<?=site_url( 'blog' )?>">Blog</a>
							</li> -->
						
						</ul>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6 <?=( wp_is_mobile() ? 'mobile-center':'' )?>">
						<h5><?=( LANG == 'en' ? 'Contact' : 'Contacto' )?></h5>
						<ul>
							<li>
								<a href="mailto:miamieatsgreen@gmail.com">miamieatsgreen@gmail.com</a>
							</li>
							<li>
								<a href="#">Miami Beach, Fl.</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-6 col-lg-6 <?=( wp_is_mobile() ? 'text-center':'' )?>">
				<div class="d-flex <?=( wp_is_mobile() ? 'justify-content-center':'justify-content-end' )?> mob-center">
					<div style="width:250px"><?php the_custom_logo() ?></div><br><br><br><br>
				</div>
				<div class="d-flex <?=( wp_is_mobile() ? 'justify-content-center':'justify-content-end' )?> mob-center">
					<p class="my-0">
						<small>© 2023 MIAMIGREENS. 
							<?=( LANG == 'es' ? 'TODOS LOS DERECHOS RESERVADOS. AVISO DE PRIVACIDAD':'ALL RIGHTS RESERVED. NOTICE OF PRIVACY')?>
						</small>
					</p>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	
		<div class="loader-section">
			<span class="loader"></span>
		</div>
	
</div><!-- #page -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
	<script>
		(function($){
			alertify.set('notifier','position', 'top-right');
			<?php if( wp_is_mobile() ): ?>
				$('#primary-menu').on('click', 'li',function(){
					$('.menu-toggle').trigger('click')
				})
			<?php endif; ?>
		})(jQuery)
		
	</script>
	<script id="tmp_item" type="text/x-handlebars-template">
		{{#each this}}
		  	<li class="list-group-item d-flex align-items-start pl-0 py-3 border-0 rounded-0 w-100">
		  		<img src="{{image}}" alt="{{title}}" />
		    	<div class="ms-2 me-auto text-left">
		      		<div class="fw-bold">{{title}}</div>
		      		<small>{{str_price}}</small>
		      		<div class="btn-group" role="group" aria-label="Basic example">
					  	<span class="btn btn-primary btn-sm" data-simbol="min" data-id="{{id}}">-</span>
					  	<span class="btn btn-outline-primary btn-sm">{{qty}}</span>
					  	<span class="btn btn-primary btn-sm" data-simbol="up" data-id="{{id}}">+</span>
					</div>
					
					<div class="w-100">
						<strong>Extras</strong>
						<small style="display:block;">{{ op_extras extras }}</small>
					</div>
					
		    	</div>
		  	</li>
		{{/each}}
	</script>
	<script id="tmp_carEmpty" type="text/x-handlebars-template">
		<li class="list-group-item d-flex align-items-start pl-0 py-3 border-0 rounded-0">
			<div class="ms-2 me-auto text-center">
		    <div class="fw-bold"><?=( LANG == 'es' ? 'Carrito vacío':'Car empty')?></div>
		    </div>
		</li>
	</script>
	<script id="template" type="text/x-handlebars-template">
		
		{{#each this}}
		  	<tr>
		  		<td><a href="{{link}}"><img src="{{image}}" alt="{{title}}" width="70"></a></td>
		  		<td>
				  	<a href="{{link}}">{{title}}</a> | <small>{{option}}</small>
				</td>
		  		<td class="text-center">
		      		<div class="btn-group" role="group" aria-label="Basic example">
					  	<span class="btn btn-primary btn-sm" data-simbol="min" data-id="{{id}}">-</span>
					  	<span class="btn btn-outline-primary btn-sm">{{qty}}</span>
					  	<span class="btn btn-primary btn-sm" data-simbol="up" data-id="{{id}}">+</span>
					</div>
		    	</td>
				<td class="text-center">
					{{str_price}}
				</td>
				<td class="text-center">
					{{ op_extras extras }}
				</td>
		  	</tr>
			<tr>
				<table>
					<thead>
						<tr>
							<th class="text-center"><?=( LANG == 'es' ? 'Conservas':'Canned food')?></th>
							<th class="text-center"><?=( LANG == 'es' ? 'Aderezos':'Dressings')?></th>
							<th class="text-center">Greens</th>
							<th class="text-center">Toppings</th>
							<th class="text-center">Veggies</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<ol class="mx-3">
								{{#each complements.cannedfood.items }}
									<li>{{this}}</li>
								{{/each}}
								</ol>
							</td>
							<td>
								<ol class="mx-3">
									{{#each complements.dressings.items }}
										<li>{{this}}</li>
									{{/each}}
								</ol>
							</td>
							<td>
								<ol class="mx-3">
									{{#each complements.green.items }}
										<li>{{this}}</li>
									{{/each}}
								</ol>
							</td>
							<td>
								<ol class="mx-3">
									{{#each complements.toppings.items }}
										<li>{{this}}</li>
									{{/each}}
								</ol>
							</td>
							<td>
								<ol class="mx-3">
									{{#each complements.veggies.items }}
										<li>{{this}}</li>
									{{/each}}
								</ol>
							</td>
						</tr>

					</tbody>
				</table>
			</tr>
			<tr>
				<table>
					<thead>
						<tr>
							<th colspan="2" ><?=( LANG == 'es' ? 'Granos':'Grains')?></th>
							<th colspan="2" ><?=( LANG == 'es' ? 'Proteina':'Protein')?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2">
								<ol class="mx-3">
								{{#each extras.grains.items }}
									<li>{{this}} - ${{../extras.grains.p}}</li>
								{{/each}}
								</ol>
							</td>
							<td colspan="2">
								<ol class="mx-3">
								{{#each extras.protein.items }}
									<li>{{this}} - ${{../extras.protein.p}}</li>
								{{/each}}
								</ol>
							</td>
						</tr>
					</tbody>
				</table>
			</tr>
		{{/each}}
	</script>

<?php wp_footer(); ?>

</body>
</html>
