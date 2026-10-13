<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $vs2_id       = get_the_ID();
    $vs2_tier     = vs2_institution_tier( $vs2_id );
    $vs2_initials = vs2_initials( get_the_title() );
    $vs2_crest    = has_post_thumbnail() ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
    $vs2_types    = get_the_terms( $vs2_id, 'vs2_institution_type' );
    $vs2_type     = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->name : '';
    $vs2_scope    = get_post_meta( $vs2_id, '_vs2i_scope', true );
    $vs2_country  = get_post_meta( $vs2_id, '_vs2i_country', true );
    $vs2_type_line = trim( implode( ' · ', array_filter( [ $vs2_type, $vs2_scope, $vs2_country ] ) ) );
    $vs2_founded  = get_post_meta( $vs2_id, '_vs2i_founded', true );
    $vs2_students = get_post_meta( $vs2_id, '_vs2i_student_count', true );
    $vs2_colleges = get_post_meta( $vs2_id, '_vs2i_college_count', true );
    $vs2_alumni   = get_post_meta( $vs2_id, '_vs2i_alumni_listed', true );
    $vs2_claim_desc = get_post_meta( $vs2_id, '_vs2i_claim_desc', true );
    $vs2_verify_text = get_post_meta( $vs2_id, '_vs2i_verification_text', true );
    $vs2_departments = vs2_institution_departments( $vs2_id );
    $vs2_website  = get_post_meta( $vs2_id, '_vs2i_website', true );
    $vs2_email    = get_post_meta( $vs2_id, '_vs2i_email', true );
    $vs2_phone    = get_post_meta( $vs2_id, '_vs2i_phone', true );
    $vs2_alumni_list = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2i_notable_alumni_ids', true ) );
    $vs2_orgs        = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2i_associated_orgs_ids', true ) );
    $vs2_related_ins = vs2_get_related_posts( get_post_meta( $vs2_id, '_vs2i_related_institutions_ids', true ) );
    $vs2_type_slug = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->slug : '';

    $vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
    $vs2_bc[] = [ 'label' => 'Institutions', 'url' => vs2_ins_base_url() ];
    if ( $vs2_type ) $vs2_bc[] = [ 'label' => $vs2_type, 'url' => vs2_ins_filter_url( vs2_ins_base_url(), [ 'type' => $vs2_type_slug ] ) ];
    if ( $vs2_country ) $vs2_bc[] = [ 'label' => $vs2_country, 'url' => null ];
    $vs2_bc[] = [ 'label' => get_the_title(), 'url' => null ];
?>
  <?php vs2_breadcrumb( $vs2_bc ); ?>
<div class="ins-profile-hero">
  <a href="<?php echo esc_url( vs2_ins_base_url() ); ?>" class="back-btn" style="padding-bottom:20px;display:block;">← Institution Directory</a>
  <div class="vs2-ins-profile-inner">
    <div class="ins-profile-header">
      <div class="ins-profile-crest" style="<?php echo $vs2_crest ? "background-image:url('" . esc_url( $vs2_crest ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_crest ? '' : esc_html( $vs2_initials ); ?></div>
      <div>
        <div class="ins-profile-name"><?php the_title(); ?></div>
        <?php if ( $vs2_type_line ) : ?><div class="ins-profile-type"><?php echo esc_html( $vs2_type_line ); ?></div><?php endif; ?>
        <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
          <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
          <?php if ( $vs2_founded ) : ?><div class="vs2-badge-meta">Est. <?php echo esc_html( $vs2_founded ); ?></div><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="ins-stats">
      <?php if ( $vs2_students ) : ?><div class="ins-stat"><div class="n"><?php echo esc_html( $vs2_students ); ?></div><div class="l">Students</div></div><?php endif; ?>
      <?php if ( $vs2_colleges ) : ?><div class="ins-stat"><div class="n"><?php echo esc_html( $vs2_colleges ); ?></div><div class="l">Colleges</div></div><?php endif; ?>
      <?php if ( $vs2_alumni ) : ?><div class="ins-stat"><div class="n"><?php echo esc_html( $vs2_alumni ); ?></div><div class="l">Alumni listed</div></div><?php endif; ?>
      <?php if ( $vs2_founded ) : ?><div class="ins-stat"><div class="n"><?php echo esc_html( $vs2_founded ); ?></div><div class="l">Founded</div></div><?php endif; ?>
    </div>
  </div>
</div>

<div class="profile-body">
  <div class="profile-layout">
    <div>

      <?php if ( get_the_content() ) : ?>
      <div class="profile-section">
        <h3>Overview</h3>
        <div class="vs2-body"><?php the_content(); ?></div>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_departments ) : ?>
      <div class="profile-section">
        <h3>Colleges &amp; Faculties</h3>
        <ul class="dept-list">
          <?php foreach ( $vs2_departments as $d ) : ?>
            <li><?php echo esc_html( $d['name'] ); ?> <span class="dept-count"><?php echo esc_html( $d['count'] ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php if ( $vs2_alumni_list ) : ?>
      <div class="profile-section">
        <h3>Notable Alumni on Venture Stori</h3>
        <div class="member-grid">
          <?php foreach ( $vs2_alumni_list as $a ) :
              $a_avatar = has_post_thumbnail( $a->ID ) ? get_the_post_thumbnail_url( $a->ID, 'thumbnail' ) : '';
              $a_role   = get_post_meta( $a->ID, '_vs2p_role', true );
          ?>
          <a href="<?php echo esc_url( get_permalink( $a ) ); ?>" class="member-mini">
            <div class="member-mini-avatar" style="<?php echo $a_avatar ? "background-image:url('" . esc_url( $a_avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $a->ID ) ) . ';'; ?>"><?php echo $a_avatar ? '' : esc_html( vs2_initials( get_the_title( $a ) ) ); ?></div>
            <div class="member-mini-name"><?php echo esc_html( get_the_title( $a ) ); ?></div>
            <?php if ( $a_role ) : ?><div class="member-mini-role"><?php echo esc_html( $a_role ); ?></div><?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php
      vs2_rel_section( 'Connected to ' . get_the_title(), [
          [ 'label' => 'Associated Organizations', 'items' => array_map( fn( $o ) => [ 'post' => $o, 'sub' => 'Organization', 'square' => true ], $vs2_orgs ) ],
          [ 'label' => 'Related Institutions', 'items' => array_map( fn( $r ) => [ 'post' => $r, 'sub' => 'Institution', 'square' => true ], $vs2_related_ins ) ],
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

      <?php if ( $vs2_verify_text ) : ?>
      <div class="sidebar-card">
        <h4>Verification</h4>
        <p style="margin:0;"><?php echo esc_html( $vs2_verify_text ); ?></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

  <?php
  $vs2_more_ins_type = array_map( function( $p ) {
      return [ 'url' => get_permalink( $p ), 'type' => 'Institution', 'name' => get_the_title( $p ), 'sub' => get_post_meta( $p->ID, '_vs2i_country', true ) ];
  }, vs2_institutions_by_type( $vs2_id, $vs2_type_slug ) );
  vs2_explore_more_row( $vs2_type ? "More {$vs2_type}" : 'More Institutions', $vs2_more_ins_type );

  $vs2_more_ins_country = array_map( function( $p ) {
      $p_types = get_the_terms( $p->ID, 'vs2_institution_type' );
      return [ 'url' => get_permalink( $p ), 'type' => 'Institution', 'name' => get_the_title( $p ), 'sub' => ( $p_types && ! is_wp_error( $p_types ) ) ? $p_types[0]->name : '' ];
  }, vs2_institutions_by_country( $vs2_id, $vs2_country ) );
  vs2_explore_more_row( $vs2_country ? "Institutions in {$vs2_country}" : '', $vs2_more_ins_country );
  ?>
<?php endwhile; ?>

<?php get_footer(); ?>
