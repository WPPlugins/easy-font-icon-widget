<?php
/*
Plugin name: Easy Font Icon Widget
Author: Premium WP Suite
Author URI: http://www.premiumwpsuite.com
Version: 1.0.0
Description: Display your widgets in fancy style by using power of font icons!
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('WPS_FIC_SLUG', 'wps_fic');
define('WPS_FIC_FONTS', 'wps_fic_fonts');

require_once 'widget.php';

class wps_fic {

  static $version = '1.0.0';

  static function init() {

    if (is_admin()) {
      add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
      add_action('wp_ajax_get_font_preview', array(__CLASS__, 'ajax_preview_font'));
    } else {
      add_action('wp_enqueue_scripts', array(__CLASS__, 'frontend_enqueue_scripts'));
      add_action('wp_print_footer_scripts', array('wps_gfwidget', 'after_page_load'));
    }


  } // init
  
  
  static function ajax_preview_font() {
    $font = trim($_POST['font']);
    $font = explode(':', $font);
    
    $font_family = str_replace('+', ' ', $font[0]);
    $variant = $font[1];
    
    $find_font = wps_gfwidget::find_font($font_family);
    if ($find_font) {
      wp_send_json_success(array('family' => $find_font['name'], 'variant' => $find_font['category']));
    } else {
      wp_send_json_error();
    }
    
    die();
  } // ajax_preview_font


  static function get_icons() {
    $fonts = wp_remote_get(plugins_url('apps/icons/css/fontello.css', __FILE__));
    $fonts = $fonts['body'];
    $icons = array();
    
    preg_match_all('/\.(icon-)(.*)\:/siU', $fonts, $matches);
    foreach ($matches[0] as $key => $icon) {
      $icon = ltrim($icon, '.');
      $icon = rtrim($icon, ':');
      $icons[] = $icon;
    }
    
    update_option(WPS_FIC_SLUG . '-icons', $icons);
  } // get_fonts
  
  
  static function frontend_enqueue_scripts() {
    wp_enqueue_style(WPS_FIC_SLUG . '-fontello', plugins_url('/apps/icons/css/fontello.css', __FILE__), array(), self::$version);
    wp_enqueue_style(WPS_FIC_SLUG . '-widget', plugins_url('/css/frontend.css', __FILE__), array(), self::$version);
  } // frontend_enqueue_scripts


  static function admin_enqueue_scripts() {
    $screen = get_current_screen();

    // wps_gfwidget
    if ($screen->base == 'widgets') {
      wp_enqueue_style(WPS_FIC_SLUG . '-fontello', plugins_url('/apps/icons/css/fontello.css', __FILE__), array(), self::$version);
    }
    
    wp_enqueue_style(WPS_FIC_SLUG . '-icon-picker-main', plugins_url('/apps/iconpicker/css/jquery.fonticonpicker.min.css', __FILE__), array(), self::$version);
    wp_enqueue_style(WPS_FIC_SLUG . '-icon-picker', plugins_url('/apps/iconpicker/themes/grey-theme/jquery.fonticonpicker.grey.min.css', __FILE__), array(), self::$version);
    wp_enqueue_script(WPS_FIC_SLUG . '-icon-picker', plugins_url('/apps/iconpicker/jquery.fonticonpicker.js', __FILE__), array('jquery'), self::$version, true);
    
    
    wp_enqueue_style(WPS_FIC_SLUG . '-widget-area', plugins_url('/css/widget-area.css', __FILE__), array(), self::$version);
    wp_enqueue_script(WPS_FIC_SLUG . '-widget-area', plugins_url('/js/widget-area.scripts.js', __FILE__), array('jquery'), self::$version, true);
    //}

    // All pages
    wp_enqueue_style('wp-color-picker'); 
    wp_enqueue_script('wp-color-picker'); 
    wp_enqueue_script(WPS_FIC_SLUG . '-widgets-js', plugins_url('/js/widgets.scripts.js', __FILE__), array('jquery'), self::$version, true);
    
    wp_enqueue_media();

  } // admin_enqueue_scripts
  
  
  static function install() {
    self::get_icons();
  } // install


} // wps_gfwidget

add_action('init', array('wps_fic', 'init'));
register_activation_hook(__FILE__, array('wps_fic', 'install'));