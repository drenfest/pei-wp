<?php
/**
 * Accordion (dynamic block)
 * Attributes:
 * - $light (bool)
 * - $items (array of { title, content })
 */

$light = ! empty( $light );
$items = isset( $items ) && is_array( $items ) ? $items : array();
$classes = array( 'pei-accordion' );
if ( $light ) { $classes[] = 'pei-accordion--light'; }
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
  <?php foreach ( $items as $idx => $it ) :
    $title = isset( $it['title'] ) ? $it['title'] : '';
    $content = isset( $it['content'] ) ? $it['content'] : '';
  ?>
    <details class="pei-accordion__item" <?php echo $idx === 0 ? 'open' : ''; ?>>
      <summary class="pei-accordion__head">
        <span class="pei-accordion__chev" aria-hidden="true">▸</span>
        <span class="pei-accordion__title"><?php echo esc_html( $title ); ?></span>
      </summary>
      <div class="pei-accordion__panel">
        <div class="pei-accordion__content"><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
      </div>
    </details>
  <?php endforeach; ?>
  <?php if ( empty( $items ) ) : ?>
    <details class="pei-accordion__item" open>
      <summary class="pei-accordion__head">
        <span class="pei-accordion__chev" aria-hidden="true">▸</span>
        <span class="pei-accordion__title">Sample item</span>
      </summary>
      <div class="pei-accordion__panel"><div class="pei-accordion__content">Add items in the block settings.</div></div>
    </details>
  <?php endif; ?>
  <div class="pei-accordion__shadow" aria-hidden="true"></div>
</div>
