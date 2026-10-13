<?php
$vs2_paged = max( (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ), 1 );
$vs2_q     = vs2_pro_query( $vs2_paged );
?>
<div class="dir-body">

    <div class="dir-sort-row">
      <div class="dir-count"><?php echo esc_html( number_format_i18n( $vs2_q->found_posts ) ); ?> professionals</div>
    </div>

    <?php if ( $vs2_q->have_posts() ) :
        $vs2_filters = vs2_pro_current_filters();
    ?>
      <div class="pro-grid" id="pro-grid">
        <?php while ( $vs2_q->have_posts() ) : $vs2_q->the_post(); ?>
          <?php get_template_part( 'template-parts/professional-card' ); ?>
        <?php endwhile; ?>
      </div>

      <?php if ( $vs2_q->max_num_pages > $vs2_paged ) : ?>
        <button
          type="button"
          class="see-more-btn vs2-load-more"
          style="display:block;width:100%;text-align:center;"
          data-grid="pro-grid"
          data-action="vs2_load_more_professionals"
          data-page="<?php echo esc_attr( $vs2_paged ); ?>"
          data-query="<?php echo esc_attr( http_build_query( array_filter( $vs2_filters ) ) ); ?>"
          data-nonce="<?php echo esc_attr( wp_create_nonce( 'vs2_load_more' ) ); ?>"
          data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
        >Load more professionals</button>
      <?php endif; ?>
    <?php else : ?>
      <div class="vs2-empty-state">No professionals match those filters yet.</div>
    <?php endif; ?>

</div>
<?php wp_reset_postdata(); ?>
