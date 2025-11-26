<?php
/**
 * Why Us (dynamic block)
 * Attributes:
 * - $heading (string)
 */

$heading = isset( $heading ) ? (string) $heading : 'Why Should You Work With Us?';

// Static items derived from old site content
$items = array(
  array(
    'title' => 'Exceptional Craftsmanship',
    'text'  => 'Precision Exteriors Inc. is committed to delivering top-notch craftsmanship. We take pride in our work and strive for excellence in every project, ensuring that your home transformation meets the highest quality standards.',
  ),
  array(
    'title' => 'Wide Range Of Services',
    'text'  => 'Whether you need roofing, siding, windows, doors, deck construction, or gutter replacement, we offer a comprehensive range of services so you can rely on a single, trusted contractor for your project.',
  ),
  array(
    'title' => 'Local Expertise With Global Perspective',
    'text'  => "Being rooted in Lena, IL, we understand the local architectural styles and community needs while staying updated on industry trends and techniques.",
  ),
  array(
    'title' => 'Responsive & Timely Service',
    'text'  => 'We prioritize responsiveness and timeliness, scheduling appointments at your convenience and ensuring our experts promptly assess your requirements.',
  ),
  array(
    'title' => 'Proven Reputation',
    'text'  => 'PEI has earned a solid reputation for integrity, transparency, and attention to detail. Your project is handled by professionals who care about your home.',
  ),
);
?>

<section class="pei-why-us">
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="col-12 col-lg-5">
        <div class="pei-why-us__media">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/media/misc/construction-worker.jpg' ); ?>" alt="PEI Construction Worker" />
        </div>
      </div>
      <div class="col-12 col-lg-7">
        <div class="pei-why-us__panel">
          <h2 class="pei-why-us__heading"><?php echo esc_html( $heading ); ?></h2>
          <ul class="pei-why-us__list">
            <?php foreach ( $items as $it ) : ?>
              <li class="pei-why-us__item">
                <div class="pei-why-us__item-title"><?php echo esc_html( $it['title'] ); ?></div>
                <div class="pei-why-us__item-text"><?php echo esc_html( $it['text'] ); ?></div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="pei-why-us__shadow" aria-hidden="true"></div>
</section>
