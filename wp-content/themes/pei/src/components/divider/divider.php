<?php
/**
 * PEI Divider (dynamic block)
 * Attributes:
 * - $accent (bool)
 * - $thick (bool)
 */

$classes = array('pei-divider');
if ( ! empty( $accent ) ) { $classes[] = 'pei-divider--accent'; }
if ( ! empty( $thick ) ) { $classes[] = 'pei-divider--thick'; }
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="separator" aria-hidden="true">
  <span class="pei-divider__line"></span>
  <span class="pei-divider__line"></span>
</div>
