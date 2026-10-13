<?php
/**
 * "Business" (post type slug: profile) custom post type, taxonomies, and meta box.
 */

function vs2_register_profile_cpt() {
    register_post_type( 'profile', [
        'labels' => [
            'name'               => 'Businesses',
            'singular_name'      => 'Business',
            'add_new_item'       => 'Add New Business',
            'edit_item'          => 'Edit Business',
            'new_item'           => 'New Business',
            'view_item'          => 'View Business',
            'search_items'       => 'Search Businesses',
            'not_found'          => 'No businesses found',
            'all_items'          => 'All Businesses',
        ],
        'public'       => true,
        'has_archive'  => 'directory',
        'rewrite'      => [ 'slug' => 'profile', 'with_front' => false ],
        'menu_icon'    => 'dashicons-store',
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'vs2_industry', 'profile', [
        'labels' => [
            'name'          => 'Industries',
            'singular_name' => 'Industry',
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'industry' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'vs2_stage', 'profile', [
        'labels' => [
            'name'          => 'Funding Stages',
            'singular_name' => 'Funding Stage',
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'stage' ],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'vs2_category', 'profile', [
        'labels' => [
            'name'          => 'Business Types',
            'singular_name' => 'Business Type',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'listing-category' ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'vs2_register_profile_cpt' );

function vs2_default_industries() {
    return [ 'FinTech', 'AI', 'Logistics', 'HealthTech', 'Nonprofits & Ventures', 'Diaspora', 'Investors', 'Healthcare', 'Fashion', 'Agriculture' ];
}

function vs2_default_categories() {
    return [ 'Startups', 'SMEs', 'Corporations', 'NGOs', 'Farms', 'Clinics' ];
}

function vs2_maybe_seed_terms() {
    if ( get_option( 'vs2_terms_seeded' ) ) return;
    foreach ( vs2_default_industries() as $term ) {
        if ( ! term_exists( $term, 'vs2_industry' ) ) {
            wp_insert_term( $term, 'vs2_industry' );
        }
    }
    foreach ( [ 'Pre-seed', 'Seed', 'Series A', 'Series B+', 'Nonprofit' ] as $term ) {
        if ( ! term_exists( $term, 'vs2_stage' ) ) {
            wp_insert_term( $term, 'vs2_stage' );
        }
    }
    update_option( 'vs2_terms_seeded', 1 );
}
add_action( 'init', 'vs2_maybe_seed_terms', 20 );

function vs2_maybe_seed_category_terms() {
    if ( get_option( 'vs2_category_terms_v2_seeded' ) ) return;
    // Replace the old (pre-directory-overhaul) category terms with the new business-type set.
    $old_terms = get_terms( [ 'taxonomy' => 'vs2_category', 'hide_empty' => false ] );
    if ( ! is_wp_error( $old_terms ) ) {
        $new_set = vs2_default_categories();
        foreach ( $old_terms as $t ) {
            if ( ! in_array( $t->name, $new_set, true ) ) {
                wp_delete_term( $t->term_id, 'vs2_category' );
            }
        }
    }
    foreach ( vs2_default_categories() as $term ) {
        if ( ! term_exists( $term, 'vs2_category' ) ) {
            wp_insert_term( $term, 'vs2_category' );
        }
    }
    update_option( 'vs2_category_terms_v2_seeded', 1 );
}
add_action( 'init', 'vs2_maybe_seed_category_terms', 20 );

function vs2_maybe_migrate_tier_values() {
    if ( get_option( 'vs2_tier_certified_migrated' ) ) return;
    global $wpdb;
    $wpdb->update( $wpdb->postmeta, [ 'meta_value' => 'certified' ], [ 'meta_key' => '_vs2_tier', 'meta_value' => 'sponsored' ] );
    update_option( 'vs2_tier_certified_migrated', 1 );
}
add_action( 'init', 'vs2_maybe_migrate_tier_values', 21 );


// ── META BOX ──

function vs2_profile_meta_fields() {
    return [
        '_vs2_role'          => 'text',
        '_vs2_founder_name'  => 'text',
        '_vs2_website'       => 'text',
        '_vs2_twitter'       => 'text',
        '_vs2_linkedin'      => 'text',
        '_vs2_tiktok'        => 'text',
        '_vs2_youtube'       => 'text',
        '_vs2_spotify'       => 'text',
        '_vs2_location'      => 'text',
        '_vs2_country'       => 'text',
        '_vs2_tier'          => 'select',
        '_vs2_founded'       => 'text',
        '_vs2_subcategory'   => 'text',
        '_vs2_team_size'     => 'text',
        '_vs2_email'         => 'text',
        '_vs2_services'      => 'text',
        '_vs2_metric1_label' => 'text',
        '_vs2_metric1_value' => 'text',
        '_vs2_metric2_label' => 'text',
        '_vs2_metric2_value' => 'text',
        '_vs2_metric3_label' => 'text',
        '_vs2_metric3_value' => 'text',
        '_vs2_views'         => 'number',
        '_vs2_timeline'      => 'textarea',
        '_vs2_related'       => 'textarea',
        '_vs2_whatsapp'      => 'text',
        '_vs2_cta_url'       => 'text',
        '_vs2_photo_1'       => 'image',
        '_vs2_photo_2'       => 'image',
        '_vs2_photo_3'       => 'image',
        '_vs2_tagline'       => 'text',
        '_vs2_locality'      => 'text',
        '_vs2_serves'        => 'text',
        '_vs2_hours'         => 'text',
        '_vs2_address'       => 'text',
        '_vs2_phone'         => 'text',
        '_vs2_instagram'     => 'text',
        '_vs2_facebook'      => 'text',
        '_vs2_submission_method' => 'select',
    ];
}

function vs2_profile_social_fields() {
    return [
        '_vs2_twitter'  => 'Twitter / X',
        '_vs2_linkedin' => 'LinkedIn',
        '_vs2_tiktok'   => 'TikTok',
        '_vs2_youtube'  => 'YouTube',
        '_vs2_spotify'  => 'Spotify',
    ];
}

function vs2_add_profile_meta_box() {
    add_meta_box( 'vs2_profile_details', 'Business Details', 'vs2_render_profile_meta_box', 'profile', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vs2_add_profile_meta_box' );

function vs2_render_profile_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_profile_meta', 'vs2_profile_meta_nonce' );
    $v = fn( $key ) => esc_attr( get_post_meta( $post->ID, $key, true ) );
    $tier = get_post_meta( $post->ID, '_vs2_tier', true ) ?: 'listed';
    ?>
    <style>
      .vs2-mb-row{display:flex;gap:16px;margin-bottom:14px;flex-wrap:wrap;}
      .vs2-mb-row > div{flex:1;min-width:150px;}
      .vs2-mb-row label{display:block;font-weight:600;margin-bottom:4px;font-size:12.5px;}
      .vs2-mb-row input[type=text],.vs2-mb-row input[type=number],.vs2-mb-row select,.vs2-mb-row textarea{width:100%;}
      .vs2-mb-hint{color:#777;font-size:11.5px;margin-top:3px;}
      .vs2-mb-photo-preview{max-width:120px;max-height:80px;display:block;margin-bottom:6px;border:1px solid #ddd;}
      .vs2-mb-section-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#333;margin:22px 0 12px;padding-top:18px;border-top:1px solid #e2e2e2;}
      .vs2-mb-section-title:first-child{margin-top:0;padding-top:0;border-top:none;}
    </style>

    <div class="vs2-mb-section-title">Basics</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2_tier">Badge</label>
        <select id="vs2_tier" name="vs2_tier">
          <option value="listed" <?php selected( $tier, 'listed' ); ?>>Listed</option>
          <option value="verified" <?php selected( $tier, 'verified' ); ?>>Verified</option>
          <option value="certified" <?php selected( $tier, 'certified' ); ?>>Certified</option>
        </select>
      </div>
      <div>
        <label for="vs2_subcategory">Sub-category</label>
        <input type="text" id="vs2_subcategory" name="vs2_subcategory" value="<?php echo $v('_vs2_subcategory'); ?>" placeholder="e.g. Startup Law, Regulatory Compliance">
      </div>
      <div>
        <label for="vs2_founded">Founded (year)</label>
        <input type="text" id="vs2_founded" name="vs2_founded" value="<?php echo $v('_vs2_founded'); ?>" placeholder="e.g. 2019">
      </div>
      <div>
        <label for="vs2_team_size">Team size</label>
        <input type="text" id="vs2_team_size" name="vs2_team_size" value="<?php echo $v('_vs2_team_size'); ?>" placeholder="e.g. 2 – 10">
      </div>
    </div>
    <p class="vs2-mb-hint" style="margin-bottom:14px;">Assign an <strong>Industry</strong> and a <strong>Business Type</strong> (Startups/SMEs/Corporations/NGOs/Farms/Clinics) in the sidebar — Business Type drives the directory tabs, Industry drives the filter dropdown.</p>

    <div class="vs2-mb-row">
      <div>
        <label for="vs2_location">Location</label>
        <input type="text" id="vs2_location" name="vs2_location" value="<?php echo $v('_vs2_location'); ?>" placeholder="e.g. Independence Layout, Enugu, Nigeria">
      </div>
      <div>
        <label for="vs2_country">Country (used for the country filter)</label>
        <input type="text" id="vs2_country" name="vs2_country" value="<?php echo $v('_vs2_country'); ?>" placeholder="e.g. Nigeria">
      </div>
      <div>
        <label for="vs2_founder_name">Founder's name</label>
        <input type="text" id="vs2_founder_name" name="vs2_founder_name" value="<?php echo $v('_vs2_founder_name'); ?>" placeholder="e.g. Chidera Nwosu">
      </div>
    </div>

    <div class="vs2-mb-row">
      <div>
        <label for="vs2_services">Products &amp; Services (comma-separated tags)</label>
        <input type="text" id="vs2_services" name="vs2_services" value="<?php echo $v('_vs2_services'); ?>" placeholder="Business Registration, Regulatory Compliance, Contract Drafting">
      </div>
    </div>

    <div class="vs2-mb-section-title">Contact</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2_website">Website</label>
        <input type="text" id="vs2_website" name="vs2_website" value="<?php echo $v('_vs2_website'); ?>" placeholder="https://example.com">
      </div>
      <div>
        <label for="vs2_email">Email</label>
        <input type="text" id="vs2_email" name="vs2_email" value="<?php echo $v('_vs2_email'); ?>" placeholder="hello@example.com">
      </div>
      <div>
        <label for="vs2_phone">Phone</label>
        <input type="text" id="vs2_phone" name="vs2_phone" value="<?php echo $v('_vs2_phone'); ?>" placeholder="+234 803 xxx xxxx">
      </div>
      <div>
        <label for="vs2_whatsapp">WhatsApp link</label>
        <input type="text" id="vs2_whatsapp" name="vs2_whatsapp" value="<?php echo $v('_vs2_whatsapp'); ?>" placeholder="https://wa.me/2348012345678">
      </div>
    </div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2_instagram">Instagram</label>
        <input type="text" id="vs2_instagram" name="vs2_instagram" value="<?php echo $v('_vs2_instagram'); ?>" placeholder="https://instagram.com/…">
      </div>
      <div>
        <label for="vs2_facebook">Facebook</label>
        <input type="text" id="vs2_facebook" name="vs2_facebook" value="<?php echo $v('_vs2_facebook'); ?>" placeholder="https://facebook.com/…">
      </div>
      <?php foreach ( vs2_profile_social_fields() as $key => $label ) :
          $field = ltrim( $key, '_' );
      ?>
      <div>
        <label for="<?php echo esc_attr( $field ); ?>"><?php echo esc_html( $label ); ?></label>
        <input type="text" id="<?php echo esc_attr( $field ); ?>" name="<?php echo esc_attr( $field ); ?>" value="<?php echo $v( $key ); ?>" placeholder="https://…">
      </div>
      <?php endforeach; ?>
    </div>

    <div class="vs2-mb-section-title">Optional extras (local listings, shops, service businesses)</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2_hours">Hours</label>
        <input type="text" id="vs2_hours" name="vs2_hours" value="<?php echo $v('_vs2_hours'); ?>" placeholder="Mon–Sat, 8am–7pm">
      </div>
      <div>
        <label for="vs2_address">Street address</label>
        <input type="text" id="vs2_address" name="vs2_address" value="<?php echo $v('_vs2_address'); ?>" placeholder="12 Market Rd, Independence Layout, Enugu">
      </div>
      <div>
        <label for="vs2_serves">Serves (comma-separated tags)</label>
        <input type="text" id="vs2_serves" name="vs2_serves" value="<?php echo $v('_vs2_serves'); ?>" placeholder="Families, Small Businesses, Individuals">
      </div>
    </div>

    <div class="vs2-mb-section-title">Photos (optional gallery — up to 3)</div>
    <div class="vs2-mb-row">
      <?php foreach ( [ '_vs2_photo_1' => 'Photo 1', '_vs2_photo_2' => 'Photo 2', '_vs2_photo_3' => 'Photo 3' ] as $key => $label ) :
          $img_id  = get_post_meta( $post->ID, $key, true );
          $img_src = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : '';
      ?>
      <div>
        <label><?php echo esc_html( $label ); ?></label>
        <div class="vs2-photo-picker" data-target="<?php echo esc_attr( $key ); ?>">
          <img class="vs2-mb-photo-preview" src="<?php echo esc_url( $img_src ); ?>" style="<?php echo $img_src ? '' : 'display:none;'; ?>">
          <input type="hidden" name="<?php echo esc_attr( ltrim( $key, '_' ) ); ?>" value="<?php echo esc_attr( $img_id ); ?>" class="vs2-photo-id">
          <button type="button" class="button vs2-photo-select">Choose image</button>
          <button type="button" class="button vs2-photo-clear" style="<?php echo $img_src ? '' : 'display:none;'; ?>">Remove</button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="vs2-mb-section-title">History &amp; relationships</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2_timeline">Timeline (optional, one entry per line: <code>Year | Description</code>)</label>
        <textarea id="vs2_timeline" name="vs2_timeline" rows="3" placeholder="2024 | Raised $1.4M seed round led by a Lagos fund&#10;2022 | Founded"><?php echo esc_textarea( get_post_meta( $post->ID, '_vs2_timeline', true ) ); ?></textarea>
      </div>
      <div>
        <label for="vs2_cta_url">"Request intro" link (optional)</label>
        <input type="text" id="vs2_cta_url" name="vs2_cta_url" value="<?php echo $v('_vs2_cta_url'); ?>" placeholder="mailto:intros@venturestori.com">
      </div>
    </div>
    <div class="vs2-mb-row">
      <?php vs2_render_relational_picker( 'vs2_related_business_ids', 'Related Businesses', 'profile', get_post_meta( $post->ID, '_vs2_related_business_ids', true ) ); ?>
      <?php vs2_render_relational_picker( 'vs2_associations_ids', 'Associations (organizations this business belongs to)', 'organization', get_post_meta( $post->ID, '_vs2_associations_ids', true ) ); ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
      if (typeof wp === 'undefined' || !wp.media) return;
      document.querySelectorAll('.vs2-photo-picker').forEach(function(picker){
        var frame;
        var img   = picker.querySelector('img');
        var input = picker.querySelector('.vs2-photo-id');
        var clearBtn = picker.querySelector('.vs2-photo-clear');
        picker.querySelector('.vs2-photo-select').addEventListener('click', function(e){
          e.preventDefault();
          if (frame) { frame.open(); return; }
          frame = wp.media({ title: 'Select image', multiple: false });
          frame.on('select', function(){
            var att = frame.state().get('selection').first().toJSON();
            input.value = att.id;
            img.src = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
            img.style.display = 'block';
            clearBtn.style.display = 'inline-block';
          });
          frame.open();
        });
        clearBtn.addEventListener('click', function(e){
          e.preventDefault();
          input.value = '';
          img.style.display = 'none';
          clearBtn.style.display = 'none';
        });
      });
    });
    </script>
    <?php
}

function vs2_save_profile_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_profile_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_profile_meta_nonce'], 'vs2_save_profile_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( vs2_profile_meta_fields() as $key => $type ) {
        $field = ltrim( $key, '_' );
        if ( ! isset( $_POST[ $field ] ) ) continue;
        $value = $_POST[ $field ];
        switch ( $type ) {
            case 'textarea':
                $value = sanitize_textarea_field( wp_unslash( $value ) );
                break;
            case 'number':
            case 'image':
                $value = absint( $value );
                break;
            default:
                $value = sanitize_text_field( wp_unslash( $value ) );
        }
        update_post_meta( $post_id, $key, $value );
    }

    vs2_save_relational_field( $post_id, '_vs2_related_business_ids', 'vs2_related_business_ids' );
    vs2_save_relational_field( $post_id, '_vs2_associations_ids', 'vs2_associations_ids' );
}
add_action( 'save_post_profile', 'vs2_save_profile_meta' );

function vs2_profile_meta_box_assets( $hook ) {
    global $post;
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
    if ( ! $post || $post->post_type !== 'profile' ) return;
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'vs2_profile_meta_box_assets' );


// ── HELPERS ──

function vs2_tier( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2_tier', true ) ?: 'listed';
}

function vs2_badge_label( $tier ) {
    return [ 'verified' => '✓ Verified', 'certified' => '✓ Certified', 'listed' => 'Listed' ][ $tier ] ?? 'Listed';
}

function vs2_initials( $title ) {
    $words = preg_split( '/\s+/', trim( wp_strip_all_tags( $title ) ) );
    $words = array_filter( $words );
    if ( empty( $words ) ) return '';
    if ( count( $words ) === 1 ) return mb_strtoupper( mb_substr( $words[0], 0, 2 ) );
    return mb_strtoupper( mb_substr( $words[0], 0, 1 ) . mb_substr( end( $words ), 0, 1 ) );
}

function vs2_metrics( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $metrics = [];
    for ( $i = 1; $i <= 3; $i++ ) {
        $label = get_post_meta( $post_id, "_vs2_metric{$i}_label", true );
        $value = get_post_meta( $post_id, "_vs2_metric{$i}_value", true );
        if ( $label !== '' && $value !== '' ) {
            $metrics[] = [ 'label' => $label, 'value' => $value ];
        }
    }
    return $metrics;
}

function vs2_timeline( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $raw     = get_post_meta( $post_id, '_vs2_timeline', true );
    if ( ! $raw ) return [];
    $rows = [];
    foreach ( preg_split( '/\r?\n/', $raw ) as $line ) {
        $line = trim( $line );
        if ( $line === '' || strpos( $line, '|' ) === false ) continue;
        [ $year, $text ] = array_map( 'trim', explode( '|', $line, 2 ) );
        $rows[] = [ 'year' => $year, 'text' => $text ];
    }
    return $rows;
}

function vs2_serves( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $raw     = get_post_meta( $post_id, '_vs2_serves', true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', explode( ',', $raw ) ) );
}

function vs2_products( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $raw     = get_post_meta( $post_id, '_vs2_services', true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', explode( ',', $raw ) ) );
}

function vs2_logo_bg( $post_id ) {
    $palette = [ '#1a2744', '#2a1a0a', '#0a2a1a', '#2a0a0a', '#1a2a0a', '#0a1a2a', '#2a1a44', '#0a2a2a' ];
    return $palette[ $post_id % count( $palette ) ];
}

function vs2_all_countries() {
    global $wpdb;
    $countries = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND p.post_status = 'publish'
         ORDER BY meta_value ASC",
        '_vs2_country'
    ) );
    return $countries ?: [];
}
