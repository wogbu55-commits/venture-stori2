<?php get_header(); ?>

<div class="profile-body">
<?php while ( have_posts() ) : the_post();
    $vs2_id       = get_the_ID();
    $vs2_tier     = vs2_tier( $vs2_id );
    $vs2_initials = vs2_initials( get_the_title() );
    $vs2_logo     = has_post_thumbnail() ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
    $vs2_location = get_post_meta( $vs2_id, '_vs2_location', true );
    $vs2_subcat   = get_post_meta( $vs2_id, '_vs2_subcategory', true );
    $vs2_founded  = get_post_meta( $vs2_id, '_vs2_founded', true );
    $vs2_team     = get_post_meta( $vs2_id, '_vs2_team_size', true );
    $vs2_website  = get_post_meta( $vs2_id, '_vs2_website', true );
    $vs2_email    = get_post_meta( $vs2_id, '_vs2_email', true );
    $vs2_phone    = get_post_meta( $vs2_id, '_vs2_phone', true );
    $vs2_wa       = get_post_meta( $vs2_id, '_vs2_whatsapp', true );
    $vs2_hours    = get_post_meta( $vs2_id, '_vs2_hours', true );
    $vs2_address  = get_post_meta( $vs2_id, '_vs2_address', true );
    $vs2_industries = get_the_terms( $vs2_id, 'vs2_industry' );
    $vs2_industry   = ( $vs2_industries && ! is_wp_error( $vs2_industries ) ) ? $vs2_industries[0]->name : '';
    $vs2_industry_slug = ( $vs2_industries && ! is_wp_error( $vs2_industries ) ) ? $vs2_industries[0]->slug : '';
    $vs2_cat_line = trim( implode( ' · ', array_filter( [ $vs2_industry, $vs2_subcat ] ) ) );
    $vs2_products = vs2_products( $vs2_id );
    $vs2_serves   = vs2_serves( $vs2_id );
    $vs2_tl       = vs2_timeline( $vs2_id );
    $vs2_related  = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2_related_business_ids', true ) );
    $vs2_assoc    = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2_associations_ids', true ) );
    $vs2_socials  = [];
    foreach ( vs2_profile_social_fields() as $vs2_skey => $vs2_slabel ) {
        $vs2_surl = get_post_meta( $vs2_id, $vs2_skey, true );
        if ( $vs2_surl ) $vs2_socials[] = [ 'label' => $vs2_slabel, 'url' => $vs2_surl ];
    }
    $vs2_ig = get_post_meta( $vs2_id, '_vs2_instagram', true );
    $vs2_fb = get_post_meta( $vs2_id, '_vs2_facebook', true );
    $vs2_gallery = array_filter( array_map(
        fn( $vs2_photo_id ) => $vs2_photo_id ? wp_get_attachment_image_url( $vs2_photo_id, 'large' ) : '',
        [
            get_post_meta( $vs2_id, '_vs2_photo_1', true ),
            get_post_meta( $vs2_id, '_vs2_photo_2', true ),
            get_post_meta( $vs2_id, '_vs2_photo_3', true ),
        ]
    ) );

    $vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
    $vs2_bc[] = [ 'label' => 'Businesses', 'url' => get_post_type_archive_link( 'profile' ) ];
    if ( $vs2_industry ) $vs2_bc[] = [ 'label' => $vs2_industry, 'url' => vs2_filter_url( vs2_directory_base_url(), [ 'ind' => $vs2_industry_slug ] ) ];
    if ( $vs2_location ) $vs2_bc[] = [ 'label' => $vs2_location, 'url' => null ];
    $vs2_bc[] = [ 'label' => get_the_title(), 'url' => null ];
?>
  <?php vs2_breadcrumb( $vs2_bc ); ?>
  <div style="background:#000;padding:0 28px;border-bottom:1px solid var(--vs2-border);">
    <a href="<?php echo esc_url( get_post_type_archive_link( 'profile' ) ); ?>" class="back-btn">← Business Directory</a>
  </div>
  <div class="profile-layout">
    <div>

      <div class="biz-profile-card">
        <div class="biz-profile-top">
          <div class="biz-profile-logo" style="<?php echo $vs2_logo ? "background-image:url('" . esc_url( $vs2_logo ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_logo ? '' : esc_html( $vs2_initials ); ?></div>
          <div>
            <div class="biz-profile-name"><?php the_title(); ?></div>
            <?php if ( $vs2_cat_line ) : ?><div class="biz-profile-cat"><?php echo esc_html( $vs2_cat_line ); ?></div><?php endif; ?>
            <div class="biz-profile-badges">
              <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
              <?php if ( $vs2_location ) : ?><div class="vs2-badge-meta">📍 <?php echo esc_html( $vs2_location ); ?></div><?php endif; ?>
              <?php if ( $vs2_founded ) : ?><div class="vs2-badge-meta">Est. <?php echo esc_html( $vs2_founded ); ?></div><?php endif; ?>
            </div>
          </div>
        </div>
        <?php if ( get_the_content() ) : ?>
        <div style="font-size:14px;color:#888;line-height:1.75;border-top:1px solid var(--vs2-border);padding-top:20px;">
          <?php the_content(); ?>
        </div>
        <?php endif; ?>
      </div>

      <?php if ( $vs2_gallery ) : ?>
      <div class="profile-section">
        <h3>Photos</h3>
        <div class="vs2-profile-gallery">
          <?php foreach ( $vs2_gallery as $vs2_photo ) : ?>
            <a href="<?php echo esc_url( $vs2_photo ); ?>" target="_blank" rel="noopener" class="vs2-profile-gallery-item" style="background-image:url('<?php echo esc_url( $vs2_photo ); ?>');"></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_products ) : ?>
      <div class="profile-section">
        <h3>Products &amp; Services</h3>
        <div class="profile-tags">
          <?php foreach ( $vs2_products as $p ) : ?><span class="profile-tag"><?php echo esc_html( $p ); ?></span><?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_serves ) : ?>
      <div class="profile-section">
        <h3>Serves</h3>
        <div class="profile-tags">
          <?php foreach ( $vs2_serves as $s ) : ?><span class="profile-tag"><?php echo esc_html( $s ); ?></span><?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="profile-section">
        <h3>Details</h3>
        <?php if ( $vs2_industry ) : ?><div class="detail-row"><div class="detail-label">Category</div><div class="detail-val"><?php echo esc_html( $vs2_industry ); ?></div></div><?php endif; ?>
        <?php if ( $vs2_subcat ) : ?><div class="detail-row"><div class="detail-label">Sub-category</div><div class="detail-val"><?php echo esc_html( $vs2_subcat ); ?></div></div><?php endif; ?>
        <?php if ( $vs2_founded ) : ?><div class="detail-row"><div class="detail-label">Founded</div><div class="detail-val"><?php echo esc_html( $vs2_founded ); ?></div></div><?php endif; ?>
        <?php if ( $vs2_location || $vs2_address ) : ?><div class="detail-row"><div class="detail-label">Location</div><div class="detail-val"><?php echo esc_html( $vs2_address ?: $vs2_location ); ?></div></div><?php endif; ?>
        <?php if ( $vs2_hours ) : ?><div class="detail-row"><div class="detail-label">Hours</div><div class="detail-val"><?php echo esc_html( $vs2_hours ); ?></div></div><?php endif; ?>
        <?php if ( $vs2_website ) : ?><div class="detail-row"><div class="detail-label">Website</div><div class="detail-val"><a href="<?php echo esc_url( $vs2_website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', $vs2_website ) ); ?></a></div></div><?php endif; ?>
        <?php if ( $vs2_wa ) : ?><div class="detail-row"><div class="detail-label">WhatsApp</div><div class="detail-val"><a href="<?php echo esc_url( $vs2_wa ); ?>" target="_blank" rel="noopener">Message on WhatsApp</a></div></div><?php endif; ?>
        <?php if ( $vs2_team ) : ?><div class="detail-row"><div class="detail-label">Team size</div><div class="detail-val"><?php echo esc_html( $vs2_team ); ?></div></div><?php endif; ?>
      </div>

      <?php if ( $vs2_tl ) : ?>
      <div class="profile-section">
        <h3>Timeline</h3>
        <?php foreach ( $vs2_tl as $row ) : ?>
          <div class="detail-row"><div class="detail-label"><?php echo esc_html( $row['year'] ); ?></div><div class="detail-val"><?php echo esc_html( $row['text'] ); ?></div></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php
      vs2_rel_section( 'Connected to ' . get_the_title(), [
          [ 'label' => 'Organizations', 'items' => array_map( fn( $o ) => [ 'post' => $o, 'sub' => 'Organization', 'square' => true ], $vs2_assoc ) ],
          [ 'label' => 'Related Businesses', 'items' => array_map( fn( $r ) => [ 'post' => $r, 'sub' => 'Business' ], $vs2_related ) ],
      ] );
      ?>

    </div>

    <div>
      <div class="sidebar-card">
        <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=<?php echo esc_attr( 'Reporting an error on ' . get_the_title() ); ?>" class="sidebar-cta-ghost">Report an error</a>
      </div>

      <?php if ( $vs2_phone || $vs2_email || $vs2_website ) : ?>
      <div class="sidebar-card">
        <h4>Contact</h4>
        <?php if ( $vs2_phone ) : ?><div class="vs2-sidebar-contact-row">📞 <?php echo esc_html( $vs2_phone ); ?></div><?php endif; ?>
        <?php if ( $vs2_email ) : ?><div class="vs2-sidebar-contact-row">✉️ <a href="mailto:<?php echo esc_attr( $vs2_email ); ?>" style="color:var(--vs2-orange);"><?php echo esc_html( $vs2_email ); ?></a></div><?php endif; ?>
        <?php if ( $vs2_website ) : ?><div class="vs2-sidebar-contact-row">🌐 <a href="<?php echo esc_url( $vs2_website ); ?>" target="_blank" rel="noopener" style="color:var(--vs2-orange);"><?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', $vs2_website ) ); ?></a></div><?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_ig || $vs2_fb || $vs2_socials ) : ?>
      <div class="sidebar-card">
        <h4>Social</h4>
        <div class="social-row">
          <?php if ( $vs2_ig ) : ?><a href="<?php echo esc_url( $vs2_ig ); ?>" class="soc-btn">Instagram</a><?php endif; ?>
          <?php if ( $vs2_fb ) : ?><a href="<?php echo esc_url( $vs2_fb ); ?>" class="soc-btn">Facebook</a><?php endif; ?>
          <?php foreach ( $vs2_socials as $s ) : ?><a href="<?php echo esc_url( $s['url'] ); ?>" class="soc-btn"><?php echo esc_html( $s['label'] ); ?></a><?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="sidebar-card">
        <h4>Verification</h4>
        <p style="margin:0;">
          <?php
          $checks = [];
          if ( $vs2_phone || $vs2_wa )  $checks[] = 'Phone';
          if ( $vs2_email )             $checks[] = 'Email';
          if ( $vs2_location )          $checks[] = 'Location';
          echo $checks ? esc_html( implode( ' and ', $checks ) . ' confirmed. ' ) : 'Awaiting verification. ';
          ?>
          Last checked <?php echo esc_html( get_the_modified_date( 'M Y' ) ); ?>.
        </p>
      </div>
    </div>
  </div>

  <?php
  $vs2_more_industry = array_map( function( $p ) {
      return [ 'url' => get_permalink( $p ), 'type' => 'Business', 'name' => get_the_title( $p ), 'sub' => get_post_meta( $p->ID, '_vs2_location', true ) ];
  }, vs2_profiles_by_industry( $vs2_id, $vs2_industry_slug ) );
  vs2_explore_more_row( $vs2_industry ? "More in {$vs2_industry}" : 'More Businesses', $vs2_more_industry );

  $vs2_more_location = array_map( function( $p ) {
      $p_industries = get_the_terms( $p->ID, 'vs2_industry' );
      $p_industry   = ( $p_industries && ! is_wp_error( $p_industries ) ) ? $p_industries[0]->name : '';
      return [ 'url' => get_permalink( $p ), 'type' => 'Business', 'name' => get_the_title( $p ), 'sub' => $p_industry ];
  }, vs2_profiles_by_location( $vs2_id, $vs2_location ) );
  vs2_explore_more_row( $vs2_location ? "Businesses in {$vs2_location}" : '', $vs2_more_location );
  ?>
<?php endwhile; ?>
</div>

<?php get_footer(); ?>
