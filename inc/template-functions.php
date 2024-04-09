<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Miami_green
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function miami_green_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'miami_green_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function miami_green_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'miami_green_pingback_header' );

function getUrlLogo(){
	$logo = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $logo , 'full' );
	return $image[0];
}
function getSrc($post_id, $size){
    $imgID = get_post_thumbnail_id( $post_id );
    $image = wp_get_attachment_image_src( $imgID , $size );
    return $image[0];
}
// Return url of image attachment
function imgUrl( $post_id ){
	return wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
}

function _complementos(){
	global $post;
	$c = get_post_meta( $post->ID, 'complements', true );
	// var_dump($c);
?>
<style>
	textarea{
		width: 100%;
	}
</style>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>Greens</th><th>Viggies</th><th>Conservas</th>
				<th>Toppings</th><th>Aderezos</th>
			</tr>
		<thead>
		<tbody id="tbody1">
			<?php if($c==""):?>
				<tr>
					<td><textarea rows='10' name='complements[0]'></textarea></td>
					<td><textarea rows='10' name='complements[1]'></textarea></td>
					<td><textarea rows='10' name='complements[2]'></textarea></td>
					<td><textarea rows='10' name='complements[3]'></textarea></td>
					<td><textarea rows='10' name='complements[4]'></textarea></td>
				</tr>
			<?php else: ?>
				<tr>
					<td><textarea rows='10' name='complements[0]?>'><?=( isset( $c[0] ) ? $c[0]: '') ?></textarea></td>
					<td><textarea rows='10' name='complements[1]?>'><?=( isset( $c[1] ) ? $c[1]: '') ?></textarea></td>
					<td><textarea rows='10' name='complements[2]?>'><?=( isset( $c[2] ) ? $c[2]: '') ?></textarea></td>
					<td><textarea rows='10' name='complements[3]?>'><?=( isset( $c[3] ) ? $c[3]: '') ?></textarea></td>
					<td><textarea rows='10' name='complements[4]?>'><?=( isset( $c[4] ) ? $c[4]: '') ?></textarea></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	
	<script type="text/javascript">
		(function($){
			var cont = jQuery('#tbody1 tr').size();
			$('#AddRelation').click(function(){
				$('#tbody1').append(
					'<tr>'+
						'<td><input type="text" name="complements['+cont+'][0]" value=""></td>'+
						'<td><input type="text" name="complements['+cont+'][1]" value=""></td>'+
						'<td><span class="destroyTr button button-primary">Remove</td>'+
					'</tr>'
				);
				cont++;
			});
			$('#tbody1').on('click','.destroyTr',function(){
				$(this).parents('tr').remove();
			});
		})(jQuery);
	</script>
	<?php
}
function _extras(){
	global $post;
	$extras = get_post_meta( $post->ID, 'extras', true );
	
?>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>Granos</th><th>Proteína</th><th></th>
			</tr>
		<thead>
		<tbody id="tbody1">
			<?php if($extras==""):?>
				<tr>
					<td><textarea rows='10' name='extras[0]'></textarea></td>
					<td><textarea rows='10' name='extras[1]'></textarea></td>
					<td><input type="text" name='extras[2]' value="" placeholder="precio"/></td>
				</tr>
			<?php else: ?>
				<tr>
					<td><textarea rows='10' name='extras[0]'><?=( isset( $extras[0] ) ? $extras[0]: '') ?></textarea></td>
					<td><textarea rows='10' name='extras[1]'><?=( isset( $extras[1] ) ? $extras[1]: '') ?></textarea></td>
					<td><input type="text" name='extras[2]' value="<?=( isset( $extras[2] ) ? $extras[2]: '') ?>" placeholder="precio"/></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	<?php
}
add_action('add_meta_boxes', 'meta_boxes');
function meta_boxes(){
	add_meta_box('complementos','Personalizar', '_complementos','product');
	add_meta_box('extras','Extras', '_extras','product');
}


add_action('save_post','save_post_product');
function save_post_product($post_id){
	if( isset( $_POST['complements'] )  ){
    	
		update_post_meta( $post_id, 'extras', $_POST['extras']  );
    	update_post_meta( $post_id, 'complements', $_POST['complements']  );
    }
}