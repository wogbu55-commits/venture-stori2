<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $vs2_id       = get_the_ID();
    $vs2_tier     = vs2_organization_tier( $vs2_id );
    $vs2_initials = vs2_initials( get_the_title() );
    $vs2_logo     = has_post_thumbnail() ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
    $vs2_types    = get_the_terms( $vs2_id, 'vs2_org_type' );
    $vs2_type     = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->name : '';
    $vs2_scope    = get_post_meta( $vs2_id, '_vs2o_scope', true );
    $vs2_type_line = trim( implode( ' · ', array_filter( [ $vs2_type, $vs2_scope ] ) ) );
    $vs2_founded  = get_post_meta( $vs2_id, '_vs2o_founded', true );
    $vs2_members  = get_post_meta( $vs2_id, '_vs2o_member_count', true );
    $vs2_chapters = get_post_meta( $vs2_id, '_vs2o_chapter_count', true );
    $vs2_profiles = get_post_meta( $vs2_id, '_vs2o_profiles_listed', true );
    $vs2_membership_desc = get_post_meta( $vs2_id, '_vs2o_membership_desc', true );
    $vs2_join_url    = get_post_meta( $vs2_id, '_vs2o_join_url', true );
    $vs2_partner_url = get_post_meta( $vs2_id, '_vs2o_partner_url', true );
    $vs2_chapter_list = vs2_organization_lines( $vs2_id, '_vs2o_chapters' );
    $vs2_events   = vs2_organization_events( $vs2_id );
    $vs2_website  = get_post_meta( $vs2_id, '_vs2o_website', true );
    $vs2_email    = get_post_meta( $vs2_id, '_vs2o_email', true );
    $vs2_phone    = get_post_meta( $vs2_id, '_vs2o_phone', true );
    $vs2_members_list = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2o_featured_members_ids', true ) );
    $vs2_institutions  = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2o_affiliated_institutions_ids', true ) );
    $vs2_country  = get_post_meta( $vs2_id, '_vs2o_country', true );
    $vs2_type_slug = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->slug : '';

    $vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
    $vs2_bc[] = [ 'label' => 'Organizations', 'url' => vs2_org_base_url() ];
    if ( $vs2_type ) $vs2_bc[] = [ 'label' => $vs2_type, 'url' => vs2_org_filter_url( vs2_org_base_url(), [ 'type' => $vs2_type_slug ] ) ];
    if ( $vs2_country ) $vs2_bc[] = [ 'label' => $vs2_country, 'url' => null ];
    $vs2_bc[] = [ 'label' => get_the_title(), 'url' => null ];
?>
  <?php vs2_breadcrumb( $vs2_bc ); ?>
<div style="background:#000;padding:0 28px;border-bottom:1px solid var(--vs2-border);">
  <a href="<?php echo esc_url( vs2_org_base_url() ); ?>" class="back-btn">← Organization Directory</a>
</div>

<div style="background:var(--vs2-black2);">
  <div class="org-banner">
    <div class="org-logo-wrap">
      <div class="org-profile-logo" style="<?php echo $vs2_logo ? "background-image:url('" . esc_url( $vs2_logo ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_logo ? '' : esc_html( $vs2_initials ); ?></div>
    </div>
  </div>
  <div class="org-profile-info-row">
    <div class="vs2-org-profile-inner">
      <div class="org-profile-name"><?php the_title(); ?></div>
      <?php if ( $vs2_type_line ) : ?><div class="org-profile-type"><?php echo esc_html( $vs2_type_line ); ?></div><?php endif; ?>
      <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
        <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
        <?php if ( $vs2_founded ) : ?><div class="vs2-badge-meta">Est. <?php echo esc_html( $vs2_founded ); ?></div><?php endif; ?>
      </div>
      <div class="org-stats-row">
        <?php if ( $vs2_members ) : ?><div class="org-stat-big"><div class="num"><?php echo esc_html( $vs2_members ); ?></div><div class="lbl">Members</div></div><?php endif; ?>
        <?php if ( $vs2_chapters ) : ?><div class="org-stat-big"><div class="num"><?php echo esc_html( $vs2_chapters ); ?></div><div class="lbl">Chapters</div></div><?php endif; ?>
        <?php if ( $vs2_profiles ) : ?><div class="org-stat-big"><div class="num"><?php echo esc_html( $vs2_profiles ); ?></div><div class="lbl">Profiles listed</div></div><?php endif; ?>
        <?php if ( $vs2_founded ) : ?><div class="org-stat-big"><div class="num"><?php echo esc_html( $vs2_founded ); ?></div><div class="lbl">Founded</div></div><?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="profile-body">
  <div class="profile-layout">
    <div>

      <?php if ( get_the_content() ) : ?>
      <div class="profile-section">
        <h3>About</h3>
        <div class="vs2-body"><?php the_content(); ?></div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_members_list ) : ?>
      <div class="profile-section">
        <h3>Featured Members</h3>
        <div class="member-grid">
          <?php foreach ( $vs2_members_list as $m ) :
              $m_avatar = has_post_thumbnail( $m->ID ) ? get_the_post_thumbnail_url( $m->ID, 'thumbnail' ) : '';
              $m_role   = get_post_meta( $m->ID, '_vs2p_role', true );
          ?>
          <a href="<?php echo esc_url( get_permalink( $m ) ); ?>" class="member-mini">
            <div class="member-mini-avatar" style="<?php echo $m_avatar ? "background-image:url('" . esc_url( $m_avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $m->ID ) ) . ';'; ?>"><?php echo $m_avatar ? '' : esc_html( vs2_initials( get_the_title( $m ) ) ); ?></div>
            <div class="member-mini-name"><?php echo esc_html( get_the_title( $m ) ); ?></div>
            <?php if ( $m_role ) : ?><div class="member-mini-role"><?php echo esc_html( $m_role ); ?></div><?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_chapter_list ) : ?>
      <div class="profile-section">
        <h3>State Chapters</h3>
        <div class="chapter-grid">
          <?php foreach ( $vs2_chapter_list as $c ) : ?><div class="chapter-item"><?php echo esc_html( $c ); ?></div><?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php
      vs2_rel_section( 'Connected to ' . get_the_title(), [
          [ 'label' => 'Affiliated Institutions', 'items' => array_map( fn( $inst ) => [ 'post' => $inst, 'sub' => 'Institution', 'square' => true ], $vs2_institutions ) ],
      ] );
      ?>

    </div>

    <div>
      <?php if ( $vs2_membership_desc || $vs2_join_url || $vs2_partner_url ) : ?>
      <div class="sidebar-card">
        <h4>Membership</h4>
        <?php if ( $vs2_membership_desc ) : ?><p><?php echo esc_html( $vs2_membership_desc ); ?></p><?php endif; ?>
        <?php if ( $vs2_join_url ) : ?><a href="<?php echo esc_url( $vs2_join_url ); ?>" class="sidebar-cta">Join <?php echo esc_html( get_the_title() ); ?></a><?php endif; ?>
        <?php if ( $vs2_partner_url ) : ?><a href="<?php echo esc_url( $vs2_partner_url ); ?>" class="sidebar-cta-ghost">Partner with us</a><?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_events ) : ?>
      <div class="sidebar-card">
        <h4>Upcoming Events</h4>
        <?php foreach ( $vs2_events as $e ) : ?>
          <div class="vs2-sidebar-event"><div class="vs2-sidebar-event-title"><?php echo esc_html( $e['title'] ); ?></div><div class="vs2-sidebar-event-meta"><?php echo esc_html( $e['meta'] ); ?></div></div>
        <?php endforeach; ?>
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
  $vs2_more_type = array_map( function( $p ) {
      return [ 'url' => get_permalink( $p ), 'type' => 'Organization', 'name' => get_the_title( $p ), 'sub' => get_post_meta( $p->ID, '_vs2o_country', true ) ];
  }, vs2_organizations_by_type( $vs2_id, $vs2_type_slug ) );
  vs2_explore_more_row( $vs2_type ? "More {$vs2_type}" : 'More Organizations', $vs2_more_type );

  $vs2_more_org_country = array_map( function( $p ) {
      $p_types = get_the_terms( $p->ID, 'vs2_org_type' );
      return [ 'url' => get_permalink( $p ), 'type' => 'Organization', 'name' => get_the_title( $p ), 'sub' => ( $p_types && ! is_wp_error( $p_types ) ) ? $p_types[0]->name : '' ];
  }, vs2_organizations_by_country( $vs2_id, $vs2_country ) );
  vs2_explore_more_row( $vs2_country ? "Organizations in {$vs2_country}" : '', $vs2_more_org_country );
  ?>
<?php endwhile; ?>

<?php get_footer(); ?>
