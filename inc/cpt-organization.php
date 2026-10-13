<?php
/**
 * "Organization" custom post type, taxonomy, and meta box.
 */

function vs2_register_organization_cpt() {
    register_post_type( 'organization', [
        'labels' => [
            'name'          => 'Organizations',
            'singular_name' => 'Organization',
            'add_new_item'  => 'Add New Organization',
            'edit_item'     => 'Edit Organization',
            'new_item'      => 'New Organization',
            'view_item'     => 'View Organization',
            'search_items'  => 'Search Organizations',
            'not_found'     => 'No organizations found',
            'all_items'     => 'All Organizations',
        ],
        'public'       => true,
        'has_archive'  => 'organizations',
        'rewrite'      => [ 'slug' => 'organization', 'with_front' => false ],
        'menu_icon'    => 'dashicons-groups',
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'vs2_org_type', 'organization', [
        'labels' => [
            'name'          => 'Organization Types',
            'singular_name' => 'Type',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'organization-type' ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'vs2_register_organization_cpt' );

function vs2_default_org_types() {
    return [ 'Professional Body', 'Trade Association', 'Student Group', 'Chamber of Commerce', 'Community' ];
}

function vs2_maybe_seed_org_types() {
    if ( get_option( 'vs2_org_types_seeded' ) ) return;
    foreach ( vs2_default_org_types() as $term ) {
        if ( ! term_exists( $term, 'vs2_org_type' ) ) {
            wp_insert_term( $term, 'vs2_org_type' );
        }
    }
    update_option( 'vs2_org_types_seeded', 1 );
}
add_action( 'init', 'vs2_maybe_seed_org_types', 20 );


// ── META BOX ──

function vs2_organization_meta_fields() {
    return [
        '_vs2o_scope'            => 'text',
        '_vs2o_tier'             => 'select',
        '_vs2o_founded'          => 'text',
        '_vs2o_member_count'     => 'text',
        '_vs2o_chapter_count'    => 'text',
        '_vs2o_profiles_listed'  => 'text',
        '_vs2o_country'          => 'text',
        '_vs2o_membership_desc'  => 'textarea',
        '_vs2o_join_url'         => 'text',
        '_vs2o_partner_url'      => 'text',
        '_vs2o_chapters'         => 'textarea',
        '_vs2o_events'           => 'textarea',
        '_vs2o_website'          => 'text',
        '_vs2o_email'            => 'text',
        '_vs2o_phone'            => 'text',
    ];
}

function vs2_add_organization_meta_box() {
    add_meta_box( 'vs2_organization_details', 'Organization Details', 'vs2_render_organization_meta_box', 'organization', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vs2_add_organization_meta_box' );

function vs2_render_organization_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_organization_meta', 'vs2_organization_meta_nonce' );
    $v = fn( $key ) => esc_attr( get_post_meta( $post->ID, $key, true ) );
    $t = fn( $key ) => esc_textarea( get_post_meta( $post->ID, $key, true ) );
    $tier = get_post_meta( $post->ID, '_vs2o_tier', true ) ?: 'listed';
    ?>
    <style>
      .vs2-mb-row{display:flex;gap:16px;margin-bottom:14px;flex-wrap:wrap;}
      .vs2-mb-row > div{flex:1;min-width:150px;}
      .vs2-mb-row label{display:block;font-weight:600;margin-bottom:4px;font-size:12.5px;}
      .vs2-mb-row input[type=text],.vs2-mb-row select,.vs2-mb-row textarea{width:100%;}
      .vs2-mb-hint{color:#777;font-size:11.5px;margin-top:3px;}
      .vs2-mb-section-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#333;margin:22px 0 12px;padding-top:18px;border-top:1px solid #e2e2e2;}
      .vs2-mb-section-title:first-child{margin-top:0;padding-top:0;border-top:none;}
    </style>

    <div class="vs2-mb-section-title">Basics</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_scope">Scope line (e.g. "Healthcare · National")</label>
        <input type="text" id="vs2o_scope" name="vs2o_scope" value="<?php echo $v('_vs2o_scope'); ?>">
      </div>
      <div>
        <label for="vs2o_tier">Badge</label>
        <select id="vs2o_tier" name="vs2o_tier">
          <option value="listed" <?php selected( $tier, 'listed' ); ?>>Listed</option>
          <option value="verified" <?php selected( $tier, 'verified' ); ?>>Verified</option>
          <option value="certified" <?php selected( $tier, 'certified' ); ?>>Certified</option>
        </select>
      </div>
      <div>
        <label for="vs2o_founded">Founded (year)</label>
        <input type="text" id="vs2o_founded" name="vs2o_founded" value="<?php echo $v('_vs2o_founded'); ?>">
      </div>
      <div>
        <label for="vs2o_country">Country (used for filters)</label>
        <input type="text" id="vs2o_country" name="vs2o_country" value="<?php echo $v('_vs2o_country'); ?>">
      </div>
    </div>
    <p class="vs2-mb-hint" style="margin-bottom:14px;">Assign an <strong>Organization Type</strong> in the sidebar — it drives the directory tabs and filter dropdown.</p>

    <div class="vs2-mb-section-title">Stats</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_member_count">Members</label>
        <input type="text" id="vs2o_member_count" name="vs2o_member_count" value="<?php echo $v('_vs2o_member_count'); ?>" placeholder="72,000">
      </div>
      <div>
        <label for="vs2o_chapter_count">Chapters</label>
        <input type="text" id="vs2o_chapter_count" name="vs2o_chapter_count" value="<?php echo $v('_vs2o_chapter_count'); ?>" placeholder="37">
      </div>
      <div>
        <label for="vs2o_profiles_listed">Profiles listed on Venture Stori</label>
        <input type="text" id="vs2o_profiles_listed" name="vs2o_profiles_listed" value="<?php echo $v('_vs2o_profiles_listed'); ?>" placeholder="4,200">
      </div>
    </div>

    <div class="vs2-mb-section-title">Membership &amp; chapters</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_membership_desc">Membership description</label>
        <textarea id="vs2o_membership_desc" name="vs2o_membership_desc" rows="3"><?php echo $t('_vs2o_membership_desc'); ?></textarea>
      </div>
      <div>
        <label for="vs2o_chapters">State / regional chapters (one per line)</label>
        <textarea id="vs2o_chapters" name="vs2o_chapters" rows="3" placeholder="Lagos State Chapter&#10;Abuja (FCT) Chapter"><?php echo $t('_vs2o_chapters'); ?></textarea>
      </div>
    </div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_join_url">Join link</label>
        <input type="text" id="vs2o_join_url" name="vs2o_join_url" value="<?php echo $v('_vs2o_join_url'); ?>">
      </div>
      <div>
        <label for="vs2o_partner_url">Partner link</label>
        <input type="text" id="vs2o_partner_url" name="vs2o_partner_url" value="<?php echo $v('_vs2o_partner_url'); ?>">
      </div>
    </div>

    <div class="vs2-mb-section-title">Upcoming events (optional)</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_events">One per line: <code>Title | Date · Location</code></label>
        <textarea id="vs2o_events" name="vs2o_events" rows="3" placeholder="NMA Annual General Meeting | Oct 14–17, 2026 · Abuja"><?php echo $t('_vs2o_events'); ?></textarea>
      </div>
    </div>

    <div class="vs2-mb-section-title">Contact &amp; relationships</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2o_website">Website</label>
        <input type="text" id="vs2o_website" name="vs2o_website" value="<?php echo $v('_vs2o_website'); ?>">
      </div>
      <div>
        <label for="vs2o_email">Email</label>
        <input type="text" id="vs2o_email" name="vs2o_email" value="<?php echo $v('_vs2o_email'); ?>">
      </div>
      <div>
        <label for="vs2o_phone">Phone</label>
        <input type="text" id="vs2o_phone" name="vs2o_phone" value="<?php echo $v('_vs2o_phone'); ?>">
      </div>
    </div>
    <div class="vs2-mb-row">
      <?php vs2_render_relational_picker( 'vs2o_featured_members_ids', 'Featured Members', 'professional', get_post_meta( $post->ID, '_vs2o_featured_members_ids', true ) ); ?>
      <?php vs2_render_relational_picker( 'vs2o_affiliated_institutions_ids', 'Affiliated Institutions', 'institution', get_post_meta( $post->ID, '_vs2o_affiliated_institutions_ids', true ) ); ?>
    </div>
    <?php
}

function vs2_save_organization_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_organization_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_organization_meta_nonce'], 'vs2_save_organization_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( vs2_organization_meta_fields() as $key => $type ) {
        $field = ltrim( $key, '_' );
        if ( ! isset( $_POST[ $field ] ) ) continue;
        $value = $type === 'textarea' ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
        update_post_meta( $post_id, $key, $value );
    }
    vs2_save_relational_field( $post_id, '_vs2o_featured_members_ids', 'vs2o_featured_members_ids' );
    vs2_save_relational_field( $post_id, '_vs2o_affiliated_institutions_ids', 'vs2o_affiliated_institutions_ids' );
}
add_action( 'save_post_organization', 'vs2_save_organization_meta' );


// ── HELPERS ──

function vs2_organization_tier( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2o_tier', true ) ?: 'listed';
}

function vs2_organization_lines( $post_id, $meta_key ) {
    $raw = get_post_meta( $post_id, $meta_key, true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', preg_split( '/\r?\n/', $raw ) ) );
}

function vs2_organization_events( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $rows = [];
    foreach ( vs2_organization_lines( $post_id, '_vs2o_events' ) as $line ) {
        if ( strpos( $line, '|' ) === false ) continue;
        [ $title, $meta ] = array_map( 'trim', explode( '|', $line, 2 ) );
        $rows[] = [ 'title' => $title, 'meta' => $meta ];
    }
    return $rows;
}

function vs2_all_organization_countries() {
    global $wpdb;
    $countries = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND p.post_status = 'publish'
         ORDER BY meta_value ASC",
        '_vs2o_country'
    ) );
    return $countries ?: [];
}
