<?php
/**
 * Minimal index template to verify theme renders.
 * Note: Keep lean and framework-free.
 */
get_header();
?>

<main id="primary" class="site-main">
  <div class="container" style="padding:40px 16px;">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </article>
        <hr>
      <?php endwhile; ?>
      <nav class="pagination" aria-label="<?php echo esc_attr__( 'Posts', 'pei' ); ?>">
        <?php the_posts_pagination(); ?>
      </nav>
    <?php else : ?>
      <h1><?php bloginfo( 'name' ); ?></h1>
      <p>Edit index.php to build the rest of your lean theme.</p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
