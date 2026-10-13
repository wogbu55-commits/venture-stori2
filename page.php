<?php get_header(); ?>

<div id="main">
<?php while ( have_posts() ) : the_post(); ?>
  <div class="vs2-page-wrap">
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </div>
<?php endwhile; ?>
</div>

<?php get_footer(); ?>
