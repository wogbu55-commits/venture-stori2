<?php
/**
 * Institution directory card. Expects to be called inside the loop (the_post() already run).
 */
$vs2_id       = get_the_ID();
$vs2_tier     = vs2_institution_tier( $vs2_id );
$vs2_initials = vs2_initials( get_the_title( $vs2_id ) );
$vs2_crest    = has_post_thumbnail( $vs2_id ) ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
$vs2_types    = get_the_terms( $vs2_id, 'vs2_institution_type' );
$vs2_type     = ( $vs2_types && ! is_wp_error( $vs2_types ) ) ? $vs2_types[0]->name : '';
$vs2_scope    = get_post_meta( $vs2_id, '_vs2i_scope', true );
$vs2_type_line = trim( implode( ' · ', array_filter( [ $vs2_type, $vs2_scope ] ) ) );
$vs2_students = get_post_meta( $vs2_id, '_vs2i_student_count', true );
$vs2_colleges = get_post_meta( $vs2_id, '_vs2i_college_count', true );
$vs2_founded  = get_post_meta( $vs2_id, '_vs2i_founded', true );
$vs2_location = get_post_meta( $vs2_id, '_vs2i_country', true );
?>
<a href="<?php the_permalink(); ?>" class="ins-card">
  <div class="ins-accent"></div>
  <div class="ins-main">
    <div class="ins-header">
      <div class="ins-crest" style="<?php echo $vs2_crest ? "background-image:url('" . esc_url( $vs2_crest ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_crest ? '' : esc_html( $vs2_initials ); ?></div>
      <div class="ins-info"><h4><?php the_title(); ?></h4><?php if ( $vs2_type_line ) : ?><div class="ins-type"><?php echo esc_html( $vs2_type_line ); ?></div><?php endif; ?></div>
    </div>
    <?php if ( get_the_excerpt() ) : ?><div class="ins-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></div><?php endif; ?>
    <?php if ( $vs2_colleges || $vs2_students || $vs2_founded ) : ?>
    <div class="ins-chips">
      <?php if ( $vs2_colleges ) : ?><span class="ins-chip"><?php echo esc_html( $vs2_colleges ); ?></span><?php endif; ?>
      <?php if ( $vs2_students ) : ?><span class="ins-chip"><?php echo esc_html( $vs2_students ); ?> Students</span><?php endif; ?>
      <?php if ( $vs2_founded ) : ?><span class="ins-chip">Est. <?php echo esc_html( $vs2_founded ); ?></span><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="ins-footer">
      <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
      <div style="font-size:11px;color:var(--grey);"><?php echo esc_html( $vs2_location ); ?></div>
    </div>
  </div>
</a>
