<?php
/**
 * Business directory card. Expects to be called inside the loop (the_post() already run).
 */
$vs2_id       = get_the_ID();
$vs2_tier     = vs2_tier( $vs2_id );
$vs2_initials = vs2_initials( get_the_title( $vs2_id ) );
$vs2_logo     = has_post_thumbnail( $vs2_id ) ? get_the_post_thumbnail_url( $vs2_id, 'thumbnail' ) : '';
$vs2_location = get_post_meta( $vs2_id, '_vs2_location', true );
$vs2_subcat   = get_post_meta( $vs2_id, '_vs2_subcategory', true );
$vs2_industries = get_the_terms( $vs2_id, 'vs2_industry' );
$vs2_industry   = ( $vs2_industries && ! is_wp_error( $vs2_industries ) ) ? $vs2_industries[0]->name : '';
$vs2_cat_line = trim( implode( ' · ', array_filter( [ $vs2_industry, $vs2_subcat ] ) ) );
?>
<a href="<?php the_permalink(); ?>" class="biz-card">
  <div class="bc-top">
    <div class="bc-logo" style="<?php echo $vs2_logo ? "background-image:url('" . esc_url( $vs2_logo ) . "');background-size:cover;" : 'background:' . esc_attr( vs2_logo_bg( $vs2_id ) ) . ';'; ?>"><?php echo $vs2_logo ? '' : esc_html( $vs2_initials ); ?></div>
    <div>
      <div class="bc-name"><?php the_title(); ?></div>
      <?php if ( $vs2_cat_line ) : ?><div class="bc-cat"><?php echo esc_html( $vs2_cat_line ); ?></div><?php endif; ?>
    </div>
  </div>
  <?php if ( get_the_excerpt() ) : ?><div class="bc-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></div><?php endif; ?>
  <div class="bc-footer">
    <div class="bc-location"><?php echo $vs2_location ? '📍 ' . esc_html( $vs2_location ) : ''; ?></div>
    <div class="badge badge-listed"><?php echo esc_html( vs2_badge_label( $vs2_tier ) ); ?></div>
  </div>
</a>
