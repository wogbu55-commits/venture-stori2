<?php

require get_template_directory() . '/inc/relational-picker.php';
require get_template_directory() . '/inc/cpt-profile.php';
require get_template_directory() . '/inc/cpt-professional.php';
require get_template_directory() . '/inc/cpt-organization.php';
require get_template_directory() . '/inc/cpt-institution.php';
require get_template_directory() . '/inc/cpt-resource.php';
require get_template_directory() . '/inc/story-features.php';

function vs2_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'automatic-feed-links' );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'venturestori2' ),
    ] );

    add_image_size( 'vs2-card', 480, 270, false );
    add_image_size( 'vs2-hero', 800, 450, false );
}
add_action( 'after_setup_theme', 'vs2_setup' );


function vs2_enqueue() {
    wp_enqueue_style(
        'vs2-fonts',
        'https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,500;1,6..72,600&family=Nunito+Sans:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap',
        [],
        null
    );
    wp_enqueue_style( 'venturestori2-style', get_stylesheet_uri(), [ 'vs2-fonts' ], filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_script( 'vs2-nav', get_stylesheet_directory_uri() . '/js/nav.js', [], filemtime( get_stylesheet_directory() . '/js/nav.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'vs2_enqueue' );


add_filter( 'excerpt_length', fn() => 24 );
add_filter( 'excerpt_more', fn() => '' );

add_action( 'after_switch_theme', fn() => flush_rewrite_rules() );

function vs2_maybe_flush_for_directory_v2() {
    if ( get_option( 'vs2_directory_v2_flushed' ) ) return;
    flush_rewrite_rules();
    update_option( 'vs2_directory_v2_flushed', 1 );
}
add_action( 'init', 'vs2_maybe_flush_for_directory_v2', 30 );


// ── NAV WALKER ──

class VS2_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = (array) $item->classes;
        $is_active = in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true );
        $cls       = 'vs2-nav-links-a' . ( $is_active ? ' active' : '' );
        $output   .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( $cls ) . '">' . esc_html( $item->title ) . '</a>';
    }
    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}


// ── TICKER STATS (customizer) ──

function vs2_customize_register( WP_Customize_Manager $wp_customize ) {
    $wp_customize->add_section( 'vs2_ticker', [
        'title'    => 'Ticker Stats',
        'priority' => 30,
    ] );

    $stats = [
        'vs2_ticker_1' => [ 'label' => 'NGX',     'num' => '98,421',   'change' => '+1.4%' ],
        'vs2_ticker_2' => [ 'label' => 'USD/NGN', 'num' => '1,612',    'change' => '-0.5%' ],
        'vs2_ticker_3' => [ 'label' => 'BTC',     'num' => '$102,480', 'change' => '+2.1%' ],
    ];

    foreach ( $stats as $id => $cfg ) {
        $wp_customize->add_setting( $id . '_label', [ 'default' => $cfg['label'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
        $wp_customize->add_control( $id . '_label', [ 'label' => $cfg['label'] . ' — Label', 'section' => 'vs2_ticker', 'type' => 'text' ] );

        $wp_customize->add_setting( $id . '_num', [ 'default' => $cfg['num'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
        $wp_customize->add_control( $id . '_num', [ 'label' => $cfg['label'] . ' — Value', 'section' => 'vs2_ticker', 'type' => 'text' ] );

        $wp_customize->add_setting( $id . '_change', [ 'default' => $cfg['change'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
        $wp_customize->add_control( $id . '_change', [ 'label' => $cfg['label'] . ' — Change (e.g. +1.4%)', 'section' => 'vs2_ticker', 'type' => 'text' ] );
    }

    // HEADER "GET LISTED" BUTTON
    $wp_customize->add_section( 'vs2_header_cta', [
        'title'    => 'Header "Get Listed" Button',
        'priority' => 31,
    ] );

    $wp_customize->add_setting( 'vs2_cta_text', [ 'default' => 'Get Listed', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'vs2_cta_text', [ 'label' => 'Button Text', 'section' => 'vs2_header_cta', 'type' => 'text' ] );

    $wp_customize->add_setting( 'vs2_cta_url', [ 'default' => vs2_get_listed_url(), 'sanitize_callback' => 'esc_url_raw', 'transport' => 'refresh' ] );
    $wp_customize->add_control( 'vs2_cta_url', [ 'label' => 'Button Link', 'section' => 'vs2_header_cta', 'type' => 'url' ] );
}
add_action( 'customize_register', 'vs2_customize_register' );

function vs2_post_type_count( $post_type ) {
    $counts = wp_count_posts( $post_type );
    return (int) ( $counts->publish ?? 0 );
}


// ── EDITOR'S PICKS ──

function vs2_editors_picks( $count = 3 ) {
    $q = new WP_Query( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category_name'  => 'featured-stories',
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_featured_stories_url() {
    $term = get_term_by( 'slug', 'featured-stories', 'category' );
    return $term ? get_category_link( $term ) : '';
}

function vs2_get_listed_url() {
    $page = get_page_by_path( 'get-listed' );
    return $page ? get_permalink( $page ) : home_url( '/#get-listed' );
}

function vs2_nigeria_states() {
    return [
        'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno',
        'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'FCT Abuja', 'Gombe',
        'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara', 'Lagos',
        'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto',
        'Taraba', 'Yobe', 'Zamfara',
    ];
}

function vs2_country_guide_url( $country ) {
    $pages = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => [
            [ 'key' => '_wp_page_template', 'value' => 'page-templates/business-guide.php' ],
            [ 'key' => '_vs2_guide_country', 'value' => $country ],
        ],
    ] );
    if ( $pages ) return get_permalink( $pages[0] );
    return vs2_filter_url( vs2_directory_base_url(), [ 'country' => $country ] );
}

function vs2_add_guide_country_meta_box() {
    add_meta_box( 'vs2_guide_country', 'Country Business Guide Settings', 'vs2_render_guide_country_meta_box', 'page', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'vs2_add_guide_country_meta_box' );

function vs2_render_guide_country_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_guide_country', 'vs2_guide_country_nonce' );
    $country = get_post_meta( $post->ID, '_vs2_guide_country', true ) ?: 'Nigeria';
    ?>
    <p class="description">Only used when this page's template is "Country Business Guide".</p>
    <select id="vs2_guide_country" name="vs2_guide_country" style="width:100%;">
      <?php foreach ( vs2_africa_countries() as $vs2_c ) : ?>
        <option value="<?php echo esc_attr( $vs2_c ); ?>" <?php selected( $country, $vs2_c ); ?>><?php echo esc_html( $vs2_c ); ?></option>
      <?php endforeach; ?>
    </select>
    <?php
}

function vs2_save_guide_country_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_guide_country_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_guide_country_nonce'], 'vs2_save_guide_country' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( ! isset( $_POST['vs2_guide_country'] ) ) return;

    $country = sanitize_text_field( wp_unslash( $_POST['vs2_guide_country'] ) );
    update_post_meta( $post_id, '_vs2_guide_country', in_array( $country, vs2_africa_countries(), true ) ? $country : 'Nigeria' );
}
add_action( 'save_post_page', 'vs2_save_guide_country_meta' );

function vs2_locations_for_country( $country, $post_type = 'profile', $location_meta_key = '_vs2_location', $country_meta_key = '_vs2_country' ) {
    global $wpdb;
    $locations = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT loc.meta_value FROM {$wpdb->postmeta} loc
         INNER JOIN {$wpdb->postmeta} ctry ON ctry.post_id = loc.post_id AND ctry.meta_key = %s AND ctry.meta_value = %s
         INNER JOIN {$wpdb->posts} p ON p.ID = loc.post_id AND p.post_type = %s AND p.post_status = 'publish'
         WHERE loc.meta_key = %s AND loc.meta_value != ''
         ORDER BY loc.meta_value ASC",
        $country_meta_key, $country, $post_type, $location_meta_key
    ) );
    return $locations ?: [];
}

function vs2_industries_for_country( $country, $post_type = 'profile', $taxonomy = 'vs2_industry', $country_meta_key = '_vs2_country' ) {
    $terms = get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false ] );
    if ( is_wp_error( $terms ) || ! $terms ) return [];
    $out = [];
    foreach ( $terms as $term ) {
        $q = new WP_Query( [
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'tax_query'      => [ [ 'taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $term->slug ] ],
            'meta_query'     => [ [ 'key' => $country_meta_key, 'value' => $country ] ],
        ] );
        if ( $q->found_posts > 0 ) {
            $out[] = [ 'term' => $term, 'count' => $q->found_posts ];
        }
    }
    wp_reset_postdata();
    return $out;
}


// ── BREADCRUMB ──
// $items: array of ['label' => string, 'url' => string|null]. Last item with no url renders as current.
function vs2_breadcrumb( $items ) {
    echo '<div class="breadcrumb">';
    $last = count( $items ) - 1;
    foreach ( $items as $i => $item ) {
        if ( $i > 0 ) echo '<span class="bc-sep">&rsaquo;</span>';
        if ( ! empty( $item['url'] ) && $i !== $last ) {
            echo '<a href="' . esc_url( $item['url'] ) . '" class="bc-item">' . esc_html( $item['label'] ) . '</a>';
        } else {
            echo '<span class="bc-current">' . esc_html( $item['label'] ) . '</span>';
        }
    }
    echo '</div>';
}


// ── CONNECTED-TO RELATIONSHIP SECTION ──
function vs2_rel_card( $post, $sub = '', $square = false ) {
    $initials = vs2_initials( get_the_title( $post ) );
    $avatar   = has_post_thumbnail( $post->ID ) ? get_the_post_thumbnail_url( $post->ID, 'thumbnail' ) : '';
    ?>
    <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="vs2-rel-card">
      <div class="vs2-rel-avatar<?php echo $square ? ' vs2-rel-avatar-sq' : ''; ?>" style="<?php echo $avatar ? "background-image:url('" . esc_url( $avatar ) . "');background-size:cover;" : 'background:' . esc_attr( vs2_logo_bg( $post->ID ) ) . ';'; ?>"><?php echo $avatar ? '' : esc_html( $initials ); ?></div>
      <div>
        <div class="vs2-rel-name"><?php echo esc_html( get_the_title( $post ) ); ?></div>
        <?php if ( $sub ) : ?><div class="vs2-rel-sub"><?php echo esc_html( $sub ); ?></div><?php endif; ?>
      </div>
      <div class="vs2-rel-arrow">&rarr;</div>
    </a>
    <?php
}

function vs2_guide_entity_card( $post, $sub = '' ) {
    $initials = vs2_initials( get_the_title( $post ) );
    $avatar   = has_post_thumbnail( $post->ID ) ? get_the_post_thumbnail_url( $post->ID, 'thumbnail' ) : '';
    ?>
    <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="guide-entity-card">
      <div class="gec-avatar" style="<?php echo $avatar ? "background-image:url('" . esc_url( $avatar ) . "');" : 'background:' . esc_attr( vs2_logo_bg( $post->ID ) ) . ';'; ?>"><?php echo $avatar ? '' : esc_html( $initials ); ?></div>
      <div class="gec-info">
        <div class="gec-name"><?php echo esc_html( get_the_title( $post ) ); ?></div>
        <?php if ( $sub ) : ?><div class="gec-role"><?php echo esc_html( $sub ); ?></div><?php endif; ?>
      </div>
      <div class="gec-arrow">&rarr;</div>
    </a>
    <?php
}

function vs2_ng_card( $post ) {
    ?>
    <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="ng-card">
      <div class="ng-card-icon">📄</div>
      <h4><?php echo esc_html( get_the_title( $post ) ); ?></h4>
      <?php if ( has_excerpt( $post ) || $post->post_content ) : ?>
      <div class="ng-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post ), 18, '…' ) ); ?></div>
      <?php endif; ?>
      <div class="ng-card-meta"><?php echo esc_html( vs2_read_time( $post->ID ) ); ?></div>
    </a>
    <?php
}

// $groups: array of [ 'label' => string, 'items' => array of [ 'post' => WP_Post, 'sub' => string, 'square' => bool ] ]
function vs2_rel_section( $title, $groups ) {
    $groups = array_filter( $groups, fn( $g ) => ! empty( $g['items'] ) );
    if ( ! $groups ) return;
    ?>
    <div class="vs2-rel-section">
      <h3><?php echo esc_html( $title ); ?></h3>
      <?php foreach ( $groups as $group ) : ?>
        <div class="vs2-rel-group">
          <div class="vs2-rel-group-label"><?php echo esc_html( $group['label'] ); ?></div>
          <?php foreach ( $group['items'] as $item ) : vs2_rel_card( $item['post'], $item['sub'] ?? '', $item['square'] ?? false ); endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php
}

// ── EXPLORE MORE (relationship discovery rows) ──
function vs2_explore_more_row( $label, $items ) {
    if ( ! $items ) return;
    ?>
    <div class="vs2-explore-more">
      <h3><span>&rarr;</span> <?php echo esc_html( $label ); ?></h3>
      <div class="vs2-em-scroll">
        <?php foreach ( $items as $item ) : ?>
          <a href="<?php echo esc_url( $item['url'] ); ?>" class="vs2-em-card">
            <div class="vs2-em-type"><?php echo esc_html( $item['type'] ); ?></div>
            <div class="vs2-em-name"><?php echo esc_html( $item['name'] ); ?></div>
            <?php if ( ! empty( $item['sub'] ) ) : ?><div class="vs2-em-sub"><?php echo esc_html( $item['sub'] ); ?></div><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php
}

function vs2_profiles_by_industry( $exclude_id, $industry_slug, $count = 8 ) {
    if ( ! $industry_slug ) return [];
    $q = new WP_Query( [
        'post_type'      => 'profile',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'tax_query'      => [ [ 'taxonomy' => 'vs2_industry', 'field' => 'slug', 'terms' => $industry_slug ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_profiles_by_location( $exclude_id, $location, $count = 8 ) {
    if ( ! $location ) return [];
    $q = new WP_Query( [
        'post_type'      => 'profile',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'meta_query'     => [ [ 'key' => '_vs2_location', 'value' => $location ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_professionals_by_field( $exclude_id, $field_slug, $count = 8 ) {
    if ( ! $field_slug ) return [];
    $q = new WP_Query( [
        'post_type'      => 'professional',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'tax_query'      => [ [ 'taxonomy' => 'vs2_pro_field', 'field' => 'slug', 'terms' => $field_slug ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_professionals_by_location( $exclude_id, $location, $count = 8 ) {
    if ( ! $location ) return [];
    $q = new WP_Query( [
        'post_type'      => 'professional',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'meta_query'     => [ [ 'key' => '_vs2p_location', 'value' => $location ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_organizations_by_type( $exclude_id, $type_slug, $count = 8 ) {
    if ( ! $type_slug ) return [];
    $q = new WP_Query( [
        'post_type'      => 'organization',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'tax_query'      => [ [ 'taxonomy' => 'vs2_org_type', 'field' => 'slug', 'terms' => $type_slug ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_organizations_by_country( $exclude_id, $country, $count = 8 ) {
    if ( ! $country ) return [];
    $q = new WP_Query( [
        'post_type'      => 'organization',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'meta_query'     => [ [ 'key' => '_vs2o_country', 'value' => $country ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_institutions_by_type( $exclude_id, $type_slug, $count = 8 ) {
    if ( ! $type_slug ) return [];
    $q = new WP_Query( [
        'post_type'      => 'institution',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'tax_query'      => [ [ 'taxonomy' => 'vs2_institution_type', 'field' => 'slug', 'terms' => $type_slug ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_institutions_by_country( $exclude_id, $country, $count = 8 ) {
    if ( ! $country ) return [];
    $q = new WP_Query( [
        'post_type'      => 'institution',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [ $exclude_id ],
        'meta_query'     => [ [ 'key' => '_vs2i_country', 'value' => $country ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_pick_category( $post_id = null ) {
    $cats = get_the_category( $post_id ?? get_the_ID() );
    return $cats ? esc_html( $cats[0]->name ) : 'Feature';
}

// ── DIRECTORY FILTERING ──

function vs2_current_filters() {
    return [
        's'       => isset( $_GET['s'] )       ? sanitize_text_field( wp_unslash( $_GET['s'] ) )       : '',
        'lcat'    => isset( $_GET['lcat'] )    ? sanitize_title( wp_unslash( $_GET['lcat'] ) )          : '',
        'ind'     => isset( $_GET['ind'] )     ? sanitize_title( wp_unslash( $_GET['ind'] ) )           : '',
        'stage'   => isset( $_GET['stage'] )   ? sanitize_title( wp_unslash( $_GET['stage'] ) )         : '',
        'country' => isset( $_GET['country'] ) ? sanitize_text_field( wp_unslash( $_GET['country'] ) )  : '',
        'loc'     => isset( $_GET['loc'] )     ? sanitize_text_field( wp_unslash( $_GET['loc'] ) )      : '',
        'sort'    => isset( $_GET['sort'] )    ? sanitize_key( wp_unslash( $_GET['sort'] ) )            : '',
    ];
}

function vs2_filter_url( $base, $overrides = [] ) {
    $filters = array_merge( vs2_current_filters(), $overrides );
    $filters = array_filter( $filters, fn( $v ) => $v !== '' );
    return $filters ? add_query_arg( $filters, $base ) : $base;
}

function vs2_directory_base_url() {
    return get_post_type_archive_link( 'profile' );
}

function vs2_directory_query( $paged = 1, $per_page = 10 ) {
    $filters   = vs2_current_filters();
    $tax_query = [];
    if ( $filters['lcat'] ) {
        $tax_query[] = [ 'taxonomy' => 'vs2_category', 'field' => 'slug', 'terms' => $filters['lcat'] ];
    }
    if ( $filters['ind'] ) {
        $tax_query[] = [ 'taxonomy' => 'vs2_industry', 'field' => 'slug', 'terms' => $filters['ind'] ];
    }
    if ( $filters['stage'] ) {
        $tax_query[] = [ 'taxonomy' => 'vs2_stage', 'field' => 'slug', 'terms' => $filters['stage'] ];
    }
    $meta_query = [];
    if ( $filters['country'] ) {
        $meta_query[] = [ 'key' => '_vs2_country', 'value' => $filters['country'] ];
    }
    if ( $filters['loc'] ) {
        $meta_query[] = [ 'key' => '_vs2_location', 'value' => $filters['loc'] ];
    }
    $args = [
        'post_type'      => 'profile',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ( $filters['sort'] === 'name' ) {
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
    } elseif ( $filters['sort'] === 'verification' ) {
        $args['meta_key'] = '_vs2_tier';
        $args['orderby']  = [ 'meta_value' => 'DESC', 'date' => 'DESC' ];
    }
    if ( $filters['s'] ) $args['s'] = $filters['s'];
    if ( $tax_query )    $args['tax_query']  = array_merge( [ 'relation' => 'AND' ], $tax_query );
    if ( $meta_query )   $args['meta_query'] = $meta_query;
    return new WP_Query( $args );
}

function vs2_ajax_load_more_businesses() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    foreach ( [ 's', 'ind', 'country', 'loc', 'lcat', 'sort' ] as $key ) {
        $_GET[ $key ] = $parsed[ $key ] ?? '';
    }

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_directory_query( $page, 10 );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/profile-card' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_businesses', 'vs2_ajax_load_more_businesses' );
add_action( 'wp_ajax_nopriv_vs2_load_more_businesses', 'vs2_ajax_load_more_businesses' );

// ── PROFESSIONAL DIRECTORY FILTERING ──

function vs2_pro_current_filters() {
    return [
        's'       => isset( $_GET['s'] )       ? sanitize_text_field( wp_unslash( $_GET['s'] ) )      : '',
        'field'   => isset( $_GET['field'] )   ? sanitize_title( wp_unslash( $_GET['field'] ) )        : '',
        'country' => isset( $_GET['country'] ) ? sanitize_text_field( wp_unslash( $_GET['country'] ) ) : '',
    ];
}

function vs2_pro_base_url() {
    return get_post_type_archive_link( 'professional' );
}

function vs2_pro_filter_url( $base, $overrides = [] ) {
    $filters = array_merge( vs2_pro_current_filters(), $overrides );
    $filters = array_filter( $filters, fn( $v ) => $v !== '' );
    return $filters ? add_query_arg( $filters, $base ) : $base;
}

function vs2_pro_query( $paged = 1, $per_page = 10 ) {
    $filters = vs2_pro_current_filters();
    $args    = [
        'post_type'      => 'professional',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ( $filters['s'] ) $args['s'] = $filters['s'];
    if ( $filters['field'] ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'vs2_pro_field', 'field' => 'slug', 'terms' => $filters['field'] ] ];
    }
    if ( $filters['country'] ) {
        $args['meta_query'] = [ [ 'key' => '_vs2p_country', 'value' => $filters['country'] ] ];
    }
    return new WP_Query( $args );
}

function vs2_ajax_load_more_professionals() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    foreach ( [ 's', 'field', 'country' ] as $key ) {
        $_GET[ $key ] = $parsed[ $key ] ?? '';
    }

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_pro_query( $page, 10 );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/professional-card' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_professionals', 'vs2_ajax_load_more_professionals' );
add_action( 'wp_ajax_nopriv_vs2_load_more_professionals', 'vs2_ajax_load_more_professionals' );

// ── ORGANIZATION DIRECTORY FILTERING ──

function vs2_org_current_filters() {
    return [
        's'       => isset( $_GET['s'] )       ? sanitize_text_field( wp_unslash( $_GET['s'] ) )      : '',
        'type'    => isset( $_GET['type'] )    ? sanitize_title( wp_unslash( $_GET['type'] ) )         : '',
        'country' => isset( $_GET['country'] ) ? sanitize_text_field( wp_unslash( $_GET['country'] ) ) : '',
    ];
}

function vs2_org_base_url() {
    return get_post_type_archive_link( 'organization' );
}

function vs2_org_filter_url( $base, $overrides = [] ) {
    $filters = array_merge( vs2_org_current_filters(), $overrides );
    $filters = array_filter( $filters, fn( $v ) => $v !== '' );
    return $filters ? add_query_arg( $filters, $base ) : $base;
}

function vs2_org_query( $paged = 1, $per_page = 10 ) {
    $filters = vs2_org_current_filters();
    $args    = [
        'post_type'      => 'organization',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ( $filters['s'] ) $args['s'] = $filters['s'];
    if ( $filters['type'] ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'vs2_org_type', 'field' => 'slug', 'terms' => $filters['type'] ] ];
    }
    if ( $filters['country'] ) {
        $args['meta_query'] = [ [ 'key' => '_vs2o_country', 'value' => $filters['country'] ] ];
    }
    return new WP_Query( $args );
}

function vs2_ajax_load_more_organizations() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    foreach ( [ 's', 'type', 'country' ] as $key ) {
        $_GET[ $key ] = $parsed[ $key ] ?? '';
    }

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_org_query( $page, 10 );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/organization-card' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_organizations', 'vs2_ajax_load_more_organizations' );
add_action( 'wp_ajax_nopriv_vs2_load_more_organizations', 'vs2_ajax_load_more_organizations' );

// ── INSTITUTION DIRECTORY FILTERING ──

function vs2_ins_current_filters() {
    return [
        's'       => isset( $_GET['s'] )       ? sanitize_text_field( wp_unslash( $_GET['s'] ) )      : '',
        'type'    => isset( $_GET['type'] )    ? sanitize_title( wp_unslash( $_GET['type'] ) )         : '',
        'country' => isset( $_GET['country'] ) ? sanitize_text_field( wp_unslash( $_GET['country'] ) ) : '',
    ];
}

function vs2_ins_base_url() {
    return get_post_type_archive_link( 'institution' );
}

function vs2_ins_filter_url( $base, $overrides = [] ) {
    $filters = array_merge( vs2_ins_current_filters(), $overrides );
    $filters = array_filter( $filters, fn( $v ) => $v !== '' );
    return $filters ? add_query_arg( $filters, $base ) : $base;
}

function vs2_ins_query( $paged = 1, $per_page = 10 ) {
    $filters = vs2_ins_current_filters();
    $args    = [
        'post_type'      => 'institution',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ( $filters['s'] ) $args['s'] = $filters['s'];
    if ( $filters['type'] ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'vs2_institution_type', 'field' => 'slug', 'terms' => $filters['type'] ] ];
    }
    if ( $filters['country'] ) {
        $args['meta_query'] = [ [ 'key' => '_vs2i_country', 'value' => $filters['country'] ] ];
    }
    return new WP_Query( $args );
}

function vs2_ajax_load_more_institutions() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    foreach ( [ 's', 'type', 'country' ] as $key ) {
        $_GET[ $key ] = $parsed[ $key ] ?? '';
    }

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_ins_query( $page, 10 );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/institution-card' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_institutions', 'vs2_ajax_load_more_institutions' );
add_action( 'wp_ajax_nopriv_vs2_load_more_institutions', 'vs2_ajax_load_more_institutions' );

function vs2_ajax_load_more_guides() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    $cat = isset( $parsed['cat'] ) ? sanitize_title( $parsed['cat'] ) : '';

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_guides_query( $page, 5, $cat );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/resource-item' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_guides', 'vs2_ajax_load_more_guides' );
add_action( 'wp_ajax_nopriv_vs2_load_more_guides', 'vs2_ajax_load_more_guides' );

function vs2_ajax_load_more_resources() {
    check_ajax_referer( 'vs2_load_more', 'nonce' );

    parse_str( isset( $_POST['query'] ) ? wp_unslash( $_POST['query'] ) : '', $parsed );
    $cat = isset( $parsed['cat'] ) ? sanitize_title( $parsed['cat'] ) : '';

    $page = max( 1, (int) ( $_POST['page'] ?? 1 ) );
    $q    = vs2_resources_query( $page, 5, $cat );

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) {
            $q->the_post();
            get_template_part( 'template-parts/resource-card' );
        }
    }
    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success( [
        'html'     => $html,
        'has_more' => $q->max_num_pages > $page,
    ] );
}
add_action( 'wp_ajax_vs2_load_more_resources', 'vs2_ajax_load_more_resources' );
add_action( 'wp_ajax_nopriv_vs2_load_more_resources', 'vs2_ajax_load_more_resources' );

function vs2_resources_page_url() {
    $pages = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-templates/business-resources.php',
    ] );
    return $pages ? get_permalink( $pages[0] ) : vs2_resource_base_url();
}

function vs2_blog_url() {
    $pages = get_posts( [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-templates/blog.php',
    ] );
    return $pages ? get_permalink( $pages[0] ) : '';
}

function vs2_read_time( $post_id = null ) {
    $content = get_post_field( 'post_content', $post_id ?? get_the_ID() );
    $words   = str_word_count( wp_strip_all_tags( $content ) );
    $mins    = max( 1, (int) ceil( $words / 200 ) );
    return $mins . ' min read';
}
