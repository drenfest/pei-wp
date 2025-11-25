<?php
/**
 * Hero with Badges component
 *
 * Supports both:
 * - Classic shortcodes: [hero_badges][hero_badge][/hero_badge][/hero_badges]
 * - Gutenberg block: pei/hero-badges with nested pei/hero-badge blocks
 *
 * Usage in Classic Editor:
 *
 * [hero_badges
 *    image_id="123"
 *    benefits="Proven Experience|Quality Craftsmanship|Local Expertise|Transparent Communication|Fully Licensed & Insured"
 *    class="extra-class"
 *    offset_class="offset-top-left"
 * ]
 *   [hero_badge title="We install MP Rubberized Asphalt"]
 *   We install Modified Polymer Rubberized Asphalt as our primary line of asphalt shingles.
 *   [/hero_badge]
 *
 *   [hero_badge title="Ice &amp; Water shield in key areas"]
 *   Ice &amp; Water shield is installed in all vulnerable roof areas.
 *   [/hero_badge]
 *
 *   [hero_badge title="Synthetic underlayment for protection"]
 *   We use synthetic underlayment for improved long-term protection.
 *   [/hero_badge]
 * [/hero_badges]
 */
// Shortcode handlers moved to hero-badges-shortcode.php, auto-loaded by functions.php

/**
 * Block render support
 * When used as a dynamic block via functions.php auto-registration, the following variables may be set:
 * - $imageId (int), $imageUrl (string), $benefits (string), $offsetClass (string), $className (string)
 * - $inner_content (string) containing rendered InnerBlocks (pei/hero-badge)
 */

// Normalize attributes if coming from Gutenberg block
$is_block_context = isset( $inner_content ) || isset( $imageId ) || isset( $imageUrl ) || isset( $offsetClass );
if ( $is_block_context ) {
    // Map block-style camelCase to shortcode-style snake_case local vars used above
    $image_id     = isset( $imageId ) ? intval( $imageId ) : 0;
    $image_url    = isset( $imageUrl ) ? $imageUrl : '';
    $benefits_str = isset( $benefits ) ? $benefits : '';
    $offset_class = isset( $offsetClass ) ? $offsetClass : 'offset-top-left';
    $extra_class  = isset( $className ) ? $className : '';

    $classes = array( 'hero-badges' );
    if ( ! empty( $offset_class ) ) {
        $classes[] = sanitize_html_class( $offset_class );
    }
    if ( ! empty( $extra_class ) ) {
        $classes[] = sanitize_html_class( $extra_class );
    }

    // Image HTML (block)
    $image_html = '';
    if ( $image_id ) {
        $image_html = wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'hero-badges__image', 'loading' => 'lazy' ) );
    } elseif ( ! empty( $image_url ) ) {
        $image_html = sprintf( '<img src="%1$s" alt="" class="hero-badges__image" loading="lazy" />', esc_url( $image_url ) );
    }

    // Benefits list
    $benefit_items = array();
    if ( ! empty( $benefits_str ) ) {
        $benefit_items = preg_split( '/[\|,]/', $benefits_str );
        $benefit_items = array_filter( array_map( 'trim', $benefit_items ) );
    }

    // Badges come from rendered InnerBlocks
    $badges_html = isset( $inner_content ) ? $inner_content : '';

    ?>
    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

        <?php if ( $image_html ) : ?>
            <?php echo $image_html; ?>
        <?php endif; ?>

        <div class="hero-badges__image-offset"></div>

        <div class="hero-badges__benefit-list">
            <?php if ( ! empty( $benefit_items ) ) : ?>
                <?php foreach ( $benefit_items as $benefit_item ) : ?>
                    <div class="hero-badges__benefit-item">
                        <span><?php echo esc_html( $benefit_item ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="hero-badges__badge-row">
            <?php echo $badges_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>

    </div>
<?php }
