<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Typewriter
 * * Renders an animated typewriter text effect using Typed.js.
 * Fixed: Added granular styling controls for Prefix, Animated Strings, and Suffix.
 */
class Typewriter extends Element {

	public $category     = 'structura';
	public $name         = 'structura-typewriter';
	public $icon         = 'ti-text'; 
	public $css_selector = '.structura-typewriter-wrapper';
	
	// Script dependency ensures Vue/JS reloads in builder
	public $scripts      = [ 'structura-ui-script' ];

	public function get_label() {
		return esc_html__( 'Typewriter Text', 'structura-ui' );
	}

	/**
	 * 1. Register Control Groups
	 */
	public function set_control_groups() {
		// Content Tab Groups
		$this->control_groups['typewriter_content'] = [
			'title' => esc_html__( 'Text Content', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['typewriter_settings'] = [
			'title' => esc_html__( 'Animation Settings', 'structura-ui' ),
			'tab'   => 'content',
		];

		// Style Tab Groups
		$this->control_groups['style_general'] = [
			'title' => esc_html__( 'General Typography', 'structura-ui' ),
			'tab'   => 'style',
		];

		$this->control_groups['style_parts'] = [
			'title' => esc_html__( 'Individual Parts Styling', 'structura-ui' ),
			'tab'   => 'style',
		];
		
		$this->control_groups['style_cursor'] = [
			'title' => esc_html__( 'Cursor Style', 'structura-ui' ),
			'tab'   => 'style',
		];
	}

	/**
	 * 2. Register Controls
	 */
	public function set_controls() {

		/**
		 * GROUP: TEXT CONTENT
		 */
		$this->controls['prefix_text'] = [
			'tab'         => 'content',
			'group'       => 'typewriter_content',
			'label'       => esc_html__( 'Prefix Text', 'structura-ui' ),
			'type'        => 'text',
			'default'     => 'We are ',
			'placeholder' => 'e.g. We are ',
			'description' => esc_html__( 'Static text visible BEFORE the animation.', 'structura-ui' ),
		];

		$this->controls['strings'] = [
			'tab'           => 'content',
			'group'         => 'typewriter_content',
			'label'         => esc_html__( 'Animated Strings', 'structura-ui' ),
			'type'          => 'repeater',
			'titleProperty' => 'text',
			'fields'        => [
				'text' => [
					'label'   => esc_html__( 'String', 'structura-ui' ),
					'type'    => 'text',
					'default' => 'Creative',
				],
			],
			'default'       => [
				[ 'text' => 'Creative' ],
				[ 'text' => 'Professional' ],
				[ 'text' => 'Fast' ],
			],
		];

		$this->controls['suffix_text'] = [
			'tab'         => 'content',
			'group'       => 'typewriter_content',
			'label'       => esc_html__( 'Suffix Text', 'structura-ui' ),
			'type'        => 'text',
			'default'     => '',
			'placeholder' => 'e.g. Agency',
			'description' => esc_html__( 'Static text visible AFTER the animation.', 'structura-ui' ),
		];

		/**
		 * GROUP: ANIMATION SETTINGS
		 */
		$this->controls['type_speed'] = [
			'tab'     => 'content',
			'group'   => 'typewriter_settings',
			'label'   => esc_html__( 'Type Speed (ms)', 'structura-ui' ),
			'type'    => 'number',
			'default' => 50,
		];

		$this->controls['back_speed'] = [
			'tab'     => 'content',
			'group'   => 'typewriter_settings',
			'label'   => esc_html__( 'Back Speed (ms)', 'structura-ui' ),
			'type'    => 'number',
			'default' => 30,
		];

		$this->controls['start_delay'] = [
			'tab'     => 'content',
			'group'   => 'typewriter_settings',
			'label'   => esc_html__( 'Start Delay (ms)', 'structura-ui' ),
			'type'    => 'number',
			'default' => 0,
		];

		$this->controls['loop'] = [
			'tab'     => 'content',
			'group'   => 'typewriter_settings',
			'label'   => esc_html__( 'Loop Animation', 'structura-ui' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['cursor_char'] = [
			'tab'     => 'content',
			'group'   => 'typewriter_settings',
			'label'   => esc_html__( 'Cursor Character', 'structura-ui' ),
			'type'    => 'text',
			'default' => '|',
		];

		/**
		 * -------------------------------------------------------------------
		 * TAB: STYLE
		 * -------------------------------------------------------------------
		 */

		/**
		 * GROUP: GENERAL TYPOGRAPHY (Base)
		 */
		$this->controls['typography_base'] = [
			'tab'   => 'style',
			'group' => 'style_general',
			'label' => esc_html__( 'Root Typography', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.typewriter-container', 
				],
			],
			'description' => esc_html__( 'Sets base font size and family for the entire element.', 'structura-ui' ),
		];

		/**
		 * GROUP: INDIVIDUAL PARTS STYLING
		 * This is where you can style Prefix, Animation, and Suffix separately.
		 */
		
		// 1. PREFIX Styling
		$this->controls['prefix_typography'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Prefix Text Style', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.prefix-text',
				],
			],
		];
		
		$this->controls['prefix_color'] = [ // Extra explicit color control if needed
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Prefix Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.prefix-text',
				],
			],
		];

		// 2. ANIMATED TEXT Styling
		$this->controls['animated_typography'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Animated Text Style', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.typed-text',
				],
			],
		];

		$this->controls['animated_bg'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Animated Background', 'structura-ui' ),
			'type'  => 'background',
			'css'   => [
				[
					'property' => 'background',
					'selector' => '.typed-text',
				],
			],
		];
		
		$this->controls['animated_padding'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Animated Padding', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.typed-text',
				],
			],
		];

		// 3. SUFFIX Styling
		$this->controls['suffix_typography'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Suffix Text Style', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.suffix-text',
				],
			],
		];

		$this->controls['suffix_color'] = [
			'tab'   => 'style',
			'group' => 'style_parts',
			'label' => esc_html__( 'Suffix Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.suffix-text',
				],
			],
		];

		/**
		 * GROUP: CURSOR STYLE
		 */
		$this->controls['cursor_color'] = [
			'tab'   => 'style',
			'group' => 'style_cursor',
			'label' => esc_html__( 'Cursor Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'color',
					'selector' => '.typed-cursor',
				],
			],
		];

		$this->controls['cursor_size'] = [
			'tab'   => 'style',
			'group' => 'style_cursor',
			'label' => esc_html__( 'Cursor Size', 'structura-ui' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'font-size',
					'selector' => '.typed-cursor',
				],
			],
		];
	}

	public function render() {
		$settings = $this->settings;

		// Process Repeater
		$strings = [];
		if ( ! empty( $settings['strings'] ) ) {
			foreach ( $settings['strings'] as $item ) {
				if ( ! empty( $item['text'] ) ) {
					$strings[] = $item['text'];
				}
			}
		}

		if ( empty( $strings ) ) {
			$strings = [ 'Design', 'Development' ];
		}

		// Props
		$props = [
			'strings'    => $strings,
			'typeSpeed'  => isset( $settings['type_speed'] ) ? (int) $settings['type_speed'] : 50,
			'backSpeed'  => isset( $settings['back_speed'] ) ? (int) $settings['back_speed'] : 30,
			'startDelay' => isset( $settings['start_delay'] ) ? (int) $settings['start_delay'] : 0,
			'loop'       => isset( $settings['loop'] ) ? (bool) $settings['loop'] : true,
			'cursorChar' => isset( $settings['cursor_char'] ) ? $settings['cursor_char'] : '|',
		];

		// Validasi Key Array
		$prefix = isset( $settings['prefix_text'] ) ? $settings['prefix_text'] : '';
		$suffix = isset( $settings['suffix_text'] ) ? $settings['suffix_text'] : '';

		// Wrapper Attributes
		$this->set_attribute( '_root', 'class', 'structura-vue-typewriter typewriter-container' );
		$this->set_attribute( '_root', 'data-settings', json_encode( $props ) );

		echo "<div {$this->render_attributes( '_root' )}>";
		
		// 1. Prefix Span
		if ( $prefix ) {
			echo '<span class="prefix-text">' . esc_html( $prefix ) . '</span> ';
		}

		// 2. Animated Target
		echo '<span class="typed-text"></span>';

		// 3. Suffix Span
		if ( $suffix ) {
			echo ' <span class="suffix-text">' . esc_html( $suffix ) . '</span>';
		}

		echo "</div>";
	}
}