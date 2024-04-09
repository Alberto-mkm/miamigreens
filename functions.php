<?php
	
/**
 * Miami green functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Miami_green
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}
$translations = pll_the_languages( array( 'raw' => 1 ) );
$lang = 'es';
if( $translations['en']['current_lang'] ){
	$lang = 'en';
}
define( 'LANG', $lang );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function miami_green_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Miami green, use a find and replace
		* to change 'miami-green' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'miami-green', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'miami-green' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'miami_green_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'miami_green_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function miami_green_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'miami_green_content_width', 640 );
}
add_action( 'after_setup_theme', 'miami_green_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function miami_green_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'miami-green' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'miami-green' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'miami_green_widgets_init' );



// function load_scripts() {
//     wp_enqueue_style( 'stylecss', get_stylesheet_uri() );  
// }

// add_action('wp_enqueue_scripts', 'load_scripts' );
/**
 * Enqueue scripts and styles.
 */
function miami_green_scripts() {
	wp_enqueue_style( 'miami-green-style', get_stylesheet_uri() );
	wp_style_add_data( 'miami-green-style', 'rtl', 'replace' );

	wp_enqueue_script( 'miami-green-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'miami-green-car', get_template_directory_uri() . '/js/car.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'miami-green-customize', get_template_directory_uri() . '/js/customizer.js', array('jquery'), _S_VERSION, true );

	wp_enqueue_style( 'miami-green-alertify-css', get_template_directory_uri().'/css/alertify.min.css' );
	wp_enqueue_script( 'miami-green-alertify-js', get_template_directory_uri(). '/js/alertify.min.js' , false );

	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'miami_green_scripts' );

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/pedido-post.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


add_filter( 'rwmb_meta_boxes', 'galeria_meta_boxes' );

function galeria_meta_boxes( $meta_boxes ) {
    $prefix = '';

    $meta_boxes[] = [
        'title'   => esc_html__( 'Galeria', 'online-generator' ),
        'id'      => 'untitled',
        'context' => 'normal',
		'post_types' => ['product'],
        'fields'  => [
            [
                'type'             => 'image_advanced',
                'name'             => esc_html__( 'Galería', 'online-generator' ),
                'id'               => $prefix . 'galeria',
                'max_file_uploads' => 40,
            ],
        ],
    ];

    return $meta_boxes;
}

function _get_terms(){
	$terms = get_terms( array(
		'taxonomy'   => 'package',
		'orderby' => 'term_id',
		'order' => 'ASC',
		'hide_empty' => false,
	) );
	return $terms;
}
function get_products($term_id){
	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'package',
				'field' => 'term_id',
				'terms' => $term_id
			),
		),
	);
	$query = new WP_Query( $args );
	return $query->posts;
}
function get_all_products(){
	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish'
	);
	$query = new WP_Query( $args );

	$products = [];
	$metas = [];
	$complements = [];
	$extras = []; $items = []; $precio = null; $custom = false;
	foreach ($query->posts as $o){
		$metas = get_post_meta( $o->ID );

		if( isset($metas['extras'][0]) ){
			$extras = unserialize($metas['extras'][0]);
			if( $extras[0] && $extras[1] ){
				$granos = explode(PHP_EOL, $extras[0] );
				$proteina = explode(PHP_EOL, $extras[1] );
				
				
				foreach($granos as $k => $c1 ){  $granos[$k] = trim($c1); }
				foreach($proteina as $k1 => $c2 ){  $proteina[$k1] = trim($c2); }
				$precio = explode('-',$extras[2]);
			}
			if( isset($precio[1]) && !empty($precio[1]) ){
				$extras = [
					'protein' => ['p'=> floatval($precio[1]),'items'=>$proteina],
					'grains' => ['p'=> floatval($precio[0]),'items'=>$granos]
					
				];
			}
			
		}
		if( isset($metas['complements'][0]) ){
			$complement = unserialize( $metas['complements'][0] );
			$cont = 0;  $qty = 0; $items;
			foreach($complement as $c){
				if( $c != "" ){
					$items = explode( PHP_EOL, $c );
					
					if( is_array($items) ){
						foreach($items as $k => $item){
							$items[$k] = trim($item);
						}
						$qty = intval( array_shift($items) );
						$custom = true;
					}
						
					if( $cont == 0 ){
						$complements['green'] = ['qty'=>$qty,'items' => $items ];
					}
					if( $cont == 1 ){
						$complements['veggies'] = ['qty'=>$qty,'items' => $items ];
					}
					if( $cont == 2 ){
						$complements['pickles'] = ['qty'=>$qty,'items' => $items ];
					}
					if( $cont == 3 ){
						$complements['toppings'] = ['qty'=>$qty,'items' => $items ];
					}
					if( $cont == 4 ){ 
						$complements['dressings'] = ['qty'=>$qty,'items' => $items ];
					}
					$cont++;
				}
			}
			
		}else{
			$custom = false;
		}
		
		array_push($products, [
			'id' => $o->ID,
			'image' => imgUrl($o->ID),
			'link' => get_the_permalink( $o->ID ),
			'title' => $o->post_title,
			'price' => $metas['price'][0],
			'complements' => $complements,
			'extras' => $extras,
			'custom' => $custom
		]);
		$custom = false;
		$complements = []; $items = [];
	}

	return $products;
}

function get_lang(){
	$translations = pll_the_languages( array( 'raw' => 1 ) );
	$lang = 'es';
	if( $translations['en']['current_lang'] ){
		$lang = 'en';
	}
	return $lang;
}

/**
 * This is our callback function that embeds our phrase in a WP_REST_Response
 */
function get_template_email($name, $number, $message, $email){
	$title = LANG == 'es' ? 'Mensaje de contacto' : 'Contact message';
	$html = '
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="x-apple-disable-message-reformatting">
		<title>'.$title.'</title>
		</head>
			<body class="clean-body u_body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000">
				<p>'.$name.'</p>
				<p>'.$message.'</p>
  				<p style="font-size: 14px; line-height: 140%;">'.$email.' | '.$number.'<s/p>
  			
			</body>
		</html>
	';
	return $html;
}

function sendMailContact($request) {
    // rest_ensure_response() wraps the data we want to return into a WP_REST_Response, and ensures it will be properly returned.
    // return rest_ensure_response( 'Hello World, this is the WordPress REST API' );
	$name = sanitize_text_field( $request['name'] );
	$number = $request['number'];
	$message = sanitize_text_field( $request['message'] );
	$email = sanitize_email($request['email']);
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response = [
			'status' => 400,
			'data' => 'no-data',
			'message' => 'Ocurrió un error intente mas tarde...'
		];

		$body = get_template_email($name, $number, $message, $email);
		$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Miamigreen<contacto@albertomkm.com>');
		$isSuccess = wp_mail('miamieatsgreen@gmail.com', 'Mensaje de contacto', $body, $headers );

		if( $isSuccess ){
			$response = [
				'status' => 200,
				'data' => 'no-data',
				'message' => 'Tu mensaje se ha enviado con éxito'
			];
		}

		return rest_ensure_response($response);

	}

	
}
function saveResponsePaypal($request){
	
	// Create post object
	$my_post = array(
		'post_title'    => wp_strip_all_tags( 'Response Paypal' ),
		'post_content'  => json_encode($request["resource"]),
		'post_status'   => 'publish',
		'post_author'   => 1,
		// 'post_category' => array( 8,39 )
	);
	
	// Insert the post into the database
	wp_insert_post( $my_post );
	return rest_ensure_response( $data );
}
function _saveOrder($request){
	$title = "Orden-".$request['orderID'];
	$data = [
		'car'			=> $request['cart'],
		'orderID' 		=> $request['orderID'],
		'payerID' 		=> $request['payerID'],
		'paymentID' 	=> $request['paymentID'],
		'paymentSource' => $request['paymentSource']
	];
	// Create post object
	$my_post = array(
		'post_title'    => wp_strip_all_tags( $title ),
		'post_content'  => json_encode($data),
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type'		=> 'pedido'
	);
	
	// Insert the post into the database
	$data['id'] = wp_insert_post( $my_post );
	
	return rest_ensure_response( $data );
}
/**
 * This function is where we register our routes for our example endpoint.
 */
require get_template_directory() . '/inc/paypal/api/Config/Config.php';
require get_template_directory() . '/inc/paypal/api/Helpers/PayPalHelper.php';

require get_template_directory() . '/inc/paypal/api/captureOrder.php';
require get_template_directory() . '/inc/paypal/api/createOrder.php';
require get_template_directory() . '/inc/paypal/api/getOrderDetails.php';
require get_template_directory() . '/inc/paypal/api/patchOrder.php';

function prefix_register_example_routes() {
    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
    register_rest_route( 'send-message/v1', '/contact', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'sendMailContact',
		'permission_callback' => function() { return ''; }
    ) );
	register_rest_route( 'process/v1', '/response-paypal', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'saveResponsePaypal',
		'permission_callback' => function() { return ''; }
    ) );

	/*
		captureOrder
		createOrder
		getOrder
		patchOrder
	*/
	register_rest_route( 'paypal/v1', '/createOrder', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'createOrder',
		'permission_callback' => function() { return ''; }
    ) );
	register_rest_route( 'paypal/v1', '/patchOrder', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'patchOrder',
		'permission_callback' => function() { return ''; }
    ) );
	register_rest_route( 'paypal/v1', '/getOrderDetails', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'getOrderDetails',
		'permission_callback' => function() { return ''; }
    ) );
	register_rest_route( 'paypal/v1', '/captureOrder', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'captureOrder',
		'permission_callback' => function() { return ''; }
    ) );

	register_rest_route( 'create', '/payment-intent', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'sendOrder',
		'permission_callback' => function() { return ''; }
    ) );
	
	
}

add_action( 'rest_api_init', 'prefix_register_example_routes' );


function sendOrder($request) {

	require_once get_template_directory() . '/inc/stripe/autoload.php';

    $stripeSecretKey = 'sk_test_51P2NR8P4kMLp6ioT0rLZNNEUYIqzV27uKE847BP0TJY4H4kdeptb6QzTDlHay2xO2ypjcLlyGIYGSkJs1PJOkfMS00ybyFJ3Cc';
    
    $stripe = new \Stripe\StripeClient($stripeSecretKey);

    $order = $stripe->paymentIntents->create(
        [
          'amount' => 4500,
          'currency' => $request['currency'],
          'automatic_payment_methods' => ['enabled' => true],
        ],
        ['stripe_account' => 'acct_1P2NR8P4kMLp6ioT']
    );
	return $order;
}



//Add input field in the settings page in WP dashboard
add_filter('admin_init', 'rp_register_general_settings_custom_field');
function rp_register_general_settings_custom_field(){
	register_setting('general', 'delivery', 'esc_attr');
	register_setting('general', 'tax', 'esc_attr');
	
	add_settings_field('delivery', '<label for="sml_host">'.__('Opciones de envío').'</label>' , 'create_sml_host_field_html', 'general');
}
function create_sml_host_field_html(){
    $delivery = get_option( 'delivery','' );
    $tax = get_option( 'tax', '' );
			
	echo '<label>Delivery: </label><input type="text" id="sml_delivery" name="delivery" class="regular-text" value="' . $delivery . '" /><br/>';
	echo '<label>Tax: </label><input type="text" id="sml_tax" name="tax" class="regular-text" value="' . $tax . '" /><br/>';
}
