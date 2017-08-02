<?php
/* WPS */

class wps_ficwidget extends WP_Widget {

  static $fonts_used = array();

  function __construct() {
    // Instantiate the parent object
    parent::__construct( false, 'Easy Font Icon Widget' );
  }


  static function after_page_load() {
    echo self::set_google_fonts_js(self::$fonts_used);
  } // after_page_load


  function widget($args, $instance) {
    $title = apply_filters('widget_title', $instance['title']);

    // Set Options
    $font = trim($instance['font']);
    if ($font == 'regular') $instance['font'] = '';
    if (!empty($font) && $font != 'regular') {
      $font = explode(':', $font);

      $font_family = str_replace('+', ' ', $font[0]);
      $variant = $font[1];

      $find_font = wps_ficwidget::find_font($font_family);
      self::$fonts_used[] = $find_font['name'] . ':' . $variant;
    }

    $css = 'style="';
    $overlay_css = 'style="';

    if (!empty($instance['font-size'])) {
      $css .= 'font-size:' . $instance['font-size'] . ';';
    } 

    if (!empty($instance['font'])) {
      $css .= 'font-family:\'' . $font_family . '\', ' . $find_font['category'] . ';font-weight:' . $variant . ';';
    } 

    if (!empty($instance['line-height'])) {
      $css .= 'line-height:' . $instance['line-height'] . ';';
    } 

    if (!empty($instance['font-color'])) {
      $css .= 'color:' . $instance['font-color'] . ';';
    }    

    if (!empty($instance['content-paddings'])) {
      $css .= 'padding:';
      foreach ($instance['content-paddings'] as $k => $padding) {
        $css .= $padding . ' ';
      }
      $css .= ';';
    }

    if (!empty($instance['content-align'])) {
      $css .= 'text-align:';
      $css .= $instance['content-align'];
      $css .= ';';
    }
    
    if (empty($instance['widget-icon-color'])) {
      $instance['widget-icon-color'] = '#333';
    }

    $overlay_css .= '"';
    $css .= '"';

    // before and after widget arguments are defined by themes
    echo $args['before_widget'];

    if (!empty($title)) {
      echo $args['before_title'] . $title . $args['after_title']; 
    }

    echo '<div class="wps-ficwidget-output ' . $instance['widget-layout'] . '" ' . $css . '>';
    echo '<div class="wps-ficwidget-content">';
    echo '<div class="wps-ficwidget-icon">';
    echo '<i class="' . $instance['widget-icon'] . '" style="font-size:' . $instance['widget-icon-size']  . ';color:' . $instance['widget-icon-color'] . ';"></i>';
    echo '</div>';

    echo '<div class="wps-ficwidget-text">';
    if (!empty($instance['paragraphs'])) {
      echo wpautop($instance['content']);
    } else {
      echo $instance['content'];
    }
    echo '</div>';
    
    echo '</div>';
    echo '</div>';

    echo $args['after_widget'];
  } // widget


  function update($new_instance, $old_instance) {
    // Save widget options
    $instance = array();

    // Title
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['content'] = $new_instance['content'];
    $instance['paragraphs'] = $new_instance['paragraphs'];
    $instance['font-color'] = $new_instance['font-color'];
    $instance['font-size'] = $new_instance['font-size'];
    $instance['font'] = $new_instance['font'];
    $instance['line-height'] = $new_instance['line-height'];
    $instance['content-paddings'] = $new_instance['content-paddings'];
    $instance['content-align'] = $new_instance['content-align'];
    $instance['widget-layout'] = $new_instance['widget-layout'];
    $instance['widget-icon'] = $new_instance['widget-icon'];
    $instance['widget-icon-size'] = $new_instance['widget-icon-size'];
    $instance['widget-icon-color'] = $new_instance['widget-icon-color'];

    return $instance;
  } // update


  function is_checked($value, $setting) {
    if (!empty($setting) && $value == $setting) {
      return 'checked="checked"';
    } else {
      return '';
    }
  } // is_checked


  function form($instance) {
    // Widget Setup Form
    $title = 'Awesome Widget';
    $content = 'Your widget text can go here...';
    $content_align = 'left';
    $font_color = '#333333';
    $icon_color = '#333333';
    $paragraphs = '';
    $font_size = '14px';
    $font = '';
    $paddings = array('left' => '15px', 'top' => '15px', 'right' => '15px', 'bottom' => '15px');
    $line_height = 'auto';
    $icon = '';
    $icon_size = '18px';
    // Widget Layout
    $layout = 'layout-1';

    // Various Options
    $line_height_sizes['auto'] = 'Auto';
    for ($i=1;$i<=65;$i++) {
      $line_height_sizes[$i . 'px'] = $i . 'px';
    }


    $content_align_opts['left'] = 'Left';
    $content_align_opts['center'] = 'Center';
    $content_align_opts['right'] = 'Right';


    if (isset($instance['title'])) {
      $title = $instance['title'];
    }

    if (isset($instance['content'])) {
      $content = $instance['content'];
    }  

    if (isset($instance['content-align'])) {
      $content_align = $instance['content-align'];
    }    

    if (isset($instance['paragraphs'])) {
      $paragraphs = $instance['paragraphs'];
    }

    if (isset($instance['font-color'])) {
      $font_color = $instance['font-color'];
    }

    if (isset($instance['font-size'])) {
      $font_size = $instance['font-size'];
    }    

    if (isset($instance['font'])) {
      $font = $instance['font'];
    }

    if (isset($instance['content-paddings'])) {
      $paddings = $instance['content-paddings'];
    }

    if (isset($instance['line-height'])) {
      $line_height = $instance['line-height'];
    }

    if (isset($instance['widget-layout'])) {
      $layout = $instance['widget-layout'];
    }
    
    if (isset($instance['widget-icon'])) {
      $icon = $instance['widget-icon'];
    }    
    
    if (isset($instance['widget-icon-size'])) {
      $icon_size = $instance['widget-icon-size'];
    }
        
    if (isset($instance['widget-icon-color'])) {
      $icon_color = $instance['widget-icon-color'];
    }

    echo '<p>
    <label for="' . $this->get_field_id('title') . '">Title:</label>
    <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" />
    </p>';   

    echo '<p>
    <label for="' . $this->get_field_id('content') . '">Content:</label><br/>
    <textarea class="widefat" id="' . $this->get_field_id('content') . '" rows="10" name="' . $this->get_field_name('content') . '">' . esc_attr($content) . '</textarea>
    </p>';

    echo '<p>
    <label for="' . $this->get_field_id('paragraphs') . '">Automatically add paragraphs:</label>
    <input type="checkbox" id="' . $this->get_field_id('paragraphs') . '" name="' . $this->get_field_name('paragraphs') . '" value="1" ' . self::is_checked('1', $paragraphs) . ' />
    </p>';


    echo '<p>
    <label for="' . $this->get_field_id('widget-layout') . '">Widget Layout:</label>
    <input class="widefat wps-widget-layout" id="' . $this->get_field_id('widget-layout') . '" name="' . $this->get_field_name('widget-layout') . '" type="hidden" value="' . esc_attr($layout) . '" />
    <div class="wps-predefined-layout-samples">
    ' . self::predefined_layouts($layout) . '
    </div>
    </p>';
    
    echo '<p>
    <label for="' . $this->get_field_id('widget-icon') . '">Icon:</label><br/>
    <select class="wps-fic-icon-picker" id="' . $this->get_field_id('widget-icon') . '" name="' . $this->get_field_name('widget-icon') . '"> 
    ' . self::predefined_icons($icon) . '
    </select>
    </p>';    
    
    echo '<p>
    <label for="' . $this->get_field_id('widget-icon-size') . '">Icon Size:</label><br/>
    <select class="wps-fic-icon-size" id="' . $this->get_field_id('widget-icon-size') . '" name="' . $this->get_field_name('widget-icon-size') . '"> 
    ' . self::list_sizes($icon_size) . '
    </select>
    </p>';
    
    echo '<p>
    <label for="' . $this->get_field_id('widget-icon-color') . '">Widget Color:</label><br/>
    <input class="wps-fic-colorpicker" id="' . $this->get_field_id('widget-icon-color') . '" name="' . $this->get_field_name('widget-icon-color') . '" type="text" value="' . esc_attr($icon_color) . '" />
    </p>';


    echo '<hr/>';
    echo '<h3>Content Setup</h3>';

    echo '<p>
    <label for="' . $this->get_field_id('content-paddings') . '"><strong>Content Paddings:</strong></label><br/>
    <span class="padding-label">Top:</span><input class="padding-input" id="' . $this->get_field_id('content-paddings-top') . '" name="' . $this->get_field_name('content-paddings[top]') . '" type="text" value="' . esc_attr($paddings['top']) . '" />
    <span class="padding-label">Right:</span><input class="padding-input" id="' . $this->get_field_id('content-paddings-right') . '" name="' . $this->get_field_name('content-paddings[right]') . '" type="text" value="' . esc_attr($paddings['right']) . '" />
    <span class="padding-label">Bottom:</span><input class="padding-input" id="' . $this->get_field_id('content-paddings-bottom') . '" name="' . $this->get_field_name('content-paddings[bottom]') . '" type="text" value="' . esc_attr($paddings['bottom']) . '" />
    <span class="padding-label">Left:</span><input class="padding-input" id="' . $this->get_field_id('content-paddings-left') . '" name="' . $this->get_field_name('content-paddings[left]') . '" type="text" value="' . esc_attr($paddings['left']) . '" />
    </p>';

    echo '<p>
    <label for="' . $this->get_field_id('content-align') . '"><strong>Content Alignement:</strong></label><br/>
    <select class="widefat" name="' . $this->get_field_name('content-align') . '" id="' . $this->get_field_id('content-align') . '">
    ' . self::list_options($content_align_opts, $content_align) . '
    </select>
    </p>';

    echo '<hr/>';
    echo '<h3>Font Setup</h3>';

    echo '<p>
    <label for="' . $this->get_field_id('font-color') . '">Font Color:</label><br/>
    <input class="wps-fic-colorpicker" id="' . $this->get_field_id('font-color') . '" name="' . $this->get_field_name('font-color') . '" type="text" value="' . esc_attr($font_color) . '" />
    </p>';

    echo '<p>
    <label for="' . $this->get_field_id('font-size') . '">Font Size:</label><br/>
    <select class="widefat wps-fic-selected-font-size" name="' . $this->get_field_name('font-size') . '" id="' . $this->get_field_id('font-size') . '">
    ' . self::list_font_sizes($font_size) . '
    </select>
    </p>';

    echo '<p>
    <label for="' . $this->get_field_id('line-height') . '">Line Height:</label><br/>
    <select class="widefat" name="' . $this->get_field_name('line-height') . '" id="' . $this->get_field_id('line-height') . '">
    ' . self::list_options($line_height_sizes, $line_height) . '
    </select>
    </p>';

    echo '<p>
    <label for="' . $this->get_field_id('font') . '">Google Fonts:</label><br/>
    <select class="widefat wps-fic-selected-font" name="' . $this->get_field_name('font') . '" id="' . $this->get_field_id('font') . '">
    ' . self::list_fonts($font) . '
    </select>
    </p>';

    echo '<div class="wps-fic-preview-font">
    </div>';

    echo '<input type="button" class="wps-fic-preview-font-button button button-primary" value="Preview Font" />';
    echo '<hr/>';

    /*
    echo '<script type="text/javascript">';
    echo 'jQuery(document).ready(function($){';
    echo 'jQuery(\'.wps-fic-colorpicker\').wpColorPicker();';
    echo '});';
    echo '</script>';
    */
  } // form


  static function list_options($options, $selected, $group = '') {
    $output = '';

    if (is_array($options)) {

      if (!empty($group)) {
        $output .= '<optgroup label="' . $group . '">';
      }

      foreach ($options as $key => $option) {
        if ($key == $selected) {
          $output .= '<option value="' . $key . '" selected="selected">' . $option . '</option>';
        } else {
          $output .= '<option value="' . $key . '">' . $option . '</option>';
        }
      }

      if (!empty($group)) {
        $output .= '</optgroup>';
      }
    }

    return $output;
  } // $bg_type


  static function list_font_sizes($font_size = '') {
    $output = '';

    for ($i=12;$i<=52;$i++) {
      if ($i . 'px' == $font_size) {
        $output .= '<option value="' . $i . 'px" selected="selected">' . $i . 'px</option>';
      } else {
        $output .= '<option value="' . $i . 'px">' . $i . 'px</option>';
      }
    }

    return $output;
  } // list_font_sizes


  static function list_sizes($size = '') {
    $output = '';

    for ($i=0;$i<=50;$i++) {
      if ($i . 'px' == $size) {
        $output .= '<option value="' . $i . 'px" selected="selected">' . $i . 'px</option>';
      } else {
        $output .= '<option value="' . $i . 'px">' . $i . 'px</option>';
      }
    }

    return $output;
  } // list_font_sizes


  static function list_fonts($font_family = '') {
    $output = '';
    $fonts = get_option(WPS_FIC_SLUG);

    $output .= '<option value="regular">Use web page default font-family.</option>';

    if ($fonts) {
      foreach ($fonts as $font) {

        if (is_array($font['variants'])) {
          foreach ($font['variants'] as $index => $variant) {
            $font_slug = str_replace(' ', '+', $font['name']);
            if ($font_slug . ':' . $variant == $font_family) {
              $output .= '<option value="' . $font_slug . ':' . $variant . '" selected="selected">' . $font['name'] . ' (' . $variant . ')</option>';
            } else {
              $output .= '<option value="' . $font_slug . ':' . $variant . '">' . $font['name'] . ' (' . $variant . ')</option>';
            }
          }
        }

      }
    } else {
      $output .= '<option value="error">Error: No google fonts loaded - contact support.</option>';
    }

    return $output; 
  } // list_fonts


  static function find_font($font) {
    $font = sanitize_title($font);
    $fonts = get_option(WPS_FIC_SLUG);

    if (!empty($fonts[$font])) {
      return $fonts[$font];
    } else {
      return false;
    }

  } // find_font


  static function set_google_fonts_js($instance) {
    $output = '';
    $fonts = '';

    if (!empty($instance) && is_array($instance)) {
      foreach ($instance as $key => $font) {
        $fonts .= "'" . $font . "',";
      }
      $fonts = rtrim($fonts,',');

      $rnd = rand(285,999)+rand(5,55);
      $output .= '<script type="text/javascript">';
      #$output .= '$("head").append("<link href=\'https://fonts.googleapis.com/css?family=' . $instance . '\' rel=\'stylesheet\' type=\'text/css\'>");';

      $output .= "WebFontConfig = {
      google: { families: [ " . $fonts . " ] }
      };

      (function() {
      var wps_" . $rnd . " = document.createElement('script');
      wps_" . $rnd . ".src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
      wps_" . $rnd . ".type = 'text/javascript';
      wps_" . $rnd . ".async = 'true';
      var s" . $rnd . " = document.getElementsByTagName('script')[0];
      s" . $rnd . ".parentNode.insertBefore(wps_" . $rnd . ", s" . $rnd . ");
      })();";

      $output .= '</script>';

    }

    return $output;
  } // set_google_fonts_js
  

  static function predefined_icons($selected = '') {
    $output = '';
    
    $icons = get_option(WPS_FIC_SLUG . '-icons');
    
    foreach ($icons as $icon_code => $icon_name) {

      if ($icon_name == $selected) {
        $output .= '<option selected="selected">' . $icon_name . '</option>';
      } else {
        $output .= '<option>' . $icon_name . '</option>';
      }
    }

    return $output;
  } // predefined_icons


  static function predefined_layouts($selected = '') {
    $output = '';

    $layout = array();
    $layout['layout-1'] = 'Layout 1';
    $layout['layout-2'] = 'Layout 2';


    foreach ($layout as $layout_k => $name) {
      $img_src = plugins_url('images/' . $layout_k . '.png', __FILE__);
      if ($layout_k == $selected) {
        $output .= '<div class="wps-fic-layout-sample selected" data-layout="' . $layout_k . '" style="background:url(\'' . $img_src . '\') top left repeat;">&nbsp;</div>';
      } else {
        $output .= '<div class="wps-fic-layout-sample" data-layout="' . $layout_k . '" style="background:url(\'' . $img_src . '\') top left repeat;">&nbsp;</div>';
      }
    }

    return $output;
  } // predefined_layouts


  static function hex2rgba($color, $opacity = false) {

    $default = 'rgb(0,0,0)';

    //Return default if no color provided
    if(empty($color))
      return $default; 

    //Sanitize $color if "#" is provided 
    if ($color[0] == '#' ) {
      $color = substr( $color, 1 );
    }

    //Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
      $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
    } elseif ( strlen( $color ) == 3 ) {
      $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
    } else {
      return $default;
    }

    //Convert hexadec to rgb
    $rgb =  array_map('hexdec', $hex);

    //Check if opacity is set(rgba or rgb)
    if($opacity){
      if(abs($opacity) > 1)
        $opacity = 1.0;
      $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
    } else {
      $output = 'rgb('.implode(",",$rgb).')';
    }

    //Return rgb(a) color string
    return $output;
  } // hex2rgba


} // wps_ficidget

function wps_ficwidget_register_widgets() {
  register_widget('wps_ficwidget');
} // wps_ficwidget_register_widgets

add_action('widgets_init', 'wps_ficwidget_register_widgets');