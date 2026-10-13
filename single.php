<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $vs2_id = get_the_ID();

    $vs2_bc = [
        [ 'label' => 'Home', 'url' => home_url( '/' ) ],
        [ 'label' => 'Featured Stories', 'url' => vs2_featured_stories_url() ?: home_url( '/' ) ],
        [ 'label' => get_the_title(), 'url' => null ],
    ];
    vs2_breadcrumb( $vs2_bc );

    $vs2_featured_entities = vs2_story_featured_entities( $vs2_id );

    $vs2_related_q = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'post__not_in'   => [ $vs2_id ],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
?>
  <div style="background:#0a0a0a;padding:0 28px;border-bottom:1px solid var(--border);">
    <a href="<?php echo esc_url( vs2_featured_stories_url() ?: home_url( '/' ) ); ?>" class="back-btn">← Featured Stories</a>
  </div>

  <div class="article-hero">
    <div class="article-cat"><?php echo esc_html( vs2_pick_category() ); ?></div>
    <h1 class="article-title"><?php the_title(); ?></h1>
    <div class="article-byline">
      <strong><?php echo esc_html( get_the_author() ); ?></strong>
      <span><?php echo esc_html( vs2_read_time() ); ?></span>
      <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
      <span style="margin-left:auto;">
        <button type="button" class="vs2-share-btn" data-url="<?php echo esc_url( get_permalink() ); ?>" style="background:none;border:none;color:var(--red);font-weight:800;font-size:13px;cursor:pointer;font-family:inherit;padding:0;">Share &rarr;</button>
      </span>
    </div>
  </div>

  <?php if ( has_post_thumbnail() ) : ?>
    <div class="article-img" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( $vs2_id, 'vs2-hero' ) ); ?>');"></div>
  <?php endif; ?>

  <div class="article-layout">
    <div class="article-body">
      <?php the_content(); ?>
    </div>
    <div class="article-sidebar">
      <?php if ( $vs2_featured_entities ) : ?>
      <div class="asc">
        <h4>Featured in this story</h4>
        <?php foreach ( $vs2_featured_entities as $vs2_fe ) :
            $vs2_fe_post   = $vs2_fe['post'];
            $vs2_fe_avatar = has_post_thumbnail( $vs2_fe_post->ID ) ? get_the_post_thumbnail_url( $vs2_fe_post->ID, 'thumbnail' ) : '';
        ?>
        <a href="<?php echo esc_url( get_permalink( $vs2_fe_post ) ); ?>" class="fe-ent">
          <div class="fe-av" style="<?php echo $vs2_fe_avatar ? "background-image:url('" . esc_url( $vs2_fe_avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $vs2_fe_post->ID ) ) . ';'; ?>"><?php echo $vs2_fe_avatar ? '' : esc_html( vs2_initials( get_the_title( $vs2_fe_post ) ) ); ?></div>
          <div>
            <div class="fe-name"><?php echo esc_html( get_the_title( $vs2_fe_post ) ); ?></div>
            <?php if ( $vs2_fe['sub'] ) : ?><div class="fe-role"><?php echo esc_html( $vs2_fe['sub'] ); ?></div><?php endif; ?>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php if ( $vs2_related_q->have_posts() ) : ?>
      <div class="asc">
        <h4>Related Stories</h4>
        <?php while ( $vs2_related_q->have_posts() ) : $vs2_related_q->the_post();
            $vs2_r_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '';
        ?>
        <a href="<?php the_permalink(); ?>" class="rel-story">
          <div class="rel-story-img" <?php if ( $vs2_r_thumb ) : ?>style="background-image:url('<?php echo esc_url( $vs2_r_thumb ); ?>');"<?php endif; ?>></div>
          <div>
            <div class="rel-story-title"><?php the_title(); ?></div>
            <div class="rel-story-meta"><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( vs2_read_time() ); ?></div>
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>
      <div class="asc">
        <h4>Get Featured</h4>
        <p style="font-size:12px;color:var(--grey2);margin-bottom:14px;line-height:1.65;font-weight:500;">Editor's Picks are earned, never paid for. Submit your story for editorial consideration.</p>
        <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" class="sidebar-cta">Submit your story</a>
      </div>
    </div>
  </div>

<?php endwhile; ?>

<script>
document.querySelectorAll('.vs2-share-btn').forEach(function(btn){
  btn.addEventListener('click', function(){
    var url = btn.dataset.url;
    if (navigator.share) {
      navigator.share({ url: url }).catch(function(){});
      return;
    }
    navigator.clipboard.writeText(url).then(function(){
      var original = btn.textContent;
      btn.textContent = 'Link copied!';
      setTimeout(function(){ btn.textContent = original; }, 1800);
    });
  });
});
</script>

<?php get_footer(); ?>
