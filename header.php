<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a href="#main" class="vs2-skip">Skip to main content</a>

<div class="vs2-ticker">
  <?php
  $t1_label = get_theme_mod( 'vs2_ticker_1_label', 'NGX' );
  $t1_num   = get_theme_mod( 'vs2_ticker_1_num', '98,421' );
  $t1_chg   = get_theme_mod( 'vs2_ticker_1_change', '+1.4%' );
  $t2_label = get_theme_mod( 'vs2_ticker_2_label', 'USD/NGN' );
  $t2_num   = get_theme_mod( 'vs2_ticker_2_num', '1,612' );
  $t2_chg   = get_theme_mod( 'vs2_ticker_2_change', '-0.5%' );
  $t3_label = get_theme_mod( 'vs2_ticker_3_label', 'BTC' );
  $t3_num   = get_theme_mod( 'vs2_ticker_3_num', '$102,480' );
  $t3_chg   = get_theme_mod( 'vs2_ticker_3_change', '+2.1%' );
  ?>
  <span><?php echo esc_html( $t1_label ); ?> <b><?php echo esc_html( $t1_num ); ?></b> <span class="<?php echo strpos( $t1_chg, '-' ) === 0 ? 'vs2-down' : 'vs2-up'; ?>"><?php echo esc_html( $t1_chg ); ?></span></span>
  <span><?php echo esc_html( $t2_label ); ?> <b><?php echo esc_html( $t2_num ); ?></b> <span class="<?php echo strpos( $t2_chg, '-' ) === 0 ? 'vs2-down' : 'vs2-up'; ?>"><?php echo esc_html( $t2_chg ); ?></span></span>
  <span><?php echo esc_html( $t3_label ); ?> <b><?php echo esc_html( $t3_num ); ?></b> <span class="<?php echo strpos( $t3_chg, '-' ) === 0 ? 'vs2-down' : 'vs2-up'; ?>"><?php echo esc_html( $t3_chg ); ?></span></span>
</div>

<?php
$vs2_nav_items = [
    [ 'label' => 'Businesses',     'url' => get_post_type_archive_link( 'profile' ) ],
    [ 'label' => 'Professionals',  'url' => vs2_pro_base_url() ],
    [ 'label' => 'Organizations',  'url' => vs2_org_base_url() ],
    [ 'label' => 'Institutions',   'url' => vs2_ins_base_url() ],
];
$vs2_nav_countries = array_slice( vs2_all_countries(), 0, 4 );
$vs2_nav_industries = get_terms( [ 'taxonomy' => 'vs2_industry', 'hide_empty' => false, 'number' => 4 ] );
?>
<header class="vs2-site">
    <nav class="vs2-nav-main">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vs2-logo">Venture<em>Stori</em></a>
      <div class="vs2-nav-center">
        <div class="vs2-nav-item vs2-has-dropdown">
          Explore &#9660;
          <div class="vs2-nav-dropdown">
            <div class="vs2-nd-cols">
              <div>
                <div class="vs2-nd-col-title">By Directory</div>
                <?php foreach ( $vs2_nav_items as $item ) : if ( ! $item['url'] ) continue; ?>
                  <a class="vs2-nd-link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                <?php endforeach; ?>
              </div>
              <div>
                <div class="vs2-nd-col-title">By Location</div>
                <?php foreach ( $vs2_nav_countries as $vs2_country_item ) : ?>
                  <a class="vs2-nd-link" href="<?php echo esc_url( vs2_filter_url( vs2_directory_base_url(), [ 'country' => $vs2_country_item ] ) ); ?>"><?php echo esc_html( $vs2_country_item ); ?></a>
                <?php endforeach; ?>
                <a class="vs2-nd-link" style="color:var(--vs2-orange);" href="<?php echo esc_url( vs2_directory_base_url() ); ?>">View all &rarr;</a>
              </div>
              <div>
                <div class="vs2-nd-col-title">By Industry</div>
                <?php foreach ( $vs2_nav_industries as $vs2_ind_term ) : ?>
                  <a class="vs2-nd-link" href="<?php echo esc_url( vs2_filter_url( vs2_directory_base_url(), [ 'ind' => $vs2_ind_term->slug ] ) ); ?>"><?php echo esc_html( $vs2_ind_term->name ); ?></a>
                <?php endforeach; ?>
                <a class="vs2-nd-link" style="color:var(--vs2-orange);" href="<?php echo esc_url( vs2_directory_base_url() ); ?>">View all &rarr;</a>
              </div>
            </div>
          </div>
        </div>
        <a class="vs2-nav-item" href="<?php echo esc_url( vs2_country_guide_url( 'Nigeria' ) ); ?>">Business Guides</a>
        <a class="vs2-nav-item" href="<?php echo esc_url( vs2_featured_stories_url() ?: home_url( '/' ) ); ?>">Featured Stories</a>
        <a class="vs2-nav-item" href="<?php echo esc_url( vs2_resources_page_url() ); ?>">Resources</a>
      </div>
      <div class="vs2-nav-right">
        <a href="<?php echo esc_url( vs2_directory_base_url() ); ?>" class="vs2-nav-search-btn">
          <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" width="14" height="14"><circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/></svg> Search
        </a>
        <a href="<?php echo esc_url( get_theme_mod( 'vs2_cta_url', vs2_get_listed_url() ) ); ?>" class="vs2-nav-cta"><?php echo esc_html( get_theme_mod( 'vs2_cta_text', 'Get Listed' ) ); ?></a>
        <button class="vs2-hamburger" aria-label="Open menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
</header>

<div id="vs2-mobile-menu" class="vs2-mobile-menu" aria-hidden="true" role="dialog" aria-label="Navigation">
  <div class="vs2-mobile-menu__backdrop"></div>
  <div class="vs2-mobile-menu__panel">
    <div class="vs2-mobile-menu__head">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="vs2-logo">Venture<em>Stori</em></a>
      <button class="vs2-mobile-menu__close" aria-label="Close menu">&#10005;</button>
    </div>
    <nav class="vs2-mobile-menu__nav">
      <?php
      foreach ( $vs2_nav_items as $item ) {
          if ( ! $item['url'] ) continue;
          echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
      }
      echo '<a href="' . esc_url( vs2_country_guide_url( 'Nigeria' ) ) . '">Business Guides</a>';
      echo '<a href="' . esc_url( vs2_featured_stories_url() ?: home_url( '/' ) ) . '">Featured Stories</a>';
      echo '<a href="' . esc_url( vs2_resources_page_url() ) . '">Resources</a>';
      ?>
    </nav>
    <a href="<?php echo esc_url( get_theme_mod( 'vs2_cta_url', vs2_get_listed_url() ) ); ?>" class="vs2-nav-cta" style="text-align:center;"><?php echo esc_html( get_theme_mod( 'vs2_cta_text', 'Get Listed' ) ); ?></a>
  </div>
</div>
