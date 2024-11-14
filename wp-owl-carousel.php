<?php
/**
 * Plugin Name: WP Owl Carousel
 * Plugin URI: https://github.com/carterfromsl/wp-owl-carousel/
 * Description: This is a plugin to integrate Owl Carousel 2.3.4 into your WordPress site. Sample shortcode <code>[owl-carousel class="custom-class" loop="true" margin="10" autoplay="false" autoplay_timeout="5000" autoplay_hover_pause="true" nav="true" mouse_drag="true" touch_drag="true" slide_by="1" lazy_load="false" screen_smallest="1" screen_small="2" screen_medium="3" screen_large="4" screen_largest="5"]</code>
 * Version: 1.0.3
 * Author: StratLab Marketing
 * Author URI: https://strategylab.ca
 * Text Domain: wp-owl-carousel
 * Requires at least: 6.0
 * Requires PHP: 7.0
 * Update URI: https://github.com/carterfromsl/wp-owl-carousel/
 * Owl Carousel 2.3.4 is originally authored by David Deutsch (https://owlcarousel2.github.io/OwlCarousel2/)
 */

// Connect with the StratLab Auto-Updater for plugin updates
add_action('plugins_loaded', function() {
    if (class_exists('StratLabUpdater')) {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugin_file = __FILE__;
        $plugin_data = get_plugin_data($plugin_file);

        do_action('stratlab_register_plugin', [
            'slug' => plugin_basename($plugin_file),
            'repo_url' => 'https://api.github.com/repos/carterfromsl/wp-owl-carousel/releases/latest',
            'version' => $plugin_data['Version'], 
            'name' => $plugin_data['Name'],
            'author' => $plugin_data['Author'],
            'homepage' => $plugin_data['PluginURI'],
            'description' => $plugin_data['Description'],
            'access_token' => '', // Add if needed for private repo
        ]);
    }
});

function enqueue_owlcarousel_files() {
  if (!is_admin()) {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'owl-carousel')) {
      wp_enqueue_style('owl-carousel', plugins_url('owl.carousel.min.css', __FILE__));
      wp_enqueue_style('owl-theme-default', plugins_url('owl.theme.default.min.css', __FILE__));
      wp_enqueue_script('owl-carousel-js', plugins_url('owl.carousel.min.js', __FILE__), array('jquery'), '', true);
    }
  }
}
add_action('wp_enqueue_scripts', 'enqueue_owlcarousel_files');

function owl_carousel_shortcode($atts) {
  $atts = shortcode_atts( array(
    'class' => 'carousel',
    'loop' => 'true',
    'margin' => 0,
    'autoplay' => 'true',
    'autoplay_timeout' => 2500,
    'autoplay_hover_pause' => 'true',
    'nav' => 'true',
    'mouse_drag' => 'true',
    'touch_drag' => 'true',
    'slide_by' => 1,
    'lazy_load' => 'false',
    'screen_smallest' => 1,
    'screen_small' => 2,
    'screen_medium' => 3,
    'screen_large' => 4,
    'screen_largest' => 4,
  ), $atts );

  $owl_carousel_script = "
  <script type='text/javascript'>
    jQuery(document).ready(function( $ ) {
      $('." . $atts['class'] . "').owlCarousel({
        loop: " . $atts['loop'] . ",
        margin: " . $atts['margin'] . ",
        autoplay: " . $atts['autoplay'] . ",
        autoplayTimeout: " . $atts['autoplay_timeout'] . ",
        autoplayHoverPause: " . $atts['autoplay_hover_pause'] . ",
        nav: " . $atts['nav'] . ",
        mouseDrag: " . $atts['mouse_drag'] . ",
        touchDrag: " . $atts['touch_drag'] . ",
        merge: false,
        slideBy: " . $atts['slide_by'] . ",
        lazyLoad: " . $atts['lazy_load'] . ",
        responsive: {
          0: { items: " . $atts['screen_smallest'] . " },
          480: { items: " . $atts['screen_small'] . " },
          767: { items: " . $atts['screen_medium'] . " },
          989: { items: " . $atts['screen_large'] . " },
          1310: { items: " . $atts['screen_largest'] . " },
        }
      });
      $('." . $atts['class'] . "').addClass('owl-carousel');
    });
  </script>";

  return $owl_carousel_script;
}
add_shortcode('owl-carousel', 'owl_carousel_shortcode');

?>
