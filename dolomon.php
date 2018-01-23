<?php
// vim:set ft=php noexpandtab:
/*
* Plugin Name: Dolomon
* Plugin URI: https://framagit.org/framasoft/wp-dolomon
* Description: Transforms an URL into an URL provided by a Dolomon server, which creates visit statistics
* Version: 1.0.1
* Author: Framasoft
* Author URI: https://framasoft.org
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl.txt
* Domain Path: /languages
*
* Dolomon is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* any later version.
*
* Dolomon is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with {Plugin Name}. If not, see {URI to Plugin License}.
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'DOLOMON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Fetch the (cached) data from the dolomon server.
 *
 * @param bool $reset_cache
 *
 * @return array|mixed
 */
function dolomon_fetch_data( $reset_cache = false ) {
	$dolo_cache = get_transient( 'dolomon_data' );
	if ( $dolo_cache && ! $reset_cache ) {
		return $dolo_cache;
	}

	$dolo_cache = [
		'dolos' => [],
		'cats'  => null,
		'tags'  => null,
	];

	if ( $url = untrailingslashit( get_option( 'dolomon-url', '' ) ) ) {
		$args = [
			'headers' => [
				'XDolomon-App-Id'     => get_option( 'dolomon-app_id', '' ),
				'XDolomon-App-Secret' => get_option( 'dolomon-app_secret', '' ),
			],
		];

		$wcats = wp_remote_get( $url . '/api/cat', $args );
		$wtags = wp_remote_get( $url . '/api/tag', $args );

		$cats = is_array( $wcats )
			? json_decode( $wcats['body'], true )
			: [ 'object' => [] ];
		$tags = is_array( $wtags )
			? json_decode( $wtags['body'], true )
			: [ 'object' => [] ];

		$dolo_cache['cats'] = [];
		foreach ( $cats['object'] as $cat ) {
			foreach ( $cat['dolos'] as $dolo ) {
				$dolo_cache['dolos'][ $dolo['id'] ] = $dolo;
			}

			// Instead of saving all dolos, just remember how many.
			$cat['dolos_count'] = count( $cat['dolos'] );
			unset( $cat['dolos'] );

			$dolo_cache['cats'][ '' . $cat['id'] ] = $cat;
		}

		$dolo_cache['tags'] = [];
		foreach ( $tags['object'] as $tag ) {
			// Instead of saving all dolos, just remember how many.
			$tag['dolos_count'] = count( $tag['dolos'] );
			unset( $tag['dolos'] );

			$dolo_cache['tags'][ '' . $tag['id'] ] = $tag;
		}

		// todo: Not really necessary any more I guess.
		$dolo_cache['last_fetch'] = time();
		set_transient( 'dolomon_data', $dolo_cache, get_option( 'dolomon-cache_expiration', 3600 ) );
	}

	return $dolo_cache;
}

// Uninstallation hook
function dolomon_uninstall() {
	delete_option( 'dolomon-url' );
	delete_option( 'dolomon-app_id' );
	delete_option( 'dolomon-app_secret' );
	delete_option( 'dolomon-cache_expiration' );
}
register_uninstall_hook( __FILE__, 'dolomon_uninstall' );

// Load languages files
load_plugin_textdomain( 'dolomon', false, basename( __DIR__ ) . '/languages' );

// add styles and scripts
function dolomon_post_scripts() {
	wp_register_style( 'dolo-metabox', plugin_dir_url( __FILE__ ) . 'assets/css/metabox.css' );
	wp_enqueue_style( 'dolo-metabox' );
	wp_register_script( 'dolo-metabox', plugin_dir_url( __FILE__ ) . 'assets/js/metabox.js', 'jquery' );
	wp_enqueue_script( 'dolo-metabox' );
}
if ( is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) ) {
	add_action( 'admin_enqueue_scripts', 'dolomon_post_scripts' );
}
function dolomon_wp_styles() {
	wp_register_style( 'dolo-shortcode', plugin_dir_url( __FILE__ ) . 'assets/css/shortcode.css' );
	wp_enqueue_style( 'dolo-shortcode' );
	wp_register_style( 'dolo-shortcode-page', plugin_dir_url( __FILE__ ) . 'assets/css/page.css' );
	wp_enqueue_style( 'dolo-shortcode-page' );
	wp_register_script( 'dolo-page', plugin_dir_url( __FILE__ ) . 'assets/js/page.js', 'jquery' );
	wp_enqueue_script( 'dolo-page' );
}
add_action( 'wp_enqueue_scripts', 'dolomon_wp_styles' );

// Add item in settings menu
function dolomon_menu() {
	add_options_page( __( 'Dolomon settings', 'dolomon' ), __( 'Dolomon' ), 'manage_options', 'dolomon-settings', 'dolomon_options' );
}
add_action( 'admin_menu', 'dolomon_menu' );

function dolomon_check_settings( $url, $appid, $appsecret, $cache_expiration ) {
	$args = [
		'headers' => [
			'XDolomon-App-Id'     => $appid,
			'XDolomon-App-Secret' => $appsecret,
		],
	];
	$url  = untrailingslashit( $url );

	$pong = wp_remote_post( $url . '/api/ping', $args );
	if ( is_wp_error( $pong ) ) {
		return false;
	}

	$result = json_decode( $pong['body'], true );
	if ( $result['success'] ) {
		return is_numeric( $cache_expiration );
	}

	return false;
}

// Dolomon settings page
function dolomon_options() {
	// check permissions
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	$action_url = $_SERVER['REQUEST_URI'];

	// Get the current settings
	$url              = untrailingslashit( get_option( 'dolomon-url', '' ) );
	$appid            = get_option( 'dolomon-app_id', '' );
	$appsecret        = get_option( 'dolomon-app_secret', '' );
	$cache_expiration = get_option( 'dolomon-cache_expiration', 3600 ) / 60;

	$settings_valid = true;

	// Store the settings
	if ( isset( $_POST['dolomon-app_id'] ) ) {
		if ( ! check_admin_referer( 'dolomon-settings' ) ) {
			$settings_valid = false;
			$notice_message = __( 'Unable to register your dolomon settings', 'dolomon' );
			require __DIR__ . '/settings.php';
			return;
		}

		$url              = untrailingslashit( esc_url_raw( $_POST['dolomon-url'] ) );
		$appid            = sanitize_text_field( $_POST['dolomon-app_id'] );
		$appsecret        = sanitize_text_field( $_POST['dolomon-app_secret'] );
		$cache_expiration = sanitize_text_field( $_POST['dolomon-cache_expiration'] );
		if ( dolomon_check_settings( $url, $appid, $appsecret, $cache_expiration ) ) {
			update_option( 'dolomon-url', $url );
			update_option( 'dolomon-app_id', $appid );
			update_option( 'dolomon-app_secret', $appsecret );
			update_option( 'dolomon-cache_expiration', $cache_expiration * 60 );
			$notice_message = __( 'Your dolomon settings have been successfully registered :-)', 'dolomon' );
		} else {
			$settings_valid = false;
			$notice_message = __( 'Your dolomon settings are invalid. Please check them and retry.', 'dolomon' );
		}
	}

	// Display the settings page
	require __DIR__ . '/settings.php';
}

// Add a box in the edition page
function add_dolomon_meta_box( $post_type ) {
	add_meta_box(
		'dolomon-meta-box',
		'Dolomon',
		'render_meta_box',
		[ 'post', 'page' ],
		'side',
		'high'
	);
}
function render_meta_box() {
	$url        = untrailingslashit( get_option( 'dolomon-url', '' ) );
	$appid      = get_option( 'dolomon-app_id', '' );
	$appsecret  = get_option( 'dolomon-app_secret', '' );
	$dolo_cache = dolomon_fetch_data();

	add_thickbox();

	require __DIR__ . '/metabox.php';
}
add_action( 'add_meta_boxes', 'add_dolomon_meta_box' );

// Add dolo with an Ajax call
function add_dolo() {
	if ( current_user_can( 'edit_posts' ) ) {
		if ( ! check_admin_referer( 'dolomon_meta_box_nonce', 'dolomon_meta_box_nonce' ) ) {
			return;
		}
		$url       = untrailingslashit( get_option( 'dolomon-url', '' ) );
		$appid     = get_option( 'dolomon-app_id', '' );
		$appsecret = get_option( 'dolomon-app_secret', '' );

		if ( $url ) {
			# No need to sanitize input: the sanitizing will be done
			# on the Dolomon server (double sanitizing may break things)
			$args   = [
				'body'    => [
					'url'    => $_POST['url'],
					'name'   => $_POST['name'],
					'extra'  => $_POST['extra'],
					'short'  => $_POST['short'],
					'cat_id' => $_POST['cat_id'],
					'tags[]' => $_POST['tags[]'],
				],
				'headers' => [
					'XDolomon-App-Id'     => $appid,
					'XDolomon-App-Secret' => $appsecret,

				],
			];
			$result = json_decode( wp_remote_post( $url . '/api/dolo', $args )['body'], true );
			if ( $result['success'] ) {
				dolomon_fetch_data( true );
			}
			wp_send_json( $result );
		} else {
			wp_send_json( [
				'success' => false,
				'msg'     => __( 'It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon' ),
			] );
		}
	} else {
		wp_send_json( [
			'success' => false,
			'msg'     => __( 'You don\'t have the right permissions.', 'dolomon' ),
		] );
	}
}
add_action( 'wp_ajax_add_dolo', 'add_dolo' );

// Add category with an Ajax call
function add_cat() {
	if ( current_user_can( 'edit_posts' ) ) {
		if ( ! check_admin_referer( 'dolomon_meta_box_nonce', 'dolomon_meta_box_nonce' ) ) {
			return;
		}
		$url       = untrailingslashit( get_option( 'dolomon-url', '' ) );
		$appid     = get_option( 'dolomon-app_id', '' );
		$appsecret = get_option( 'dolomon-app_secret', '' );

		if ( $url ) {
			$args   = [
				'body'    => [
					'name' => stripslashes( sanitize_text_field( $_POST['name'] ) ),
				],
				'headers' => [
					'XDolomon-App-Id'     => $appid,
					'XDolomon-App-Secret' => $appsecret,

				],
			];
			$result = json_decode( wp_remote_post( $url . '/api/cat', $args )['body'], true );
			if ( $result['success'] ) {
				dolomon_fetch_data( true );
			}
			wp_send_json( $result );
		} else {
			wp_send_json( [
				'success' => false,
				'msg'     => __( 'It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon' ),
			] );
		}
	} else {
		wp_send_json( [
			'success' => false,
			'msg'     => __( 'You don\'t have the right permissions.', 'dolomon' ),
		] );
	}
}
add_action( 'wp_ajax_add_dolo_cat', 'add_cat' );

// Add dolo with an Ajax call
function add_tag() {
	if ( current_user_can( 'edit_posts' ) ) {
		if ( ! check_admin_referer( 'dolomon_meta_box_nonce', 'dolomon_meta_box_nonce' ) ) {
			wp_send_json( [
				'success' => false,
				'msg'     => __( 'There was a problem while checking the referer.', 'dolomon' ),
			] );
		}
		$url       = untrailingslashit( get_option( 'dolomon-url', '' ) );
		$appid     = get_option( 'dolomon-app_id', '' );
		$appsecret = get_option( 'dolomon-app_secret', '' );

		if ( $url ) {
			$args   = [
				'body'    => [
					'name' => stripslashes( sanitize_text_field( $_POST['name'] ) ),
				],
				'headers' => [
					'XDolomon-App-Id'     => $appid,
					'XDolomon-App-Secret' => $appsecret,

				],
			];
			$result = json_decode( wp_remote_post( $url . '/api/tag', $args )['body'], true );
			if ( $result['success'] ) {
				dolomon_fetch_data( true );
			}
			wp_send_json( $result );
		} else {
			wp_send_json( [
				'success' => false,
				'msg'     => __( 'It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon' ),
			] );
		}
	} else {
		wp_send_json( [
			'success' => false,
			'msg'     => __( 'You don\'t have the right permissions.', 'dolomon' ),
		] );
	}
}
add_action( 'wp_ajax_add_dolo_tag', 'add_tag' );

// Clean the shortcode attributes
function dolo_parse_atts( $atts ) {
	$a = [];
	foreach ( $atts as $name => $att ) {
		if ( $att === 'true' ) {
			$a[ $name ] = true;
		} elseif ( $att === 'false' ) {
			$a[ $name ] = false;
		} else {
			$a[ $name ] = $att;
		}
	}
	return $a;
}

// Formatting a dolo
function dolo_format( $dolo, $atts ) {
	$a = shortcode_atts( [
		'name'   => null,
		'self'   => false,
		'link'   => false,
		'button' => false,
		'count'  => false,
		'extra'  => false,
	], $atts );

	if ( ! isset( $dolo['short'] ) ) {
		return;
	}
	$url  = untrailingslashit( get_option( 'dolomon-url', '' ) );
	$name = $url . $dolo['short'];
	if ( $a['self'] ) {
		if ( ! empty( $dolo['name'] ) ) {
			$name = $dolo['name'];
		} else {
			$name         = $url . $dolo['short'];
			$dolo['name'] = $dolo['url'];
		}
	}
	if ( isset( $a['name'] ) ) {
		$name = $a['name'];
		$name = preg_replace( '/%count/', $dolo['count'], $name );
		$name = preg_replace( '/%name/', $dolo['name'], $name );
		$name = preg_replace( '/%extra/', $dolo['extra'], $name );
		$name = preg_replace( '/%cat/', $dolo['category_name'], $name );
		$name = preg_replace( '/%url/', $dolo['url'], $name );
		$tags = [];
		foreach ( $dolo['tags'] as $tag ) {
			$tags[] = $tag['name'];
		}
		$name = preg_replace( '/%tags/', implode( ', ', $tags ), $name );
	} elseif ( $a['count'] ) {
		$name = $dolo['count'];
	} elseif ( $a['extra'] ) {
		$name = $dolo['extra'];
	}
	if ( $a['link'] ) {
		if ( $a['button'] ) {
			return "<a class=\"dolo-button\" href=\"$url" . $dolo['short'] . "\">$name</a>";
		}
		return "<a href=\"$url" . $dolo['short'] . "\">$name</a>";
	}
	return $name;
}

// DoloS short code
function dolos_short( $atts ) {
	$a = shortcode_atts( [
		'page'     => false,
		'cat'      => null,
		'tag'      => null,
		'cats'     => null,
		'tags'     => null,
		'name'     => null,
		'featured' => null,
		'self'     => false,
		'link'     => false,
		'button'   => false,
		'count'    => false,
		'extra'    => false,
		'notitle'  => false,
	], dolo_parse_atts( $atts ) );

	$dolo_cache = dolomon_fetch_data();

	$ar = [];
	if ( $a['page'] ) {
		$ar = $dolo_cache['cats'];
	} elseif ( isset( $a['cat'] ) ) {
		$cat = $dolo_cache['cats'][ $a['cat'] ];
		if ( isset( $a['tags'] ) ) {
			$atags = explode( ',', $a['tags'] );
			$dolos = [];
			foreach ( $cat['dolos'] as $dolo ) {
				foreach ( $dolo['tags'] as $tag ) {
					if ( in_array( $tag['id'], $atags ) ) {
						$dolos[] = $dolo;
						break;
					}
				}
			}
			if ( count( $dolos ) > 0 ) {
				$cat['dolos']          = $dolos;
				$ar[ '' . $cat['id'] ] = $cat;
			}
		} else {
			$ar[ $a['cat'] ] = $dolo_cache['cats'][ $a['cat'] ];
		}
	} elseif ( isset( $a['tag'] ) ) {
		$tag = $dolo_cache['tags'][ $a['tag'] ];
		if ( isset( $a['cats'] ) ) {
			$acats = explode( ',', $a['cat'] );
			$dolos = [];
			foreach ( $tag['dolos'] as $dolo ) {
				if ( in_array( $dolo['category_id'], $acats ) ) {
					$dolos[] = $dolo;
				}
			}
			if ( count( $dolos ) > 0 ) {
				$tag['dolos']          = $dolos;
				$ar[ '' . $tag['id'] ] = $tag;
			}
		} else {
			$ar[ $a['tag'] ] = $dolo_cache['tags'][ $a['tag'] ];
		}
	}

	$r = '';
	if ( $a['page'] ) {
		$b         = $a;
		$b['link'] = false;
		$r         = '<input id="doloSearch" placeholder="Search">';
		$r         .= '<div>';
		if ( isset( $a['featured'] ) ) {
			$dolo_ids = explode( ',', $a['featured'] );
			$r        .= '<div class="doloCat featured">';
			$r        .= '<h3>';
			$r        .= 'Featured';
			$r        .= '</h3>';
			$r        .= '<ul>';
			foreach ( $dolo_ids as $id ) {
				$dolo = $dolo_cache['dolos'][ $id ];
				$r    .= '<li data-search="' . dolo_format( $dolo, $b ) . '">';
				$r    .= dolo_format( $dolo, $a );
				$r    .= '</li>';
			}
			$r .= '</ul>';
			$r .= '</div>';
		}
		foreach ( $ar as $hum ) {
			$r .= '<div class="doloCat">';
			if ( ! $a['notitle'] ) {
				$r .= '<h3>';
				$r .= $hum['name'];
				$r .= '</h3>';
			}
			$r .= '<ul>';
			foreach ( $hum['dolos'] as $dolo ) {
				$r .= '<li data-search="' . dolo_format( $dolo, $b ) . '">';
				$r .= dolo_format( $dolo, $a );
				$r .= '</li>';
			}
			$r .= '</ul>';
			$r .= '</div>';
		}
		$r .= '</div>';
	} else {
		foreach ( $ar as $hum ) {
			if ( ! $a['notitle'] ) {
				$r .= '<h3>';
				$r .= $hum['name'];
				$r .= '</h3>';
			}
			$r .= '<ul>';
			foreach ( $hum['dolos'] as $dolo ) {
				$r .= '<li>';
				$r .= dolo_format( $dolo, $a );
				$r .= '</li>';
			}
			$r .= '</ul>';
		}
	}
	return $r;
}
add_shortcode( 'dolos', 'dolos_short' );

// Dolo short code
function dolo_short( $atts ) {
	$a = shortcode_atts( [
		'id'     => null,
		'name'   => null,
		'self'   => false,
		'link'   => false,
		'button' => false,
		'count'  => false,
		'extra'  => false,
	], dolo_parse_atts( $atts ) );
	if ( ! isset( $a['id'] ) ) {
		return;
	}

	$id = $a['id'];

	$dolo_cache = dolomon_fetch_data();

	if ( isset( $dolo_cache['dolos']["$id"] ) ) {
		$dolo = $dolo_cache['dolos']["$id"];
		return dolo_format( $dolo, $a );
	} else {
		return 'Error';
	}
}
add_shortcode( 'dolo', 'dolo_short' );

// Widget
require __DIR__ . '/widget.php';
