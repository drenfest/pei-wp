<?php
/**
 * Footer template
 */
?>

<footer class="site-footer"
        itemscope
        itemtype="https://schema.org/LocalBusiness">

    <?php
    $business_name   = get_bloginfo( 'name' );
    $phone           = get_theme_mod( 'pei_footer_phone' );
    $email           = get_theme_mod( 'pei_footer_email' );
    $street          = get_theme_mod( 'pei_footer_street' );
    $city            = get_theme_mod( 'pei_footer_city' );
    $state           = get_theme_mod( 'pei_footer_state' );
    $postcode        = get_theme_mod( 'pei_footer_postcode' );
    $hours_weekday   = get_theme_mod( 'pei_footer_hours_weekday', 'M–F: 8am – 4pm' );
    $hours_weekend   = get_theme_mod( 'pei_footer_hours_weekend', 'S–S: By Appt' );
    $copyright_extra = get_theme_mod( 'pei_footer_copyright', __( 'All Rights Reserved', 'pei' ) );

    // Logo URL for schema
    $logo_id  = get_theme_mod( 'custom_logo' );
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';
    ?>

    <meta itemprop="name" content="<?php echo esc_attr( $business_name ); ?>">
    <?php if ( $logo_url ) : ?>
        <meta itemprop="logo" content="<?php echo esc_url( $logo_url ); ?>">
    <?php endif; ?>

    <div class="site-footer-top">
        <div class="container">
            <div class="row footer-row">
                <!-- Useful links -->
                <div class="col footer-col footer-links">
                    <h2 class="footer-heading">Useful links</h2>
                    <nav class="footer-navigation" aria-label="<?php echo esc_attr__( 'Footer Useful Links', 'pei' ); ?>">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_links',
                            'container'      => false,
                            'menu_class'     => 'footer-menu',
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </nav>
                </div>

                <!-- Center logo -->
                <div class="col footer-col footer-logo">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <span class="footer-site-name"><?php echo esc_html( $business_name ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Contact + hours -->
                <div class="col footer-col footer-contact">
                    <div class="footer-contact-block">
                        <h2 class="footer-heading">Contact Information</h2>
                        <ul class="footer-contact-list">
                            <?php if ( $phone ) : ?>
                                <li>
                                    <span itemprop="telephone">
                                        <?php echo esc_html( $phone ); ?>
                                    </span>
                                </li>
                            <?php endif; ?>

                            <?php if ( $email ) : ?>
                                <li>
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>" itemprop="email">
                                        <?php echo esc_html( $email ); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ( $street || $city || $state || $postcode ) : ?>
                                <li itemprop="address"
                                    itemscope
                                    itemtype="https://schema.org/PostalAddress">
                                    <?php if ( $street ) : ?>
                                        <span itemprop="streetAddress"><?php echo esc_html( $street ); ?></span><br>
                                    <?php endif; ?>
                                    <?php if ( $city ) : ?>
                                        <span itemprop="addressLocality"><?php echo esc_html( $city ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( $state ) : ?>
                                        , <span itemprop="addressRegion"><?php echo esc_html( $state ); ?></span>
                                    <?php endif; ?>
                                    <?php if ( $postcode ) : ?>
                                        &nbsp;<span itemprop="postalCode"><?php echo esc_html( $postcode ); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="footer-hours-block">
                        <h2 class="footer-heading">Hours of operation</h2>
                        <ul class="footer-contact-list">
                            <?php if ( $hours_weekday ) : ?>
                                <li>
                                    <span itemprop="openingHours">
                                        <?php echo esc_html( $hours_weekday ); ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                            <?php if ( $hours_weekend ) : ?>
                                <li>
                                    <span itemprop="openingHours">
                                        <?php echo esc_html( $hours_weekend ); ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .site-footer-top -->

    <div class="site-footer-bottom">
        <div class="container">
            <p class="footer-copy">
                &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                <?php echo esc_html( $business_name ); ?>
                <?php if ( $copyright_extra ) : ?>
                    <?php echo esc_html( ' ' . $copyright_extra ); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <?php wp_footer(); ?>
</footer>

</body>
</html>
