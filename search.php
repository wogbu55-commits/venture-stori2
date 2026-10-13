<?php get_header(); ?>

<div id="main">
  <div class="vs2-dir-hero">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vs2-back">← Home</a>
      <h1>Results for &ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</h1>
  </div>

  <div class="vs2-dir-body">
      <?php if ( have_posts() ) : ?>
        <div class="vs2-biz-grid">
          <?php while ( have_posts() ) : the_post();
              $vs2_type_labels = [
                  'profile'      => 'Business',
                  'professional' => 'Professional',
                  'organization' => 'Organization',
                  'institution'  => 'Institution',
                  'resource'     => 'Guide',
                  'post'         => 'Story',
              ];
              $vs2_type_label = $vs2_type_labels[ get_post_type() ] ?? get_post_type();
          ?>
            <a href="<?php the_permalink(); ?>" class="vs2-biz-card">
              <div class="vs2-biz-info">
                <div class="vs2-biz-cat"><?php echo esc_html( $vs2_type_label ); ?></div>
                <h4><?php the_title(); ?></h4>
              </div>
              <div class="vs2-biz-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></div>
            </a>
          <?php endwhile; ?>
        </div>
        <div class="vs2-pagination" style="margin-top:36px;">
          <?php echo paginate_links( [ 'prev_text' => '‹ Prev', 'next_text' => 'Next ›' ] ); ?>
        </div>
      <?php else : ?>
        <div class="vs2-empty-state">No results found.</div>
      <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
