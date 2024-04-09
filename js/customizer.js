/* eslint-disable no-var, no-undef, camelcase, array-callback-return, no-console */
/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 * @param $
 */

( function( $ ) {
	$.fn.serializeObject = function() {
		var self = this,
			json = {},
			push_counters = {},
			patterns = {
				validate: /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
				key: /[a-zA-Z0-9_]+|(?=\[\])/g,
				push: /^$/,
				fixed: /^\d+$/,
				named: /^[a-zA-Z0-9_]+$/,
			};

		this.build = function( base, key, value ) {
			base[ key ] = value;
			return base;
		};

		this.push_counter = function( key ) {
			if ( push_counters[ key ] === undefined ) {
				push_counters[ key ] = 0;
			}
			return push_counters[ key ]++;
		};

		$.each( $( this ).serializeArray(), function() {
			// Skip invalid keys
			if ( ! patterns.validate.test( this.name ) ) {
				return;
			}

			var k,
				keys = this.name.match( patterns.key ),
				merge = this.value,
				reverse_key = this.name;

			while ( ( k = keys.pop() ) !== undefined ) {
				// Adjust reverse_key
				reverse_key = reverse_key.replace( new RegExp( '\\[' + k + '\\]$' ), '' );

				// Push
				if ( k.match( patterns.push ) ) {
					merge = self.build( [], self.push_counter( reverse_key ), merge );
				}

				// Fixed
				else if ( k.match( patterns.fixed ) ) {
					merge = self.build( [], k, merge );
				}

				// Named
				else if ( k.match( patterns.named ) ) {
					merge = self.build( {}, k, merge );
				}
			}

			json = $.extend( true, json, merge );
		} );

		return json;
	};
	$( '.list-products' ).on( 'click', '.addItem', function() {
		const product_id = $( this ).data( 'id' );
		carrito.addItem( product_id, 'add' );
	} );

	$( '.single-product' ).on( 'click', '.addItem', function() {
		const product_id = $( this ).data( 'id' );
		carrito.addItem( product_id, 'add' );
	} );

	// controls item-card
	$( '.controls' ).on( 'click', 'span', function() {
		const controls = $( this ).parents( '.wrap-controls' );
		const input = $( controls ).find( '.count' );

		let count = $( input ).html() || 1;
		const product_id = $( this ).data( 'id' );

		if ( $( this ).html() === '+' && count < 40 ) {
			count++;
			carrito.addItem( product_id, 'add' );
		}
		if ( $( this ).html() === '-' && count > 0 ) {
			count--;
			carrito.updateItem( product_id, 'rest' );
		}
	} );

	const listProducts = document.getElementById( 'listProducts' );
	const checkoutList = document.getElementById( 'checkoutList' );
	// cart_items es el id de la lista del carrito que esta en el header
	cart_items.addEventListener( 'click', ( { target } ) => {
		if ( target.dataset.hasOwnProperty( 'id' ) ) {
			carrito.updateItem( target.dataset.id, target.dataset.simbol );
			// console.log( checkoutList );
			if ( checkoutList ) {
				compiled_tmp( 'temp_item', 'checkoutList', carrito.items );
			}
		}
	} );

	if ( listProducts ) {
		// compiled_tmp( 'template', 'checkoutList', carrito.items );
		listProducts.addEventListener( 'click', ( { target } ) => {
			if ( target.dataset.hasOwnProperty( 'id' ) ) {
				console.log( target.dataset.id, target.dataset.simbol );
				carrito.updateItem( target.dataset.id, target.dataset.simbol );
				compiled_tmp( 'template', 'listProducts', carrito.items );
			}
		} );
	}

	const wrapComplements = $( 'ul.complements' );
	if ( wrapComplements && post_id ) {
		const prod = carrito.items.find( ( product ) => product.id === post_id );
		// if ( prod ) {
		// 	prod.complements.forEach( ( item, ) => {
		// 		if ( $( '#' + item.name + '-' + post_id ) && item.select ) {
		// 			$( '#' + item.name + '-' + post_id ).prop( 'checked', true );
		// 		}
		// 	} );
		// }

		$( wrapComplements ).on( 'change', 'input', ( { target } ) => {
			carrito.items.map( ( item ) => {
				if ( item.id == post_id ) {
					item = prod;
				}
				return item;
			} );
			carrito.save();
		} );
	}

	async function sendMessage( data ) {
		const location = window.location.hostname;
		const settings = {
			method: 'POST',
			headers: {
				Accept: 'application/json',
				'Content-Type': 'application/json',
			},
			body: JSON.stringify( data ),
		};
		try {
			const url = `https://${ location }/wp-json/send-message/v1/contact`;
			const fetchResponse = await fetch( url, settings );
			const data = await fetchResponse.json();

			alertify.success( data.message );
			$( '.loader-section' ).toggleClass( 'show' );
			// return data;
		} catch ( e ) {
			return e;
		}
	}
	$( '#commentForm' ).validate( {
		rules: {
			name: 'required',
			message: 'required',
			email: {
				required: true,
				email: true,
			},
		},
		submitHandler( data ) {
			const body = $( data ).serializeObject();
			$( '.loader-section' ).toggleClass( 'show' );
			sendMessage( body );
		},
	} );
}( jQuery ) );
