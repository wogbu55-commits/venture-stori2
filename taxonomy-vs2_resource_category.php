<?php get_header();

$vs2_current_term = get_queried_object();

$vs2_bc = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Business Resources', 'url' => vs2_resources_page_url() ],
    [ 'label' => $vs2_current_term->name, 'url' => null ],
];
vs2_breadcrumb( $vs2_bc );

$vs2_res_cats     = get_terms( [ 'taxonomy' => 'vs2_resource_category', 'hide_empty' => false ] );
$vs2_resources_q  = vs2_resources_query( 1, 5, $vs2_current_term->slug );
?>

<div class="editorial-hero">
  <div class="sec-label">Evergreen Content</div>
  <h1><?php echo esc_html( $vs2_current_term->name ); ?> Resources</h1>
  <p style="color:var(--grey);font-size:14px;margin-top:8px;line-height:1.65;font-weight:500;">Career guides, industry explainers, export handbooks — built for African ventures.</p>
  <div class="editorial-filters">
    <a href="<?php echo esc_url( vs2_resources_page_url() ); ?>" class="ef-btn">All Resources</a>
    <?php foreach ( $vs2_res_cats as $term ) : ?>
      <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="ef-btn<?php echo $term->term_id === $vs2_current_term->term_id ? ' ef-active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
    <?php endforeach; ?>
  </div>
</div>

<div class="dir-body" style="background:#fff;">
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
        data-query="<?php echo esc_attr( http_build_query( [ 'cat' => $vs2_current_term->slug ] ) ); ?>"
        data-nonce="<?php echo esc_attr( wp_create_nonce( 'vs2_load_more' ) ); ?>"
        data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
      >Load more resources</button>
    <?php endif; ?>
  <?php else : ?>
    <p style="color:var(--grey);font-size:13px;">No resources published yet in this category.</p>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
