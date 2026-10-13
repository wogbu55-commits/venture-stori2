<?php get_header(); ?>

<div id="main">
  <div class="vs2-picks-section">
      <div class="vs2-sec-label">Venture Stori</div>
      <div class="vs2-sec-title"><?php echo wp_kses_post( get_the_archive_title() ); ?></div>

      <?php if ( have_posts() ) : ?>
        <div class="vs2-picks-grid" style="margin-top:32px;">
          <?php while ( have_posts() ) : the_post(); ?>
            <a href="<?php the_permalink(); ?>" class="vs2-pick-card">
              <div class="vs2-pick-img" <?php if ( has_post_thumbnail() ) : ?>style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'vs2-card' ) ); ?>');"<?php endif; ?>></div>
              <div class="vs2-pick-body">
                <div class="vs2-pick-cat"><?php echo esc_html( vs2_pick_category() ); ?></div>
                <div class="vs2-pick-title"><?php the_title(); ?></div>
                <div class="vs2-pick-byline"><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( vs2_read_time() ); ?></div>
              </div>
            </a>
          <?php endwhile; ?>
        </div>
        <div class="vs2-pagination" style="margin-top:36px;">
          <?php echo paginate_links( [ 'prev_text' => '‹ Prev', 'next_text' => 'Next ›' ] ); ?>
        </div>
      <?php else : ?>
        <div class="vs2-empty-state">Nothing published here yet.</div>
      <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>
