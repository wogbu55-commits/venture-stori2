<?php get_header(); ?>

<?php
$vs2_bc_filters = vs2_pro_current_filters();
$vs2_bc = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
if ( $vs2_bc_filters['field'] ) {
    $vs2_bc_term = get_term_by( 'slug', $vs2_bc_filters['field'], 'vs2_pro_field' );
    $vs2_bc[] = [ 'label' => 'Professionals', 'url' => vs2_pro_base_url() ];
    $vs2_bc[] = [ 'label' => $vs2_bc_term ? $vs2_bc_term->name : $vs2_bc_filters['field'], 'url' => null ];
} else {
    $vs2_bc[] = [ 'label' => 'Professionals', 'url' => null ];
}
vs2_breadcrumb( $vs2_bc );
?>

<div id="main">
  <?php
  get_template_part( 'template-parts/professional-filters' );
  get_template_part( 'template-parts/professional-grid' );
  ?>
</div>

<?php get_footer(); ?>
