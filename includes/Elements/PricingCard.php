<?php

namespace StructuraUI\Elements;

use Bricks\Element;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PricingCard
 * * Renders a Pricing Card with built-in Monthly/Yearly toggle switcher.
 */
class PricingCard extends Element {

	public $category     = 'structura';
	public $name         = 'structura-pricing-card';
	public $icon         = 'ti-money'; 
	public $css_selector = '.structura-pricing-wrapper';
	
	// Script dependency
	public $scripts      = [ 'structura-ui-script' ];

	public function get_label() {
		return esc_html__( 'Pricing Card (Toggle)', 'structura-ui' );
	}

	/**
	 * 1. Register Control Groups
	 */
	public function set_control_groups() {
		// Content Tab
		$this->control_groups['pricing_header'] = [
			'title' => esc_html__( 'Header & Plan Name', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['pricing_monthly'] = [
			'title' => esc_html__( 'Monthly Data (Default)', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['pricing_yearly'] = [
			'title' => esc_html__( 'Yearly Data (Switch)', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['pricing_features'] = [
			'title' => esc_html__( 'Features List', 'structura-ui' ),
			'tab'   => 'content',
		];

		$this->control_groups['pricing_button'] = [
			'title' => esc_html__( 'Action Button', 'structura-ui' ),
			'tab'   => 'content',
		];

		// Style Tab
		$this->control_groups['style_card'] = [
			'title' => esc_html__( 'Card Layout', 'structura-ui' ),
			'tab'   => 'style',
		];
		
		$this->control_groups['style_toggle'] = [
			'title' => esc_html__( 'Toggle Switcher', 'structura-ui' ),
			'tab'   => 'style',
		];

		$this->control_groups['style_price'] = [
			'title' => esc_html__( 'Price Typography', 'structura-ui' ),
			'tab'   => 'style',
		];
	}

	/**
	 * 2. Register Controls
	 */
	public function set_controls() {

		/**
		 * GROUP: HEADER
		 */
		$this->controls['plan_name'] = [
			'tab'     => 'content',
			'group'   => 'pricing_header',
			'label'   => esc_html__( 'Plan Name', 'structura-ui' ),
			'type'    => 'text',
			'default' => 'Professional',
		];

		$this->controls['plan_desc'] = [
			'tab'     => 'content',
			'group'   => 'pricing_header',
			'label'   => esc_html__( 'Description', 'structura-ui' ),
			'type'    => 'textarea',
			'default' => 'Best for growing businesses.',
		];

		$this->controls['icon'] = [
			'tab'     => 'content',
			'group'   => 'pricing_header',
			'label'   => esc_html__( 'Icon', 'structura-ui' ),
			'type'    => 'icon',
			'default' => [
				'library' => 'themify',
				'icon'    => 'ti-rocket',
			],
		];

		/**
		 * GROUP: MONTHLY DATA
		 */
		$this->controls['price_monthly'] = [
			'tab'     => 'content',
			'group'   => 'pricing_monthly',
			'label'   => esc_html__( 'Monthly Price', 'structura-ui' ),
			'type'    => 'text',
			'default' => '49',
		];

		$this->controls['period_monthly'] = [
			'tab'     => 'content',
			'group'   => 'pricing_monthly',
			'label'   => esc_html__( 'Period Label', 'structura-ui' ),
			'type'    => 'text',
			'default' => '/ month',
		];

		/**
		 * GROUP: YEARLY DATA
		 */
		$this->controls['price_yearly'] = [
			'tab'     => 'content',
			'group'   => 'pricing_yearly',
			'label'   => esc_html__( 'Yearly Price', 'structura-ui' ),
			'type'    => 'text',
			'default' => '490',
		];

		$this->controls['period_yearly'] = [
			'tab'     => 'content',
			'group'   => 'pricing_yearly',
			'label'   => esc_html__( 'Period Label', 'structura-ui' ),
			'type'    => 'text',
			'default' => '/ year',
		];

		$this->controls['discount_badge'] = [
			'tab'     => 'content',
			'group'   => 'pricing_yearly',
			'label'   => esc_html__( 'Discount Text', 'structura-ui' ),
			'type'    => 'text',
			'default' => 'Save 20%',
			'description' => esc_html__( 'Leave empty to hide badge.', 'structura-ui' ),
		];

		/**
		 * GROUP: FEATURES
		 */
		$this->controls['features'] = [
			'tab'           => 'content',
			'group'         => 'pricing_features',
			'label'         => esc_html__( 'Features', 'structura-ui' ),
			'type'          => 'repeater',
			'titleProperty' => 'text',
			'fields'        => [
				'text' => [
					'label'   => esc_html__( 'Feature Text', 'structura-ui' ),
					'type'    => 'text',
					'default' => 'Feature item',
				],
				'available' => [
					'label'   => esc_html__( 'Included?', 'structura-ui' ),
					'type'    => 'checkbox',
					'default' => true,
				],
			],
			'default'       => [
				[ 'text' => '5 Projects', 'available' => true ],
				[ 'text' => 'Unlimited Users', 'available' => true ],
				[ 'text' => 'Priority Support', 'available' => false ],
			],
		];

		/**
		 * GROUP: BUTTON
		 */
		$this->controls['button_text'] = [
			'tab'     => 'content',
			'group'   => 'pricing_button',
			'label'   => esc_html__( 'Button Text', 'structura-ui' ),
			'type'    => 'text',
			'default' => 'Get Started',
		];

		$this->controls['link'] = [
			'tab'   => 'content',
			'group' => 'pricing_button',
			'label' => esc_html__( 'Link', 'structura-ui' ),
			'type'  => 'link',
		];

		/**
		 * -------------------------------------------------------------------
		 * TAB: STYLE
		 * -------------------------------------------------------------------
		 */

		// CARD STYLE
		$this->controls['card_bg'] = [
			'tab'   => 'style',
			'group' => 'style_card',
			'label' => esc_html__( 'Card Background', 'structura-ui' ),
			'type'  => 'background',
			'css'   => [
				[
					'property' => 'background',
					'selector' => '.pricing-card',
				],
			],
		];

		$this->controls['card_padding'] = [
			'tab'   => 'style',
			'group' => 'style_card',
			'label' => esc_html__( 'Padding', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'padding',
					'selector' => '.pricing-card',
				],
			],
		];
		
		$this->controls['card_border'] = [
			'tab'   => 'style',
			'group' => 'style_card',
			'label' => esc_html__( 'Border', 'structura-ui' ),
			'type'  => 'border',
			'css'   => [
				[
					'property' => 'border',
					'selector' => '.pricing-card',
				],
			],
		];

		$this->controls['card_radius'] = [
			'tab'   => 'style',
			'group' => 'style_card',
			'label' => esc_html__( 'Border Radius', 'structura-ui' ),
			'type'  => 'dimensions',
			'css'   => [
				[
					'property' => 'border-radius',
					'selector' => '.pricing-card',
				],
			],
		];

		// TOGGLE STYLE
		$this->controls['toggle_color'] = [
			'tab'   => 'style',
			'group' => 'style_toggle',
			'label' => esc_html__( 'Active Color', 'structura-ui' ),
			'type'  => 'color',
			'css'   => [
				[
					'property' => 'background-color',
					'selector' => '.toggle-active-bg',
				],
			],
		];
		
		// PRICE TYPOGRAPHY
		$this->controls['price_typo'] = [
			'tab'   => 'style',
			'group' => 'style_price',
			'label' => esc_html__( 'Price Typography', 'structura-ui' ),
			'type'  => 'typography',
			'css'   => [
				[
					'property' => 'typography',
					'selector' => '.pricing-amount',
				],
			],
		];
	}

	public function render() {
		$settings = $this->settings;

		// 1. Prepare Data
		$props = [
			'monthly' => [
				'price'  => isset( $settings['price_monthly'] ) ? $settings['price_monthly'] : '49',
				'period' => isset( $settings['period_monthly'] ) ? $settings['period_monthly'] : '/mo',
			],
			'yearly' => [
				'price'    => isset( $settings['price_yearly'] ) ? $settings['price_yearly'] : '490',
				'period'   => isset( $settings['period_yearly'] ) ? $settings['period_yearly'] : '/yr',
				'discount' => isset( $settings['discount_badge'] ) ? $settings['discount_badge'] : '',
			],
			'isYearlyDefault' => false, // Could be a control if needed
		];

		// Static Data (for SEO & Layout)
		$plan_name = isset( $settings['plan_name'] ) ? $settings['plan_name'] : 'Plan Name';
		$plan_desc = isset( $settings['plan_desc'] ) ? $settings['plan_desc'] : '';
		
		// Icon
		$icon_html = '';
		if ( isset( $settings['icon'] ) && is_array( $settings['icon'] ) ) {
			$icon_html = '<i class="' . esc_attr( $settings['icon']['library'] . ' ' . $settings['icon']['icon'] ) . ' text-3xl mb-4 text-blue-500"></i>';
		}

		// Button
		$btn_text = isset( $settings['button_text'] ) ? $settings['button_text'] : 'Get Started';
		$btn_link = isset( $settings['link'] ) && isset( $settings['link']['url'] ) ? $settings['link']['url'] : '#';

		// Features
		$features = isset( $settings['features'] ) ? $settings['features'] : [];

		// Render Wrapper
		$this->set_attribute( '_root', 'class', 'structura-vue-pricing w-full' );
		$this->set_attribute( '_root', 'data-settings', json_encode( $props ) );

		echo "<div {$this->render_attributes( '_root' )}>";
			// VUE COMPONENT MOUNTS HERE (REPLACING INNER HTML) 
			// But for SEO, we should render semantic HTML inside, and let Vue "hydrate" or take over the price part.
			// Simplest strategy for Bricks Vue: Render everything in Vue Template to ensure reactivity sync.
			// Pass static content via Props as well? 
			// BETTER: Pass static content via Slots? No, main.js setup is JSON props based.
			
			// Let's pass EVERYTHING via JSON props for this component 
			// because the state (Month/Year) affects Price AND Period AND Badge.
			// It's cleaner if Vue handles the whole card rendering.
		echo "</div>";
		
		// Wait, if I pass everything to Vue, the 'Static' controls like Icon/Features won't benefit from SEO 
		// if Vue renders them client-side.
		// HYBRID STRATEGY: 
		// Render the Card in PHP (Static), but wrapping the Price Section in a specific DIV.
		// Mount Vue ONLY on the Price Section + Toggle.
		
		// RE-THINK RENDER FOR HYBRID:
		// 1. Header (PHP)
		// 2. Toggle & Price (Vue Target)
		// 3. Features (PHP)
		// 4. Button (PHP)
		
		// This is much better for SEO and performance!
		
		/* --- REVISED RENDER --- */
		
		// 1. Card Container
		echo '<div class="pricing-card bg-white rounded-xl shadow-lg border border-gray-100 p-8 flex flex-col h-full relative overflow-hidden transition-all hover:shadow-xl">';
			
			// Header
			echo '<div class="pricing-header mb-6 text-center">';
				if($icon_html) echo $icon_html;
				echo '<h3 class="text-xl font-bold text-gray-900">' . esc_html( $plan_name ) . '</h3>';
				if($plan_desc) echo '<p class="text-sm text-gray-500 mt-2">' . esc_html( $plan_desc ) . '</p>';
			echo '</div>';

			// --- VUE TARGET AREA (Toggle + Price) ---
			// We pass the settings JSON here specifically
			echo '<div class="pricing-reactive-area mb-8" data-settings="' . esc_attr( json_encode( $props ) ) . '"></div>';
			// ----------------------------------------

			// Features
			if ( ! empty( $features ) ) {
				echo '<ul class="pricing-features space-y-3 mb-8 flex-grow">';
				foreach ( $features as $feature ) {
					$txt = isset( $feature['text'] ) ? $feature['text'] : '';
					$avail = isset( $feature['available'] ) ? $feature['available'] : true;
					$opacity = $avail ? 'opacity-100' : 'opacity-50 line-through';
					$icon = $avail ? 'ti-check text-green-500' : 'ti-close text-gray-400';
					
					echo '<li class="flex items-center text-sm text-gray-600 ' . $opacity . '">';
						echo '<i class="' . $icon . ' mr-3 text-xs"></i>';
						echo '<span>' . esc_html( $txt ) . '</span>';
					echo '</li>';
				}
				echo '</ul>';
			}

			// Footer Button
			echo '<div class="pricing-footer mt-auto">';
				echo '<a href="' . esc_url( $btn_link ) . '" class="block w-full py-3 px-6 text-center rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition-colors duration-300">';
					echo esc_html( $btn_text );
				echo '</a>';
			echo '</div>';

		echo '</div>'; // End Card
	}
}