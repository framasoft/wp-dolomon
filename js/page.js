// vim:set ft=javascript noexpandtab:
jQuery( document ).ready( function ( $ ) {
	$( '#doloSearch' ).val( '' );
	$( '#doloSearch' ).on( 'keyup', function ( e ) {
		var thi = this;
		var input = $( thi );
		var val = input.val();
		$( '.doloCat li' ).each( function ( index, element ) {
			var e = $( element );
			if ( val === undefined || val === '' ) {
				e.removeClass( 'hidden' );
			} else {
				if ( !e.data( 'search' ).match( new RegExp( val ) ) ) {
					e.addClass( 'hidden' );
				} else {
					e.removeClass( 'hidden' );
				}
			}
		} );
	} );
} );
