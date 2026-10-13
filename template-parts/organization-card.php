<?php
/**
 * Organization directory card. Expects to be called inside the loop (the_post() already run).
 */
$vs2_id       = get_the_ID();
$vs2_tier     = vs2_organization_tier( $vs2_id );
$vs2_initials = vs2_initials( get_the_title( $vs2_id ) );
$vs2_logo     = has_post_thumbnail( $vs2_id ) ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
$vs2_types    = get_the_terms( $vs2_id, 'vs2_org_type' );
$vs2_type     = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->name : '';
$vs2_scope    = get_post_meta( $vs2_id, '_vs2o_scope', true );
$vs2_type_line = trim( implode( ' · ', array_filter( [ $vs2_type, $vs2_scope ] ) ) );
$vs2_members  = get_post_meta( $vs2_id, '_vs2o_member_count', true );
$vs2_chapters = get_post_meta( $vs2_id, '_vs2o_chapter_count', true );
$vs2_founded  = get_post_meta( $vs2_id, '_vs2o_founded', true );
?>
<a href="<?php the_permalink(); ?>" class="org-card">
  <div class="org-stripe"></div>
  <div class="org-card-body">
    <div class="org-top">
      <div class="org-logo" style="<?php echo $vs2_logo ? "background-image:url('" . esc_url( $vs2_logo ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_logo ? '' : esc_html( $vs2_initials ); ?></div>
      <div class="org-info"><h4><?php the_title(); ?></h4><?php if ( $vs2_type_line ) : ?><div class="org-type"><?php echo esc_html( $vs2_type_line ); ?></div><?php endif; ?></div>
    </div>
    <div class="badge badge-listed" style="margin-bottom:12px;"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
    <?php if ( get_the_excerpt() ) : ?><div class="org-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></div><?php endif; ?>
    <?php if ( $vs2_members || $vs2_chapters || $vs2_founded ) : ?>
    <div class="org-stats">
      <?php if ( $vs2_members ) : ?><div class="org-stat"><div class="org-stat-num"><?php echo esc_html( $vs2_members ); ?></div><div class="org-stat-label">Members</div></div><?php endif; ?>
      <?php if ( $vs2_chapters ) : ?><div class="org-stat"><div class="org-stat-num"><?php echo esc_html( $vs2_chapters ); ?></div><div class="org-stat-label">Chapters</div></div><?php endif; ?>
      <?php if ( $vs2_founded ) : ?><div class="org-stat"><div class="org-stat-num"><?php echo esc_html( $vs2_founded ); ?></div><div class="org-stat-label">Founded</div></div><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</a>
