<?php
/**
 * Single Hero Badge (child of hero-badges)
 * Renders dynamically for both block and shortcode contexts.
 *
 * Attributes/variables (block): $title (string), $className (string), $inner_content (string)
 * Attributes/variables (shortcode path not used here; handled in parent file):
 *   see hero-badges.php > hero_badges_badge_shortcode()
 */

$title      = isset( $title ) ? $title : '';
$class_name = isset( $className ) ? $className : '';
$classes    = array( 'hero-badges__badge' );
if ( ! empty( $class_name ) ) {
    $classes[] = sanitize_html_class( $class_name );
}

?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
    <div class="hero-badges__badge-inner">
        <?php if ( ! empty( $title ) ) : ?>
            <h3 class="hero-badges__badge-title"><?php echo esc_html( $title ); ?></h3>
        <?php endif; ?>

        <div class="hero-badges__badge-content">
            <?php echo isset( $inner_content ) ? $inner_content : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>
  </div>
