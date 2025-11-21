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

  // Editor styles (optional; keep lean)
  add_editor_style( array( 'style.css' ) );
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
