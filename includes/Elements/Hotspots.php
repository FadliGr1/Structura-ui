<?php
namespace StructuraUI\Elements;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Image Hotspots Element
 * Interactive image component with customizable icons and premium tooltips.
 * Features:
 * - Native Bricks Icon Support
 * - Pulse Animations
 * - Responsive Positioning
 */
class Hotspots extends \Bricks\Element {
    
    /**
     * Element Properties
     */
    public $category     = 'structura'; 
    public $name         = 'structura-hotspots';
    public $icon         = 'ti-target'; 
    public $css_selector = '.structura-hotspots-wrapper';
    public $scripts      = [];
    public $nestable     = false;

    /**
     * Get Element Label
     */
    public function get_label() {
        return esc_html__( 'Image Hotspots', 'structura-ui' );
    }

    /**
     * Get Search Keywords
     */
    public function get_keywords() {
        return ['image', 'hotspot', 'tooltip', 'map', 'product', 'marker', 'pin'];
    }

    /**
     * Define Control Groups
     */
    public function set_control_groups() {
        $this->control_groups['hotspot_content'] = [
            'title' => esc_html__( 'Content & Points', 'structura-ui' ),
            'tab'   => 'content',
        ];
        $this->control_groups['hotspot_style'] = [
            'title' => esc_html__( 'Styling', 'structura-ui' ),
            'tab'   => 'content',
        ];
    }

    /**
     * Define Controls
     */
    public function set_controls() {
        
        // --- Group: Content ---
        $this->controls['baseImage'] = [
            'tab'      => 'content',
            'group'    => 'hotspot_content',
            'label'    => esc_html__( 'Base Image', 'structura-ui' ),
            'type'     => 'image',
            'default'  => [ 'url' => 'https://images.unsplash.com/photo-1493723843689-ce20b36b8d28?q=80&w=1200&auto=format&fit=crop' ],
            'description' => esc_html__( 'High-quality background image.', 'structura-ui' ),
        ];

        // --- Repeater: Hotspots ---
        $this->controls['points'] = [
            'tab'     => 'content',
            'group'   => 'hotspot_content',
            'label'   => esc_html__( 'Hotspots List', 'structura-ui' ),
            'type'    => 'repeater',
            'titleProperty' => 'title',
            'fields' => [
                'icon' => [
                    'label'   => 'Marker Icon',
                    'type'    => 'icon',
                    'default' => 'ti-plus', 
                ],
                'posX' => [
                    'label'   => 'Position X (%)',
                    'type'    => 'number',
                    'min'     => 0,
                    'max'     => 100,
                    'default' => 50,
                ],
                'posY' => [
                    'label'   => 'Position Y (%)',
                    'type'    => 'number',
                    'min'     => 0,
                    'max'     => 100,
                    'default' => 50,
                ],
                'title' => [
                    'label'   => 'Tooltip Title',
                    'type'    => 'text',
                    'default' => 'Product Name',
                ],
                'description' => [
                    'label'   => 'Description',
                    'type'    => 'textarea',
                    'default' => 'Short description here.',
                ],
                'price' => [
                    'label'   => 'Price / Label',
                    'type'    => 'text',
                    'default' => '$0.00',
                ],
                'link' => [
                    'label'   => 'Link',
                    'type'    => 'link',
                ],
            ],
            // 🚀 VISUAL DUMMY DATA (Modern Workspace)
            'default' => [
                [
                    'icon'        => 'ti-desktop',
                    'posX'        => 50, 
                    'posY'        => 42, 
                    'title'       => '5K Retina Display', 
                    'description' => 'Crystal clear resolution for designers.',
                    'price'       => '$1,299'
                ],
                [
                    'icon'        => 'ti-pencil-alt',
                    'posX'        => 75, 
                    'posY'        => 65, 
                    'title'       => 'Mechanical Keyboard', 
                    'description' => 'Tactile feedback for fast typing.',
                    'price'       => '$149'
                ],
                [
                    'icon'        => 'ti-shine',
                    'posX'        => 20, 
                    'posY'        => 55, 
                    'title'       => 'Ambient Lamp', 
                    'description' => 'Warm light for late night sessions.',
                    'price'       => '$89'
                ],
            ],
        ];

        // --- Group: Styling ---
        $this->controls['markerColor'] = [
            'tab'     => 'content',
            'group'   => 'hotspot_style',
            'label'   => esc_html__( 'Marker Color', 'structura-ui' ),
            'type'    => 'color',
            'default' => '#3b82f6',
        ];

        $this->controls['iconColor'] = [
            'tab'     => 'content',
            'group'   => 'hotspot_style',
            'label'   => esc_html__( 'Icon Color', 'structura-ui' ),
            'type'    => 'color',
            'default' => '#ffffff',
        ];

        $this->controls['pulseAnimation'] = [
            'tab'     => 'content',
            'group'   => 'hotspot_style',
            'label'   => esc_html__( 'Enable Pulse Animation', 'structura-ui' ),
            'type'    => 'checkbox',
            'default' => true,
        ];
    }

    /**
     * Render HTML
     */
    public function render() {
        $settings = $this->settings;

        /**
         * Helper: Get Image URL
         */
        $get_image_url = function( $img_data ) {
            if ( is_array( $img_data ) && isset( $img_data['url'] ) ) return $img_data['url'];
            if ( is_array( $img_data ) && isset( $img_data['id'] ) ) return wp_get_attachment_url( $img_data['id'] );
            if ( is_numeric( $img_data ) ) return wp_get_attachment_url( $img_data );
            if ( is_string( $img_data ) && ! empty( $img_data ) ) return $img_data;
            return 'https://via.placeholder.com/1200x800?text=Select+Image';
        };

        $image_url = $get_image_url( isset($settings['baseImage']) ? $settings['baseImage'] : [] );

        // Process Points
        $points = isset($settings['points']) ? $settings['points'] : [];
        $processed_points = [];

        foreach ($points as $point) {
            // Icon handling: Bricks returns class string (e.g. 'ti-user') or array for SVG
            $icon_class = isset($point['icon']) ? $point['icon'] : 'ti-plus';
            
            // If it's an array (SVG), we might need extra logic, 
            // but for standard icons, it's a string.
            if (is_array($icon_class) && isset($icon_class['library'])) {
                 $icon_class = $icon_class['library'] . ' ' . $icon_class['icon'];
            }

            $processed_points[] = [
                'x'     => isset($point['posX']) ? (float)$point['posX'] : 50,
                'y'     => isset($point['posY']) ? (float)$point['posY'] : 50,
                'icon'  => $icon_class,
                'title' => $this->render_dynamic_data( isset($point['title']) ? $point['title'] : '' ),
                'desc'  => $this->render_dynamic_data( isset($point['description']) ? $point['description'] : '' ),
                'price' => $this->render_dynamic_data( isset($point['price']) ? $point['price'] : '' ),
                'link'  => isset($point['link']) ? $point['link'] : null,
            ];
        }

        $vue_props = [
            'image'       => $image_url,
            'points'      => $processed_points,
            'markerColor' => isset($settings['markerColor']) ? $settings['markerColor'] : '#3b82f6',
            'iconColor'   => isset($settings['iconColor']) ? $settings['iconColor'] : '#ffffff',
            'pulse'       => isset($settings['pulseAnimation']) ? (bool)$settings['pulseAnimation'] : true,
        ];

        $json_props = htmlspecialchars( json_encode( $vue_props ), ENT_QUOTES, 'UTF-8' );

        $this->set_attribute( '_root', 'class', 'structura-hotspots-wrapper' );

        echo "<div {$this->render_attributes( '_root' )}>";
        echo sprintf(
            '<div class="structura-vue-hotspots w-full h-auto relative" data-settings="%s"></div>',
            $json_props
        );
        echo "</div>";
    }
}