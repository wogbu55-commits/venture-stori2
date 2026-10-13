<?php get_header(); ?>

<?php
$vs2_bc_filters = vs2_org_current_filters();
$vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
if ( $vs2_bc_filters['type'] ) {
    $vs2_bc_term = get_term_by( 'slug', $vs2_bc_filters['type'], 'vs2_org_type' );
    $vs2_bc[] = [ 'label' => 'Organizations', 'url' => vs2_org_base_url() ];
    $vs2_bc[] = [ 'label' => $vs2_bc_term ? $vs2_bc_term->name : $vs2_bc_filters['type'], 'url' => null ];
} else {
    $vs2_bc[] = [ 'label' => 'Organizations', 'url' => null ];
}
vs2_breadcrumb( $vs2_bc );
?>

<div id="main">
  <?php
  get_template_part( 'template-parts/organization-filters' );
  get_template_part( 'template-parts/organization-grid' );
  ?>
</div>

<?php get_footer(); ?>
