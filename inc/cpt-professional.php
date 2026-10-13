<?php
/**
 * "Professional" custom post type, taxonomy, and meta box.
 */

function vs2_register_professional_cpt() {
    register_post_type( 'professional', [
        'labels' => [
            'name'          => 'Professionals',
            'singular_name' => 'Professional',
            'add_new_item'  => 'Add New Professional',
            'edit_item'     => 'Edit Professional',
            'new_item'      => 'New Professional',
            'view_item'     => 'View Professional',
            'search_items'  => 'Search Professionals',
            'not_found'     => 'No professionals found',
            'all_items'     => 'All Professionals',
        ],
        'public'       => true,
        'has_archive'  => 'professionals',
        'rewrite'      => [ 'slug' => 'professional', 'with_front' => false ],
        'menu_icon'    => 'dashicons-businessperson',
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'vs2_pro_field', 'professional', [
        'labels' => [
            'name'          => 'Professional Fields',
            'singular_name' => 'Field',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'professional-field' ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'vs2_register_professional_cpt' );

function vs2_default_pro_fields() {
    return [ 'Healthcare', 'Legal', 'Engineering', 'Technology', 'Finance', 'Education' ];
}

function vs2_maybe_seed_pro_fields() {
    if ( get_option( 'vs2_pro_fields_seeded' ) ) return;
    foreach ( vs2_default_pro_fields() as $term ) {
        if ( ! term_exists( $term, 'vs2_pro_field' ) ) {
            wp_insert_term( $term, 'vs2_pro_field' );
        }
    }
    update_option( 'vs2_pro_fields_seeded', 1 );
}
add_action( 'init', 'vs2_maybe_seed_pro_fields', 20 );


// ── META BOX ──

function vs2_professional_meta_fields() {
    return [
        '_vs2p_role'              => 'text',
        '_vs2p_org'                => 'text',
        '_vs2p_location'           => 'text',
        '_vs2p_country'            => 'text',
        '_vs2p_tier'               => 'select',
        '_vs2p_tags'               => 'text',
        '_vs2p_credentials_short'  => 'text',
        '_vs2p_experience_years'   => 'text',
        '_vs2p_qualifications'     => 'textarea',
        '_vs2p_publications'       => 'textarea',
        '_vs2p_timeline'           => 'textarea',
        '_vs2p_leadership'         => 'textarea',
        '_vs2p_website'            => 'text',
        '_vs2p_email'              => 'text',
        '_vs2p_phone'              => 'text',
    ];
}

function vs2_add_professional_meta_box() {
    add_meta_box( 'vs2_professional_details', 'Professional Details', 'vs2_render_professional_meta_box', 'professional', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vs2_add_professional_meta_box' );

function vs2_render_professional_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_professional_meta', 'vs2_professional_meta_nonce' );
    $v = fn( $key ) => esc_attr( get_post_meta( $post->ID, $key, true ) );
    $t = fn( $key ) => esc_textarea( get_post_meta( $post->ID, $key, true ) );
    $tier = get_post_meta( $post->ID, '_vs2p_tier', true ) ?: 'listed';
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
        <label for="vs2p_role">Role</label>
        <input type="text" id="vs2p_role" name="vs2p_role" value="<?php echo $v('_vs2p_role'); ?>" placeholder="e.g. General Practitioner & Researcher">
      </div>
      <div>
        <label for="vs2p_org">Organization</label>
        <input type="text" id="vs2p_org" name="vs2p_org" value="<?php echo $v('_vs2p_org'); ?>" placeholder="e.g. University of Ghana Medical School">
      </div>
      <div>
        <label for="vs2p_tier">Badge</label>
        <select id="vs2p_tier" name="vs2p_tier">
          <option value="listed" <?php selected( $tier, 'listed' ); ?>>Listed</option>
          <option value="verified" <?php selected( $tier, 'verified' ); ?>>Verified</option>
          <option value="certified" <?php selected( $tier, 'certified' ); ?>>Certified</option>
        </select>
      </div>
    </div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2p_location">Location</label>
        <input type="text" id="vs2p_location" name="vs2p_location" value="<?php echo $v('_vs2p_location'); ?>" placeholder="e.g. Accra, Ghana">
      </div>
      <div>
        <label for="vs2p_country">Country (used for filters)</label>
        <input type="text" id="vs2p_country" name="vs2p_country" value="<?php echo $v('_vs2p_country'); ?>" placeholder="e.g. Ghana">
      </div>
      <div>
        <label for="vs2p_tags">Specialty tags (comma-separated)</label>
        <input type="text" id="vs2p_tags" name="vs2p_tags" value="<?php echo $v('_vs2p_tags'); ?>" placeholder="Maternal Health, Public Health, Research">
      </div>
    </div>
    <p class="vs2-mb-hint" style="margin-bottom:14px;">Assign a <strong>Professional Field</strong> (Healthcare/Legal/Engineering/etc.) in the sidebar — it drives the directory tabs and filter dropdown.</p>

    <div class="vs2-mb-section-title">Profile header chips</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2p_credentials_short">Credentials (short, e.g. "MBBS · FWACP")</label>
        <input type="text" id="vs2p_credentials_short" name="vs2p_credentials_short" value="<?php echo $v('_vs2p_credentials_short'); ?>">
      </div>
      <div>
        <label for="vs2p_experience_years">Experience (e.g. "12 yrs experience")</label>
        <input type="text" id="vs2p_experience_years" name="vs2p_experience_years" value="<?php echo $v('_vs2p_experience_years'); ?>">
      </div>
    </div>
    <p class="vs2-mb-hint" style="margin-bottom:14px;">The publications chip count is worked out automatically from the Publications list below.</p>

    <div class="vs2-mb-section-title">Career &amp; credentials</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2p_timeline">Career timeline (one entry per line: <code>Title | Organization | Years</code>)</label>
        <textarea id="vs2p_timeline" name="vs2p_timeline" rows="4" placeholder="Consultant Physician | Korle Bu Teaching Hospital, Accra | 2021 – Present"><?php echo $t('_vs2p_timeline'); ?></textarea>
      </div>
      <div>
        <label for="vs2p_leadership">Leadership &amp; volunteer (one per line: <code>Years | Description</code>)</label>
        <textarea id="vs2p_leadership" name="vs2p_leadership" rows="4" placeholder="2016 – 2018 | President, Ghana Medical Students Association"><?php echo $t('_vs2p_leadership'); ?></textarea>
      </div>
    </div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2p_qualifications">Qualifications (one per line)</label>
        <textarea id="vs2p_qualifications" name="vs2p_qualifications" rows="4" placeholder="MBBS — University of Ghana&#10;FWACP — West African College of Physicians"><?php echo $t('_vs2p_qualifications'); ?></textarea>
      </div>
      <div>
        <label for="vs2p_publications">Publications (one per line)</label>
        <textarea id="vs2p_publications" name="vs2p_publications" rows="4" placeholder='"Maternal Mortality in Peri-Urban Ghana" · BMC Public Health, 2023'><?php echo $t('_vs2p_publications'); ?></textarea>
      </div>
    </div>

    <div class="vs2-mb-section-title">Contact &amp; relationships</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2p_website">Website</label>
        <input type="text" id="vs2p_website" name="vs2p_website" value="<?php echo $v('_vs2p_website'); ?>" placeholder="https://…">
      </div>
      <div>
        <label for="vs2p_email">Email</label>
        <input type="text" id="vs2p_email" name="vs2p_email" value="<?php echo $v('_vs2p_email'); ?>">
      </div>
      <div>
        <label for="vs2p_phone">Phone</label>
        <input type="text" id="vs2p_phone" name="vs2p_phone" value="<?php echo $v('_vs2p_phone'); ?>">
      </div>
    </div>
    <div class="vs2-mb-row">
      <?php vs2_render_relational_picker( 'vs2p_organizations_ids', 'Organizations (affiliated)', 'organization', get_post_meta( $post->ID, '_vs2p_organizations_ids', true ) ); ?>
    </div>
    <?php
}

function vs2_save_professional_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_professional_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_professional_meta_nonce'], 'vs2_save_professional_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( vs2_professional_meta_fields() as $key => $type ) {
        $field = ltrim( $key, '_' );
        if ( ! isset( $_POST[ $field ] ) ) continue;
        $value = $type === 'textarea' ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
        update_post_meta( $post_id, $key, $value );
    }
    vs2_save_relational_field( $post_id, '_vs2p_organizations_ids', 'vs2p_organizations_ids' );
}
add_action( 'save_post_professional', 'vs2_save_professional_meta' );


// ── HELPERS ──

function vs2_professional_tier( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2p_tier', true ) ?: 'listed';
}

function vs2_professional_tags( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $raw     = get_post_meta( $post_id, '_vs2p_tags', true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', explode( ',', $raw ) ) );
}

function vs2_professional_lines( $post_id, $meta_key ) {
    $raw = get_post_meta( $post_id, $meta_key, true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', preg_split( '/\r?\n/', $raw ) ) );
}

function vs2_professional_timeline( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $rows = [];
    foreach ( vs2_professional_lines( $post_id, '_vs2p_timeline' ) as $line ) {
        $parts = array_map( 'trim', explode( '|', $line, 3 ) );
        if ( count( $parts ) < 3 ) continue;
        $rows[] = [ 'title' => $parts[0], 'sub' => $parts[1], 'year' => $parts[2] ];
    }
    return $rows;
}

function vs2_professional_leadership( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $rows = [];
    foreach ( vs2_professional_lines( $post_id, '_vs2p_leadership' ) as $line ) {
        if ( strpos( $line, '|' ) === false ) continue;
        [ $when, $text ] = array_map( 'trim', explode( '|', $line, 2 ) );
        $rows[] = [ 'when' => $when, 'text' => $text ];
    }
    return $rows;
}

function vs2_all_professional_countries() {
    global $wpdb;
    $countries = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND p.post_status = 'publish'
         ORDER BY meta_value ASC",
        '_vs2p_country'
    ) );
    return $countries ?: [];
}
