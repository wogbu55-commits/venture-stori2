<?php
/**
 * Business Resource card. Expects to be called inside the loop (the_post() already run).
 */
$vs2_cat = vs2_resource_category_name();
?>
<a href="<?php the_permalink(); ?>" class="res-card">
  <?php if ( $vs2_cat ) : ?><div class="res-tag"><?php echo esc_html( $vs2_cat ); ?></div><?php endif; ?>
  <div class="res-title"><?php the_title(); ?></div>
  <?php if ( get_the_excerpt() ) : ?><div class="res-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></div><?php endif; ?>
  <div class="res-meta" style="margin-top:12px;"><?php echo esc_html( vs2_read_time() ); ?> · <?php echo esc_html( get_the_author() ); ?></div>
</a>
