<?php
/**
 * Business directory hero: title, search/filter form, and business-type tabs.
 */
$vs2_filters    = vs2_current_filters();
$vs2_base_url   = vs2_directory_base_url();
$vs2_industries = get_terms( [ 'taxonomy' => 'vs2_industry', 'hide_empty' => false ] );
$vs2_types      = get_terms( [ 'taxonomy' => 'vs2_category', 'hide_empty' => false ] );
$vs2_countries  = vs2_all_countries();
?>
<div class="dir-hero">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-btn">← All directories</a>
    <h1>Business Directory</h1>
    <p>Search and browse verified African businesses across every industry, city and funding stage.</p>

    <form class="dir-search-row" method="get" action="<?php echo esc_url( $vs2_base_url ); ?>">
      <input class="dir-search-input" type="text" name="s" value="<?php echo esc_attr( $vs2_filters['s'] ); ?>" placeholder="Search by name, category or location…">
      <select class="dir-filter-select" name="ind">
        <option value="">All industries</option>
        <?php foreach ( $vs2_industries as $term ) : ?>
          <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $vs2_filters['ind'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
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
    <a href="<?php echo esc_url( vs2_filter_url( $vs2_base_url, [ 'lcat' => '' ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['lcat'] === '' ? ' active' : ''; ?>">All</a>
    <?php foreach ( $vs2_types as $term ) : ?>
      <a href="<?php echo esc_url( vs2_filter_url( $vs2_base_url, [ 'lcat' => $term->slug ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['lcat'] === $term->slug ? ' active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
    <?php endforeach; ?>
  </div>
</div>
