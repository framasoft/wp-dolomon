// vim:set ft=javascript noexpandtab:
// Stolen from https://github.com/mozilla-services/push-dev-dashboard/blob/3ad4de737380d0842f40c82301d1f748c1b20f2b/push/static/js/validation.js
function createNode( text ) {
	var node = document.createElement( 'pre' );
	node.style.width = '1px';
	node.style.height = '1px';
	node.style.position = 'fixed';
	node.style.top = '5px';
	node.textContent = text;
	return node;
}

function copyNode( node ) {
	var selection = getSelection();
	selection.removeAllRanges();

	var range = document.createRange();
	range.selectNodeContents( node );
	selection.addRange( range );

	var success = document.execCommand( 'copy' );
	selection.removeAllRanges();
	return success;
}

function copyText( text ) {
	var node = createNode( text );
	document.body.appendChild( node );
	var success = copyNode( node );
	document.body.removeChild( node );
	if ( !success ) {
		prompt( dolomon_hit_enter, text );
	}
}

function copyInput( node ) {
	node.select();
	var success = document.execCommand( 'copy' );
	getSelection().removeAllRanges();
	return success;
}

jQuery( document ).ready( function ( $ ) {
	window.$ = $;
	var url = dolomonPostUrl;
	$( '#dolo-sc-name' ).val( '' );
	$( '#dolo-sc-count, #dolo-sc-extra, #dolo-sc-link, #dolo-sc-button, #dolo-sc-self, #dolo-sc-page' ).prop( 'checked', false );
	$( '#dolo-sc-cat option, dolo-sc-tag option' ).prop( 'selected', false );
	$( '#dolomon-submit' ).click( function ( e ) {
		e.preventDefault();
		$.ajax( {
			url: ajaxurl + '?action=add_dolo',
			method: 'POST',
			data: {
				url: $( '#dolomon-url' ).val(),
				name: $( '#dolomon-name' ).val(),
				extra: $( '#dolomon-extra' ).val(),
				short: $( '#dolomon-short' ).val(),
				cat_id: $( '#dolomon-cat' ).val(),
				tags: $( '#dolomon-tag' ).val(),
				dolomon_meta_box_nonce: $( '#dolomon_meta_box_nonce' ).val(),
				_wp_http_referer: $( 'input[name="_wp_http_referer"]' ).val(),
			},
			dataType: 'json',
			success: function ( data, textStatus, jqXHR ) {
				if ( data.success ) {
					var tags = new Array();
					data.object.tags.forEach( function ( t ) {
						tags.push( t.name );
					} );
					$( '#tab-1 tbody' ).prepend( [
						'<tr>',
						'    <td class="dolo-filter-category">', data.object.category_name, '</td>',
						'    <td class="dolo-filter-url">', data.object.url, '</td>',
						'    <td><a href="#" class="dolo-filter-durl" onclick="copyText(\'', url, data.object.short, '\')">', url, data.object.short, '</a></td>',
						'    <td class="dolo-filter-name">', data.object.name, '</td>',
						'    <td class="dolo-filter-extra">', data.object.extra, '</td>',
						'    <td><a href="#" class="dolo-filter-shortcode" onclick="copyText(\'[dolo id=', data.object.id, '\')">[dolo id=', data.object.id, ']</a></td>',
						'    <td class="dolo-filter-tags">', tags.join( ', ' ), '</td>',
						'</tr>'
					].join( '' ) );
					addAlert( dolomon_add_dolo_success );
				} else {
					alert( data.msg );
				}
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
			}
		} );
	} );
	$( '#dolomon-cat-submit' ).click( function ( e ) {
		e.preventDefault();
		$.ajax( {
			url: ajaxurl + '?action=add_dolo_cat',
			method: 'POST',
			data: {
				name: $( '#dolomon-cat-name' ).val(),
				dolomon_meta_box_nonce: $( '#dolomon_meta_box_nonce' ).val(),
				_wp_http_referer: $( 'input[name="_wp_http_referer"]' ).val(),
			},
			dataType: 'json',
			success: function ( data, textStatus, jqXHR ) {
				if ( data.success ) {
					$( '#dolomon-cat option' ).prop( 'selected', false );
					$( '#dolomon-cat' ).prepend( [
						'<option value="', data.object.id, '" selected>',
						data.object.name,
						'</option>'
					].join( '' ) );
					$( '#tab-2 tbody' ).prepend( [
						'<tr>',
						'    <td class="dolo-filter-name">', data.object.name, '</td>',
						'    <td class="dolo-filter-number">', data.object.dolos.length, '</td>',
						'    <td><a href="#" class="dolo-filter-shortcode" data-id="', data.object.id, '" onclick="copyText(\'[dolos cat=', data.object.id, '\')">[dolos cat=', data.object.id, '</a></td>',
						'</tr>'
					].join( '' ) );
					$( '#TB_closeWindowButton' ).click();
					addAlert( dolomon_add_cat_success );
				} else {
					alert( data.msg );
				}
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
			}
		} );
	} );
	$( '#dolomon-tag-submit' ).click( function ( e ) {
		e.preventDefault();
		$.ajax( {
			url: ajaxurl + '?action=add_dolo_tag',
			method: 'POST',
			data: {
				name: $( '#dolomon-tag-name' ).val(),
				dolomon_meta_box_nonce: $( '#dolomon_meta_box_nonce' ).val(),
				_wp_http_referer: $( 'input[name="_wp_http_referer"]' ).val(),
			},
			dataType: 'json',
			success: function ( data, textStatus, jqXHR ) {
				if ( data.success ) {
					$( '#dolomon-tag' ).prepend( [
						'<option value="', data.object.id, '" selected>',
						data.object.name,
						'</option>'
					].join( '' ) );
					$( '#tab-3 tbody' ).prepend( [
						'<tr>',
						'    <td class="dolo-filter-name">', data.object.name, '</td>',
						'    <td class="dolo-filter-number">', data.object.dolos.length, '</td>',
						'    <td><a href="#" class="dolo-filter-shortcode" data-id="', data.object.id, '" onclick="copyText(\'[dolos tag=', data.object.id, '\')">[dolos tag=', data.object.id, '</a></td>',
						'</tr>'
					].join( '' ) );
					$( '#TB_closeWindowButton' ).click();
					addAlert( dolomon_add_tag_success );
				} else {
					alert( data.msg );
				}
			},
			error: function ( jqXHR, textStatus, errorThrown ) {
			}
		} );
	} );
	$( '#showadddolocat' ).click( function ( e ) {
		e.preventDefault();
		tb_show( '', '#TB_inline?width=800&height=600&inlineId=addDoloCat' );
	} );
	$( '#showadddolotag' ).click( function ( e ) {
		e.preventDefault();
		tb_show( '', '#TB_inline?width=800&height=600&inlineId=addDoloTag' );
	} );
	$( '#showdolotb' ).click( function ( e ) {
		e.preventDefault();
		tb_show( '', '#TB_inline?width=800&height=600&inlineId=myDolos' );

		var tb = document.getElementById( 'TB_ajaxContent' );
		tb.setAttribute( 'style', '' );
		$( '#TB_window' ).addClass( 'dolosTB' );

		$( '#TB_window .dolo-filter' ).val( '' );
		$( '#TB_window .dolo-filter' ).unbind( 'keyup' );
		$( '#TB_window .dolo-filter' ).on( 'keyup', doloFilter );
	} );
	$( '.tabs-menu a' ).click( function ( event ) {
		event.preventDefault();
		$( this ).parent().addClass( 'current' );
		$( this ).parent().siblings().removeClass( 'current' );
		var tab = $( this ).attr( 'href' );
		$( '.tab-content' ).not( tab ).css( 'display', 'none' ).removeClass( 'current' );
		$( tab ).addClass( 'current' );
		updateShortcodes();
		$( tab ).fadeIn();
	} );

	function doloFilter( event ) {
		var thi = this;
		var input = $( thi );
		$( '#TB_window .dolo-filter' ).each( function ( index, element ) {
			if ( element != thi ) {
				$( element ).val( '' );
			}
		} );
		var val = input.val();
		var sel = input.data( 'filter' );
		input.parents( 'table' ).find( sel ).each( function ( index, element ) {
			var e = $( element );
			if ( val === undefined || val === '' ) {
				e.parents( 'tr' ).removeClass( 'hidden' );
			} else {
				if ( !e.text().match( new RegExp( val ) ) ) {
					e.parents( 'tr' ).addClass( 'hidden' );
				} else {
					e.parents( 'tr' ).removeClass( 'hidden' );
				}
			}
		} );
	}

	$( '#dolo-sc-name' ).on( 'keyup', function () {
		updateShortcodes();
	} );
	$( '#dolo-sc-count, #dolo-sc-extra, #dolo-sc-button, #dolo-sc-self, #dolo-sc-link, #dolo-sc-page, #dolo-sc-tag, #dolo-sc-cat' ).on( 'change', function () {
		updateShortcodes();
	} );

	function updateShortcodes() {
		$( '.tab-content.current' ).find( '.dolo-filter-shortcode' ).each( function ( i ) {
			var e = $( this );
			var a = new Array()
			switch ( $( '.tab-content.current' ).data( 'selected' ) ) {
				case 'dolo':
					$( '#dolo-sc-tag' ).parent().addClass( 'hidden' );
					$( '#dolo-sc-cat' ).parent().addClass( 'hidden' );
					$( '#dolo-sc-page' ).parent().addClass( 'hidden' );
					a.push( '[dolo' );
					a.push( 'id=' + e.data( 'id' ) );
					break;
				case 'cat':
					a.push( '[dolos' );
					if ( $( '#dolo-sc-page' ).is( ':checked' ) ) {
						$( '#dolo-sc-tag' ).parent().addClass( 'hidden' );
						a = new Array( '[dolos page=true' );
					} else {
						$( '#dolo-sc-cat' ).parent().addClass( 'hidden' );
						$( '#dolo-sc-tag' ).parent().removeClass( 'hidden' );
						$( '#dolo-sc-page' ).parent().removeClass( 'hidden' );
						a.push( 'cat=' + e.data( 'id' ) );
						var t = new Array();
						$( '#dolo-sc-tag option:selected' ).each( function ( i ) {
							var e = $( this );
							t.push( e.val() );
						} );
						if ( t.length > 0 ) {
							a.push( 'tags=' + t.join( ',' ) );
						}
					}
					break;
				case 'tag':
					a.push( '[dolos' );
					if ( $( '#dolo-sc-page' ).is( ':checked' ) ) {
						$( '#dolo-sc-cat' ).parent().addClass( 'hidden' );
						a = new Array( '[dolos page=true' );
					} else {
						$( '#dolo-sc-tag' ).parent().addClass( 'hidden' );
						$( '#dolo-sc-cat' ).parent().removeClass( 'hidden' );
						$( '#dolo-sc-page' ).parent().removeClass( 'hidden' );
						a.push( 'tag=' + e.data( 'id' ) );
						var c = new Array();
						$( '#dolo-sc-cat option:selected' ).each( function ( i ) {
							var e = $( this );
							c.push( e.val() );
						} );
						if ( c.length > 0 ) {
							a.push( 'cats=' + c.join( ',' ) );
						}
					}
					break;
			}
			if ( $( '#dolo-sc-name' ).val() ) {
				a.push( 'name="' + $( '#dolo-sc-name' ).val() + '"' );
			}
			if ( $( '#dolo-sc-count' ).is( ':checked' ) ) {
				a.push( 'count=true' );
			}
			if ( $( '#dolo-sc-extra' ).is( ':checked' ) ) {
				a.push( 'extra=true' );
			}
			if ( $( '#dolo-sc-link' ).is( ':checked' ) ) {
				a.push( 'link=true' );
				$( '#dolo-sc-button, #dolo-sc-self' ).parent().removeClass( 'hidden' );
				if ( $( '#dolo-sc-button' ).is( ':checked' ) ) {
					a.push( 'button=true' );
				}
				if ( $( '#dolo-sc-self' ).is( ':checked' ) ) {
					a.push( 'self=true' );
				}
			} else {
				$( '#dolo-sc-button, #dolo-sc-self' ).parent().addClass( 'hidden' );
			}

			var s = a.join( ' ' );
			s += ']';

			e.text( s );
			e.unbind( 'click' );
			e.click( function () {
				copyText( s );
			} );
		} );
	}

	function addAlert( text ) {
		$( '#wpbody-content' ).prepend( [
			'<div class="updated settings-error notice is-dismissible">',
			'    <p>',
			'        <strong>',
			'            <span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;">',
			text,
			'            </span>',
			'        </strong>',
			'    </p>',
			'    <button type="button" class="notice-dismiss" onclick="var el = this.parentNode; el.parentNode.removeChild(el);">',
			'        <span class="screen-reader-text">', dolomon_dismiss_notice, '</span>',
			'    </button>',
			'</div>'
		].join( '' ) );
	}
} );
