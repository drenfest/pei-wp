<?php
/**
 * Hero Component
 *
 * Expects variables from shortcode:
 *  $title, $subtitle, $text, $link_location, $link_text
 */

?>

<?php
// Normalize CTA data – support new block attributes with fallback to legacy shortcode attrs.
$linkMode     = isset( $linkMode ) ? $linkMode : 'custom';
$linkPageId   = isset( $linkPageId ) ? intval( $linkPageId ) : 0;
$linkCustom   = isset( $linkCustom ) ? $linkCustom : '';
$linkNoFollow = ! empty( $linkNoFollow );
$linkNewTab   = ! empty( $linkNewTab );

// Determine CTA href with precedence: page -> custom -> legacy link_location
$cta_href = '';
if ( 'page' === $linkMode && $linkPageId ) {
    $cta_href = get_permalink( $linkPageId );
} elseif ( 'custom' === $linkMode && ! empty( $linkCustom ) ) {
    $cta_href = $linkCustom;
} elseif ( ! empty( $link_location ) ) { // legacy
    $cta_href = $link_location;
}

$cta_label = isset( $link_text ) ? $link_text : '';

// Target and rel handling
$cta_target = $linkNewTab ? '_blank' : '';
$rel_parts  = array();
if ( $linkNoFollow ) {
    $rel_parts[] = 'nofollow';
}
if ( $linkNewTab ) {
    // Security best practice for new tabs
    $rel_parts[] = 'noopener';
    $rel_parts[] = 'noreferrer';
}
$cta_rel = implode( ' ', array_unique( $rel_parts ) );

if (
    ! empty( $title ) ||
    ! empty( $subtitle ) ||
    ! empty( $text ) ||
    ( ! empty( $cta_href ) && ! empty( $cta_label ) )
) : ?>
    <section class="pei-hero">
        <div class="container">
            <div class="pei-hero-frame">
                <div class="pei-hero-card">

                    <h1 class="pei-hero-title">
                        <?php echo $title; // intentionally NOT escaped for full control ?>
                    </h1>

                    <?php if ( ! empty( $subtitle ) ) : ?>
                        <p class="pei-hero-subtitle">
                            <?php echo esc_html( $subtitle ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $text ) ) : ?>
                        <p class="pei-hero-text">
                            <?php echo esc_html( $text ); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ( ! empty( $cta_href ) && ! empty( $cta_label ) ) : ?>
                        <a href="<?php echo esc_url( $cta_href ); ?>"
                           class="pei-hero-cta"
                           title="<?php echo esc_attr( $cta_label ); ?>"
                           <?php echo $cta_target ? 'target="' . esc_attr( $cta_target ) . '"' : ''; ?>
                           <?php echo $cta_rel ? 'rel="' . esc_attr( $cta_rel ) . '"' : ''; ?>>
                            <?php echo esc_html( $cta_label ); ?>
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
