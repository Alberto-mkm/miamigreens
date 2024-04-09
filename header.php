<?php session_start();
	$args = [
		// 'theme_location' => 'Menu-en',
		'menu_id'        => 'primary-menu',
	];
	$lang = get_lang();
	// echo "<pre>";
	// var_dump( json_encode( get_all_products() ) );
	// echo "</pre>";
	// die();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Overpass&display=swap" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js" integrity="sha512-zT3zHcFYbQwjHdKjCu6OMmETx8fJA9S7E6W7kBeFxultf75OPTYUJigEKX58qgyQMi1m1EgenfjMXlRZG8BXaw==" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
	
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	<?php wp_head(); ?>
	<?php 
		$todos = get_all_products();
		// print_r($todos[2]); die();
	?>
	<script>
		// json_encode( get_all_products() )
		var allProducts = JSON.parse('<?=json_encode($todos)?>');
		console.log( allProducts )
		localStorage.setItem('todos', '<?=json_encode($todos)?>');
		var post_id = <?=( isset($post->ID) ? $post->ID : false )?>;
		var complements = {
            green : {'qty':0,'items':[]},
            veggies : {'qty':0,'items':[]},
            pickles : {'qty':0,'items':[]},
            toppings : {'qty':0,'items':[]},
            dressings : {'qty':0,'items':[]}
        };
		var extras = {
			grains : { p: 0, items : [] },
			protein : { p: 0, items : [] }
		}
	</script>
	<style>
		#zoid-paypal-buttons-uid_4a694d9a4c_mji6mzi6mty > iframe.visible{
			z-index: 1!important;
		}
		.ajs-message{
			z-index: 1111111111;
		}
		.alertify-notifier.ajs-right .ajs-message.ajs-visible{
			right:90px;
			top: -25px;
		}
		.menu-toggle {
    		background-color: white;
		}
		.loader{
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translateY(-50%,-50%);
		}
		#cart_items{
			display: block;
			height: 380px;
			overflow: scroll;
		}
		.btn-outline-secondary.border.rounded-4.bg-dark.text-white{
			margin-bottom: 20px;
		}
		
		.ajs-message.ajs-warning.ajs-visible{
			padding: 10px 0px;
			margin: 0px;
			display: inline-block;
			left: inherit;
			right: 250px !important;
			top: 0px !important;
			background-color: #c1a51d;
			transition: .5s;
			width: 230px;
			text-align: center;
		}
		.slogo-mobile{
			display: block;
			margin: 0 auto;
		}
	</style>
</head>

<body <?php body_class(); ?>>
<div id="page" class="container-fluid px-0">

	<header id="masthead" class="site-header">
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<?php esc_html_e( 'Menu', 'miami-green' ); ?>
			</button>
			<div class="logo-mobile" style="width: 220px;"><?php the_custom_logo() ?></div>
			<?php wp_nav_menu($args); ?>
				<div class="wrap-cart">
					<input id="aux" type="checkbox" class="d-none">
					<label id="showCar_list" for="aux" class="btn btn-sm">
						<img src="<?=site_url('/wp-content/uploads/2024/01/car.png')?>" alt="Miamigreen" width="50" style="position:relative;top:2px">
					</label>
					<label id="hideCar_list" for="aux" class="btn btn-primary btn-sm"><?=( LANG == 'es' ? 'Cerrar' : 'Close' )?></label>
					<div id="previewCar" class="bg-white">
						<ul id="cart_items" class="list-group m-0 p-0 bg-white border rounded-0">
						</ul>
						<div class="justify-content-between list-group-item d-flex align-items-start p-3 border rounded-0">
							<strong>Total: </strong><span id="totalCar"></span>
						</div>
						<div class="justify-content-between list-group-item d-flex align-items-start p-3 border rounded-0">
							<a href="<?=site_url('/checkout')?>" class="btn btn-primary text-white w-100">
								<?=( LANG == 'es' ? 'Ver Resumen de compra' : 'Checkout' )?>
							</a>
						</div>
					</div>
					<span id="totalItems"></span>
				</div>
		</nav><!-- #site-navigation -->
		<div class="slogo-mobile d-block d-sm-none d-md-none d-lg-none text-center" style="width: 220px;"><?php the_custom_logo() ?></div>
	</header><!-- #masthead -->
