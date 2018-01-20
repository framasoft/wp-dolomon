// vim:set ft=javascript noexpandtab:
jQuery( document ).ready( function ( $ ) {
	$( '.dolo-widget-type' ).unbind( 'change' );
	$( '.dolo-widget-type' ).change( function () {
		var e = $( this );
		var par = e.parent().parent();
		par.find( '.dolo-choose-cat' ).addClass( 'hidden' );
		par.find( '.dolo-choose-tag' ).addClass( 'hidden' );
		par.find( '.dolo-choose-dolo' ).addClass( 'hidden' );
		par.find( '.' + e.val() ).removeClass( 'hidden' );
	} );
} );
