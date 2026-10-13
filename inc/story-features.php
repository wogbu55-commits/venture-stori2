<?php
/**
 * "Featured in this story" — lets an editor link a blog post (story) to the
 * real business/professional profiles it's actually about.
 */

function vs2_add_story_meta_box() {
    add_meta_box( 'vs2_story_features', 'Featured in this story', 'vs2_render_story_meta_box', 'post', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'vs2_add_story_meta_box' );

function vs2_render_story_meta_box( $post ) {
    wp_nonce_field( 'vs2_save_story_meta', 'vs2_story_meta_nonce' );
    vs2_render_relational_picker( 'vs2_story_biz_ids', 'Businesses', 'profile', get_post_meta( $post->ID, '_vs2_story_biz_ids', true ) );
    vs2_render_relational_picker( 'vs2_story_pro_ids', 'Professionals', 'professional', get_post_meta( $post->ID, '_vs2_story_pro_ids', true ) );
}

function vs2_save_story_meta( $post_id ) {
    if ( ! isset( $_POST['vs2_story_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vs2_story_meta_nonce'], 'vs2_save_story_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    vs2_save_relational_field( $post_id, '_vs2_story_biz_ids', 'vs2_story_biz_ids' );
    vs2_save_relational_field( $post_id, '_vs2_story_pro_ids', 'vs2_story_pro_ids' );
}
add_action( 'save_post_post', 'vs2_save_story_meta' );

function vs2_story_featured_entities( $post_id ) {
    $out = [];
    foreach ( vs2_get_related_posts( get_post_meta( $post_id, '_vs2_story_biz_ids', true ) ) as $p ) {
        $out[] = [ 'post' => $p, 'sub' => trim( implode( ' · ', array_filter( [ 'Business', get_post_meta( $p->ID, '_vs2_location', true ) ] ) ) ) ];
    }
    foreach ( vs2_get_related_posts( get_post_meta( $post_id, '_vs2_story_pro_ids', true ) ) as $p ) {
        $out[] = [ 'post' => $p, 'sub' => get_post_meta( $p->ID, '_vs2p_role', true ) ];
    }
    return $out;
}
