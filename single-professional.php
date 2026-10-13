<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $vs2_id       = get_the_ID();
    $vs2_tier     = vs2_professional_tier( $vs2_id );
    $vs2_initials = vs2_initials( get_the_title() );
    $vs2_avatar   = has_post_thumbnail() ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
    $vs2_role     = get_post_meta( $vs2_id, '_vs2p_role', true );
    $vs2_org      = get_post_meta( $vs2_id, '_vs2p_org', true );
    $vs2_location = get_post_meta( $vs2_id, '_vs2p_location', true );
    $vs2_credentials = get_post_meta( $vs2_id, '_vs2p_credentials_short', true );
    $vs2_experience  = get_post_meta( $vs2_id, '_vs2p_experience_years', true );
    $vs2_website  = get_post_meta( $vs2_id, '_vs2p_website', true );
    $vs2_email    = get_post_meta( $vs2_id, '_vs2p_email', true );
    $vs2_phone    = get_post_meta( $vs2_id, '_vs2p_phone', true );
    $vs2_tags     = vs2_professional_tags( $vs2_id );
    $vs2_quals    = vs2_professional_lines( $vs2_id, '_vs2p_qualifications' );
    $vs2_pubs     = vs2_professional_lines( $vs2_id, '_vs2p_publications' );
    $vs2_tl       = vs2_professional_timeline( $vs2_id );
    $vs2_lead     = vs2_professional_leadership( $vs2_id );
    $vs2_orgs     = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2p_organizations_ids', true ) );
    $vs2_fields   = get_the_terms( $vs2_id, 'vs2_pro_field' );
    $vs2_field      = ( $vs2_fields && ! is_wp_error( $vs2_fields ) ) ? $vs2_fields[0]->name : '';
    $vs2_field_slug = ( $vs2_fields && ! is_wp_error( $vs2_fields ) ) ? $vs2_fields[0]->slug : '';

    $vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
    $vs2_bc[] = [ 'label' => 'Professionals', 'url' => vs2_pro_base_url() ];
    if ( $vs2_field ) $vs2_bc[] = [ 'label' => $vs2_field, 'url' => vs2_pro_filter_url( vs2_pro_base_url(), [ 'field' => $vs2_field_slug ] ) ];
    if ( $vs2_location ) $vs2_bc[] = [ 'label' => $vs2_location, 'url' => null ];
    $vs2_bc[] = [ 'label' => get_the_title(), 'url' => null ];
?>
  <?php vs2_breadcrumb( $vs2_bc ); ?>
<div class="pro-profile-hero">
  <a href="<?php echo esc_url( vs2_pro_base_url() ); ?>" class="back-btn" style="padding-bottom:20px;">← Professional Directory</a>
  <div class="vs2-pro-profile-inner">
    <div class="pro-profile-avatar" style="<?php echo $vs2_avatar ? "background-image:url('" . esc_url( $vs2_avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_avatar ? '' : esc_html( $vs2_initials ); ?></div>
    <div>
      <div class="pro-profile-name"><?php the_title(); ?></div>
      <?php if ( $vs2_role ) : ?><div class="pro-profile-role"><?php echo esc_html( $vs2_role ); ?></div><?php endif; ?>
      <?php if ( $vs2_org || $vs2_location ) : ?><div class="pro-profile-org"><?php echo esc_html( trim( implode( ' · ', array_filter( [ $vs2_org, $vs2_location ] ) ) ) ); ?></div><?php endif; ?>
      <div class="pro-profile-chips">
        <div class="pro-chip"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
        <?php if ( $vs2_credentials ) : ?><div class="pro-chip"><?php echo esc_html( $vs2_credentials ); ?></div><?php endif; ?>
        <?php if ( $vs2_experience ) : ?><div class="pro-chip"><?php echo esc_html( $vs2_experience ); ?></div><?php endif; ?>
        <?php if ( $vs2_pubs ) : ?><div class="pro-chip"><?php echo esc_html( count( $vs2_pubs ) ); ?> publication<?php echo count( $vs2_pubs ) === 1 ? '' : 's'; ?></div><?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="profile-body">
  <div class="profile-layout">
    <div>

      <?php if ( get_the_content() ) : ?>
      <div class="profile-section">
        <h3>Biography</h3>
        <div class="vs2-body"><?php the_content(); ?></div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_tl ) : ?>
      <div class="profile-section">
        <h3>Career Timeline</h3>
        <?php foreach ( $vs2_tl as $i => $row ) : ?>
          <div class="timeline-item">
            <div class="tl-dot-wrap"><div class="tl-dot"></div><?php if ( $i < count( $vs2_tl ) - 1 ) : ?><div class="tl-line"></div><?php endif; ?></div>
            <div class="tl-content">
              <h5><?php echo esc_html( $row['title'] ); ?></h5>
              <div class="tl-sub"><?php echo esc_html( $row['sub'] ); ?></div>
              <div class="tl-year"><?php echo esc_html( $row['year'] ); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_tags ) : ?>
      <div class="profile-section">
        <h3>Skills &amp; Specialties</h3>
        <div class="profile-tags">
          <?php foreach ( $vs2_tags as $tag ) : ?><span class="profile-tag"><?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_lead ) : ?>
      <div class="profile-section">
        <h3>Leadership &amp; Volunteer</h3>
        <?php foreach ( $vs2_lead as $row ) : ?>
          <div class="detail-row"><div class="detail-label"><?php echo esc_html( $row['when'] ); ?></div><div class="detail-val"><?php echo esc_html( $row['text'] ); ?></div></div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php
      vs2_rel_section( 'Connected to ' . get_the_title(), [
          [ 'label' => 'Organizations', 'items' => array_map( fn( $o ) => [ 'post' => $o, 'sub' => 'Organization', 'square' => true ], $vs2_orgs ) ],
      ] );
      ?>

    </div>

    <div>
      <div class="sidebar-card">
        <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>?subject=<?php echo esc_attr( 'Request removal: ' . get_the_title() ); ?>" class="sidebar-cta-ghost">Request removal</a>
      </div>

      <?php if ( $vs2_quals ) : ?>
      <div class="sidebar-card">
        <h4>Qualifications</h4>
        <?php foreach ( $vs2_quals as $q ) : ?><div class="vs2-sidebar-contact-row"><?php echo esc_html( $q ); ?></div><?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_pubs ) : ?>
      <div class="sidebar-card">
        <h4>Publications</h4>
        <?php foreach ( $vs2_pubs as $p ) : ?><p><?php echo esc_html( $p ); ?></p><?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_phone || $vs2_email || $vs2_website ) : ?>
      <div class="sidebar-card">
        <h4>Contact</h4>
        <?php if ( $vs2_phone ) : ?><div class="vs2-sidebar-contact-row">📞 <?php echo esc_html( $vs2_phone ); ?></div><?php endif; ?>
        <?php if ( $vs2_email ) : ?><div class="vs2-sidebar-contact-row">✉️ <a href="mailto:<?php echo esc_attr( $vs2_email ); ?>" style="color:var(--vs2-orange);"><?php echo esc_html( $vs2_email ); ?></a></div><?php endif; ?>
        <?php if ( $vs2_website ) : ?><div class="vs2-sidebar-contact-row">🌐 <a href="<?php echo esc_url( $vs2_website ); ?>" target="_blank" rel="noopener" style="color:var(--vs2-orange);"><?php echo esc_html( preg_replace( '#^https?://(www\.)?#', '', $vs2_website ) ); ?></a></div><?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

  <?php
  $vs2_more_field = array_map( function( $p ) {
      return [ 'url' => get_permalink( $p ), 'type' => 'Professional', 'name' => get_the_title( $p ), 'sub' => get_post_meta( $p->ID, '_vs2p_location', true ) ];
  }, vs2_professionals_by_field( $vs2_id, $vs2_field_slug ) );
  vs2_explore_more_row( $vs2_field ? "More {$vs2_field} Professionals" : 'More Professionals', $vs2_more_field );

  $vs2_more_pro_location = array_map( function( $p ) {
      return [ 'url' => get_permalink( $p ), 'type' => 'Professional', 'name' => get_the_title( $p ), 'sub' => get_post_meta( $p->ID, '_vs2p_role', true ) ];
  }, vs2_professionals_by_location( $vs2_id, $vs2_location ) );
  vs2_explore_more_row( $vs2_location ? "Professionals in {$vs2_location}" : '', $vs2_more_pro_location );
  ?>
<?php endwhile; ?>

<?php get_footer(); ?>
