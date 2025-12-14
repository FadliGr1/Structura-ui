<?php
namespace StructuraUI\Elements;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Chart Element Class
 * A professional chart component with mixed-type capabilities.
 * Designed for high-visual impact data visualization.
 */
class Chart extends \Bricks\Element {
    
    /**
     * Element Properties
     */
    public $category     = 'structura'; 
    public $name         = 'structura-chart';
    public $icon         = 'ti-bar-chart'; 
    public $css_selector = '.structura-chart-wrapper';
    public $scripts      = [];
    public $nestable     = false;

    /**
     * Get Element Label
     */
    public function get_label() {
        return esc_html__( 'Structura Chart', 'structura-ui' );
    }

    /**
     * Get Keywords
     */
    public function get_keywords() {
        return ['chart', 'graph', 'statistics', 'visual', 'mixed'];
    }

    /**
     * Define Control Groups
     */
    public function set_control_groups() {
        $this->control_groups['chart_config'] = [
            'title' => esc_html__( 'Configuration', 'structura-ui' ),
            'tab'   => 'content',
        ];
        
        $this->control_groups['chart_data'] = [
            'title' => esc_html__( 'Data Points', 'structura-ui' ),
            'tab'   => 'content',
        ];
    }

    /**
     * Define Controls
     */
    public function set_controls() {
        
        // --- Group: Configuration ---
        $this->controls['chartType'] = [
            'tab'   => 'content',
            'group' => 'chart_config',
            'label' => esc_html__( 'Global Chart Type', 'structura-ui' ),
            'type'  => 'select',
            'options' => [
                'bar'      => 'Bar Chart',
                'line'     => 'Line Chart',
                'pie'      => 'Pie Chart',
                'doughnut' => 'Doughnut Chart',
            ],
            'default' => 'bar',
            'description' => esc_html__( 'Default style for all datasets. Override per item below.', 'structura-ui' ),
        ];

        $this->controls['legendPosition'] = [
            'tab'   => 'content',
            'group' => 'chart_config',
            'label' => esc_html__( 'Legend Position', 'structura-ui' ),
            'type'  => 'select',
            'options' => [
                'top'    => 'Top',
                'bottom' => 'Bottom',
                'left'   => 'Left',
                'right'  => 'Right',
                'false'  => 'Hidden',
            ],
            'default' => 'top',
        ];

        // --- Group: Data Repeater ---
        $this->controls['datasets'] = [
            'tab'   => 'content',
            'group' => 'chart_data',
            'label' => esc_html__( 'Datasets', 'structura-ui' ),
            'type'  => 'repeater',
            'titleProperty' => 'label',
            'fields' => [
                'label' => [
                    'label'   => 'Label',
                    'type'    => 'text',
                    'default' => 'Label',
                ],
                'type' => [
                    'label'   => 'Dataset Type (Mixed)',
                    'type'    => 'select',
                    'options' => [
                        'global' => 'Inherit Global',
                        'line'   => 'Line (Overlay)',
                        'bar'    => 'Bar (Column)',
                    ],
                    'default' => 'global',
                    'description' => 'Select "Line" to create an overlay effect on Bar charts.',
                ],
                'value' => [
                    'label'   => 'Value',
                    'type'    => 'text', 
                    'default' => '0',
                    'description' => 'Numeric value or dynamic tag {acf_field}.',
                ],
                'color' => [
                    'label'   => 'Color',
                    'type'    => 'color',
                    'default' => '#3b82f6',
                ],
            ],
            // 🚀 VISUAL DATA PRESET (For beautiful screenshots)
            'default' => [
                [
                    'label' => 'Q1 Revenue', 
                    'value' => '450', 
                    'color' => '#6366f1', // Indigo
                    'type'  => 'global'
                ],
                [
                    'label' => 'Q2 Revenue', 
                    'value' => '580', 
                    'color' => '#8b5cf6', // Violet
                    'type'  => 'global'
                ],
                [
                    'label' => 'Q3 Revenue', 
                    'value' => '420', 
                    'color' => '#ec4899', // Pink
                    'type'  => 'global'
                ],
                [
                    'label' => 'Target Trend', 
                    'value' => '600', 
                    'color' => '#fbbf24', // Amber (Gold)
                    'type'  => 'global'     // Mixed Type!
                ],
            ],
        ];
    }

    /**
     * Render HTML
     */
    public function render() {
        $settings = $this->settings;
        $raw_datasets = isset($settings['datasets']) ? $settings['datasets'] : [];
        $processed_data = [];

        foreach ($raw_datasets as $item) {
            
            // 1. Process Label
            $label = $this->render_dynamic_data( isset($item['label']) ? $item['label'] : '' );

            // 2. Process Value
            $value_raw = $this->render_dynamic_data( isset($item['value']) ? $item['value'] : 0 );
            $value = (float) preg_replace('/[^0-9\.-]/', '', $value_raw);

            // 3. Process Color
            $color_raw = isset($item['color']) ? $item['color'] : '#3b82f6';
            if ( is_array( $color_raw ) && isset( $color_raw['hex'] ) ) {
                $color_raw = $color_raw['hex'];
            }
            if ( empty( $color_raw ) ) $color_raw = '#3b82f6';
            $color = $this->render_dynamic_data( $color_raw );

            // 4. Process Type
            $type = isset($item['type']) ? $item['type'] : 'global';

            $processed_data[] = [
                'label' => $label,
                'value' => $value,
                'color' => $color,
                'type'  => $type,
            ];
        }

        $vue_props = [
            'type'   => isset($settings['chartType']) ? $settings['chartType'] : 'bar',
            'legend' => isset($settings['legendPosition']) ? $settings['legendPosition'] : 'top',
            'data'   => $processed_data,
        ];

        $json_props = htmlspecialchars( json_encode( $vue_props ), ENT_QUOTES, 'UTF-8' );

        $this->set_attribute( '_root', 'class', 'structura-chart-wrapper' );

        echo "<div {$this->render_attributes( '_root' )}>";
        echo sprintf(
            '<div class="structura-vue-chart w-full" style="min-height: 300px; position: relative;" data-settings="%s"></div>',
            $json_props
        );
        echo "</div>";
    }
}