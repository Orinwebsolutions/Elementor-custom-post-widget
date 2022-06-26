<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor custom-post Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Elementor_custom_post_Widget extends \Elementor\Widget_Base
{

    public $colors;

    /**
     * Get widget name.
     *
     * Retrieve custom-post widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'custom-post';
    }

    /**
     * Get widget title.
     *
     * Retrieve custom-post widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('custom-post', 'elementor-custom-post-widget');
    }

    /**
     * Get widget icon.
     *
     * Retrieve custom-post widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-code';
    }

    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url()
    {
        return 'https://developers.elementor.com/docs/widgets/';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the custom-post widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the custom-post widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['custom-post', 'grid-layout', 'link'];
    }

    /**
     * Register custom-post widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'elementor-custom-post-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $categories = get_categories(['hide_empty' => false]);
        $options = [];
        $colorsindex = ['5dc', 'bf5', 'ff6', '732', '2cb', '46c', 'ffe', '4c1', 'ff6', '5ac', 'ffc', 'c05', 'C8B', '8AD'];
        foreach ($categories as $key => $category) {

            if (count($colorsindex) - 1 >= $key) {
                $this->colors[$category->term_id] = '#' . substr($colorsindex[$key] . strrev(uniqid()), 0, 6);
            } else {
                $this->colors[$category->term_id] = '#' . substr(strrev(uniqid()), 0, 6);
            }
            error_log($category->term_id);
            $options[$category->term_id] = $category->name;
        }

        $this->add_control(
            'selected_categories',
            [
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label' => esc_html__('Select Category', 'elementor-custom-post-widget'),
                'options' => $options,
                'multiple' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render custom-post widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $arg = [];
        if (count($settings['selected_categories']) > 0) {
            $arg = ['category' => implode(",", $settings['selected_categories'])];
        }
        $arg['numberposts'] = 3;
        $posts  = get_posts($arg);
        if ($posts) {
            $allcategories = get_categories(['hide_empty' => false]);
            echo '<div class="posts-archive-categories">';
            foreach ($allcategories as $key => $category) {
                echo '<div class="btn-category" style="background-color:' . $this->colors[$category->term_id] . '">' . $category->name . '</div>';
            }
            echo "</div>";
            foreach ($posts as $key => $post) {

                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
                $imageurl = '';
                if ($image) {
                    $imageurl = $image[0];
                }
                $posturl = get_permalink($post->ID);
                $categories = get_the_category($post->ID);
                echo '<div class="news-item">
                    <a href="' . $posturl . '" class="news-a">
                        <div class="item-news-content">
                            <div class="item-date">
                                <div class="item-date-format">' . date_create($post->post_date)->format('d.m.Y') . '</div>
                            </div>
                            <h2 class="item-news-h">' . $post->post_title . '</h2>
                            <div class="item-news-img">';
                if ($imageurl) {
                    echo '<img src="' . $imageurl . '" class="img-responsive wp-post-image lazy entered loaded" alt="" loading="lazy" title="gs-11-full" data-ll-status="loaded" sizes="(max-width: 600px) 100vw, 600px">';
                }
                echo '</div>
                            <div class="item-news-category">';
                foreach ($categories as $key => $category) {
                    echo '<div class="btn-category" style="background-color:' . $this->colors[$category->term_id] . '">' . $category->name . '</div>';
                }
                echo '</div>
                        </div>
                    </a>';
                echo '</div>';
            }
        }
    }
}
