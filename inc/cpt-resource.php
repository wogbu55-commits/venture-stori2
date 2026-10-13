<?php
/**
 * "Resource" (Guides & Resources) custom post type and taxonomy.
 * Deliberately its own post type — these guides never mix with Editor's Picks/Stories.
 */

function vs2_register_resource_cpt() {
    register_post_type( 'resource', [
        'labels' => [
            'name'          => 'Guides & Resources',
            'singular_name' => 'Guide / Resource',
            'add_new_item'  => 'Add New Guide',
            'edit_item'     => 'Edit Guide',
            'new_item'      => 'New Guide',
            'view_item'     => 'View Guide',
            'search_items'  => 'Search Guides',
            'not_found'     => 'No guides found',
            'all_items'     => 'All Guides & Resources',
        ],
        'public'       => true,
        'has_archive'  => 'guides-resources',
        'rewrite'      => [ 'slug' => 'guide', 'with_front' => false ],
        'menu_icon'    => 'dashicons-book-alt',
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'vs2_resource_category', 'resource', [
        'labels' => [
            'name'          => 'Resource Categories',
            'singular_name' => 'Resource Category',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'guide-category' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'vs2_guide_section', 'resource', [
        'labels' => [
            'name'          => 'Guide Sections',
            'singular_name' => 'Guide Section',
            'add_new_item'  => 'Add New Guide Section',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'guide-section' ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'vs2_register_resource_cpt' );

function vs2_default_resource_categories() {
    return [ 'Registration', 'Career Guide', 'Export', 'Industry', 'Associations', 'Startup' ];
}

function vs2_default_guide_sections() {
    return [ 'Start a Business', 'Grow a Business', 'Find Businesses', 'Find Professionals', 'By State', 'By Industry' ];
}

function vs2_africa_countries() {
    return [
        'Algeria', 'Angola', 'Benin', 'Botswana', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cameroon',
        'Central African Republic', 'Chad', 'Comoros', 'Democratic Republic of the Congo', 'Republic of the Congo',
        'Djibouti', 'Egypt', 'Equatorial Guinea', 'Eritrea', 'Eswatini', 'Ethiopia', 'Gabon', 'Gambia', 'Ghana',
        'Guinea', 'Guinea-Bissau', 'Ivory Coast', 'Kenya', 'Lesotho', 'Liberia', 'Libya', 'Madagascar', 'Malawi',
        'Mali', 'Mauritania', 'Mauritius', 'Morocco', 'Mozambique', 'Namibia', 'Niger', 'Nigeria', 'Rwanda',
        'Sao Tome and Principe', 'Senegal', 'Seychelles', 'Sierra Leone', 'Somalia', 'South Africa', 'South Sudan',
        'Sudan', 'Tanzania', 'Togo', 'Tunisia', 'Uganda', 'Zambia', 'Zimbabwe', 'Other',
    ];
}

function vs2_maybe_seed_resource_categories() {
    if ( ! get_option( 'vs2_resource_categories_seeded' ) ) {
        foreach ( vs2_default_resource_categories() as $term ) {
            if ( ! term_exists( $term, 'vs2_resource_category' ) ) {
                wp_insert_term( $term, 'vs2_resource_category' );
            }
        }
        update_option( 'vs2_resource_categories_seeded', 1 );
    }

    if ( ! get_option( 'vs2_guide_sections_seeded' ) ) {
        foreach ( vs2_default_guide_sections() as $term ) {
            if ( ! term_exists( $term, 'vs2_guide_section' ) ) {
                wp_insert_term( $term, 'vs2_guide_section' );
            }
        }
        update_option( 'vs2_guide_sections_seeded', 1 );
    }
}
add_action( 'init', 'vs2_maybe_seed_resource_categories', 20 );


// ── META BOX: TYPE + COUNTRY ──

function vs2_add_resource_meta_box() {
    add_meta_box( 'vs2_resource_details', 'Guide / Resource Details', 'vs2_render_resource_meta_box', 'resource', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'vs2_add_resource_meta_box' );

function vs2_render_resource_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_resource_meta', 'vs2_resource_meta_nonce' );
    $type    = get_post_meta( $post->ID, '_vs2_resource_type', true ) ?: 'guide';
    $country = get_post_meta( $post->ID, '_vs2_resource_country', true ) ?: 'Nigeria';
    $state   = get_post_meta( $post->ID, '_vs2_resource_state', true );
    ?>
    <p><strong>List this as</strong></p>
    <p>
      <label><input type="radio" name="vs2_resource_type" value="guide" <?php checked( $type, 'guide' ); ?>> Guide</label><br>
      <label><input type="radio" name="vs2_resource_type" value="resource" <?php checked( $type, 'resource' ); ?>> Resource</label>
    </p>
    <p>
      <label for="vs2_resource_country"><strong>Country</strong></label><br>
      <select id="vs2_resource_country" name="vs2_resource_country" style="width:100%;">
        <?php foreach ( vs2_africa_countries() as $vs2_c ) : ?>
          <option value="<?php echo esc_attr( $vs2_c ); ?>" <?php selected( $country, $vs2_c ); ?>><?php echo esc_html( $vs2_c ); ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p id="vs2_resource_state_row" style="<?php echo $country === 'Nigeria' ? '' : 'display:none;'; ?>">
      <label for="vs2_resource_state"><strong>State</strong></label><br>
      <select id="vs2_resource_state" name="vs2_resource_state" style="width:100%;">
        <option value="" <?php selected( $state, '' ); ?>>All states / Nationwide</option>
        <?php foreach ( vs2_nigeria_states() as $vs2_s ) : ?>
          <option value="<?php echo esc_attr( $vs2_s ); ?>" <?php selected( $state, $vs2_s ); ?>><?php echo esc_html( $vs2_s ); ?></option>
        <?php endforeach; ?>
      </select>
    </p>
    <p class="description">Guides tagged with a Guide Section and set to a country appear inside that country's business guide page.</p>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var radios     = document.querySelectorAll('input[name="vs2_resource_type"]');
      var guideBox   = document.getElementById('vs2_guide_sectiondiv');
      var resBox     = document.getElementById('vs2_resource_categorydiv');
      var countrySel = document.getElementById('vs2_resource_country');
      var stateRow   = document.getElementById('vs2_resource_state_row');
      function sync(){
        var isGuide = document.querySelector('input[name="vs2_resource_type"]:checked').value === 'guide';
        if (guideBox) guideBox.style.display = isGuide ? '' : 'none';
        if (resBox)   resBox.style.display   = isGuide ? 'none' : '';
      }
      function syncState(){
        if (stateRow) stateRow.style.display = countrySel.value === 'Nigeria' ? '' : 'none';
      }
      radios.forEach(function(r){ r.addEventListener('change', sync); });
      if (countrySel) countrySel.addEventListener('change', syncState);
      sync();
      syncState();
    });
    </script>
    <?php
}

function vs2_save_resource_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_resource_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_resource_meta_nonce'], 'vs2_save_resource_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['vs2_resource_type'] ) ) {
        $type = sanitize_key( wp_unslash( $_POST['vs2_resource_type'] ) );
        update_post_meta( $post_id, '_vs2_resource_type', in_array( $type, [ 'guide', 'resource' ], true ) ? $type : 'guide' );
    }
    if ( isset( $_POST['vs2_resource_country'] ) ) {
        $country = sanitize_text_field( wp_unslash( $_POST['vs2_resource_country'] ) );
        update_post_meta( $post_id, '_vs2_resource_country', in_array( $country, vs2_africa_countries(), true ) ? $country : 'Nigeria' );
    }
    if ( isset( $_POST['vs2_resource_state'] ) ) {
        $state = sanitize_text_field( wp_unslash( $_POST['vs2_resource_state'] ) );
        update_post_meta( $post_id, '_vs2_resource_state', in_array( $state, vs2_nigeria_states(), true ) ? $state : '' );
    }
}
add_action( 'save_post_resource', 'vs2_save_resource_meta' );


// ── HELPERS ──

function vs2_resource_category_name( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $terms   = get_the_terms( $post_id, 'vs2_resource_category' );
    return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

function vs2_guide_section_name( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $terms   = get_the_terms( $post_id, 'vs2_guide_section' );
    return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

// Returns the right category label for a post depending on whether it's a Guide or a Resource.
function vs2_resource_tag_name( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return vs2_resource_type( $post_id ) === 'guide' ? vs2_guide_section_name( $post_id ) : vs2_resource_category_name( $post_id );
}

function vs2_resource_type( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2_resource_type', true ) ?: 'guide';
}

function vs2_resource_country( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2_resource_country', true ) ?: 'Nigeria';
}

function vs2_resource_state( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2_resource_state', true );
}

function vs2_resources( $count = 6 ) {
    $q = new WP_Query( [
        'post_type'      => 'resource',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}

function vs2_resource_base_url() {
    return get_post_type_archive_link( 'resource' );
}

// $paged: 1-indexed page number. Returns WP_Query of published guide-type posts only.
// $section_slug filters by vs2_guide_section (Start a Business, Grow a Business, etc.) — the guides page's real category axis.
function vs2_guides_query( $paged = 1, $per_page = 5, $section_slug = '' ) {
    $args = [
        'post_type'      => 'resource',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            'relation' => 'OR',
            [ 'key' => '_vs2_resource_type', 'value' => 'guide' ],
            [ 'key' => '_vs2_resource_type', 'compare' => 'NOT EXISTS' ],
        ],
    ];
    if ( $section_slug ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'vs2_guide_section', 'field' => 'slug', 'terms' => $section_slug ] ];
    }
    return new WP_Query( $args );
}

// $paged: 1-indexed page number. Returns WP_Query of published resource-type posts only.
function vs2_resources_query( $paged = 1, $per_page = 5, $cat_slug = '' ) {
    $args = [
        'post_type'      => 'resource',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [ [ 'key' => '_vs2_resource_type', 'value' => 'resource' ] ],
    ];
    if ( $cat_slug ) {
        $args['tax_query'] = [ [ 'taxonomy' => 'vs2_resource_category', 'field' => 'slug', 'terms' => $cat_slug ] ];
    }
    return new WP_Query( $args );
}

// Real guide posts for a given ng-guide section + country, used inside business-guide.php.
function vs2_guides_by_section( $section_slug, $country = 'Nigeria', $count = 4 ) {
    $q = new WP_Query( [
        'post_type'      => 'resource',
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'tax_query'       => [ [ 'taxonomy' => 'vs2_guide_section', 'field' => 'slug', 'terms' => $section_slug ] ],
        'meta_query'      => [ [ 'key' => '_vs2_resource_country', 'value' => $country ] ],
    ] );
    $posts = $q->posts;
    wp_reset_postdata();
    return $posts;
}
