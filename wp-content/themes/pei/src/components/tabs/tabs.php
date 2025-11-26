<?php
/**
 * Tabs (dynamic block)
 * Attributes:
 * - $tabs: array of { label, content, imageId?, imageUrl? }
 * - $active: number (default 0)
 */

$tabs   = isset( $tabs ) && is_array( $tabs ) ? $tabs : array();
$active = isset( $active ) ? intval( $active ) : 0;

if ( empty( $tabs ) ) {
    $tabs = array(
            array( 'label' => 'Tab 1', 'content' => 'Add tab items in block settings.' ),
            array( 'label' => 'Tab 2', 'content' => 'Add tab items in block settings.' ),
    );
}
?>

<section class="pei-tabs" data-active-index="<?php echo esc_attr( $active ); ?>">
    <div class="container">
        <div class="pei-tabs__nav" role="tablist">
            <?php foreach ( $tabs as $i => $t ) : ?>
                <?php $is_active = ( $i === $active ); ?>
                <button
                        class="pei-tabs__tab <?php echo $is_active ? 'is-active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        data-index="<?php echo esc_attr( $i ); ?>"
                >
                    <?php echo esc_html( $t['label'] ?? ( 'Tab ' . ( $i + 1 ) ) ); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="pei-tabs__panes">
            <?php foreach ( $tabs as $i => $t ) : ?>
                <?php
                $is_active = ( $i === $active );
                $img       = isset( $t['imageUrl'] ) ? $t['imageUrl'] : '';
                $raw       = isset( $t['content'] ) ? $t['content'] : '';
                // Decode HTML entities so typed HTML like <h3>Title</h3> renders properly.
                $decoded   = html_entity_decode( $raw, ENT_QUOTES, 'UTF-8' );
                ?>
                <div
                        class="pei-tabs__pane <?php echo $is_active ? 'is-active' : ''; ?>"
                        role="tabpanel"
                        data-index="<?php echo esc_attr( $i ); ?>"
                >
                    <?php if ( $img ) : ?>
                        <div class="pei-tabs__media">
                            <img src="<?php echo esc_url( $img ); ?>" alt=""/>
                        </div>
                    <?php endif; ?>
                    <div class="pei-tabs__content">
                        <?php
                        // Allow safe HTML, and auto-wrap paragraphs if you want
                        echo wp_kses_post( wpautop( $decoded ) );
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
