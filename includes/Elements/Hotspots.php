<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Hotspots
 * Handles backend configuration for the Image Hotspots element.
 * Fixed: Icon picker initialization and PHP array warnings.
 */
class Hotspots extends Element {

	/**
	 * Element category
	 */
	public $category = 'structura';

	/**
	 * Element name
	 */
	public $name = 'structura-hotspots';

	/**
	 * Element icon
	 */
	public $icon = 'ti-target';

	/**
	 * Element CSS class
	 */
	public $css_selector = '.structura-hotspots-wrapper';

	/**
	 * Get element label
	 */
	public function get_label() {
		return esc_html__( 'Image Hotspots', 'structura-ui' );
	}

	/**
	 * Register element controls
	 */
	public function set_controls() {

		// 1. Content: Base Image
		$this->controls['image'] = [
			'tab'     => 'content',
			'label'   => esc_html__( 'Base Image', 'structura-ui' ),
			'type'    => 'image',
			'default' => [
				'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop',
			],
		];

		// 2. Content: Hotspots Repeater
		$this->controls['hotspots'] = [
			'tab'           => 'content',
			'label'         => esc_html__( 'Hotspots List', 'structura-ui' ),
			'type'          => 'repeater',
			'titleProperty' => 'title',
			'fields'        => [
				// --- Position ---
				'posX'        => [
					'label'   => esc_html__( 'Position X (%)', 'structura-ui' ),
					'type'    => 'number',
					'min'     => 0,
					'max'     => 100,
					'default' => 50,
				],
				'posY'        => [
					'label'   => esc_html__( 'Position Y (%)', 'structura-ui' ),
					'type'    => 'number',
					'min'     => 0,
					'max'     => 100,
					'default' => 50,
				],

				// --- Marker Type ---
				'markerType'  => [
					'label'   => esc_html__( 'Marker Type', 'structura-ui' ),
					'type'    => 'select',
					'options' => [
						'icon'  => esc_html__( 'Icon Library', 'structura-ui' ),
						'image' => esc_html__( 'Custom Image / SVG', 'structura-ui' ),
					],
					'default' => 'icon',
				],

				// --- Marker: Icon ---
				// FIXED: Default value must be an array for Bricks icon picker to work correctly
				'icon'        => [
					'label'    => esc_html__( 'Icon', 'structura-ui' ),
					'type'     => 'icon',
					'default'  => [
						'library' => 'themify',
						'icon'    => 'ti-plus',
					],
					'required' => [ 'markerType', '=', 'icon' ],
				],

				// --- Marker: Custom Image ---
				'customImage' => [
					'label'    => esc_html__( 'Custom Image', 'structura-ui' ),
					'type'     => 'image',
					'required' => [ 'markerType', '=', 'image' ],
				],

				// --- Tooltip Data ---
				'title'       => [
					'label'   => esc_html__( 'Title', 'structura-ui' ),
					'type'    => 'text',
					'default' => 'Hotspot Title',
				],
				'price'       => [
					'label'   => esc_html__( 'Price / Badge', 'structura-ui' ),
					'type'    => 'text',
					'default' => '',
				],
				'description' => [
					'label'   => esc_html__( 'Description', 'structura-ui' ),
					'type'    => 'textarea',
					'default' => 'Description here.',
				],
				'link'        => [
					'label' => esc_html__( 'Link', 'structura-ui' ),
					'type'  => 'link',
				],
			],
			// FIXED: Default data now uses array format for icons
			'default'       => [
				[
					'markerType'  => 'icon',
					'icon'        => [ 'library' => 'themify', 'icon' => 'ti-info-alt' ],
					'posX'        => 30,
					'posY'        => 40,
					'title'       => 'Smart Lamp',
					'price'       => '$120',
					'description' => 'Adjustable brightness.',
				],
				[
					'markerType'  => 'icon',
					'icon'        => [ 'library' => 'themify', 'icon' => 'ti-settings' ],
					'posX'        => 65,
					'posY'        => 55,
					'title'       => 'Ergonomic Chair',
					'price'       => '$350',
					'description' => 'Designed for comfort.',
				],
			],
		];

		// 3. Styles
		$this->controls['markerColor'] = [
			'tab'     => 'style',
			'label'   => esc_html__( 'Marker Background', 'structura-ui' ),
			'type'    => 'color',
			'default' => '#3b82f6',
		];

		$this->controls['iconColor'] = [
			'tab'     => 'style',
			'label'   => esc_html__( 'Icon Color', 'structura-ui' ),
			'type'    => 'color',
			'default' => '#ffffff',
		];
	}

	/**
	 * Helper: Safe Image URL Getter
	 *
	 * @param mixed $image_data
	 * @return string
	 */
	private function get_image_url( $image_data ) {
		if ( empty( $image_data ) ) {
			return '';
		}
		if ( is_array( $image_data ) && isset( $image_data['url'] ) ) {
			return $image_data['url'];
		}
		if ( is_numeric( $image_data ) ) {
			$src = wp_get_attachment_image_src( $image_data, 'full' );
			return $src ? $src[0] : '';
		}
		return is_string( $image_data ) ? $image_data : '';
	}

	/**
	 * Helper: Safe Link Getter
	 *
	 * @param mixed $link_data
	 * @return array|null
	 */
	private function get_link_data( $link_data ) {
		if ( empty( $link_data ) ) {
			return null;
		}
		if ( is_array( $link_data ) ) {
			return [
				'url'    => isset( $link_data['url'] ) ? $link_data['url'] : '',
				'target' => isset( $link_data['target'] ) ? $link_data['target'] : '_self',
			];
		}
		if ( is_string( $link_data ) ) {
			return [ 'url' => $link_data, 'target' => '_self' ];
		}
		return null;
	}

	/**
	 * Render element HTML
	 */
	public function render() {
		$settings = $this->settings;

		// 1. Base Image
		$image_url = $this->get_image_url( isset( $settings['image'] ) ? $settings['image'] : '' );
		if ( empty( $image_url ) ) {
			$image_url = 'https://via.placeholder.com/1200x800?text=Select+Image';
		}

		// 2. Process Hotspots
		$raw_hotspots       = isset( $settings['hotspots'] ) ? $settings['hotspots'] : [];
		$processed_hotspots = [];

		foreach ( $raw_hotspots as $item ) {
			// Marker Type
			$marker_type = isset( $item['markerType'] ) ? $item['markerType'] : 'icon';

			// Icon Processing (FIXED: Added strict checks to prevent Undefined Array Key warning)
			$icon_raw   = isset( $item['icon'] ) ? $item['icon'] : [];
			$icon_class = 'ti-plus'; // Fallback

			if ( is_array( $icon_raw ) ) {
				// Check if keys exist before access
				if ( isset( $icon_raw['library'], $icon_raw['icon'] ) ) {
					$icon_class = $icon_raw['library'] . ' ' . $icon_raw['icon'];
				}
			} elseif ( is_string( $icon_raw ) && ! empty( $icon_raw ) ) {
				// Handle legacy string format if any
				$icon_class = $icon_raw;
			}

			// Custom Image Processing
			$custom_image_url = '';
			if ( $marker_type === 'image' && ! empty( $item['customImage'] ) ) {
				$custom_image_url = $this->get_image_url( $item['customImage'] );
			}

			// Link & Text Processing
			$link        = $this->get_link_data( isset( $item['link'] ) ? $item['link'] : null );
			$title       = $this->render_dynamic_data( isset( $item['title'] ) ? $item['title'] : '' );
			$price       = $this->render_dynamic_data( isset( $item['price'] ) ? $item['price'] : '' );
			$description = $this->render_dynamic_data( isset( $item['description'] ) ? $item['description'] : '' );

			$processed_hotspots[] = [
				'x'           => isset( $item['posX'] ) ? (float) $item['posX'] : 50,
				'y'           => isset( $item['posY'] ) ? (float) $item['posY'] : 50,
				'type'        => $marker_type,
				'icon'        => $icon_class,
				'image'       => $custom_image_url,
				'title'       => $title,
				'price'       => $price,
				'description' => $description,
				'link'        => $link,
			];
		}

		// 3. Vue Props
		$props = [
			'imageUrl'    => $image_url,
			'hotspots'    => $processed_hotspots,
			'markerColor' => isset( $settings['markerColor'] ) ? $settings['markerColor'] : '#3b82f6',
			'iconColor'   => isset( $settings['iconColor'] ) ? $settings['iconColor'] : '#ffffff',
		];

		// 4. Output
		echo "<div class='structura-vue-hotspots w-full h-auto relative' data-settings='" . esc_attr( json_encode( $props ) ) . "'></div>";
	}
}