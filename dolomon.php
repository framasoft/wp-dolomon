<?php
    // vim:set sw=4 ts=4 sts=4 ft=php expandtab:
    /*
    * Plugin Name: Dolomon
    * Plugin URI: https://framagit.org/framasoft/wp-dolomon
    * Description: Transforms an URL into an URL provided by a Dolomon server, which creates visit statistics
    * Version: 0.1
    * Author: Framasoft
    * Author URI: https://framasoft.org
    * License: GPLv3
    * License URI: http://www.gnu.org/licenses/gpl.txt
    * Domain Path: /languages
    *
    * {Plugin Name} is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * any later version.
    *
    * {Plugin Name} is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with {Plugin Name}. If not, see {URI to Plugin License}.
    */
    defined('ABSPATH') or die('No script kiddies please!');

    define('DOLOMON_PLUGIN_URL', plugin_dir_url(__FILE__));

    // Dolomon's data cache system
    $dolo_cache = array(
        'dolos' => array(),
        'cats'  => null,
        'tags'  => null
    );
    $dolo_cachefile = dirname(__FILE__).'/cache.json';
    if (file_exists($dolo_cachefile)) {
        $dolo_cache = json_decode(file_get_contents($dolo_cachefile), true);
        if (time() - $dolo_cache['last_fetch'] > get_option('dolomon-cache_expiration', 3600)) {
            dolomon_refresh_cache();
        }
    } else {
        dolomon_refresh_cache();
    }

    // Get data from dolomon and put it in the cache file
    function dolomon_refresh_cache() {
        $url       = get_option('dolomon-url', '');
        $appid     = get_option('dolomon-app_id', '');
        $appsecret = get_option('dolomon-app_secret', '');

        $args = array(
            'headers' => array(
                'XDolomon-App-Id'     => $appid,
                'XDolomon-App-Secret' => $appsecret

            )
        );
        $url  = preg_replace('/\/$/', '', $url);
        $cats = json_decode(wp_remote_get($url.'/api/cat', $args)['body'], true);
        $tags = json_decode(wp_remote_get($url.'/api/tag', $args)['body'], true);

        global $dolo_cache, $dolo_cachefile;

        $file = fopen($dolo_cachefile, 'w') or die(printf(__('Unable to open cache file %s!', 'dolomon'), $dolo_cachefile));

        $dolo_cache['cats'] = array();
        $dolo_cache['tags'] = array();
        $dolo_cache['dolos'] = array();

        foreach ($cats['object'] as $cat) {
            $dolo_cache['cats']["".$cat['id']] = $cat;
            foreach ($cat['dolos'] as $dolo) {
                $dolo_cache['dolos'][$dolo['id']] = $dolo;
            }
        }
        foreach ($tags['object'] as $tag) {
            $dolo_cache['tags']["".$tag['id']] = $tag;
        }
        $dolo_cache['last_fetch'] = time();
        fwrite($file, json_encode($dolo_cache));
    }

    // Uninstallation hook
    function dolomon_uninstall() {
        delete_option('dolomon-url');
        delete_option('dolomon-app_id');
        delete_option('dolomon-app_secret');
        delete_option('dolomon-cache_expiration');
    }
    register_uninstall_hook(__FILE__, 'dolomon_uninstall');

    // Load languages files
    load_plugin_textdomain('dolomon', false, basename(dirname(__FILE__)).'/languages');

    // add styles and scripts
    function dolomon_post_scripts() {
        wp_register_style('dolo-metabox', plugin_dir_url(__FILE__).'assets/css/metabox.css');
        wp_enqueue_style('dolo-metabox');
        wp_register_script('dolo-metabox', plugin_dir_url(__FILE__).'assets/js/metabox.js', 'jquery');
        wp_enqueue_script('dolo-metabox');
    }
    if (is_admin() && ($pagenow == 'post-new.php' || $pagenow == 'post.php')) {
        add_action('admin_enqueue_scripts', 'dolomon_post_scripts');
    }
    function dolomon_wp_styles() {
        wp_register_style('dolo-shortcode', plugin_dir_url(__FILE__).'assets/css/shortcode.css');
        wp_enqueue_style('dolo-shortcode');
        wp_register_style('dolo-shortcode-page', plugin_dir_url(__FILE__).'assets/css/page.css');
        wp_enqueue_style('dolo-shortcode-page');
        wp_register_script('dolo-page', plugin_dir_url(__FILE__).'assets/js/page.js', 'jquery');
        wp_enqueue_script('dolo-page');
    }
    add_action('wp_enqueue_scripts', 'dolomon_wp_styles');

    // Add item in settings menu
    function dolomon_menu() {
        add_options_page(__('Dolomon settings', 'dolomon'), __('Dolomon'), 'manage_options', 'dolomon-settings', 'dolomon_options');
    }
    add_action('admin_menu', 'dolomon_menu');

    function dolomon_check_settings($url, $appid, $appsecret) {
        $args = array(
            'headers' => array(
                'XDolomon-App-Id'     => $appid,
                'XDolomon-App-Secret' => $appsecret
            )
        );
        $url    = preg_replace('/\/$/', '', $url);
        $result = json_decode(wp_remote_post($url.'/api/ping', $args)['body'], true);
        if ($result['success']) {
            return true;
        } else {
            return false;
        }
    }
    // Dolomon settings page
    function dolomon_options() {
        // check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $action_url = $_SERVER['REQUEST_URI'];

        // Get the current settings
        $url              = get_option('dolomon-url', '');
        $appid            = get_option('dolomon-app_id', '');
        $appsecret        = get_option('dolomon-app_secret', '');
        $cache_expiration = get_option('dolomon-cache_expiration', 3600) / 60;

        // Store the settings
        if (isset($_POST['dolomon-app_id'])) {
            if (!check_admin_referer('dolomon-settings')) {
                $msg = __('Unable to register your settings', 'dolomon');
                include(dirname(__FILE__).'/settings.php');
                return;
            }

            $url              = $_POST['dolomon-url'];
            $appid            = $_POST['dolomon-app_id'];
            $appsecret        = $_POST['dolomon-app_secret'];
            $cache_expiration = $_POST['dolomon-cache_expiration'];
            if (dolomon_check_settings($url, $appid, $appsecret)) {
                update_option('dolomon-url',              $url);
                update_option('dolomon-app_id',           $appid);
                update_option('dolomon-app_secret',       $appsecret);
                update_option('dolomon-cache_expiration', $cache_expiration * 60);
                $msg = __('Your settings has been successfully registered :-)', 'dolomon');
            } else {
                $msg = __('Your dolomon settings are invalid. Please check and retry.', 'dolomon');
            }
        }

        // Display the settings page
        include(dirname(__FILE__).'/settings.php');
    }

    // Add a box in the edition page
    function add_dolomon_meta_box($post_type) {
        add_meta_box(
            'dolomon-meta-box',
            'Dolomon',
            'render_meta_box',
            array('post', 'page'),
            'side',
            'high'
        );
    }
    function render_meta_box() {
        $url       = get_option('dolomon-url', '');
        $url       = preg_replace('/\/$/', '', $url);
        $appid     = get_option('dolomon-app_id', '');
        $appsecret = get_option('dolomon-app_secret', '');

        dolomon_refresh_cache();

        global $dolo_cache;

        add_thickbox();

        include(dirname(__FILE__).'/metabox.php');
    }
    add_action('add_meta_boxes', 'add_dolomon_meta_box');

    // Add dolo with an Ajax call
    function add_dolo() {
        if (current_user_can('edit_posts')) {
            if (!check_admin_referer('dolomon_meta_box_nonce', 'dolomon_meta_box_nonce')) {
                return;
            }
            $url       = get_option('dolomon-url', '');
            $appid     = get_option('dolomon-app_id', '');
            $appsecret = get_option('dolomon-app_secret', '');

            if (!empty($url)) {
                $args = array(
                    'body'    => array(
                        'url'    => $_POST['url'],
                        'name'   => $_POST['name'],
                        'extra'  => $_POST['extra'],
                        'short'  => $_POST['short'],
                        'cat_id' => $_POST['cat_id'],
                        'tags[]' => $_POST['tags[]'],
                    ),
                    'headers' => array(
                        'XDolomon-App-Id'     => $appid,
                        'XDolomon-App-Secret' => $appsecret

                    )
                );
                $url    = preg_replace('/\/$/', '', $url);
                $result = json_decode(wp_remote_post($url.'/api/dolo', $args)['body'], true);
                if ($result['success']) {
                    dolomon_refresh_cache();
                }
                wp_send_json($result);
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => __('It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon')
                ));
            }
        } else {
            wp_send_json(array(
                'success' => false,
                'msg'     => __('You don\'t have the right permissions.', 'dolomon')
            ));
        }
    }
    add_action('wp_ajax_add_dolo', 'add_dolo');

    // Add category with an Ajax call
    function add_cat() {
        if (current_user_can('edit_posts')) {
            if (!check_admin_referer('dolomon_meta_box_nonce', 'dolomon_meta_box_nonce')) {
                return;
            }
            $url       = get_option('dolomon-url', '');
            $appid     = get_option('dolomon-app_id', '');
            $appsecret = get_option('dolomon-app_secret', '');

            if (!empty($url)) {
                $args = array(
                    'body'    => array(
                        'name' => stripslashes($_POST['name']),
                    ),
                    'headers' => array(
                        'XDolomon-App-Id'     => $appid,
                        'XDolomon-App-Secret' => $appsecret

                    )
                );
                $url    = preg_replace('/\/$/', '', $url);
                $result = json_decode(wp_remote_post($url.'/api/cat', $args)['body'], true);
                if ($result['success']) {
                    dolomon_refresh_cache();
                }
                wp_send_json($result);
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => __('It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon')
                ));
            }
        } else {
            wp_send_json(array(
                'success' => false,
                'msg'     => __('You don\'t have the right permissions.', 'dolomon')
            ));
        }
    }
    add_action('wp_ajax_add_dolo_cat', 'add_cat');

    // Add dolo with an Ajax call
    function add_tag() {
        if (current_user_can('edit_posts')) {
            if (!check_admin_referer('dolomon_meta_box_nonce', 'dolomon_meta_box_nonce')) {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => __('There was a problem while checking the referer.', 'dolomon')
                ));
            }
            $url       = get_option('dolomon-url', '');
            $appid     = get_option('dolomon-app_id', '');
            $appsecret = get_option('dolomon-app_secret', '');

            if (!empty($url)) {
                $args = array(
                    'body'    => array(
                        'name' => stripslashes($_POST['name']),
                    ),
                    'headers' => array(
                        'XDolomon-App-Id'     => $appid,
                        'XDolomon-App-Secret' => $appsecret

                    )
                );
                $url    = preg_replace('/\/$/', '', $url);
                $result = json_decode(wp_remote_post($url.'/api/tag', $args)['body'], true);
                if ($result['success']) {
                    dolomon_refresh_cache();
                }
                wp_send_json($result);
            } else {
                wp_send_json(array(
                    'success' => false,
                    'msg'     => __('It seems that the Dolomon server URL is not set. Check your Dolomon settings.', 'dolomon')
                ));
            }
        } else {
            wp_send_json(array(
                'success' => false,
                'msg'     => __('You don\'t have the right permissions.', 'dolomon')
            ));
        }
    }
    add_action('wp_ajax_add_dolo_tag', 'add_tag');

    // Clean the shortcode attributes
    function dolo_parse_atts($atts) {
        $a = array();
        foreach ($atts as $name => $att) {
            if ($att === 'true') {
                $a[$name] = true;
            } else if ($att === 'false') {
                $a[$name] = false;
            } else {
                $a[$name] = $att;
            }
        }
        return $a;
    }

    // Formatting a dolo
    function dolo_format($dolo, $atts) {
        $a = shortcode_atts(array(
            'name'   => null,
            'self'   => false,
            'link'   => false,
            'button' => false,
            'count'  => false,
            'extra'  => false,
        ), $atts);

        if (!isset($dolo['short'])) {
            return;
        }
        $url  = get_option('dolomon-url', '');
        $url  = preg_replace('/\/$/', '', $url);
        $name = $url.$dolo['short'];
        if ($a['self']) {
            if (!empty($dolo['name'])) {
                $name = $dolo['name'];
            } else {
                $name         = $url.$dolo['short'];
                $dolo['name'] = $dolo['url'];
            }
        }
        if (isset($a['name'])) {
            $name = $a['name'];
            $name = preg_replace('/%count/', $dolo['count'], $name);
            $name = preg_replace('/%name/', $dolo['name'], $name);
            $name = preg_replace('/%extra/', $dolo['extra'], $name);
            $name = preg_replace('/%cat/', $dolo['category_name'], $name);
            $name = preg_replace('/%url/', $dolo['url'], $name);
            $tags = array();
            foreach ($dolo['tags'] as $tag) {
                $tags[] = $tag['name'];
            }
            $name = preg_replace('/%tags/', implode(', ', $tags), $name);
        } else if ($a['count']) {
            $name = $dolo['count'];
        } else if ($a['extra']) {
            $name = $dolo['extra'];
        }
        if ($a['link']) {
            if ($a['button']) {
                return "<a class=\"dolo-button\" href=\"$url".$dolo['short']."\">$name</a>";
            }
            return "<a href=\"$url".$dolo['short']."\">$name</a>";
        }
        return $name;
    }

    // DoloS short code
    function dolos_short($atts) {
        $a = shortcode_atts(array(
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
        ), dolo_parse_atts($atts));

        global $dolo_cache;
        $cache_expiration = get_option('dolomon-cache_expiration', 3600);
        if (!isset($dolo_cache['dolos']["$id"]) || (time() - $dolo_cache['last_fetch'] > $cache_expiration)) {
            dolomon_refresh_cache();
        }
        $ar = array();
        if ($a['page']) {
            $ar = $dolo_cache['cats'];
        } else if (isset($a['cat'])) {
            $cat = $dolo_cache['cats'][$a['cat']];
            if (isset($a['tags'])) {
                $atags = explode(',', $a['tags']);
                $dolos = array();
                foreach ($cat['dolos'] as $dolo) {
                    $ok = false;
                    foreach ($dolo['tags'] as $tag) {
                        if (in_array($tag['id'], $atags)) {
                            $ok = true;
                            break;
                        }
                    }
                    if ($ok) {
                        $dolos[] = $dolo;
                    }
                }
                if (count($dolos) > 0) {
                    $cat['dolos'] = $dolos;
                    $ar["".$cat['id']] = $cat;
                }
            } else {
                $ar[$a['cat']] = $dolo_cache['cats'][$a['cat']];
            }
        } else if (isset($a['tag'])) {
            $tag = $dolo_cache['tags'][$a['tag']];
            if (isset($a['cats'])) {
                $acat = explode(',', $a['cat']);
                $dolos = array();
                foreach ($tag['dolos'] as $dolo) {
                    $ok = false;
                    if (in_array($dolo['category_id'], $acats)) {
                        $ok = true;
                    }
                    if ($ok) {
                        $dolos[] = $dolo;
                    }
                }
                if (count($dolos) > 0) {
                    $tag['dolos'] = $dolos;
                    $ar["".$tag['id']] = $tag;
                }
            } else {
                $ar[$a['tag']] = $dolo_cache['tags'][$a['tag']];
            }
        }

        $r = '';
        if ($a['page']) {
            $b         = $a;
            $b['link'] = false;
            $r         = '<input id="doloSearch" placeholder="Search">';
            $r        .= '<div>';
            if (isset($a['featured'])) {
                $dolo_ids = explode(',', $a['featured']);
                $r .= '<div class="doloCat featured">';
                $r .= '<h3>';
                $r .= 'Featured';
                $r .= '</h3>';
                $r .= '<ul>';
                foreach ($dolo_ids as $id) {
                    $dolo = $dolo_cache['dolos'][$id];
                    $r .= '<li data-search="'.dolo_format($dolo, $b).'">';
                    $r .= dolo_format($dolo, $a);
                    $r .= '</li>';
                }
                $r .= '</ul>';
                $r .= '</div>';
            }
            foreach ($ar as $hum) {
                $r .= '<div class="doloCat">';
                if (!$a['notitle']) {
                    $r .= '<h3>';
                    $r .= $hum['name'];
                    $r .= '</h3>';
                }
                $r .= '<ul>';
                foreach ($hum['dolos'] as $dolo) {
                    $r .= '<li data-search="'.dolo_format($dolo, $b).'">';
                    $r .= dolo_format($dolo, $a);
                    $r .= '</li>';
                }
                $r .= '</ul>';
                $r .= '</div>';
            }
            $r .= '</div>';
        } else {
            foreach ($ar as $hum) {
                if (!$a['notitle']) {
                    $r .= '<h3>';
                    $r .= $hum['name'];
                    $r .= '</h3>';
                }
                $r .= '<ul>';
                foreach ($hum['dolos'] as $dolo) {
                    $r .= '<li>';
                    $r .= dolo_format($dolo, $a);
                    $r .= '</li>';
                }
                $r .= '</ul>';
            }
        }
        return $r;
    }
    add_shortcode('dolos', 'dolos_short');

    // Dolo short code
    function dolo_short($atts) {
        $a = shortcode_atts(array(
            'id'     => null,
            'name'   => null,
            'self'   => false,
            'link'   => false,
            'button' => false,
            'count'  => false,
            'extra'  => false,
        ), dolo_parse_atts($atts));
        if (!isset($a['id'])) {
            return;
        }

        $id = $a['id'];

        global $dolo_cache;
        $cache_expiration = get_option('dolomon-cache_expiration', 3600);
        if (!isset($dolo_cache['dolos']["$id"]) || (time() - $dolo_cache['last_fetch'] > $cache_expiration)) {
            dolomon_refresh_cache();
        }
        if (isset($dolo_cache['dolos']["$id"])) {
            $dolo = $dolo_cache['dolos']["$id"];
            return dolo_format($dolo, $a);
        } else {
            return 'Error';
        }
    }
    add_shortcode('dolo', 'dolo_short');

    // Widget
    include(dirname(__FILE__).'/widget.php');
?>
