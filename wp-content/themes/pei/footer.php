<?php
/**
 * Footer template for the "pei" theme
 * Author: Gary Hunt
 */
?>
<div class="footer-top-bar"></div>
<footer class="pei-footer"
        itemscope
        itemtype="https://schema.org/LocalBusiness">

    <?php
    $business_name   = get_bloginfo('name');
    $phone           = get_theme_mod('pei_footer_phone');
    $email           = get_theme_mod('pei_footer_email');
    $street          = get_theme_mod('pei_footer_street');
    $city            = get_theme_mod('pei_footer_city');
    $state           = get_theme_mod('pei_footer_state');
    $postcode        = get_theme_mod('pei_footer_postcode');
    $hours_weekday   = get_theme_mod('pei_footer_hours_weekday', '8am – 4pm');
    $hours_weekend   = get_theme_mod('pei_footer_hours_weekend', 'By Appt');
    $copyright_extra = get_theme_mod('pei_footer_copyright', __('All Rights Reserved', 'pei'));

    $logo_id  = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';

    // Useful links menu selected in Customizer
    $useful_links_menu = get_theme_mod('pei_footer_useful_links_menu');

    // Theme media directory
    $media_dir = get_template_directory_uri() . '/media/';
    ?>
    <meta itemprop="name" content="<?php echo esc_attr($business_name); ?>">
    <?php if ($logo_url) : ?>
        <meta itemprop="logo" content="<?php echo esc_url($logo_url); ?>">
    <?php endif; ?>

    <div class="container">
        <div class="footer-grid">

            <!-- Left Column (License + Links + License Numbers) -->
            <div class="footer-col">

                <div class="footer-section footer-license-img">
                    <picture>
                        <img src="<?php echo esc_url($media_dir . 'misc/roofing-license.jpg'); ?>"
                             alt="PEI License"
                             title="Precision Exteriors Inc License">
                    </picture>
                    <p><strong>License Number:</strong> 104.020225</p>
                    <p><strong>License Number:</strong> 105.011205</p>
                </div>

                <div class="footer-section useful-links">
                    <h5>Useful Links</h5>

                    <?php
                    // Menu args
                    $menu_args = [
                            'container'   => false,
                            'menu_class'  => '',
                            'fallback_cb' => false,
                            'items_wrap'  => '<ul class="footer-links">%3$s</ul>',
                    ];

                    // Use chosen menu or fallback
                    if ($useful_links_menu) {
                        $menu_args['menu'] = $useful_links_menu;
                    } else {
                        $menu_args['theme_location'] = 'footer_links';
                    }

                    wp_nav_menu($menu_args);
                    ?>
                </div>


            </div>

            <!-- Right Column (Contact + Hours) -->
            <div class="footer-col">

                <div class="footer-section contact-block">
                    <h5>Contact Information</h5>

                    <?php if ($phone) : ?>
                        <p><strong>PHONE NUMBER</strong></p>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="footer-contact-link">
                            <?php echo esc_html($phone); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($email) : ?>
                        <p><strong>EMAIL ADDRESS</strong></p>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="footer-contact-link">
                            <?php echo esc_html($email); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ($city || $postcode || $state) : ?>
                        <p><strong>OFFICE LOCATION</strong></p>
                        <p class="footer-location">
                            <?php echo esc_html($city); ?>
                            <?php if ($state) echo ', ' . esc_html($state); ?>
                            <?php if ($postcode) echo ' ' . esc_html($postcode); ?>
                        </p>
                    <?php endif; ?>
                </div>


                <div class="footer-section">
                    <h5>Hours of Operation</h5>

                    <table>
                        <thead>
                        <tr>
                            <th>Day</th>
                            <th>Hours</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr><td>Monday</td><td><?php echo esc_html($hours_weekday); ?></td></tr>
                        <tr><td>Tuesday</td><td><?php echo esc_html($hours_weekday); ?></td></tr>
                        <tr><td>Wednesday</td><td><?php echo esc_html($hours_weekday); ?></td></tr>
                        <tr><td>Thursday</td><td><?php echo esc_html($hours_weekday); ?></td></tr>
                        <tr><td>Friday</td><td><?php echo esc_html($hours_weekday); ?></td></tr>
                        <tr><td>Saturday</td><td><?php echo esc_html($hours_weekend); ?></td></tr>
                        <tr><td>Sunday</td><td><?php echo esc_html($hours_weekend); ?></td></tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</footer>

<div class="pei-footer-bottom">
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($business_name); ?>
            <?php echo esc_html($copyright_extra); ?>
        </p>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
