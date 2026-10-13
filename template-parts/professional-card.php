<?php
/**
 * Professional directory card. Expects to be called inside the loop (the_post() already run).
 */
$vs2_id       = get_the_ID();
$vs2_tier     = vs2_professional_tier( $vs2_id );
$vs2_initials = vs2_initials( get_the_title( $vs2_id ) );
$vs2_avatar   = has_post_thumbnail( $vs2_id ) ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
$vs2_role     = get_post_meta( $vs2_id, '_vs2p_role', true );
$vs2_org      = get_post_meta( $vs2_id, '_vs2p_org', true );
$vs2_location = get_post_meta( $vs2_id, '_vs2p_location', true );
$vs2_tags     = array_slice( vs2_professional_tags( $vs2_id ), 0, 3 );
?>
<a href="<?php the_permalink(); ?>" class="pro-card">
  <div class="pro-avatar" style="<?php echo $vs2_avatar ? "background-image:url('" . esc_url( $vs2_avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_avatar ? '' : esc_html( $vs2_initials ); ?></div>
  <h4><?php the_title(); ?></h4>
  <?php if ( $vs2_role ) : ?><div class="pro-role"><?php echo esc_html( $vs2_role ); ?></div><?php endif; ?>
  <?php if ( $vs2_org ) : ?><div class="pro-org"><?php echo esc_html( $vs2_org ); ?></div><?php endif; ?>
  <?php if ( $vs2_tags ) : ?>
  <div class="pro-tags">
    <?php foreach ( $vs2_tags as $tag ) : ?><span class="pro-tag"><?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
  </div>
  <?php endif; ?>
  <div class="pro-footer">
    <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
    <div style="font-size:11px;color:var(--grey);"><?php echo esc_html( $vs2_location ); ?></div>
  </div>
</a>
