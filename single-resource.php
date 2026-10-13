<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $vs2_id      = get_the_ID();
    $vs2_cat     = vs2_resource_tag_name( $vs2_id );
    $vs2_country = vs2_resource_country( $vs2_id );
    $vs2_state   = vs2_resource_state( $vs2_id );
    $vs2_sections = get_the_terms( $vs2_id, 'vs2_guide_section' );
    $vs2_is_guide = vs2_resource_type( $vs2_id ) === 'guide';
    $vs2_hub_label = $vs2_is_guide ? 'Business Guides' : 'Business Resources';
    $vs2_hub_url   = $vs2_is_guide ? vs2_resource_base_url() : vs2_resources_page_url();

    $vs2_bc = [
        [ 'label' => 'Home', 'url' => home_url( '/' ) ],
        [ 'label' => $vs2_hub_label, 'url' => $vs2_hub_url ],
        [ 'label' => get_the_title(), 'url' => null ],
    ];
    vs2_breadcrumb( $vs2_bc );

    $vs2_related_args = [
        'post_type'      => 'resource',
        'post_status'    => 'publish',
        'posts_per_page' => 2,
        'post__not_in'   => [ $vs2_id ],
    ];
    $vs2_res_cat = vs2_resource_category_name( $vs2_id );
    if ( $vs2_sections && ! is_wp_error( $vs2_sections ) ) {
        $vs2_related_args['tax_query'] = [ [ 'taxonomy' => 'vs2_guide_section', 'field' => 'slug', 'terms' => $vs2_sections[0]->slug ] ];
    } elseif ( $vs2_res_cat ) {
        $vs2_related_args['tax_query'] = [ [ 'taxonomy' => 'vs2_resource_category', 'field' => 'slug', 'terms' => sanitize_title( $vs2_res_cat ) ] ];
    }
    $vs2_related_q = new WP_Query( $vs2_related_args );
?>
  <div style="background:#0a0a0a;padding:0 28px;border-bottom:1px solid var(--border);">
    <a href="<?php echo esc_url( $vs2_hub_url ); ?>" class="back-btn">← <?php echo esc_html( $vs2_hub_label ); ?></a>
  </div>

  <div class="article-hero">
    <div class="article-cat"><?php echo esc_html( trim( implode( ' · ', array_filter( [ $vs2_cat, $vs2_country, $vs2_state ] ) ) ) ); ?></div>
    <h1 class="article-title"><?php the_title(); ?></h1>
    <div class="article-byline">
      <strong><?php echo esc_html( get_the_author() ); ?></strong>
      <span><?php echo esc_html( vs2_read_time() ); ?></span>
      <span>Updated <?php echo esc_html( get_the_modified_date( 'M Y' ) ); ?></span>
      <span style="margin-left:auto;">
        <button type="button" class="vs2-share-btn" data-url="<?php echo esc_url( get_permalink() ); ?>" style="background:none;border:none;color:var(--red);font-weight:800;font-size:13px;cursor:pointer;font-family:inherit;padding:0;">Share &rarr;</button>
      </span>
    </div>
  </div>

  <?php if ( has_post_thumbnail() ) : ?>
    <div class="article-img" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( $vs2_id, 'vs2-hero' ) ); ?>');"></div>
  <?php endif; ?>

  <div class="article-layout">
    <div class="article-body" style="padding-top:36px;">
      <?php the_content(); ?>
    </div>
    <div class="article-sidebar">
      <?php if ( $vs2_related_q->have_posts() ) : ?>
      <div class="asc">
        <h4>Related <?php echo $vs2_is_guide ? 'Guides' : 'Resources'; ?></h4>
        <?php while ( $vs2_related_q->have_posts() ) : $vs2_related_q->the_post();
            $vs2_r_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '';
        ?>
        <a href="<?php the_permalink(); ?>" class="rel-story">
          <div class="rel-story-img" <?php if ( $vs2_r_thumb ) : ?>style="background-image:url('<?php echo esc_url( $vs2_r_thumb ); ?>');"<?php endif; ?>></div>
          <div>
            <div class="rel-story-title"><?php the_title(); ?></div>
            <div class="rel-story-meta"><?php echo esc_html( vs2_read_time() ); ?></div>
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>
      <div class="asc">
        <h4>Find Nigerian Businesses</h4>
        <a class="sid-link" href="<?php echo esc_url( vs2_directory_base_url() ); ?>">Browse the business directory &rarr;</a>
        <a class="sid-link" href="<?php echo esc_url( vs2_country_guide_url( 'Nigeria' ) ); ?>">Nigerian Business Guide &rarr;</a>
      </div>
      <div class="asc">
        <h4>Get Listed</h4>
        <p style="font-size:12px;color:var(--grey2);margin-bottom:14px;line-height:1.65;font-weight:500;">Already registered? Add your business to the Venture Stori directory.</p>
        <a href="<?php echo esc_url( vs2_get_listed_url() ); ?>" class="sidebar-cta">Get Listed</a>
      </div>
    </div>
  </div>

<?php endwhile; ?>

<script>
document.querySelectorAll('.vs2-share-btn').forEach(function(btn){
  btn.addEventListener('click', function(){
    var url = btn.dataset.url;
    if (navigator.share) { navigator.share({ url: url }).catch(function(){}); return; }
    navigator.clipboard.writeText(url).then(function(){
      var original = btn.textContent;
      btn.textContent = 'Link copied!';
      setTimeout(function(){ btn.textContent = original; }, 1800);
    });
  });
});
</script>

<?php get_footer(); ?>
