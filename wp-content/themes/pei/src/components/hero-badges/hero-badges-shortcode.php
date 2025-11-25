<?php
/**
 * Shortcodes for Hero Badges component
 * Parent: [hero_badges]
 * Child:  [hero_badge]
 */

if ( ! function_exists( 'hero_badges_register_shortcodes' ) ) {
    function hero_badges_register_shortcodes() {
        add_shortcode( 'hero_badges', 'hero_badges_parent_shortcode' );
        add_shortcode( 'hero_badge', 'hero_badges_badge_shortcode' );
    }
}
add_action( 'init', 'hero_badges_register_shortcodes' );

/**
 * Parent container shortcode: [hero_badges]
 */
if ( ! function_exists( 'hero_badges_parent_shortcode' ) ) {
    function hero_badges_parent_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts(
            array(
                'image_id'     => '',
                'image_url'    => '',
                'benefits'     => '', // pipe or comma separated list
                'class'        => '',
                'offset_class' => 'offset-top-left',
            ),
            $atts,
            'hero_badges'
        );

        $classes = array( 'hero-badges' );

        if ( ! empty( $atts['offset_class'] ) ) {
            $classes[] = sanitize_html_class( $atts['offset_class'] );
        }

        if ( ! empty( $atts['class'] ) ) {
            $classes[] = sanitize_html_class( $atts['class'] );
        }

        // Image HTML
        $image_html = '';
        if ( ! empty( $atts['image_id'] ) ) {
            $image_html = wp_get_attachment_image(
                (int) $atts['image_id'],
                'large',
                false,
                array(
                    'class'   => 'hero-badges__image',
                    'loading' => 'lazy',
                )
            );
        } elseif ( ! empty( $atts['image_url'] ) ) {
            $image_html = sprintf(
                '<img src="%1$s" alt="" class="hero-badges__image" loading="lazy" />',
                esc_url( $atts['image_url'] )
            );
        }

        // Benefits list
        $benefit_items = array();
        if ( ! empty( $atts['benefits'] ) ) {
            $benefit_items = preg_split( '/[\|,]/', $atts['benefits'] );
            $benefit_items = array_filter( array_map( 'trim', $benefit_items ) );
        }

        // Badges (child shortcodes)
        $badges_html = do_shortcode( $content );

        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

            <?php if ( $image_html ) : ?>
                <?php echo $image_html; ?>
            <?php endif; ?>

            <div class="hero-badges__image-offset"></div>

            <div class="hero-badges__benefit-list">
                <?php if ( ! empty( $benefit_items ) ) : ?>
                    <?php foreach ( $benefit_items as $benefit ) : ?>
                        <div class="hero-badges__benefit-item">
                            <span><?php echo esc_html( $benefit ); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="hero-badges__badge-row">
                <?php echo $badges_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>

        </div>
        <?php

        return ob_get_clean();
    }
}

/**
 * Child shortcode for badges: [hero_badge]
 */
if ( ! function_exists( 'hero_badges_badge_shortcode' ) ) {
    function hero_badges_badge_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts(
            array(
                'title' => '',
                'class' => '',
            ),
            $atts,
            'hero_badge'
        );

        $classes = array( 'hero-badges__badge' );
        if ( ! empty( $atts['class'] ) ) {
            $classes[] = sanitize_html_class( $atts['class'] );
        }

        $content_html = wpautop( do_shortcode( $content ) );

        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="hero-badges__badge-inner">
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h3 class="hero-badges__badge-title">
                        <?php echo esc_html( $atts['title'] ); ?>
                    </h3>
                <?php endif; ?>

                <div class="hero-badges__badge-content">
                    <?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }
}
