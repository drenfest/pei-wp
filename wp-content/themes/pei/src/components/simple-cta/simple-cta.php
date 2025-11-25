<?php
/**
 * Simple CTA component render template
 *
 * Block attributes exposed as variables by functions.php renderer:
 * - $message (string)
 * - $buttonText (string)
 * - $phone (string)
 * - $linkType (string: 'page'|'custom')
 * - $pageId (int)
 * - $customUrl (string)
 * - $className (string)
 */

// Normalize defaults/safety
$message    = isset( $message ) && $message !== '' ? (string) $message : "Call Anytime — We're Here 24/7\nEmergency service available day or night.";
$buttonText = isset( $buttonText ) && $buttonText !== '' ? (string) $buttonText : '815-990-7796';
$phone      = isset( $phone ) && $phone !== '' ? preg_replace( '/[^0-9+]/', '', (string) $phone ) : '8159907796';
$linkType   = isset( $linkType ) && in_array( $linkType, array( 'page', 'custom' ), true ) ? $linkType : 'custom';
$page_id    = isset( $pageId ) ? intval( $pageId ) : 0;
$custom_url = isset( $customUrl ) ? (string) $customUrl : '';
$extra_class = isset( $className ) ? $className : '';

// Compute href
$href = '';
if ( 'page' === $linkType && $page_id ) {
    $href = get_permalink( $page_id );
}
if ( ! $href ) {
    // Fallback to custom. If not present, use tel: from phone value.
    if ( ! empty( $custom_url ) ) {
        $href = $custom_url;
    } else {
        $href = 'tel:' . $phone;
    }
}

$classes = array( 'pei-simple-cta' );
if ( ! empty( $extra_class ) ) {
    $classes[] = sanitize_html_class( $extra_class );
}
?>

<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
  <div class="container">
    <div class="pei-simple-cta__frame" aria-hidden="false">
      <div class="pei-simple-cta__card">
        <div class="pei-simple-cta__message">
          <?php
          // Split message into lines. First line → bold H3, second line → italic subtitle.
          $lines = preg_split( "/\r\n|\r|\n/", (string) $message );
          $lines = array_map( 'trim', array_filter( $lines, function( $l ) { return $l !== ''; } ) );

          $title   = isset( $lines[0] ) ? $lines[0] : '';
          $subtitle_lines = array_slice( $lines, 1 );
          $subtitle = '';
          if ( ! empty( $subtitle_lines ) ) {
            $subtitle = implode( "\n", $subtitle_lines );
          }
          ?>
          <?php if ( $title ) : ?>
            <h3 class="pei-simple-cta__title"><?php echo esc_html( $title ); ?></h3>
          <?php endif; ?>
          <?php if ( $subtitle ) : ?>
            <p class="pei-simple-cta__subtitle"><em><?php echo nl2br( esc_html( $subtitle ) ); ?></em></p>
          <?php endif; ?>
        </div>
        <div class="pei-simple-cta__action">
          <a class="pei-simple-cta__button" href="<?php echo esc_url( $href ); ?>">
            <span class="pei-simple-cta__icon" aria-hidden="true">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V21a1 1 0 01-1 1C10.07 22 2 13.93 2 4a1 1 0 011-1h4.5a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.24 1.01l-2.21 2.2z" fill="currentColor"/>
              </svg>
            </span>
            <span class="pei-simple-cta__button-text"><?php echo esc_html( $buttonText ); ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="pei-simple-cta__shadow" aria-hidden="true"></div>
</section>
