<?php get_header(); ?>

<div id="main">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-eyebrow">Africa's Largest Venture Directory</div>
    <h1>Discover Africa's Businesses, Professionals,<br><em>Institutions &amp; Organizations.</em></h1>
    <p class="hero-sub">One interconnected ecosystem — explore ventures, professionals and institutions across the continent.</p>

    <form class="search-wrap" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="text" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search businesses, professionals, organizations, institutions...">
      <button type="submit" class="search-btn">Explore Directory</button>
    </form>

    <div class="hero-explore">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'profile' ) ); ?>" class="hero-chip hc-active">Businesses</a>
      <a href="<?php echo esc_url( vs2_pro_base_url() ); ?>" class="hero-chip">Professionals</a>
      <a href="<?php echo esc_url( vs2_org_base_url() ); ?>" class="hero-chip">Organizations</a>
      <a href="<?php echo esc_url( vs2_ins_base_url() ); ?>" class="hero-chip">Institutions</a>
    </div>

  </section>

  <!-- FOUR PILLARS -->
  <section class="section">
    <div class="sec-label">Explore the Directory</div>
    <div class="sec-title">Four pillars. One connected ecosystem.</div>
    <div class="sec-sub">Every entity type has its own dedicated directory — interconnected through relationships, industries and locations.</div>
    <div class="pillars-grid">

      <a href="<?php echo esc_url( get_post_type_archive_link( 'profile' ) ); ?>" class="pillar-card">
        <div class="p-icon"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="15" rx="1"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></div>
        <h3>Businesses</h3>
        <p>SMEs, startups, corporations, farms, clinics, restaurants, agencies and every operating entity across Africa.</p>
        <div class="p-count"><?php echo esc_html( number_format_i18n( vs2_post_type_count( 'profile' ) ) ); ?>+ Listed</div>
        <div class="p-browse">Browse businesses <span class="p-arrow">→</span></div>
      </a>
      <a href="<?php echo esc_url( vs2_pro_base_url() ); ?>" class="pillar-card">
        <div class="p-icon"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <h3>Professionals</h3>
        <p>Doctors, engineers, lawyers, designers, researchers, consultants and every career across the continent.</p>
        <div class="p-count"><?php echo esc_html( number_format_i18n( vs2_post_type_count( 'professional' ) ) ); ?>+ Profiles</div>
        <div class="p-browse">Browse professionals <span class="p-arrow">→</span></div>
      </a>
      <a href="<?php echo esc_url( vs2_org_base_url() ); ?>" class="pillar-card">
        <div class="p-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h3>Organizations</h3>
        <p>Professional bodies, trade associations, student groups, chambers of commerce and community networks.</p>
        <div class="p-count"><?php echo esc_html( number_format_i18n( vs2_post_type_count( 'organization' ) ) ); ?>+ Organizations</div>
        <div class="p-browse">Browse organizations <span class="p-arrow">→</span></div>
      </a>
      <a href="<?php echo esc_url( vs2_ins_base_url() ); ?>" class="pillar-card">
        <div class="p-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <h3>Institutions</h3>
        <p>Universities, hospitals, schools, ministries, research institutes and innovation hubs shaping Africa.</p>
        <div class="p-count"><?php echo esc_html( number_format_i18n( vs2_post_type_count( 'institution' ) ) ); ?>+ Institutions</div>
        <div class="p-browse">Browse institutions <span class="p-arrow">→</span></div>
      </a>

    </div>
  </section>

  <!-- FEATURED ENTITIES -->
  <?php
  $vs2_featured_types = [
      'profile'      => [ 'label' => 'Business',     'url_fn' => 'get_post_type_archive_link', 'tier_fn' => 'vs2_tier',              'loc_key' => '_vs2_location'  ],
      'professional' => [ 'label' => 'Professional',  'url_fn' => null,                          'tier_fn' => 'vs2_professional_tier', 'loc_key' => '_vs2p_location' ],
      'organization' => [ 'label' => 'Organization',  'url_fn' => null,                          'tier_fn' => 'vs2_organization_tier', 'loc_key' => '_vs2o_country'  ],
      'institution'  => [ 'label' => 'Institution',   'url_fn' => null,                          'tier_fn' => 'vs2_institution_tier',  'loc_key' => '_vs2i_country'  ],
  ];
  $vs2_featured_items = [];
  foreach ( $vs2_featured_types as $vs2_pt => $vs2_cfg ) {
      $vs2_fq = new WP_Query( [ 'post_type' => $vs2_pt, 'post_status' => 'publish', 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'DESC' ] );
      if ( $vs2_fq->have_posts() ) {
          $vs2_featured_items[ $vs2_pt ] = [ 'post' => $vs2_fq->posts[0], 'cfg' => $vs2_cfg ];
      }
      wp_reset_postdata();
  }
  ?>
  <?php if ( $vs2_featured_items ) : ?>
  <section class="section-alt">
    <div class="sec-header">
      <div>
        <div class="sec-label">Featured Ventures</div>
        <div class="sec-title">Discovered and verified across Africa.</div>
      </div>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'profile' ) ); ?>" class="view-all">View all →</a>
    </div>
    <div class="featured-strip">
      <?php foreach ( $vs2_featured_items as $vs2_pt => $vs2_item ) :
          $vs2_p        = $vs2_item['post'];
          $vs2_cfg      = $vs2_item['cfg'];
          $vs2_location = get_post_meta( $vs2_p->ID, $vs2_cfg['loc_key'], true );
          $vs2_avatar   = has_post_thumbnail( $vs2_p->ID ) ? get_the_post_thumbnail_url( $vs2_p->ID, 'thumbnail' ) : '';
          $vs2_round    = $vs2_pt === 'professional' ? 'border-radius:50%;' : '';
      ?>
      <a href="<?php echo esc_url( get_permalink( $vs2_p ) ); ?>" class="featured-card">
        <div class="featured-label">Featured</div>
        <div class="fc-top">
          <div class="fc-logo" style="<?php echo $vs2_round; echo $vs2_avatar ? "background-image:url('" . esc_url( $vs2_avatar ) . "');background-size:cover;background-position:center;" : 'background:' . esc_attr( vs2_logo_bg( $vs2_p->ID ) ) . ';'; ?>"><?php echo $vs2_avatar ? '' : esc_html( vs2_initials( get_the_title( $vs2_p ) ) ); ?></div>
          <div>
            <div class="fc-name"><?php echo esc_html( get_the_title( $vs2_p ) ); ?></div>
            <div class="fc-type"><?php echo esc_html( $vs2_cfg['label'] . ( $vs2_location ? ' · ' . $vs2_location : '' ) ); ?></div>
          </div>
        </div>
        <div class="fc-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt( $vs2_p ) ?: $vs2_p->post_content, 20, '…' ) ); ?></div>
        <div class="fc-footer">
          <div class="fc-location"><?php echo $vs2_location ? '📍 ' . esc_html( $vs2_location ) : ''; ?></div>
          <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( call_user_func( $vs2_cfg['tier_fn'], $vs2_p->ID ) ) ); ?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- EDITOR'S PICKS / FEATURED STORIES -->
  <?php $vs2_picks = vs2_editors_picks( 3 ); ?>
  <?php if ( $vs2_picks ) : ?>
  <section class="section" id="stories">
    <div class="sec-header">
      <div>
        <div class="sec-label">Editor's Picks</div>
        <div class="sec-title">Earned, never paid for.</div>
      </div>
      <a href="<?php echo esc_url( vs2_featured_stories_url() ?: home_url( '/' ) ); ?>" class="view-all">View all stories →</a>
    </div>
    <div class="stories-grid">
      <?php foreach ( $vs2_picks as $vs2_i => $vs2_p ) : setup_postdata( $vs2_p );
          $vs2_thumb = has_post_thumbnail( $vs2_p->ID ) ? get_the_post_thumbnail_url( $vs2_p->ID, 'vs2-hero' ) : '';
      ?>
        <?php if ( $vs2_i === 0 ) : ?>
        <a href="<?php echo esc_url( get_permalink( $vs2_p ) ); ?>" class="story-featured">
          <div class="story-featured-img" <?php if ( $vs2_thumb ) : ?>style="background-image:url('<?php echo esc_url( $vs2_thumb ); ?>');"<?php endif; ?>></div>
          <div class="story-featured-body">
            <div class="story-cat"><?php echo esc_html( vs2_pick_category( $vs2_p->ID ) ); ?></div>
            <div class="story-featured-title"><?php echo esc_html( get_the_title( $vs2_p ) ); ?></div>
            <div class="story-featured-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $vs2_p ), 24, '…' ) ); ?></div>
            <div style="font-size:12px;color:var(--grey2);font-weight:600;"><?php echo esc_html( get_the_author_meta( 'display_name', $vs2_p->post_author ) ); ?> · <?php echo esc_html( vs2_read_time( $vs2_p->ID ) ); ?></div>
          </div>
        </a>
        <?php else : ?>
        <a href="<?php echo esc_url( get_permalink( $vs2_p ) ); ?>" class="story-card">
          <div class="story-img" <?php if ( $vs2_thumb ) : ?>style="background-image:url('<?php echo esc_url( $vs2_thumb ); ?>');"<?php endif; ?>></div>
          <div class="story-body">
            <div class="story-cat"><?php echo esc_html( vs2_pick_category( $vs2_p->ID ) ); ?></div>
            <div class="story-title"><?php echo esc_html( get_the_title( $vs2_p ) ); ?></div>
            <div class="story-byline"><?php echo esc_html( get_the_author_meta( 'display_name', $vs2_p->post_author ) ); ?> · <?php echo esc_html( vs2_read_time( $vs2_p->ID ) ); ?></div>
          </div>
        </a>
        <?php endif; ?>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- COUNTRY BUSINESS GUIDES -->
  <?php $vs2_priority_countries = [ 'Nigeria' => 'NG', 'Ghana' => 'GH', 'Kenya' => 'KE', 'South Africa' => 'ZA' ]; ?>
  <section class="guides-entry">
    <div class="sec-label" style="color:var(--red);">Country Business Guides</div>
    <div class="sec-title" style="color:#fff;">Navigate Africa's business ecosystems.</div>
    <div class="sec-sub" style="color:#888;">Country-specific guides combining editorial content with directory functionality.</div>
    <div class="country-guides-grid">
      <?php foreach ( $vs2_priority_countries as $vs2_c => $vs2_code ) : ?>
      <a href="<?php echo esc_url( vs2_country_guide_url( $vs2_c ) ); ?>" class="country-guide-card">
        <div class="cgc-country"><?php echo esc_html( $vs2_code ); ?> · <?php echo esc_html( $vs2_c ); ?></div>
        <div class="cgc-title"><?php echo esc_html( $vs2_c ); ?> Business Guide</div>
        <div class="cgc-desc">Browse businesses, professionals, organizations and institutions listed in <?php echo esc_html( $vs2_c ); ?>.</div>
      </a>
      <?php endforeach; ?>
      <a href="<?php echo esc_url( vs2_resource_base_url() ); ?>" class="country-guide-card" style="border-left-color:rgba(224,32,32,.4);background:rgba(224,32,32,.05);">
        <div class="cgc-country" style="color:#888;">More countries</div>
        <div class="cgc-title" style="color:#aaa;">Explore all guides &amp; resources</div>
        <div class="cgc-desc" style="color:#555;">Country coverage expands as new listings are added.</div>
      </a>
    </div>
  </section>

  <!-- GET LISTED -->
  <div id="get-listed" class="get-listed-strip">
    <div class="gls-title">Put your venture on the map.</div>
    <div class="gls-sub">Join businesses, professionals, organizations and institutions already on Venture Stori.</div>
    <div class="gls-btns">
      <a href="<?php echo esc_url( get_theme_mod( 'vs2_cta_url', vs2_get_listed_url() ) ); ?>" class="gls-btn-primary">Get Listed on Venture Stori</a>
    </div>
  </div>

</div>

<?php get_footer(); ?>
