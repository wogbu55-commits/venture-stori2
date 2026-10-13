<?php
$vs2_filters = vs2_pro_current_filters();
$vs2_base    = vs2_pro_base_url();
$vs2_fields  = get_terms( [ 'taxonomy' => 'vs2_pro_field', 'hide_empty' => false ] );
$vs2_countries = vs2_all_professional_countries();
?>
<div class="dir-hero">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-btn">← All directories</a>
    <h1>Professional Directory</h1>
    <p>Discover verified African professionals across medicine, law, engineering, technology, creative industries and more.</p>

    <form class="dir-search-row" method="get" action="<?php echo esc_url( $vs2_base ); ?>">
      <input class="dir-search-input" type="text" name="s" value="<?php echo esc_attr( $vs2_filters['s'] ); ?>" placeholder="Search by name, skill, qualification or specialty…">
      <select class="dir-filter-select" name="field">
        <option value="">All fields</option>
        <?php foreach ( $vs2_fields as $term ) : ?>
          <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $vs2_filters['field'], $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
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
    <a href="<?php echo esc_url( vs2_pro_filter_url( $vs2_base, [ 'field' => '' ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['field'] === '' ? ' active' : ''; ?>">All</a>
    <?php foreach ( $vs2_fields as $term ) : ?>
      <a href="<?php echo esc_url( vs2_pro_filter_url( $vs2_base, [ 'field' => $term->slug ] ) ); ?>" class="dir-tab<?php echo $vs2_filters['field'] === $term->slug ? ' active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
    <?php endforeach; ?>
  </div>
</div>
