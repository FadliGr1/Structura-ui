<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Lottie
 * * Renders advanced Lottie JSON animations.
 * Fixed: Now uses set_control_groups() as per official Bricks documentation.
 */
class Lottie extends Element {

	/**
	 * Element category
	 */
	public $category = 'structura';

	/**
	 * Element name
	 */
	public $name = 'structura-lottie';

	/**
	 * Element icon
	 */
	public $icon = 'ti-control-play';

	/**
	 * Element CSS class
	 */
	public $css_selector = '.structura-lottie-wrapper';

	/**
	 * Get element label
	 */
	public function get_label() {
		return esc_html__( 'Lottie Animation', 'structura-ui' );
	}

	/**
	 * 1. REGISTER CONTROL GROUPS
	 * Sesuai dokumentasi, group harus didefinisikan di sini, bukan di set_controls.
	 */
	public function set_control_groups() {
		
		// Group 1: Source
		$this->control_groups['lottie_source'] = [
			'title' => esc_html__( 'Animation Source', 'structura-ui' ),
			'tab'   => 'content', // Ada di tab Content
		];

		// Group 2: Playback Settings
		$this->control_groups['lottie_settings'] = [
			'title' => esc_html__( 'Playback Settings', 'structura-ui' ),
			'tab'   => 'content',
		];

		// Group 3: Dimensions (Style Tab)
		$this->control_groups['lottie_dimensions'] = [
			'title' => esc_html__( 'Dimensions', 'structura-ui' ),
			'tab'   => 'style',
		];
	}

	/**
	 * 2. REGISTER CONTROLS
	 * Assign control ke group menggunakan key 'group'.
	 */
	public function set_controls() {

		/**
		 * GROUP: ANIMATION SOURCE
		 */

		// Source Type Selection
		$this->controls['source_type'] = [
			'tab'     => 'content',
			'group'   => 'lottie_source', // Referensi ke key di set_control_groups
			'label'   => esc_html__( 'Source Type', 'structura-ui' ),
			'type'    => 'select',
			'options' => [
				'url'  => esc_html__( 'Remote URL', 'structura-ui' ),
				'file' => esc_html__( 'Media Library (Link)', 'structura-ui' ),
			],
			'default' => 'url',
		];

		// Input: Remote URL
		$this->controls['url_source'] = [
			'tab'         => 'content',
			'group'       => 'lottie_source',
			'label'       => esc_html__( 'Lottie URL', 'structura-ui' ),
			'type'        => 'text',
			'default'     => 'https://assets9.lottiefiles.com/packages/lf20_jcikwtux.json',
			'placeholder' => 'https://...',
			'description' => esc_html__( 'Paste a direct link to a Lottie JSON file.', 'structura-ui' ),
			'required'    => [ 'source_type', '=', 'url' ],
		];

		// Input: File Link
		$this->controls['file_source'] = [
			'tab'         => 'content',
			'group'       => 'lottie_source',
			'label'       => esc_html__( 'JSON File URL', 'structura-ui' ),
			'type'        => 'text',
			'placeholder' => 'https://your-site.com/.../anim.json',
			'description' => esc_html__( 'Upload JSON to Media Library, copy URL, and paste here.', 'structura-ui' ),
			'required'    => [ 'source_type', '=', 'file' ],
		];

		/**
		 * GROUP: PLAYBACK SETTINGS
		 */

		$this->controls['trigger'] = [
			'tab'     => 'content',
			'group'   => 'lottie_settings',
			'label'   => esc_html__( 'Trigger', 'structura-ui' ),
			'type'    => 'select',
			'options' => [
				'autoplay' => esc_html__( 'Autoplay (Always)', 'structura-ui' ),
				'viewport' => esc_html__( 'Play when in Viewport', 'structura-ui' ),
				'hover'    => esc_html__( 'Play on Hover', 'structura-ui' ),
				'click'    => esc_html__( 'Play on Click', 'structura-ui' ),
			],
			'default' => 'viewport',
		];

		$this->controls['loop'] = [
			'tab'     => 'content',
			'group'   => 'lottie_settings',
			'label'   => esc_html__( 'Loop Animation', 'structura-ui' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['speed'] = [
			'tab'     => 'content',
			'group'   => 'lottie_settings',
			'label'   => esc_html__( 'Playback Speed', 'structura-ui' ),
			'type'    => 'number',
			'min'     => 0.1,
			'max'     => 5,
			'step'    => 0.1,
			'default' => 1,
		];

		/**
		 * TAB: STYLE
		 */

		$this->controls['width'] = [
			'tab'     => 'style',
			'group'   => 'lottie_dimensions',
			'label'   => esc_html__( 'Width', 'structura-ui' ),
			'type'    => 'number',
			'units'   => true,
			'default' => '300px',
			'css'     => [
				[
					'property' => 'width',
					'selector' => '.lottie-container',
				],
			],
		];

		$this->controls['height'] = [
			'tab'     => 'style',
			'group'   => 'lottie_dimensions',
			'label'   => esc_html__( 'Height', 'structura-ui' ),
			'type'    => 'number',
			'units'   => true,
			'css'     => [
				[
					'property' => 'height',
					'selector' => '.lottie-container',
				],
			],
		];

		$this->controls['opacity'] = [
			'tab'   => 'style',
			'group' => 'lottie_dimensions', // Optional: put in same group or default style tab
			'label' => esc_html__( 'Opacity', 'structura-ui' ),
			'type'  => 'number',
			'min'   => 0,
			'max'   => 1,
			'step'  => 0.1,
			'css'   => [
				[
					'property' => 'opacity',
					'selector' => '.lottie-container',
				],
			],
		];
	}

	/**
	 * Helper: Get Animation URL
	 *
	 * @param array $settings
	 * @return string
	 */
	private function get_animation_url( $settings ) {
		$source_type = isset( $settings['source_type'] ) ? $settings['source_type'] : 'url';

		if ( $source_type === 'file' ) {
			return isset( $settings['file_source'] ) ? $settings['file_source'] : '';
		}

		return isset( $settings['url_source'] ) ? $settings['url_source'] : '';
	}

	/**
	 * Render element HTML
	 */
	public function render() {
		$settings = $this->settings;
		$url      = $this->get_animation_url( $settings );

		if ( empty( $url ) ) {
			$url = 'https://assets9.lottiefiles.com/packages/lf20_jcikwtux.json';
		}

		// Prepare Vue Props
		$props = [
			'src'     => $url,
			'trigger' => isset( $settings['trigger'] ) ? $settings['trigger'] : 'viewport',
			'loop'    => isset( $settings['loop'] ) ? (bool) $settings['loop'] : true,
			'speed'   => isset( $settings['speed'] ) ? (float) $settings['speed'] : 1,
		];

		// Wrapper with Bricks attributes support
		// Using set_attribute to handle ID/Classes properly as per docs, 
		// but for Vue wrapper we also need our specific class.
		
		$this->set_attribute( '_root', 'class', 'structura-vue-lottie w-full h-auto relative flex justify-center items-center' );
		$this->set_attribute( '_root', 'data-settings', json_encode( $props ) );

		echo "<div {$this->render_attributes( '_root' )}></div>";
	}
}