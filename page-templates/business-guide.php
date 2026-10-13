<?php
/**
 * Template Name: Country Business Guide
 */
get_header();

$vs2_country = get_post_meta( get_queried_object_id(), '_vs2_guide_country', true ) ?: 'Nigeria';
$vs2_biz_url = get_post_type_archive_link( 'profile' );
$vs2_pro_url = get_post_type_archive_link( 'professional' );

$vs2_biz_q = new WP_Query( [
    'post_type'      => 'profile',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'      => [ [ 'key' => '_vs2_country', 'value' => $vs2_country ] ],
] );

$vs2_pro_q = new WP_Query( [
    'post_type'      => 'professional',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'      => [ [ 'key' => '_vs2p_country', 'value' => $vs2_country ] ],
] );

$vs2_grow_q = new WP_Query( [
    'post_type'      => 'profile',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'meta_key'        => '_vs2_tier',
    'orderby'         => [ 'meta_value' => 'DESC', 'date' => 'DESC' ],
    'meta_query'      => [ [ 'key' => '_vs2_country', 'value' => $vs2_country ] ],
] );

$vs2_biz_locations = vs2_locations_for_country( $vs2_country, 'profile', '_vs2_location', '_vs2_country' );
$vs2_pro_locations  = vs2_locations_for_country( $vs2_country, 'professional', '_vs2p_location', '_vs2p_country' );
$vs2_industries      = vs2_industries_for_country( $vs2_country );
$vs2_all_industry_terms = get_terms( [ 'taxonomy' => 'vs2_industry', 'hide_empty' => false ] );

$vs2_guides_start     = vs2_guides_by_section( 'start-a-business', $vs2_country );
$vs2_guides_grow      = vs2_guides_by_section( 'grow-a-business', $vs2_country );
$vs2_guides_find_biz  = vs2_guides_by_section( 'find-businesses', $vs2_country );
$vs2_guides_find_pro  = vs2_guides_by_section( 'find-professionals', $vs2_country );
$vs2_guides_state     = vs2_guides_by_section( 'by-state', $vs2_country );
$vs2_guides_industry  = vs2_guides_by_section( 'by-industry', $vs2_country );
?>

<div class="ng-guide-nav">
  <div class="ng-nav-item active" data-target="ngs-start">Start a Business</div>
  <div class="ng-nav-item" data-target="ngs-grow">Grow a Business</div>
  <div class="ng-nav-item" data-target="ngs-find-biz">Find Businesses</div>
  <div class="ng-nav-item" data-target="ngs-find-pro">Find Professionals</div>
  <div class="ng-nav-item" data-target="ngs-state">By State</div>
  <div class="ng-nav-item" data-target="ngs-industry">By Industry</div>
</div>

<div style="display:flex;flex:1;background:#0a0a0a;">
  <div class="ng-content">

    <!-- START A BUSINESS -->
    <div id="ngs-start" class="ng-section-panel">
      <div class="ng-section-title">Start a Business in <?php echo esc_html( $vs2_country ); ?></div>
      <p class="ng-section-sub">Everything you need to register, set up, and launch a business in <?php echo esc_html( $vs2_country ); ?> — from registration to your first year of compliance.</p>
      <?php if ( $vs2_guides_start ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_start as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_biz_q->have_posts() ) : ?>
      <div class="guide-section-label">Businesses That Started Here — On Venture Stori</div>
      <div class="guide-entity-grid">
        <?php $i = 0; while ( $vs2_biz_q->have_posts() && $i < 3 ) : $vs2_biz_q->the_post(); $i++;
          $vs2_ind = get_the_terms( get_the_ID(), 'vs2_industry' );
          $vs2_sub = trim( implode( ' · ', array_filter( [ $vs2_ind && ! is_wp_error( $vs2_ind ) ? $vs2_ind[0]->name : '', get_post_meta( get_the_ID(), '_vs2_location', true ) ] ) ) );
          vs2_guide_entity_card( get_post(), $vs2_sub );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( 'country', $vs2_country, $vs2_biz_url ) ); ?>" class="guide-view-all">View all listed businesses &rarr;</a>
      <?php endif; ?>

      <?php if ( $vs2_pro_q->have_posts() ) : ?>
      <div class="guide-section-label">Professionals Who Can Help</div>
      <div class="guide-entity-grid">
        <?php $i = 0; while ( $vs2_pro_q->have_posts() && $i < 2 ) : $vs2_pro_q->the_post(); $i++;
          $vs2_sub = trim( implode( ' · ', array_filter( [ get_post_meta( get_the_ID(), '_vs2p_role', true ), get_post_meta( get_the_ID(), '_vs2p_location', true ) ] ) ) );
          vs2_guide_entity_card( get_post(), $vs2_sub );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( 'country', $vs2_country, $vs2_pro_url ) ); ?>" class="guide-view-all">View all listed professionals &rarr;</a>
      <?php endif; ?>
    </div>

    <!-- GROW A BUSINESS -->
    <div id="ngs-grow" class="ng-section-panel" style="display:none;">
      <div class="ng-section-title">Grow Your Business</div>
      <p class="ng-section-sub">Strategies and resources for scaling a business in <?php echo esc_html( $vs2_country ); ?> — from first hire to multi-region operations.</p>
      <?php if ( $vs2_guides_grow ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_grow as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_grow_q->have_posts() ) : ?>
      <div class="guide-section-label">Growing Businesses Featured on Venture Stori</div>
      <div class="guide-entity-grid">
        <?php while ( $vs2_grow_q->have_posts() ) : $vs2_grow_q->the_post();
          $vs2_tier = vs2_badge_label( vs2_tier( get_the_ID() ) );
          $vs2_sub  = trim( implode( ' · ', array_filter( [ $vs2_tier, get_post_meta( get_the_ID(), '_vs2_location', true ) ] ) ) );
          vs2_guide_entity_card( get_post(), $vs2_sub );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( 'country', $vs2_country, $vs2_biz_url ) ); ?>" class="guide-view-all">See all growing businesses &rarr;</a>
      <?php endif; ?>
    </div>

    <!-- FIND BUSINESSES -->
    <div id="ngs-find-biz" class="ng-section-panel" style="display:none;">
      <div class="ng-section-title">Find Businesses in <?php echo esc_html( $vs2_country ); ?></div>
      <p class="ng-section-sub">Browse businesses in <?php echo esc_html( $vs2_country ); ?> by city or industry. Every profile below links directly to the full listing.</p>

      <?php if ( $vs2_guides_find_biz ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_find_biz as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_biz_q->have_posts() ) : ?>
      <div class="guide-section-label">Featured Businesses</div>
      <div class="guide-entity-grid">
        <?php while ( $vs2_biz_q->have_posts() ) : $vs2_biz_q->the_post();
          $vs2_ind = get_the_terms( get_the_ID(), 'vs2_industry' );
          $vs2_sub = trim( implode( ' · ', array_filter( [ $vs2_ind && ! is_wp_error( $vs2_ind ) ? $vs2_ind[0]->name : '', get_post_meta( get_the_ID(), '_vs2_location', true ) ] ) ) );
          vs2_guide_entity_card( get_post(), $vs2_sub );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( 'country', $vs2_country, $vs2_biz_url ) ); ?>" class="guide-view-all">Browse all businesses &rarr;</a>
      <?php endif; ?>

      <?php if ( $vs2_biz_locations ) : ?>
      <div class="guide-section-label">Find by City</div>
      <div>
        <?php foreach ( $vs2_biz_locations as $loc ) : ?>
          <a href="<?php echo esc_url( add_query_arg( [ 'country' => $vs2_country, 'loc' => $loc ], $vs2_biz_url ) ); ?>" class="city-pill">🏙 <?php echo esc_html( $loc ); ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- FIND PROFESSIONALS -->
    <div id="ngs-find-pro" class="ng-section-panel" style="display:none;">
      <div class="ng-section-title">Find Professionals in <?php echo esc_html( $vs2_country ); ?></div>
      <p class="ng-section-sub">Connect with doctors, lawyers, engineers, designers and more. Every profile links directly to a full listing.</p>

      <?php if ( $vs2_guides_find_pro ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_find_pro as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_pro_q->have_posts() ) : ?>
      <div class="guide-section-label">Featured Professionals</div>
      <div class="guide-entity-grid">
        <?php while ( $vs2_pro_q->have_posts() ) : $vs2_pro_q->the_post();
          $vs2_sub = trim( implode( ' · ', array_filter( [ get_post_meta( get_the_ID(), '_vs2p_role', true ), get_post_meta( get_the_ID(), '_vs2p_location', true ) ] ) ) );
          vs2_guide_entity_card( get_post(), $vs2_sub );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( 'country', $vs2_country, $vs2_pro_url ) ); ?>" class="guide-view-all">Browse all professionals &rarr;</a>
      <?php endif; ?>

      <?php if ( $vs2_pro_locations ) : ?>
      <div class="guide-section-label">Find by City</div>
      <div>
        <?php foreach ( $vs2_pro_locations as $loc ) : ?>
          <span class="city-pill">🏙 <?php echo esc_html( $loc ); ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- BY STATE -->
    <div id="ngs-state" class="ng-section-panel" style="display:none;">
      <div class="ng-section-title">Business Guides by State</div>
      <p class="ng-section-sub">Each location below features real businesses and professionals listed on Venture Stori from that area.</p>

      <?php if ( $vs2_guides_state ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_state as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php
      $vs2_state_shown = 0;
      foreach ( $vs2_biz_locations as $vs2_loc ) :
          if ( $vs2_state_shown >= 3 ) break;
          $vs2_state_biz = new WP_Query( [
              'post_type'      => 'profile',
              'post_status'    => 'publish',
              'posts_per_page' => 4,
              'meta_query'      => [
                  [ 'key' => '_vs2_country', 'value' => $vs2_country ],
                  [ 'key' => '_vs2_location', 'value' => $vs2_loc ],
              ],
          ] );
          if ( ! $vs2_state_biz->have_posts() ) continue;
          $vs2_state_shown++;
      ?>
      <div class="guide-section-label" style="<?php echo $vs2_state_shown > 1 ? 'margin-top:32px;' : ''; ?>"><?php echo esc_html( $vs2_loc ); ?> — Featured Listings</div>
      <div class="guide-entity-grid">
        <?php while ( $vs2_state_biz->have_posts() ) : $vs2_state_biz->the_post();
          $vs2_ind = get_the_terms( get_the_ID(), 'vs2_industry' );
          vs2_guide_entity_card( get_post(), $vs2_ind && ! is_wp_error( $vs2_ind ) ? $vs2_ind[0]->name : 'Business' );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <a href="<?php echo esc_url( add_query_arg( [ 'country' => $vs2_country, 'loc' => $vs2_loc ], $vs2_biz_url ) ); ?>" class="guide-view-all">All <?php echo esc_html( $vs2_loc ); ?> businesses &rarr;</a>
      <?php endforeach; ?>

      <?php if ( $vs2_country === 'Nigeria' ) : ?>
      <div class="guide-section-label" style="margin-top:32px;">All 36 States + FCT</div>
      <div>
        <?php foreach ( vs2_nigeria_states() as $vs2_state ) : ?>
          <a href="<?php echo esc_url( add_query_arg( [ 'country' => $vs2_country, 'loc' => $vs2_state ], $vs2_biz_url ) ); ?>" class="city-pill"><?php echo esc_html( $vs2_state ); ?></a>
        <?php endforeach; ?>
      </div>
      <?php elseif ( $vs2_biz_locations ) : ?>
      <div class="guide-section-label" style="margin-top:32px;">All Locations</div>
      <div>
        <?php foreach ( $vs2_biz_locations as $vs2_loc ) : ?>
          <a href="<?php echo esc_url( add_query_arg( [ 'country' => $vs2_country, 'loc' => $vs2_loc ], $vs2_biz_url ) ); ?>" class="city-pill"><?php echo esc_html( $vs2_loc ); ?></a>
        <?php endforeach; ?>
      </div>
      <?php else : ?>
      <p style="color:var(--grey);font-size:13px;">No locations recorded yet for <?php echo esc_html( $vs2_country ); ?> listings.</p>
      <?php endif; ?>
    </div>

    <!-- BY INDUSTRY -->
    <div id="ngs-industry" class="ng-section-panel" style="display:none;">
      <div class="ng-section-title">Business Guides by Industry</div>
      <p class="ng-section-sub">Browse businesses on Venture Stori by industry sector.</p>

      <?php if ( $vs2_guides_industry ) : ?>
      <div class="ng-card-grid">
        <?php foreach ( $vs2_guides_industry as $vs2_g ) : vs2_ng_card( $vs2_g ); endforeach; ?>
      </div>
      <?php endif; ?>

      <?php foreach ( $vs2_industries as $vs2_row ) :
          $vs2_ind_biz = new WP_Query( [
              'post_type'      => 'profile',
              'post_status'    => 'publish',
              'posts_per_page' => 3,
              'tax_query'       => [ [ 'taxonomy' => 'vs2_industry', 'field' => 'slug', 'terms' => $vs2_row['term']->slug ] ],
              'meta_query'      => [ [ 'key' => '_vs2_country', 'value' => $vs2_country ] ],
          ] );
          if ( ! $vs2_ind_biz->have_posts() ) continue;
      ?>
      <div class="guide-section-label" style="margin-top:24px;"><?php echo esc_html( $vs2_row['term']->name ); ?></div>
      <div class="guide-entity-grid">
        <?php while ( $vs2_ind_biz->have_posts() ) : $vs2_ind_biz->the_post();
          vs2_guide_entity_card( get_post(), get_post_meta( get_the_ID(), '_vs2_location', true ) );
        endwhile; wp_reset_postdata(); ?>
      </div>
      <?php endforeach; ?>

      <?php if ( $vs2_all_industry_terms && ! is_wp_error( $vs2_all_industry_terms ) ) : ?>
      <div class="guide-section-label" style="margin-top:24px;">Browse All Industries</div>
      <div>
        <?php foreach ( $vs2_all_industry_terms as $vs2_term ) : ?>
          <a href="<?php echo esc_url( add_query_arg( [ 'country' => $vs2_country, 'ind' => $vs2_term->slug ], $vs2_biz_url ) ); ?>" class="industry-pill"><?php echo esc_html( $vs2_term->name ); ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
document.querySelectorAll('.ng-guide-nav .ng-nav-item').forEach(function(tab){
  tab.addEventListener('click', function(){
    document.querySelectorAll('.ng-guide-nav .ng-nav-item').forEach(function(t){ t.classList.remove('active'); });
    tab.classList.add('active');
    document.querySelectorAll('.ng-section-panel').forEach(function(p){ p.style.display = 'none'; });
    var target = document.getElementById(tab.dataset.target);
    if (target) target.style.display = 'block';
  });
});
</script>

<?php get_footer(); ?>
