<?php
/**
 * Template Name: Stories (Blog Listing)
 */
get_header();

$vs2_paged = max( (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ), 1 );
$vs2_q = new WP_Query( [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $vs2_paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
?>

<div id="main">
  <div class="vs2-picks-section">
      <div class="vs2-sec-label">Venture Stori</div>
      <div class="vs2-sec-title"><?php the_title(); ?></div>

      <?php if ( $vs2_q->have_posts() ) : ?>
        <div class="vs2-picks-grid" style="margin-top:32px;">
          <?php while ( $vs2_q->have_posts() ) : $vs2_q->the_post(); ?>
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

        <?php if ( $vs2_q->max_num_pages > 1 ) :
            $vs2_big = 999999999;
        ?>
        <div class="vs2-pagination" style="margin-top:36px;">
          <?php
          echo paginate_links( [
              'base'      => str_replace( $vs2_big, '%#%', esc_url( get_pagenum_link( $vs2_big ) ) ),
              'current'   => $vs2_paged,
              'total'     => $vs2_q->max_num_pages,
              'prev_text' => '‹ Prev',
              'next_text' => 'Next ›',
          ] );
          ?>
        </div>
        <?php endif; ?>
      <?php else : ?>
        <div class="vs2-empty-state">Nothing published here yet.</div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
  </div>
</div>

<?php get_footer(); ?>
