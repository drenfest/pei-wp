<?php
/**
 * Benefits List block/template
 *
 * Attributes available when rendered as a dynamic block (auto-registered in functions.php):
 * - $heading (string)
 * - $imageId (int)
 * - $imageUrl (string)
 * - $benefits (string) — either pipe/comma/newline separated list
 * - $useBadges (bool)
 * - $badgeHtml1, $badgeHtml2, $badgeHtml3 (strings, allow HTML)
 */

// Normalize
$heading    = isset( $heading ) ? $heading : '';
$image_id   = isset( $imageId ) ? intval( $imageId ) : 0;
$image_url  = isset( $imageUrl ) ? $imageUrl : '';
$benefits   = isset( $benefits ) ? $benefits : '';
$use_badges = ! empty( $useBadges );
$badge1     = isset( $badgeHtml1 ) ? $badgeHtml1 : '';
$badge2     = isset( $badgeHtml2 ) ? $badgeHtml2 : '';
$badge3     = isset( $badgeHtml3 ) ? $badgeHtml3 : '';

// Parse benefits into array
$benefit_items = array();
if ( ! empty( $benefits ) ) {
    // Split on pipes, commas, or newlines
    $benefit_items = preg_split( '/[\|,\r\n]+/', $benefits );
    $benefit_items = array_filter( array_map( 'trim', $benefit_items ) );
}

// Build image HTML
$image_html = '';
if ( $image_id ) {
    $image_html = wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'pei-benefits__image', 'loading' => 'lazy' ) );
} elseif ( ! empty( $image_url ) ) {
    $image_html = sprintf( '<img src="%1$s" alt="" class="pei-benefits__image" loading="lazy" />', esc_url( $image_url ) );
}
?>

<section class="pei-benefits">
  <!-- Container holds the card + offset (heading ABOVE the image) -->
  <div class="container">
    <div class="pei-benefits-frame" aria-hidden="false">
      <!-- Beige card layer with heading -->
      <div class="pei-benefits-card">
        <?php if ( ! empty( $heading ) ) : ?>
          <h2 class="pei-benefits-heading"><?php echo wp_kses_post( $heading ); ?></h2>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Full-bleed media + overlay list (outside container, centered full width) -->
  <div class="pei-benefits-media">
    <?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

    <?php if ( ! empty( $benefit_items ) ) : ?>
      <!-- Benefit list overlays image; now constrained by a container -->
      <div class="pei-benefits-list-overlay">
        <div class="container">
          <div class="pei-benefits-list">
            <?php foreach ( $benefit_items as $item ) : ?>
              <div class="pei-benefits-item"><span><?php echo esc_html( $item ); ?></span></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Badges remain within content container beneath the image -->
  <?php if ( $use_badges ) : ?>
    <div class="container">
      <div class="pei-benefits-badges">
        <?php
        $badges = array( $badge1, $badge2, $badge3 );
        foreach ( $badges as $idx => $content ) :
            ?>
            <div class="pei-benefit-badge" aria-label="Benefit badge <?php echo intval( $idx + 1 ); ?>">
              <div class="pei-benefit-badge__bg" aria-hidden="true">
                <?php
                // Unique IDs per badge to avoid collisions across multiple blocks on a page
                $uid      = uniqid('peiB', false);
                $grad_id  = $uid . '-grad';
                $rim_id   = $uid . '-rim';
                $glow_id  = $uid . '-glow';
                $hatch_id = $uid . '-hatch';
                ?>
                <!-- Edgy faceted plate (tech/industrial), 300x150 canvas -->
                <svg viewBox="0 0 300 150" preserveAspectRatio="xMidYMid meet" role="img" focusable="false">
                  <defs>
                    <!-- Graphite/charcoal plate with subtle vertical depth -->
                    <linearGradient id="<?php echo esc_attr( $grad_id ); ?>" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stop-color="#2d3239" />
                      <stop offset="50%" stop-color="#21262c" />
                      <stop offset="100%" stop-color="#1a1f25" />
                    </linearGradient>
                    <!-- Gold rim/highlight for edges -->
                    <linearGradient id="<?php echo esc_attr( $rim_id ); ?>" x1="0" y1="0" x2="1" y2="0">
                      <stop offset="0%" stop-color="#e7c372" />
                      <stop offset="50%" stop-color="#c89226" />
                      <stop offset="100%" stop-color="#f0d38b" />
                    </linearGradient>
                    <!-- Soft glow used for top sheen -->
                    <linearGradient id="<?php echo esc_attr( $glow_id ); ?>" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.35" />
                      <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                    </linearGradient>
                    <!-- Very subtle diagonal hatch for texture -->
                    <pattern id="<?php echo esc_attr( $hatch_id ); ?>" width="6" height="6" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                      <rect width="6" height="6" fill="none" />
                      <rect x="0" y="0" width="1" height="6" fill="#ffffff" opacity="0.06" />
                    </pattern>
                    <!-- Clip path for inner inset to keep texture inside -->
                    <clipPath id="<?php echo esc_attr( $uid ); ?>-insetClip">
                      <!-- Inner faceted shape (smaller inset) -->
                      <path d="M20,16 L52,16 L64,8 L236,8 L248,16 L280,16 L292,32 L292,118 L276,134 L24,134 L8,118 L8,32 Z" />
                    </clipPath>
                  </defs>

                  <!-- Outer faceted plate silhouette (sharp chamfers, bottom notch) -->
                  <path d="M10,12 L50,12 L66,2 L234,2 L250,12 L290,12 L298,28 L298,122 L282,140 L18,140 L2,122 L2,28 Z" fill="url(#<?php echo esc_attr( $grad_id ); ?>)" />

                  <!-- Gold edge/rim stroke -->
                  <path d="M10,12 L50,12 L66,2 L234,2 L250,12 L290,12 L298,28 L298,122 L282,140 L18,140 L2,122 L2,28 Z" fill="none" stroke="url(#<?php echo esc_attr( $rim_id ); ?>)" stroke-width="2.5" />

                  <!-- Inner inset (beveled) -->
                  <path d="M20,16 L52,16 L64,8 L236,8 L248,16 L280,16 L292,32 L292,118 L276,134 L24,134 L8,118 L8,32 Z" fill="#2a2f36" stroke="#0f1317" stroke-width="1.5" />

                  <!-- Subtle top glow clipped to inset area -->
                  <rect x="8" y="8" width="284" height="60" fill="url(#<?php echo esc_attr( $glow_id ); ?>)" clip-path="url(#<?php echo esc_attr( $uid ); ?>-insetClip)" />

                  <!-- Diagonal hatch texture inside inset -->
                  <rect x="8" y="8" width="284" height="126" fill="url(#<?php echo esc_attr( $hatch_id ); ?>)" clip-path="url(#<?php echo esc_attr( $uid ); ?>-insetClip)" />
                </svg>
              </div>
              <div class="pei-benefit-badge__content">
                <?php echo wp_kses_post( $content ); ?>
              </div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
