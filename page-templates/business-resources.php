<?php
/**
 * Template Name: Business Resources
 */
get_header();

$vs2_bc = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Business Resources', 'url' => null ],
];
vs2_breadcrumb( $vs2_bc );

$vs2_res_cats   = get_terms( [ 'taxonomy' => 'vs2_resource_category', 'hide_empty' => false ] );
$vs2_resources_q = vs2_resources_query( 1, 5 );
$vs2_featured_q  = vs2_resources_query( 1, 1 );
?>

<div class="editorial-hero">
  <div class="sec-label">Evergreen Content</div>
  <h1>Business Resources</h1>
  <p style="color:var(--grey);font-size:14px;margin-top:8px;line-height:1.65;font-weight:500;">Career guides, industry explainers, export handbooks, registration walkthroughs — built for African ventures.</p>
  <div class="editorial-filters">
    <a href="<?php echo esc_url( get_permalink( get_queried_object_id() ) ); ?>" class="ef-btn ef-active">All Resources</a>
    <?php foreach ( $vs2_res_cats as $term ) : ?>
      <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="ef-btn"><?php echo esc_html( $term->name ); ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="dir-body" style="background:#fff;">
  <?php if ( $vs2_featured_q->have_posts() ) : $vs2_featured_q->the_post();
      $vs2_f_cat = vs2_resource_category_name();
  ?>
  <a href="<?php the_permalink(); ?>" style="display:block;background:var(--red-light);border-left:3px solid var(--red);padding:24px 28px;margin-bottom:20px;">
    <div style="font-size:10px;font-weight:800;letter-spacing:.1em;color:var(--red);margin-bottom:8px;text-transform:uppercase;">Featured Resource</div>
    <div style="font-size:18px;font-weight:900;color:var(--black);letter-spacing:-.02em;margin-bottom:8px;"><?php the_title(); ?></div>
    <div style="font-size:13px;color:var(--grey);font-weight:500;"><?php echo esc_html( $vs2_f_cat ); ?><?php echo $vs2_f_cat ? ' · ' : ''; ?><?php echo esc_html( vs2_read_time() ); ?></div>
  </a>
  <?php wp_reset_postdata(); endif; ?>

  <?php if ( $vs2_resources_q->have_posts() ) : ?>
    <div class="res-grid" id="vs2-resources-grid">
      <?php while ( $vs2_resources_q->have_posts() ) : $vs2_resources_q->the_post(); ?>
        <?php get_template_part( 'template-parts/resource-card' ); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php if ( $vs2_resources_q->max_num_pages > 1 ) : ?>
      <button
        type="button"
        class="see-more-btn vs2-load-more"
        data-grid="vs2-resources-grid"
        data-action="vs2_load_more_resources"
        data-page="1"
        data-query=""
        data-nonce="<?php echo esc_attr( wp_create_nonce( 'vs2_load_more' ) ); ?>"
        data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
      >Load more resources</button>
    <?php endif; ?>
  <?php else : ?>
    <p style="color:var(--grey);font-size:13px;">No resources published yet.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
