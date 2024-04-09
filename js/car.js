/* eslint-disable no-var, no-undef, camelcase, array-callback-return, no-console */
const currency = 'USD'; // predeterminado
function format( amount, decimalCount = 0, decimal = '.', thousands = ',' ) {
	try {
		decimalCount = Math.abs( decimalCount );
		decimalCount = isNaN( decimalCount ) ? 2 : decimalCount;
		const negativeSign = amount < 0 ? '-' : '';
		const i = parseInt( amount = Math.abs( Number( amount ) || 0 ).toFixed( decimalCount ) ).toString();
		const j = ( i.length > 3 ) ? i.length % 3 : 0;
		const price = negativeSign + ( j ? i.substr( 0, j ) + thousands : '' ) + i.substr( j ).replace( /(\d{3})(?=\d)/g, '$1' + thousands ) + ( decimalCount ? decimal + Math.abs( amount - i ).toFixed( decimalCount ).slice( 2 ) : '' );
		return `$${ price } ${ currency }`;
	} catch ( e ) {
		console.log( e );
	}
}
// registramos la función para poder utilizarlo en las plantillas de handelbars
Handlebars.registerHelper( 'formatN', function( price ) {
	return format( price, 2 );
} );

Handlebars.registerHelper( 'op_extras', function( extras ) {
	let total = 0;
	total = total + ( extras.protein.p * extras.protein.items.length );
	total = total + ( extras.grains.p * extras.grains.items.length );
	return format( total, 2 );
} );

Handlebars.registerHelper( 'ctotal', function( price, complements ) {
	let total = 0;
	// if ( complements ) {
	// 	total = total + complements.reduce( ( a, c ) => parseInt( a ) + parseInt( c.price ), 0 );
	// }
	total = total + parseFloat( price );
	return format( total, 2 );
} );

// Template, Render, object => single or collection
function compiled_tmp( tmp, tmpOut, object ) {
	// existe?
	if ( ! document.getElementById( tmpOut ) ) {
		return;
	}
	//Grab the inline template
	const template = document.getElementById( tmp ).innerHTML;

	//Compile the template
	const compiled_template = Handlebars.compile( template );
	//Render the data into the template
	const rendered = compiled_template( object );
	//Overwrite the contents of #target with the renderer HTML
	document.getElementById( tmpOut ).innerHTML = rendered;
}
class Carrito {
	constructor( array ) {
		this.items = array;
	}
	init() {
		if ( Cookies.getJSON( 'items' ) === undefined ) {
			Cookies.set( 'items', [], { expires: 15 } );
		}
		this.items = Cookies.getJSON( 'items' );
		this.render();
	}
	getData( product_id ) {
		return this.items.find( ( p ) => p.id === product_id );
	}
	save() {
		Cookies.set( 'items', this.items );
		this.render( 'save' );
	}
	updateQtyItem( e, a ) {
		this.items.find( ( p ) => {
			if ( p.id === e ) {
				if ( a === 'up' ) {
					this.deleteItem( e );
				} else {
					p.qty--;
				}
			}
			this.save();
		} );
	}
	empty() {
		Cookies.set( 'items', [] );
	}
	totalItems() {
		return this.items.length;
	}
	deleteItem( product_id ) {
		const items = this.items;
		this.items = items.filter( ( p ) => p.id !== product_id );
		this.save();
	}
	updateItem( e, a ) {
		let qty = 0;
		e = parseInt( e );
		this.items.find( ( p ) => {
			if ( p.id === e ) {
				qty = ( a === 'up' ? p.qty + 1 : p.qty - 1 );
				if ( qty === 0 ) {
					this.deleteItem( e );
				} else {
					p.qty = qty;
				}
				qty = 0;
			}
			this.save();
		} );
	}
	tax() {
		const taxElement = document.getElementById( 'tax' );
		if ( taxElement ) {
			const dataTax = parseFloat( taxElement.dataset.tax );
			const subTotal = this.getSubtotal();
			return ( parseFloat( dataTax ) * subTotal.total ).toFixed( 2 );
		}
		return 0;
	}
	getSubtotal() {
		let total = 0,
			total_extras = 0;
		this.items.forEach( ( p ) => {
			total_extras = total_extras + ( p.extras.protein.p * p.extras.protein.items.length );
			total_extras = total_extras + ( p.extras.grains.p * p.extras.grains.items.length );
			total_extras = total_extras * p.qty;
			total = total + ( Number( p.price ) * p.qty );
		} );
		// total = total;
		// total_extras = total_extras;
		total = total + total_extras;
		return { total, total_extras };
	}
	total() {
		// let total = 0,
		// 	total_extras = 0;
		// this.items.forEach( ( p ) => {
		// 	total_extras = total_extras + ( p.extras.protein.p * p.extras.protein.items.length );
		// 	total_extras = total_extras + ( p.extras.grains.p * p.extras.grains.items.length );
		// 	total_extras = total_extras * p.qty;
		// 	total = total + ( Number( p.price ) * p.qty );
		// } );
		const subTotal = this.getSubtotal();
		if ( document.getElementById( 'totalCheckout' ) ) {
			totalCheckout.innerHTML = `$${ subTotal.total } USD`;
		}

		let realTotal = 0;
		const tax = this.tax( subTotal );
		if ( tax ) {
			// delivery
			let delivery = document.getElementById( 'delivery' );
			delivery = parseFloat( delivery.dataset.delivery );
			console.log( tax, subTotal.total, delivery );
			realTotal = parseFloat( tax ) + subTotal.total + parseFloat( delivery );

			extrasCheckout.firstChild.nodeValue = subTotal.total_extras;

			taxCheckout.firstChild.nodeValue = tax + ' ' + currency;
			ftotalCheckout.innerHTML = ( realTotal.toFixed( 2 ) ) + ' ' + currency;
		}

		const _total = realTotal || subTotal.total;
		const string = format( _total, 2 );
		return { number: _total, string };
	}
	getItem( product_id ) {
		return this.items.find( ( p ) => p.id === product_id );
	}
	validateCant( db_product, add_product ) {
		const db_comp = db_product.complements;
		const add_comp = add_product.complements;
		console.log( db_comp, add_comp );
		let complete = false;
		const claves = Object.keys( db_comp );
		const limit = claves.length;
		for ( let i = 0; i < limit; i++ ) {
			const clave = claves[ i ];
			if ( add_comp[ clave ].qty > 0 ) {
				complete = ( add_comp[ clave ].qty === add_comp[ clave ].qty ) ? true : false;
			} else {
				complete = false;
			}
		}

		return complete;
	}
	resetVars() {
		complements = {
			green: { qty: 0, items: [] },
			veggies: { qty: 0, items: [] },
			cannedfood: { qty: 0, items: [] },
			toppings: { qty: 0, items: [] },
			dressings: { qty: 0, items: [] },
		};
		extras = {
			grains: { p: 0, items: [] },
			protein: { p: 0, items: [] },
		};
	}
	addItem( product_id, operacion ) {
		const product = this.getData( product_id );
		if ( product === undefined ) {
			const todos = JSON.parse( localStorage.getItem( 'todos' ) );
			const _product = allProducts.find( ( item ) => item.id === product_id );

			_product.qty = 1;
			_product.str_price = format( _product.price, 2 );
			_product.complements = complements;

			_product.extras = extras;
			if ( _product.title === 'Miami basket' ) {
				if ( jQuery( '#kib' ).prop( 'checked' ) === true ) {
					_product.option = jQuery( '#valuekib' ).html();
				}
			}
			// this.resetVars();
			const db_product = todos.find( ( item ) => item.id === product_id );

			let insert = false;
			if ( _product.custom ) {
				insert = this.validateCant( db_product, _product );
			} else {
				insert = true;
			}

			if ( insert ) {
				this.items.push( _product );
				this.save();
				const svg = `<svg width="35" height="35" viewBox="0 0 256 256" xml:space="preserve">
				<defs>
				</defs>
				<g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
					<circle cx="45" cy="45" r="45" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(40,201,55); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
					<path d="M 38.478 64.5 c -0.01 0 -0.02 0 -0.029 0 c -1.3 -0.009 -2.533 -0.579 -3.381 -1.563 L 21.59 47.284 c -1.622 -1.883 -1.41 -4.725 0.474 -6.347 c 1.884 -1.621 4.725 -1.409 6.347 0.474 l 10.112 11.744 L 61.629 27.02 c 1.645 -1.862 4.489 -2.037 6.352 -0.391 c 1.862 1.646 2.037 4.49 0.391 6.352 l -26.521 30 C 40.995 63.947 39.767 64.5 38.478 64.5 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
				</g>
				</svg>`;
				alertify.message( svg );
			} else {
				alertify.warning( 'Agregue sus complementos' ).delay( 3 );
			}
		} else {
			operacion = operacion === 'add' ? 'up' : 'remove';
			this.updateItem( product.id, operacion );
		}
	}
	render() {
		// totalItems es una etiqueta span que esta junto al botón ver carrito
		totalItems.innerHTML = this.totalItems();
		const total = this.total();
		totalCar.innerHTML = total.string;
		if ( document.getElementById( 'MtotalCheckout' ) ) {
			MtotalCheckout.innerHTML = total.string;
		}

		if ( this.totalItems() > 0 ) {
			// template para los items
			compiled_tmp( 'tmp_item', 'cart_items', this.items );

			compiled_tmp( 'template', 'listProducts', this.items );
		} else {
			// template para mensaje de carrito vacio
			compiled_tmp( 'tmp_carEmpty', 'cart_items' );
		}
	}
}
var carrito = new Carrito( [] );
carrito.init();
