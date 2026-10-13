<?php
/**
 * "Institution" custom post type, taxonomy, and meta box.
 */

function vs2_register_institution_cpt() {
    register_post_type( 'institution', [
        'labels' => [
            'name'          => 'Institutions',
            'singular_name' => 'Institution',
            'add_new_item'  => 'Add New Institution',
            'edit_item'     => 'Edit Institution',
            'new_item'      => 'New Institution',
            'view_item'     => 'View Institution',
            'search_items'  => 'Search Institutions',
            'not_found'     => 'No institutions found',
            'all_items'     => 'All Institutions',
        ],
        'public'       => true,
        'has_archive'  => 'institutions',
        'rewrite'      => [ 'slug' => 'institution', 'with_front' => false ],
        'menu_icon'    => 'dashicons-welcome-learn-more',
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'vs2_institution_type', 'institution', [
        'labels' => [
            'name'          => 'Institution Types',
            'singular_name' => 'Type',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'institution-type' ],
        'show_in_rest'      => true,
    ] );
}
add_action( 'init', 'vs2_register_institution_cpt' );

function vs2_default_institution_types() {
    return [ 'University', 'Hospital', 'School', 'Ministry', 'Research Institute', 'Innovation Hub' ];
}

function vs2_maybe_seed_institution_types() {
    if ( get_option( 'vs2_institution_types_seeded' ) ) return;
    foreach ( vs2_default_institution_types() as $term ) {
        if ( ! term_exists( $term, 'vs2_institution_type' ) ) {
            wp_insert_term( $term, 'vs2_institution_type' );
        }
    }
    update_option( 'vs2_institution_types_seeded', 1 );
}
add_action( 'init', 'vs2_maybe_seed_institution_types', 20 );


// ── META BOX ──

function vs2_institution_meta_fields() {
    return [
        '_vs2i_scope'               => 'text',
        '_vs2i_tier'                => 'select',
        '_vs2i_founded'             => 'text',
        '_vs2i_student_count'       => 'text',
        '_vs2i_college_count'       => 'text',
        '_vs2i_alumni_listed'       => 'text',
        '_vs2i_country'             => 'text',
        '_vs2i_departments'         => 'textarea',
        '_vs2i_claim_desc'          => 'textarea',
        '_vs2i_verification_text'   => 'textarea',
        '_vs2i_website'             => 'text',
        '_vs2i_email'               => 'text',
        '_vs2i_phone'               => 'text',
    ];
}

function vs2_add_institution_meta_box() {
    add_meta_box( 'vs2_institution_details', 'Institution Details', 'vs2_render_institution_meta_box', 'institution', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'vs2_add_institution_meta_box' );

function vs2_render_institution_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_institution_meta', 'vs2_institution_meta_nonce' );
    $v = fn( $key ) => esc_attr( get_post_meta( $post->ID, $key, true ) );
    $t = fn( $key ) => esc_textarea( get_post_meta( $post->ID, $key, true ) );
    $tier = get_post_meta( $post->ID, '_vs2i_tier', true ) ?: 'listed';
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
        <label for="vs2i_scope">Scope line (e.g. "Public · Federal")</label>
        <input type="text" id="vs2i_scope" name="vs2i_scope" value="<?php echo $v('_vs2i_scope'); ?>">
      </div>
      <div>
        <label for="vs2i_tier">Badge</label>
        <select id="vs2i_tier" name="vs2i_tier">
          <option value="listed" <?php selected( $tier, 'listed' ); ?>>Listed</option>
          <option value="verified" <?php selected( $tier, 'verified' ); ?>>Verified</option>
          <option value="certified" <?php selected( $tier, 'certified' ); ?>>Certified</option>
        </select>
      </div>
      <div>
        <label for="vs2i_founded">Founded (year)</label>
        <input type="text" id="vs2i_founded" name="vs2i_founded" value="<?php echo $v('_vs2i_founded'); ?>">
      </div>
      <div>
        <label for="vs2i_country">Country (used for filters)</label>
        <input type="text" id="vs2i_country" name="vs2i_country" value="<?php echo $v('_vs2i_country'); ?>">
      </div>
    </div>
    <p class="vs2-mb-hint" style="margin-bottom:14px;">Assign an <strong>Institution Type</strong> in the sidebar — it drives the directory tabs and filter dropdown.</p>

    <div class="vs2-mb-section-title">Stats</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2i_student_count">Students (e.g. "30,000+")</label>
        <input type="text" id="vs2i_student_count" name="vs2i_student_count" value="<?php echo $v('_vs2i_student_count'); ?>">
      </div>
      <div>
        <label for="vs2i_college_count">Colleges / Faculties label (e.g. "16 Faculties")</label>
        <input type="text" id="vs2i_college_count" name="vs2i_college_count" value="<?php echo $v('_vs2i_college_count'); ?>">
      </div>
      <div>
        <label for="vs2i_alumni_listed">Alumni listed on Venture Stori</label>
        <input type="text" id="vs2i_alumni_listed" name="vs2i_alumni_listed" value="<?php echo $v('_vs2i_alumni_listed'); ?>" placeholder="6,200">
      </div>
    </div>

    <div class="vs2-mb-section-title">Colleges &amp; Faculties</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2i_departments">One per line: <code>Name | Student count</code></label>
        <textarea id="vs2i_departments" name="vs2i_departments" rows="4" placeholder="School of Medicine | 4,200 students&#10;College of Engineering | 6,800 students"><?php echo $t('_vs2i_departments'); ?></textarea>
      </div>
    </div>

    <div class="vs2-mb-section-title">Sidebar text</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2i_claim_desc">"Claim this profile" description</label>
        <textarea id="vs2i_claim_desc" name="vs2i_claim_desc" rows="2" placeholder="Are you authorized to manage this institution's profile? Claim it to add official information and respond to enquiries."><?php echo $t('_vs2i_claim_desc'); ?></textarea>
      </div>
      <div>
        <label for="vs2i_verification_text">Verification note</label>
        <textarea id="vs2i_verification_text" name="vs2i_verification_text" rows="2" placeholder="Documentary verification completed. Registration confirmed. Last reviewed Aug 2026."><?php echo $t('_vs2i_verification_text'); ?></textarea>
      </div>
    </div>

    <div class="vs2-mb-section-title">Contact &amp; relationships</div>
    <div class="vs2-mb-row">
      <div>
        <label for="vs2i_website">Website</label>
        <input type="text" id="vs2i_website" name="vs2i_website" value="<?php echo $v('_vs2i_website'); ?>">
      </div>
      <div>
        <label for="vs2i_email">Email</label>
        <input type="text" id="vs2i_email" name="vs2i_email" value="<?php echo $v('_vs2i_email'); ?>">
      </div>
      <div>
        <label for="vs2i_phone">Phone</label>
        <input type="text" id="vs2i_phone" name="vs2i_phone" value="<?php echo $v('_vs2i_phone'); ?>">
      </div>
    </div>
    <div class="vs2-mb-row">
      <?php vs2_render_relational_picker( 'vs2i_notable_alumni_ids', 'Notable Alumni', 'professional', get_post_meta( $post->ID, '_vs2i_notable_alumni_ids', true ) ); ?>
      <?php vs2_render_relational_picker( 'vs2i_associated_orgs_ids', 'Associated Organizations', 'organization', get_post_meta( $post->ID, '_vs2i_associated_orgs_ids', true ) ); ?>
      <?php vs2_render_relational_picker( 'vs2i_related_institutions_ids', 'Related Institutions', 'institution', get_post_meta( $post->ID, '_vs2i_related_institutions_ids', true ) ); ?>
    </div>
    <?php
}

function vs2_save_institution_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_institution_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_institution_meta_nonce'], 'vs2_save_institution_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( vs2_institution_meta_fields() as $key => $type ) {
        $field = ltrim( $key, '_' );
        if ( ! isset( $_POST[ $field ] ) ) continue;
        $value = $type === 'textarea' ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
        update_post_meta( $post_id, $key, $value );
    }
    vs2_save_relational_field( $post_id, '_vs2i_notable_alumni_ids', 'vs2i_notable_alumni_ids' );
    vs2_save_relational_field( $post_id, '_vs2i_associated_orgs_ids', 'vs2i_associated_orgs_ids' );
    vs2_save_relational_field( $post_id, '_vs2i_related_institutions_ids', 'vs2i_related_institutions_ids' );
}
add_action( 'save_post_institution', 'vs2_save_institution_meta' );


// ── HELPERS ──

function vs2_institution_tier( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    return get_post_meta( $post_id, '_vs2i_tier', true ) ?: 'listed';
}

function vs2_institution_departments( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $raw     = get_post_meta( $post_id, '_vs2i_departments', true );
    if ( ! $raw ) return [];
    $rows = [];
    foreach ( preg_split( '/\r?\n/', $raw ) as $line ) {
        $line = trim( $line );
        if ( $line === '' || strpos( $line, '|' ) === false ) continue;
        [ $name, $count ] = array_map( 'trim', explode( '|', $line, 2 ) );
        $rows[] = [ 'name' => $name, 'count' => $count ];
    }
    return $rows;
}

function vs2_all_institution_countries() {
    global $wpdb;
    $countries = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = %s AND pm.meta_value != '' AND p.post_status = 'publish'
         ORDER BY meta_value ASC",
        '_vs2i_country'
    ) );
    return $countries ?: [];
}
