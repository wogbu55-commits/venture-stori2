<?php
$vs2_res = vs2_resources( 6 );
if ( ! $vs2_res ) return;
?>
<section class="vs2-resources-section">
    <div class="vs2-sec-label">Guides &amp; Resources</div>
    <div class="vs2-sec-title">Everything you need to grow.</div>
    <div class="vs2-sec-sub">Registration guides, career explainers, industry handbooks — built for African ventures.</div>
    <div class="vs2-resources-grid">
      <?php foreach ( $vs2_res as $vs2_r ) : setup_postdata( $vs2_r ); ?>
        <?php get_template_part( 'template-parts/resource-item' ); ?>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
</section>
