<?php
/**
 * Guide/Resource list item. Expects to be called inside the loop (the_post() already run).
 */
$vs2_cat = vs2_resource_tag_name();
?>
<a href="<?php the_permalink(); ?>" class="guide-card">
  <?php if ( $vs2_cat ) : ?><div class="guide-tag"><?php echo esc_html( $vs2_cat ); ?></div><?php endif; ?>
  <div class="guide-title"><?php the_title(); ?></div>
  <?php if ( get_the_excerpt() ) : ?><div class="guide-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></div><?php endif; ?>
  <div class="guide-meta">
    <span><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( vs2_read_time() ); ?></span>
    <span class="guide-read">Read &rarr;</span>
  </div>
</a>
