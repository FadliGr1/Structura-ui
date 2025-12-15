<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TiltImage
 * * Renders an image with interactive 3D Tilt effect using vanilla-tilt.js.
 */
class TiltImage extends Element {

	public $category     = 'structura';
	public $name         = 'structura-tilt-image';
	public $icon         = 'ti-layers-alt'; 
	public $css_selector = '.structura-tilt-wrapper';
	
	// Script dependency
	public $scripts      = [ 'structura-ui-script' ];

	public function get_label() {
		return esc_html__( '3D Tilt Image', 'structura-ui' );
	}

	/**
	 * 1. Register Control Groups
	 */
	public function set_control_groups() {
		$this->control_groups['tilt_content'] = [
			'title' => esc_html__( 'Image Content', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['tilt_settings'] = [
			'title' => esc_html__( 'Tilt Physics', 'structura-ui' ),
			'tab'   => 'content',
		];
		
		$this->control_groups['tilt_effects'] = [
			'title' => esc_html__( 'Visual Effects', 'structura-ui' ),
			'tab'   => 'content',
		];

		// Style Tab
		$this->control_groups['style_image'] = [
			'title' => esc_html__( 'Image Style', 'structura-ui' ),
			'tab'   => 'style',
		];
		
		$this->control_groups['style_caption'] = [
			'title' => esc_html__( 'Caption Style', 'structura-ui' ),
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
		$this->controls['image'] = [
			'tab'     => 'content',
			'group'   => 'tilt_content',
			'label'   => esc_html__( 'Image', 'structura-ui' ),
			'type'    => 'image',
			'default' => [
				'url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=1000&auto=format&fit=crop',
			],
		];

		$this->controls['caption'] = [
			'tab'         => 'content',
			'group'       => 'tilt_content',
			'label'       => esc_html__( 'Overlay Caption', 'structura-ui' ),
			'type'        => 'text',
			'placeholder' => 'Enter text...',
			'default'     => '',
		];

		/**
		 * GROUP: PHYSICS
		 */
		$this->controls['max_tilt'] = [
			'tab'         => 'content',
			'group'       => 'tilt_settings',
			'label'       => esc_html__( 'Max Tilt (deg)', 'structura-ui' ),
			'type'        => 'number',
			'default'     => 15,
			'description' => esc_html__( 'Maximum tilt rotation in degrees.', 'structura-ui' ),
		];

		$this->controls['perspective'] = [
			'tab'         => 'content',
			'group'       => 'tilt_settings',
			'label'       => esc_html__( 'Perspective (px)', 'structura-ui' ),
			'type'        => 'number',
			'default'     => 1000,
			'description' => esc_html__( 'Lower = more extreme tilt.', 'structura-ui' ),
		];

		$this->controls['speed'] = [
			'tab'     => 'content',
			'group'   => 'tilt_settings',
			'label'   => esc_html__( 'Speed (ms)', 'structura-ui' ),
			'type'    => 'number',
			'default' => 400,
		];

		/**
		 * GROUP: EFFECTS
		 */
		$this->controls['scale'] = [
			'tab'         => 'content',
			'group'       => 'tilt_effects',
			'label'       => esc_html__( 'Scale on Hover', 'structura-ui' ),
			'type'        => 'number',
			'step'        => 0.05,
			'default'     => 1.05,
			'description' => esc_html__( '1 = No scale. 1.1 = 110% size.', 'structura-ui' ),
		];

		$this->controls['glare'] = [
			'tab'     => 'content',
			'group'   => 'tilt_effects',
			'label'   => esc_html__( 'Enable Glare', 'structura-ui' ),
			'type'    => 'checkbox',
			'default' => true,
		];

		$this->controls['max_glare'] = [
			'tab'      => 'content',
			'group'    => 'tilt_effects',
			'label'    => esc_html__( 'Max Glare Opacity', 'structura-ui' ),
			'type'     => 'number',
			'min'      => 0,
			'max'      => 1,
			'step'     => 0.1,
			'default'  => 0.5,
			'required' => [ 'glare', '=', true ],
		];

		$this->controls['reverse'] = [
			'tab'     => 'content',
			'group'   => 'tilt_effects',
			'label'   => esc_html__( 'Reverse Direction', 'structura-ui' ),
			'type'    => 'checkbox',
			'default' => false,
		];

		/**
		 * -------------------------------------------------------------------
		 * TAB: STYLE
		 * -------------------------------------------------------------------
		 */

		// Dimensions & Border
		$this->controls['width'] = [
			'tab'   => 'style',
			'group' => 'style_image',
			'label' => esc_html__( 'Width', 'structura-ui' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'width',
					'selector' => '.tilt-inner',
				],
			],
		];

		$this->controls['height'] = [
			'tab'   => 'style',
			'group' => 'style_image',
			'label' => esc_html__( 'Height', 'structura-ui' ),
			'type'  => 'number',
			'units' => true,
			'css'   => [
				[
					'property' => 'height',
					'selector' => '.tilt-inner',
				],
			],
		];

		$this->controls['radius'] = [
			'tab'   => 'style',
			'group' => 'style_image',
			'label' => esc_html__( 'Border Radius', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'border-radius',
					'selector' => '.tilt-inner',
				],
			],
		];

		$this->controls['shadow'] = [
			'tab'   => 'style',
			'group' => 'style_image',
			'label' => esc_html__( 'Box Shadow', 'structura-ui' ),
			'type'  => 'box-shadow',
			'css'   => [
				[
					'property' => 'box-shadow',
					'selector' => '.tilt-inner',
				],
			],
		];

		// Caption Styling
		$this->controls['caption_typography'] = [
			'tab'   => 'style',
			'group' => 'style_caption',
			'label' => esc_html__( 'Typography', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.tilt-caption',
				],
			],
		];

		$this->controls['caption_bg'] = [
			'tab'   => 'style',
			'group' => 'style_caption',
			'label' => esc_html__( 'Background', 'structura-ui' ),
			'type'  => 'background',
			'css'   => [
				[
					'property' => 'background',
					'selector' => '.tilt-caption',
				],
			],
		];

		$this->controls['caption_padding'] = [
			'tab'   => 'style',
			'group' => 'style_caption',
			'label' => esc_html__( 'Padding', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.tilt-caption',
				],
			],
		];
	}

	// Helper Image URL
	private function get_image_url( $image_data ) {
		if ( empty( $image_data ) ) return '';
		if ( is_array( $image_data ) && isset( $image_data['url'] ) ) return $image_data['url'];
		if ( is_numeric( $image_data ) ) {
			$src = wp_get_attachment_image_src( $image_data, 'full' );
			return $src ? $src[0] : '';
		}
		return is_string( $image_data ) ? $image_data : '';
	}

	public function render() {
		$settings = $this->settings;

		$image_url = $this->get_image_url( isset( $settings['image'] ) ? $settings['image'] : '' );
		if ( empty( $image_url ) ) {
			$image_url = 'https://via.placeholder.com/600x800?text=Tilt+Image';
		}

		$caption = isset( $settings['caption'] ) ? $settings['caption'] : '';

		// Props for JS
		$props = [
			'max'         => isset( $settings['max_tilt'] ) ? (int) $settings['max_tilt'] : 15,
			'perspective' => isset( $settings['perspective'] ) ? (int) $settings['perspective'] : 1000,
			'speed'       => isset( $settings['speed'] ) ? (int) $settings['speed'] : 400,
			'scale'       => isset( $settings['scale'] ) ? (float) $settings['scale'] : 1.05,
			'glare'       => isset( $settings['glare'] ) ? (bool) $settings['glare'] : true,
			'maxGlare'    => isset( $settings['max_glare'] ) ? (float) $settings['max_glare'] : 0.5,
			'reverse'     => isset( $settings['reverse'] ) ? (bool) $settings['reverse'] : false,
		];

		// Wrapper
		$this->set_attribute( '_root', 'class', 'structura-vue-tilt w-full h-auto flex justify-center' );
		$this->set_attribute( '_root', 'data-settings', json_encode( $props ) );

		echo "<div {$this->render_attributes( '_root' )}>";
		
			// Inner Element (This is what actually tilts)
			echo '<div class="tilt-inner relative overflow-hidden transform-gpu bg-black rounded-xl">';
				
				// Image
				echo '<img src="' . esc_url( $image_url ) . '" class="w-full h-auto object-cover block" alt="Tilt Image">';

				// Caption
				if ( $caption ) {
					echo '<div class="tilt-caption absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent text-white transform translate-y-full transition-transform duration-300 group-hover:translate-y-0">';
						echo '<span class="font-bold text-lg">' . esc_html( $caption ) . '</span>';
					echo '</div>';
				}

			echo '</div>';

		echo "</div>";
	}
}