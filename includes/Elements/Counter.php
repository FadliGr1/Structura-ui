<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Counter
 * * Renders an animated number counter.
 * Features granular styling for Number, Prefix, Suffix, and Label.
 */
class Counter extends Element {

	public $category     = 'structura';
	public $name         = 'structura-counter';
	public $icon         = 'ti-stats-up'; 
	public $css_selector = '.structura-counter-wrapper';
	
	// Script dependency
	public $scripts      = [ 'structura-ui-script' ];

	public function get_label() {
		return esc_html__( 'Animated Counter', 'structura-ui' );
	}

	/**
	 * 1. Register Control Groups
	 */
	public function set_control_groups() {
		// Content Tab
		$this->control_groups['counter_content'] = [
			'title' => esc_html__( 'Counter Value', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['counter_settings'] = [
			'title' => esc_html__( 'Animation Settings', 'structura-ui' ),
			'tab'   => 'content',
		];

		// Style Tab
		$this->control_groups['style_number'] = [
			'title' => esc_html__( 'Number Styling', 'structura-ui' ),
			'tab'   => 'style',
		];

		$this->control_groups['style_affix'] = [
			'title' => esc_html__( 'Prefix & Suffix', 'structura-ui' ),
			'tab'   => 'style',
		];

		$this->control_groups['style_label'] = [
			'title' => esc_html__( 'Label Styling', 'structura-ui' ),
			'tab'   => 'style',
		];
	}

	/**
	 * 2. Register Controls
	 */
	public function set_controls() {

		/**
		 * GROUP: CONTENT
		 */
		$this->controls['start_number'] = [
			'tab'     => 'content',
			'group'   => 'counter_content',
			'label'   => esc_html__( 'Start Number', 'structura-ui' ),
			'type'    => 'number',
			'default' => 0,
		];

		$this->controls['end_number'] = [
			'tab'     => 'content',
			'group'   => 'counter_content',
			'label'   => esc_html__( 'End Number', 'structura-ui' ),
			'type'    => 'number',
			'default' => 100,
		];

		$this->controls['prefix'] = [
			'tab'         => 'content',
			'group'       => 'counter_content',
			'label'       => esc_html__( 'Prefix', 'structura-ui' ),
			'type'        => 'text',
			'placeholder' => '$',
			'default'     => '',
		];

		$this->controls['suffix'] = [
			'tab'         => 'content',
			'group'       => 'counter_content',
			'label'       => esc_html__( 'Suffix', 'structura-ui' ),
			'type'        => 'text',
			'placeholder' => '+',
			'default'     => '+',
		];

		$this->controls['label_text'] = [
			'tab'     => 'content',
			'group'   => 'counter_content',
			'label'   => esc_html__( 'Label / Title', 'structura-ui' ),
			'type'    => 'text',
			'default' => 'Happy Clients',
		];

		/**
		 * GROUP: SETTINGS
		 */
		$this->controls['duration'] = [
			'tab'         => 'content',
			'group'       => 'counter_settings',
			'label'       => esc_html__( 'Duration (ms)', 'structura-ui' ),
			'type'        => 'number',
			'default'     => 2000,
			'description' => esc_html__( 'How long the animation takes to complete.', 'structura-ui' ),
		];

		$this->controls['separator'] = [
			'tab'     => 'content',
			'group'   => 'counter_settings',
			'label'   => esc_html__( 'Thousand Separator', 'structura-ui' ),
			'type'    => 'select',
			'options' => [
				','  => esc_html__( 'Comma (,)', 'structura-ui' ),
				'.'  => esc_html__( 'Dot (.)', 'structura-ui' ),
				' '  => esc_html__( 'Space ( )', 'structura-ui' ),
				''   => esc_html__( 'None', 'structura-ui' ),
			],
			'default' => ',',
		];

		/**
		 * -------------------------------------------------------------------
		 * TAB: STYLE
		 * -------------------------------------------------------------------
		 */

		/**
		 * GROUP: NUMBER STYLING
		 */
		$this->controls['number_typography'] = [
			'tab'   => 'style',
			'group' => 'style_number',
			'label' => esc_html__( 'Typography', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.counter-number',
				],
			],
		];

		$this->controls['number_color'] = [
			'tab'   => 'style',
			'group' => 'style_number',
			'label' => esc_html__( 'Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.counter-number',
				],
			],
		];

		/**
		 * GROUP: PREFIX & SUFFIX
		 */
		$this->controls['prefix_typography'] = [
			'tab'   => 'style',
			'group' => 'style_affix',
			'label' => esc_html__( 'Prefix Style', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.counter-prefix',
				],
			],
		];

		$this->controls['suffix_typography'] = [
			'tab'   => 'style',
			'group' => 'style_affix',
			'label' => esc_html__( 'Suffix Style', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.counter-suffix',
				],
			],
		];
		
		$this->controls['affix_color'] = [
			'tab'   => 'style',
			'group' => 'style_affix',
			'label' => esc_html__( 'Common Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.counter-prefix, .counter-suffix',
				],
			],
		];

		/**
		 * GROUP: LABEL STYLING
		 */
		$this->controls['label_typography'] = [
			'tab'   => 'style',
			'group' => 'style_label',
			'label' => esc_html__( 'Label Typography', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.counter-label',
				],
			],
		];

		$this->controls['label_color'] = [
			'tab'   => 'style',
			'group' => 'style_label',
			'label' => esc_html__( 'Label Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.counter-label',
				],
			],
		];

		$this->controls['label_margin'] = [
			'tab'   => 'style',
			'group' => 'style_label',
			'label' => esc_html__( 'Margin / Spacing', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'margin',
					'selector' => '.counter-label',
				],
			],
		];
	}

	public function render() {
		$settings = $this->settings;

		// Props for Vue
		$props = [
			'start'     => isset( $settings['start_number'] ) ? (float) $settings['start_number'] : 0,
			'end'       => isset( $settings['end_number'] ) ? (float) $settings['end_number'] : 100,
			'duration'  => isset( $settings['duration'] ) ? (int) $settings['duration'] : 2000,
			'separator' => isset( $settings['separator'] ) ? $settings['separator'] : ',',
		];

		// Static content
		$prefix = isset( $settings['prefix'] ) ? $settings['prefix'] : '';
		$suffix = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
		$label  = isset( $settings['label_text'] ) ? $settings['label_text'] : '';

		// Wrapper Attributes
		$this->set_attribute( '_root', 'class', 'structura-vue-counter flex flex-col items-center justify-center' );
		$this->set_attribute( '_root', 'data-settings', json_encode( $props ) );

		echo "<div {$this->render_attributes( '_root' )}>";

		// 1. Number Wrapper (Prefix + Number + Suffix)
		echo '<div class="counter-value-wrapper flex items-baseline leading-none">';
			if ( $prefix ) echo '<span class="counter-prefix">' . esc_html( $prefix ) . '</span>';
			echo '<span class="counter-number font-bold text-4xl">0</span>'; // Default 0, Vue will animate
			if ( $suffix ) echo '<span class="counter-suffix">' . esc_html( $suffix ) . '</span>';
		echo '</div>';

		// 2. Label
		if ( $label ) {
			echo '<div class="counter-label mt-2 text-lg text-gray-600">' . esc_html( $label ) . '</div>';
		}

		echo "</div>";
	}
}