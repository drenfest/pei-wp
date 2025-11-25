<?php
/**
 * Theme functions for PEI Construction
 *
 * @package pei
 */

if ( ! defined( 'PEI_VERSION' ) ) {
  define( 'PEI_VERSION', '1.1.0' );
}

/**
 * Theme setup
 */
function pei_setup() {
  // Let WordPress handle the <title> tag.
  add_theme_support( 'title-tag' );

  // Custom Logo from Customizer.
  add_theme_support( 'custom-logo', array(
    'height'      => 100,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
  ) );

  // HTML5 markup support.
  add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

  // Menus.
  register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'pei' ),
    'footer'  => __( 'Footer Menu',  'pei' ),
  ) );

  // Editor styles: load the main theme stylesheet inside the block editor
  // so blocks look the same as on the front end. Keep to a single sheet
  // per user preference.
  add_theme_support( 'editor-styles' );
  add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'pei_setup' );

/**
 * Enqueue front-end assets
 */
function pei_enqueue_assets() {
  // Theme CSS only (lean)
  wp_enqueue_style( 'pei-style', get_stylesheet_uri(), array(), PEI_VERSION );

  // Minimal JS for hamburger toggle
  $nav_js = get_stylesheet_directory_uri() . '/assets/js/navigation.js';
  wp_enqueue_script( 'pei-navigation', $nav_js, array(), PEI_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'pei_enqueue_assets' );

/**
 * Register additional menus (Footer Useful Links)
 * Priority 5 to ensure these are available early.
 */
function pei_lean_register_menus() {
  register_nav_menus( array(
    'primary'      => __( 'Primary Menu', 'pei' ),
    'footer_links' => __( 'Footer Useful Links', 'pei' ),
  ) );
}
add_action( 'after_setup_theme', 'pei_lean_register_menus', 5 );

/**
 * Customizer: Footer settings (contact info, address, hours, copyright)
 */
function pei_lean_customize_register( $wp_customize ) {

  $wp_customize->add_section( 'pei_footer_section', array(
    'title'       => __( 'Footer Settings', 'pei' ),
    'priority'    => 160,
    'description' => __( 'Configure the footer contact info and hours.', 'pei' ),
  ) );

  // Phone
  $wp_customize->add_setting( 'pei_footer_phone', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_phone', array(
    'label'   => __( 'Phone Number', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // Email
  $wp_customize->add_setting( 'pei_footer_email', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_email',
  ) );
  $wp_customize->add_control( 'pei_footer_email', array(
    'label'   => __( 'Email Address', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'email',
  ) );

  // Street address
  $wp_customize->add_setting( 'pei_footer_street', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_street', array(
    'label'   => __( 'Street Address', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // City
  $wp_customize->add_setting( 'pei_footer_city', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_city', array(
    'label'   => __( 'City', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // State / Region
  $wp_customize->add_setting( 'pei_footer_state', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_state', array(
    'label'   => __( 'State / Region', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // Postal code
  $wp_customize->add_setting( 'pei_footer_postcode', array(
    'default'           => '',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_postcode', array(
    'label'   => __( 'Postal Code', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // Weekday hours
  $wp_customize->add_setting( 'pei_footer_hours_weekday', array(
    'default'           => 'M–F: 8am – 4pm',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_hours_weekday', array(
    'label'   => __( 'Weekday Hours (display)', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // Weekend hours
  $wp_customize->add_setting( 'pei_footer_hours_weekend', array(
    'default'           => 'S–S: By Appt',
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_hours_weekend', array(
    'label'   => __( 'Weekend Hours (display)', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );

  // Copyright extra text
  $wp_customize->add_setting( 'pei_footer_copyright', array(
    'default'           => __( 'All Rights Reserved', 'pei' ),
    'sanitize_callback' => 'sanitize_text_field',
  ) );
  $wp_customize->add_control( 'pei_footer_copyright', array(
    'label'   => __( 'Copyright Text (after year & name)', 'pei' ),
    'section' => 'pei_footer_section',
    'type'    => 'text',
  ) );
}
add_action( 'customize_register', 'pei_lean_customize_register' );
/**
 * Auto-load all component shortcode files:
 * /src/components/{component}/*-shortcode.php
 */
function pei_load_component_shortcodes() {
    $components_dir = get_template_directory() . '/src/components';

    if ( ! is_dir( $components_dir ) ) {
        return;
    }

    // Find any file that ends with "-shortcode.php" one level deep
    $pattern = $components_dir . '/*/*-shortcode.php';
    foreach ( glob( $pattern ) as $shortcode_file ) {
        require_once $shortcode_file;
    }
}
add_action( 'init', 'pei_load_component_shortcodes' );

/**
 * Register a custom block category for PEI components in Gutenberg
 */
function pei_register_block_category( $categories, $post ) {
  $exists = wp_list_pluck( $categories, 'slug' );
  if ( ! in_array( 'pei', $exists, true ) ) {
    $categories[] = array(
      'slug'  => 'pei',
      'title' => __( 'PEI Components', 'pei' ),
      'icon'  => null,
    );
  }
  return $categories;
}
add_filter( 'block_categories_all', 'pei_register_block_category', 10, 2 );

/**
 * Auto-register dynamic Gutenberg blocks for each component that contains a block.json
 * Directory convention: /src/components/{component}/block.json
 *
 * Each component should also have a PHP template file used for rendering on the server,
 * typically the existing {component}.php template already used by shortcodes.
 */
function pei_register_component_blocks() {
  $components_dir = get_template_directory() . '/src/components';

  if ( ! is_dir( $components_dir ) ) {
    return;
  }

  foreach ( glob( $components_dir . '/*', GLOB_ONLYDIR ) as $component_path ) {
    $component      = basename( $component_path );
    $block_json     = $component_path . '/block.json';
    $template_php   = $component_path . '/' . $component . '.php';
    $component_uri  = get_template_directory_uri() . '/src/components/' . $component . '/';
    $style_path     = $component_path . '/' . $component . '.css';
    $script_path    = $component_path . '/' . $component . '.js';
    $editor_script  = $component_path . '/' . $component . '-editor.js';

    if ( ! file_exists( $block_json ) ) {
      continue;
    }

    // Register frontend/editor styles (if present)
    $style_handle = null;
    if ( file_exists( $style_path ) ) {
      $style_handle = 'pei-' . $component;
      // Register component styles without depending on the front-end theme handle
      // so they load reliably in the editor iframe as well.
      wp_register_style( $style_handle, $component_uri . $component . '.css', array(), PEI_VERSION );
    }

    // Register frontend view script (if present)
    $script_handle = null;
    if ( file_exists( $script_path ) ) {
      $script_handle = 'pei-' . $component;
      wp_register_script( $script_handle, $component_uri . $component . '.js', array(), PEI_VERSION, true );
    }

    // Build register args. We'll load settings from block.json then augment them.
    $args = array();
    $args['render_callback'] = function( $attributes = array(), $content = '' ) use ( $template_php, $component ) {
      // Expose attributes as variables for the included template, similar to shortcode behavior
      if ( is_array( $attributes ) ) {
        // Prevent overriding existing variables accidentally
        foreach ( $attributes as $key => $value ) {
          // Create variables like $title, $subtitle, etc.
          if ( preg_match( '/^[A-Za-z_][A-Za-z0-9_]*$/', $key ) ) {
            ${$key} = $value; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
          }
        }
      }

      // Allow using $content in templates if needed
      $inner_content = $content;

      ob_start();
      if ( file_exists( $template_php ) ) {
        include $template_php;
      }
      return ob_get_clean();
    };

    if ( $style_handle ) {
      $args['style']        = $style_handle;     // front-end
      $args['editor_style'] = $style_handle;     // editor preview
    }
    if ( $script_handle ) {
      $args['view_script'] = $script_handle;     // front-end behavior
    }

    // Register and attach editor script if present
    if ( file_exists( $editor_script ) ) {
      $editor_handle = 'pei-' . $component . '-editor';
      wp_register_script( $editor_handle, $component_uri . $component . '-editor.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-server-side-render' ), PEI_VERSION, true );
      $args['editor_script'] = $editor_handle;
    }

    register_block_type( $block_json, $args );
  }
}
add_action( 'init', 'pei_register_component_blocks' );
