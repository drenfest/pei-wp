<?php
/**
 * Why Wait CTA (dynamic block)
 * Attributes: $title, $subtitle, $ctaTitle, $ctaSubtitle, $phone, $phoneHref
 */

$title       = isset( $title ) ? (string) $title : 'Why Wait?';
$subtitle    = isset( $subtitle ) ? (string) $subtitle : "Fast, friendly service at your convenience. Let's solve your issue today — we're just a phone call away.";
$cta_title   = isset( $ctaTitle ) ? (string) $ctaTitle : "Call Anytime — We're Here 24/7";
$cta_sub     = isset( $ctaSubtitle ) ? (string) $ctaSubtitle : 'Emergency service available day or night.';
$phone       = isset( $phone ) ? (string) $phone : '(815) 990-7996';
$phone_href  = isset( $phoneHref ) ? (string) $phoneHref : 'tel:+18159907996';
?>

<section class="pei-why-wait">
  <div class="container">
    <div class="pei-why-wait__wrap">
      <div class="pei-why-wait__left">
        <h2 class="pei-why-wait__title"><?php echo esc_html( $title ); ?></h2>
        <p class="pei-why-wait__subtitle"><?php echo esc_html( $subtitle ); ?></p>
      </div>
      <div class="pei-why-wait__right">
        <h3 class="pei-why-wait__cta-title"><?php echo esc_html( $cta_title ); ?></h3>
        <p class="pei-why-wait__cta-sub"><em><?php echo esc_html( $cta_sub ); ?></em></p>
        <a class="pei-why-wait__button" href="<?php echo esc_url( $phone_href ); ?>">
          <span class="pei-why-wait__icon" aria-hidden="true">📞</span>
          <span class="pei-why-wait__label"><?php echo esc_html( $phone ); ?></span>
        </a>
      </div>
    </div>
  </div>
</section>
