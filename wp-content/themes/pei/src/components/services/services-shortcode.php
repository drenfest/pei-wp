<?php
/**
 * Services component shortcode
 *
 * Usage:
 * [pei_services
 *    heading="Services"
 *    images="roof.jpg,siding.jpg,windows.jpg,decks.jpg,gutters.jpg"
 *    titles="ROOFING,SIDING,WINDOWS,DECKS,GUTTERS"
 * ]
 */

function pei_render_services_shortcode( $atts ) {

    $atts = shortcode_atts(
        array(
            'heading' => 'Services',
            'images'  => '',
            'titles'  => '',
        ),
        $atts,
        'pei_services'
    );

    // Build arrays from comma-separated lists
    $images = $atts['images'] !== ''
        ? array_map( 'trim', explode( ',', $atts['images'] ) )
        : array();

    $titles = $atts['titles'] !== ''
        ? array_map( 'trim', explode( ',', $atts['titles'] ) )
        : array();

    // Make these variables available in the template scope
    $heading = $atts['heading'];

    $component_dir = get_template_directory()     . '/src/components/services/';
    $component_url = get_template_directory_uri() . '/src/components/services/';

    // Enqueue CSS
    if ( file_exists( $component_dir . 'services.css' ) ) {
        wp_enqueue_style(
            'pei-services',
            $component_url . 'services.css',
            array( 'pei-style' ),
            defined( 'PEI_VERSION' ) ? PEI_VERSION : null
        );
    }

    // Optional JS (if you add it later)
    if ( file_exists( $component_dir . 'services.js' ) ) {
        wp_enqueue_script(
            'pei-services',
            $component_url . 'services.js',
            array(),
            defined( 'PEI_VERSION' ) ? PEI_VERSION : null,
            true
        );
    }

    ob_start();

    if ( file_exists( $component_dir . 'services.php' ) ) {
        include $component_dir . 'services.php';
    }

    return ob_get_clean();
}

add_shortcode( 'pei_services', 'pei_render_services_shortcode' );
