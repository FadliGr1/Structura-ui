<?php
/**
 * Plugin Name: Structura UI
 * Description: Advanced Bricks Components.
 * Plugin URI:  https://orbitcore.id/wordpress/plugin/structura-ui
 * Version:     1.0.0
 * Author:      Orbit Core
 * Text Domain: structura-ui
 */

namespace StructuraUI;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Plugin Class
 * Implements Singleton Pattern to ensure only one instance exists.
 */
final class StructuraUI {

    /**
     * Plugin Constants
     */
    const VERSION = '1.0.0';

    /**
     * Singleton Instance
     */
    private static $instance = null;

    /**
     * Get Singleton Instance
     * Ensures we don't create multiple copies of the plugin class.
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     * Initialize hooks and load dependencies.
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load Vendor Dependencies
     * Loads Composer autoloader if present.
     */
    private function load_dependencies() {
        if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
            require_once __DIR__ . '/vendor/autoload.php';
        }
    }

    /**
     * Initialize WordPress Hooks
     * Registers all actions and filters used by the plugin.
     */
    private function init_hooks() {
        // 1. Register Custom Category in Bricks
        add_filter( 'bricks/elements/categories', [ $this, 'register_category' ] );

        // 2. Register Elements
        add_action( 'init', [ $this, 'register_elements' ], 11 );
        
        // 3. Enqueue Assets (CSS/JS)
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // 4. Fix Vite Module Loading (Add type="module")
        add_filter( 'script_loader_tag', [ $this, 'add_type_attribute' ], 10, 3 );
    }

    /**
     * Register Custom Bricks Category
     * Creates a dedicated "Structura UI" section in the builder panel.
     * * @param array $categories Existing categories.
     * @return array Modified categories.
     */
    public function register_category( $categories ) {
        $categories[] = [
            'id'    => 'structura',    // ID must match the $category property in Element classes
            'title' => 'Structura UI', // The label visible in the panel
            'icon'  => 'ti-package',   // Icon for the category header
        ];
        return $categories;
    }

    /**
     * Register Bricks Elements
     * Scans and registers all available element files.
     */
    public function register_elements() {
        // Ensure Bricks is active before registering
        if ( ! class_exists( '\Bricks\Elements' ) ) return;

        $elements = [
            'structura-chart' => [
                'file'  => __DIR__ . '/includes/Elements/Chart.php',
                'class' => 'StructuraUI\Elements\Chart',
            ],

            'structura-before-after' => [
                'file'  => __DIR__ . '/includes/Elements/BeforeAfter.php',
                'class' => 'StructuraUI\Elements\BeforeAfter',
            ],

            'structura-hotspots' => [
                'file'  => __DIR__ . '/includes/Elements/Hotspots.php',
                'class' => 'StructuraUI\Elements\Hotspots',
            ],

            // Future elements can be added here easily
        ];

        foreach ( $elements as $name => $data ) {
            if ( file_exists( $data['file'] ) ) {
                \Bricks\Elements::register_element( $data['file'], $name, $data['class'] );
            }
        }
    }

    /**
     * Enqueue Frontend Assets
     * Loads the compiled CSS and JS files for the frontend.
     */
    public function enqueue_assets() {
        $plugin_url  = plugin_dir_url( __FILE__ );
        $plugin_path = plugin_dir_path( __FILE__ );

        $css_file = 'assets/css/style.css';
        $js_file  = 'assets/js/main.js';

        // Enqueue CSS
        if ( file_exists( $plugin_path . $css_file ) ) {
            wp_enqueue_style( 
                'structura-ui-style', 
                $plugin_url . $css_file, 
                [], 
                self::VERSION 
            );
        }

        // Enqueue JS
        if ( file_exists( $plugin_path . $js_file ) ) {
            wp_enqueue_script( 
                'structura-ui-script', 
                $plugin_url . $js_file, 
                [], 
                self::VERSION, 
                true 
            );

            // Pass PHP data to JS (Configuration Object)
            wp_localize_script( 'structura-ui-script', 'StructuraConfig', [
                'isBuilder' => ( function_exists( 'bricks_is_builder_main' ) && bricks_is_builder_main() ),
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'structura_nonce' ),
            ]);
        }
    }

    /**
     * Add 'type="module"' to Script Tags
     * Required for Vite/Vue 3 modern builds.
     * * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @param string $src The script source URL.
     * @return string Modified script tag.
     */
    public function add_type_attribute( $tag, $handle, $src ) {
        if ( 'structura-ui-script' !== $handle ) return $tag;
        
        return '<script type="module" src="' . esc_url( $src ) . '"></script>';
    }
}

// Initialize Plugin
StructuraUI::get_instance();