<?php
/**
 * Hero shortcode registration and rendering
 * Path: /wp-content/themes/pei/src/components/hero/hero-shortcode.php
 */

/**
 * Shortcode: [pei_hero]
 * Attributes:
 *  - title         (string) – required-ish, outputs H1
 *  - subtitle      (string) – optional, paragraph under title
 *  - text          (string) – optional, paragraph under subtitle
 *  - link_location (string) – optional, URL for CTA
 *  - link_text     (string) – optional, CTA label (and title attr)
 */
function pei_render_hero_shortcode( $atts = array(), $content = null ) {

    $atts = shortcode_atts(
        array(
            'title'         => '',
            'subtitle'      => '',
            'text'          => '',
            'link_location' => '',
            'link_text'     => '',
        ),
        $atts,
        'pei_hero'
    );

    // Map attributes to variables used in hero.php
    $title         = $atts['title'];
    $subtitle      = $atts['subtitle'];
    $text          = $atts['text'];
    $link_location = $atts['link_location'];
    $link_text     = $atts['link_text'];

    // Allow enclosed content as a fallback for text, e.g. [pei_hero title="..."]Your text[/pei_hero]
    if ( empty( $text ) && ! is_null( $content ) ) {
        // Keep as plain text; hero.php escapes $text with esc_html()
        $text = trim( wp_strip_all_tags( $content ) );
    }

    $component_dir = get_template_directory()     . '/src/components/hero/';
    $component_url = get_template_directory_uri() . '/src/components/hero/';

    // Enqueue CSS if present
    if ( file_exists( $component_dir . 'hero.css' ) ) {
        wp_enqueue_style(
            'pei-hero',
            $component_url . 'hero.css',
            array( 'pei-style' ),
            defined( 'PEI_VERSION' ) ? PEI_VERSION : null
        );
    }

    // Enqueue JS if present
    if ( file_exists( $component_dir . 'hero.js' ) ) {
        wp_enqueue_script(
            'pei-hero',
            $component_url . 'hero.js',
            array(),
            defined( 'PEI_VERSION' ) ? PEI_VERSION : null,
            true
        );
    }

    ob_start();

    if ( file_exists( $component_dir . 'hero.php' ) ) {
        // variables are already set above, available in hero.php
        include $component_dir . 'hero.php';
    }

    return ob_get_clean();
}
add_shortcode( 'pei_hero', 'pei_render_hero_shortcode' );
