<?php
/**
 * Header template
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ( function_exists( 'wp_body_open' ) ) { wp_body_open(); } ?>

<header class="site-header">
    <div class="container">
        <div class="row site-header-top">
            <div class="col site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <span><?php bloginfo( 'name' ); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="col main-nav-wrap">
                <div class="nav-inner">
                    <nav class="main-navigation" aria-label="<?php echo esc_attr__( 'Primary Menu', 'pei' ); ?>">
                        <button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span class="screen-reader-text"><?php echo esc_html__( 'Toggle navigation', 'pei' ); ?></span>
                        </button>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => '',
                            'fallback_cb'    => false,
                        ) );
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
