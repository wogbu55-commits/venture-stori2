<?php get_header(); ?>

<?php
$vs2_bc_filters = vs2_current_filters();
$vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
if ( $vs2_bc_filters['ind'] ) {
    $vs2_bc_term = get_term_by( 'slug', $vs2_bc_filters['ind'], 'vs2_industry' );
    $vs2_bc[] = [ 'label' => 'Businesses', 'url' => vs2_directory_base_url() ];
    $vs2_bc[] = [ 'label' => $vs2_bc_term ? $vs2_bc_term->name : $vs2_bc_filters['ind'], 'url' => null ];
} else {
    $vs2_bc[] = [ 'label' => 'Businesses', 'url' => null ];
}
vs2_breadcrumb( $vs2_bc );
?>

<div id="main">
  <?php
  get_template_part( 'template-parts/directory-hero' );
  get_template_part( 'template-parts/directory-grid' );
  ?>
</div>

<?php get_footer(); ?>
