<?php
namespace StructuraUI\Elements;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Before/After Image Slider Element
 * A specialized component for comparing two images interactively.
 * Includes robust fallback for placeholder images.
 */
class BeforeAfter extends \Bricks\Element {
    
    /**
     * Element Properties
     */
    public $category     = 'structura'; 
    public $name         = 'structura-before-after';
    public $icon         = 'ti-split-v'; 
    public $css_selector = '.structura-before-after-wrapper';
    public $scripts      = [];
    public $nestable     = false;

    /**
     * Get Element Label
     */
    public function get_label() {
        return esc_html__( 'Before/After Slider', 'structura-ui' );
    }

    /**
     * Get Search Keywords
     */
    public function get_keywords() {
        return ['slider', 'image', 'compare', 'comparison', 'before', 'after'];
    }

    /**
     * Define Control Groups
     */
    public function set_control_groups() {
        $this->control_groups['slider_content'] = [
            'title' => esc_html__( 'Images & Content', 'structura-ui' ),
            'tab'   => 'content',
        ];

        $this->control_groups['slider_settings'] = [
            'title' => esc_html__( 'Settings', 'structura-ui' ),
            'tab'   => 'content',
        ];
    }

    /**
     * Define Controls
     */
    public function set_controls() {
        
        // --- Group: Images ---
        $this->controls['imageBefore'] = [
            'tab'   => 'content',
            'group' => 'slider_content',
            'label' => esc_html__( 'Before Image (Left)', 'structura-ui' ),
            'type'  => 'image',
            'default' => [], // Leave empty here, handled in render fallback
            'description' => esc_html__( 'The original image shown underneath.', 'structura-ui' ),
        ];

        $this->controls['imageAfter'] = [
            'tab'   => 'content',
            'group' => 'slider_content',
            'label' => esc_html__( 'After Image (Right)', 'structura-ui' ),
            'type'  => 'image',
            'default' => [], // Leave empty here, handled in render fallback
            'description' => esc_html__( 'The modified image shown on top.', 'structura-ui' ),
        ];

        // --- Group: Labels ---
        $this->controls['labelBefore'] = [
            'tab'     => 'content',
            'group'   => 'slider_content',
            'label'   => esc_html__( 'Before Label', 'structura-ui' ),
            'type'    => 'text',
            'default' => 'Before',
        ];

        $this->controls['labelAfter'] = [
            'tab'     => 'content',
            'group'   => 'slider_content',
            'label'   => esc_html__( 'After Label', 'structura-ui' ),
            'type'    => 'text',
            'default' => 'After',
        ];

        // --- Group: Settings ---
        $this->controls['orientation'] = [
            'tab'     => 'content',
            'group'   => 'slider_settings',
            'label'   => esc_html__( 'Orientation', 'structura-ui' ),
            'type'    => 'select',
            'options' => [
                'horizontal' => 'Horizontal (Left/Right)',
                'vertical'   => 'Vertical (Top/Bottom)',
            ],
            'default' => 'horizontal',
        ];

        $this->controls['startPos'] = [
            'tab'     => 'content',
            'group'   => 'slider_settings',
            'label'   => esc_html__( 'Start Position (%)', 'structura-ui' ),
            'type'    => 'number',
            'min'     => 0,
            'max'     => 100,
            'default' => 50,
        ];
    }

    /**
     * Render HTML
     */
    public function render() {
        $settings = $this->settings;

        /**
         * Helper: Get Image URL safely
         * Handles array format (Bricks default), ID, or string URL.
         */
        $get_image_url = function( $img_data ) {
            if ( is_array( $img_data ) && isset( $img_data['url'] ) ) return $img_data['url'];
            if ( is_array( $img_data ) && isset( $img_data['id'] ) ) return wp_get_attachment_url( $img_data['id'] );
            if ( is_numeric( $img_data ) ) return wp_get_attachment_url( $img_data );
            if ( is_string( $img_data ) && ! empty( $img_data ) ) return $img_data;
            return false;
        };

        // 1. Extract URLs
        $url_before = $get_image_url( isset( $settings['imageBefore'] ) ? $settings['imageBefore'] : [] );
        $url_after  = $get_image_url( isset( $settings['imageAfter'] ) ? $settings['imageAfter'] : [] );

        /**
         * Fallback: SVG Data URI
         * Used if no image is selected. A lightweight gray placeholder with text.
         * This avoids external HTTP requests to via.placeholder.com (which can fail).
         */
        $placeholder_svg = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='600' viewBox='0 0 800 600'%3E%3Crect width='800' height='600' fill='%23e2e8f0'/%3E%3Ctext x='50%25' y='50%25' font-family='sans-serif' font-size='30' fill='%2394a3b8' dominant-baseline='middle' text-anchor='middle'%3ESelect Image%3C/text%3E%3C/svg%3E";

        if ( ! $url_before ) $url_before = $placeholder_svg;
        if ( ! $url_after ) $url_after = $placeholder_svg;

        // 2. Prepare Props for Vue
        $vue_props = [
            'beforeImage' => $url_before,
            'afterImage'  => $url_after,
            'beforeLabel' => isset( $settings['labelBefore'] ) ? $settings['labelBefore'] : 'Before',
            'afterLabel'  => isset( $settings['labelAfter'] ) ? $settings['labelAfter'] : 'After',
            'orientation' => isset( $settings['orientation'] ) ? $settings['orientation'] : 'horizontal',
            'startPos'    => isset( $settings['startPos'] ) ? (int) $settings['startPos'] : 50,
        ];

        // 3. Encode Props
        $json_props = htmlspecialchars( json_encode( $vue_props ), ENT_QUOTES, 'UTF-8' );

        $this->set_attribute( '_root', 'class', 'structura-before-after-wrapper' );

        echo "<div {$this->render_attributes( '_root' )}>";
        echo sprintf(
            '<div class="structura-vue-slider w-full h-full" data-settings="%s"></div>',
            $json_props
        );
        echo "</div>";
    }
}