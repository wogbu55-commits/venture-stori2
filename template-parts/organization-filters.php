<?php
$vs2_filters = vs2_org_current_filters();
$vs2_base    = vs2_org_base_url();
$vs2_types   = get_terms( [ 'taxonomy' => 'vs2_org_type', 'hide_empty' => false ] );
$vs2_countries = vs2_all_organization_countries();
?>
<div class="dir-hero">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-btn">← All directories</a>
    <h1>Organization Directory</h1>
    <p>Professional bodies, trade associations, student groups and community networks — with verified member directories.</p>

    <form class="dir-search-row" method="get" action="<?php echo esc_url( $vs2_base ); ?>">
      <input class="dir-search-input" type="text" name="s" value="<?php echo esc_attr( $vs2_filters['s'] ); ?>" placeholder="Search associations, chambers, professional bodies…">
      <select class="dir-filter-select" name="type">
        <option value="">All types</option>
        <?php foreach ( $vs2_types as $term ) : ?>
          <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $vs2_filters['type'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
        <?php endforeach; ?>
      </select>
      <select class="dir-filter-select" name="country">
        <option value="">All countries</option>
        <?php foreach ( $vs2_countries as $country ) : ?>
          <option value="<?php echo esc_attr( $country ); ?>" <?php selected( $vs2_filters['country'], $country ); ?>><?php echo esc_html( $country ); ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="dir-search-go">Search</button>
    </form>

  <div class="dir-tabs">
    <a href="<?php echo esc_url( vs2_org_filter_url( $vs2_base, [ 'type' => '' ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['type'] === '' ? ' active' : ''; ?>">All</a>
    <?php foreach ( $vs2_types as $term ) : ?>
      <a href="<?php echo esc_url( vs2_org_filter_url( $vs2_base, [ 'type' => $term->slug ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['type'] === $term->slug ? ' active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
    <?php endforeach; ?>
  </div>
</div>
