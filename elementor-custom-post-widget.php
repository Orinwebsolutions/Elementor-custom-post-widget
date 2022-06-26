<?php

/**
 * Plugin Name: Elementor custom-post Widget
 * Description: Custom post grid add into Elementor.
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Amila Priyankara
 * Author URI:  https://developers.elementor.com/
 * Text Domain: elementor-custom-post-widget
 *
 * Elementor tested up to: 3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register custom-post Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_custom_post_widget($widgets_manager)
{

    require_once(__DIR__ . '/widgets/custom-post-widget.php');

    $widgets_manager->register(new \Elementor_custom_post_Widget());
}
add_action('elementor/widgets/register', 'register_custom_post_widget');
