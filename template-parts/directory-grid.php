<?php
/**
 * Business results grid + sort row + pagination. Reads filters from $_GET via vs2_directory_query().
 */
$vs2_paged  = max( (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ), 1 );
$vs2_dir_q  = vs2_directory_query( $vs2_paged );
$vs2_filters = vs2_current_filters();
?>
<div class="dir-body">

    <div class="dir-sort-row">
      <div class="dir-count"><?php echo esc_html( number_format_i18n( $vs2_dir_q->found_posts ) ); ?> businesses</div>
      <form method="get" action="<?php echo esc_url( vs2_directory_base_url() ); ?>" class="dir-sort">
        <?php foreach ( [ 's', 'ind', 'country', 'lcat' ] as $vs2_k ) : if ( $vs2_filters[ $vs2_k ] ) : ?>
          <input type="hidden" name="<?php echo esc_attr( $vs2_k ); ?>" value="<?php echo esc_attr( $vs2_filters[ $vs2_k ] ); ?>">
        <?php endif; endforeach; ?>
        <label for="vs2-sort">Sort by</label>
        <select id="vs2-sort" name="sort" onchange="this.form.submit()">
          <option value="" <?php selected( $vs2_filters['sort'], '' ); ?>>Recent</option>
          <option value="verification" <?php selected( $vs2_filters['sort'], 'verification' ); ?>>Verification</option>
          <option value="name" <?php selected( $vs2_filters['sort'], 'name' ); ?>>Name A–Z</option>
        </select>
      </form>
    </div>

    <?php if ( $vs2_dir_q->have_posts() ) : ?>
      <div class="biz-grid" id="vs2-biz-grid">
        <?php while ( $vs2_dir_q->have_posts() ) : $vs2_dir_q->the_post(); ?>
          <?php get_template_part( 'template-parts/profile-card' ); ?>
        <?php endwhile; ?>
      </div>

      <?php if ( $vs2_dir_q->max_num_pages > $vs2_paged ) : ?>
        <button
          type="button"
          class="see-more-btn vs2-load-more"
          data-grid="vs2-biz-grid"
          data-action="vs2_load_more_businesses"
          data-page="<?php echo esc_attr( $vs2_paged ); ?>"
          data-query="<?php echo esc_attr( http_build_query( array_filter( $vs2_filters ) ) ); ?>"
          data-nonce="<?php echo esc_attr( wp_create_nonce( 'vs2_load_more' ) ); ?>"
          data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
        >Load more businesses</button>
      <?php endif; ?>
    <?php else : ?>
      <div class="vs2-empty-state">No businesses match those filters yet. Try widening your search.</div>
    <?php endif; ?>

</div>
<?php wp_reset_postdata(); ?>
