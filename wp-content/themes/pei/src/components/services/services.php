<?php
/**
 * Services component template
 * Variables available:
 * $images  — array of image filenames (from /media/services/) [legacy]
 * $titles  — array of service names [legacy]
 * $items   — array of objects with keys: id, url, title, pageId (preferred for block)
 */

// Ensure sane defaults to avoid notices when rendered from blocks
$images = isset($images) && is_array($images) ? $images : array();
$titles = isset($titles) && is_array($titles) ? $titles : array();
$heading = isset($heading) ? $heading : '';
// Normalize modern items structure
$items = isset($items) && is_array($items) ? $items : array();

$media_base = get_template_directory_uri() . '/media/services/';
?>

<section class="pei-services">
    <div class="container">
        <?php if ( ! empty( $heading ) ) : ?>
            <div class="pei-services-frame" aria-hidden="false">
                <div class="pei-services-card">
                    <h2 class="pei-services-heading"><?php echo esc_html( $heading ); ?></h2>
                </div>
            </div>
        <?php endif; ?>

        <div class="pei-services-row">

            <?php
            // Prefer new items array; else fallback to legacy arrays
            if ( ! empty( $items ) ) :
                foreach ( $items as $it ) :
                    $it = is_array( $it ) ? $it : array();
                    $url = isset( $it['url'] ) ? $it['url'] : '';
                    $title = isset( $it['title'] ) ? $it['title'] : '';
                    $page_id = isset( $it['pageId'] ) ? intval( $it['pageId'] ) : 0;

                    // If URL looks relative (no scheme), treat as legacy filename relative to media/services/
                    if ( $url && ! preg_match( '#^https?://#i', $url ) && strpos( $url, '//' ) !== 0 ) {
                        $url = trailingslashit( $media_base ) . ltrim( $url, '/\\' );
                    }

                    $href = $page_id ? get_permalink( $page_id ) : '';
                    ?>
                    <?php if ( $href ) : ?>
                        <a class="pei-service-item pei-service-item--link" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
                            <?php if ( $url ) : ?>
                                <img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                            <?php endif; ?>
                            <span class="pei-service-label">
                                <?php echo esc_html( $title ); ?>
                            </span>
                        </a>
                    <?php else : ?>
                        <div class="pei-service-item">
                            <?php if ( $url ) : ?>
                                <img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                            <?php endif; ?>
                            <span class="pei-service-label">
                                <?php echo esc_html( $title ); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <?php foreach ($images as $i => $img): ?>
                    <?php
                    $title = $titles[$i] ?? '';
                    $src   = $media_base . $img;
                    ?>
                    <div class="pei-service-item">
                        <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($title); ?>">
                        <span class="pei-service-label"><?php echo esc_html($title); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>
