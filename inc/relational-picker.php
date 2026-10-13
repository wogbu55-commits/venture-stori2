<?php
/**
 * Generic relational picker: lets an admin search for and select other posts
 * (of any post type) from a meta box field, and lets templates resolve those
 * IDs back into real WP_Post objects with working links.
 */

function vs2_render_relational_picker( $field_key, $label, $target_post_type, $selected_csv, $hint = '' ) {
    $ids = array_filter( array_map( 'absint', explode( ',', $selected_csv ) ) );
    ?>
    <div>
      <label><?php echo esc_html( $label ); ?></label>
      <div class="vs2-rel-picker" data-post-type="<?php echo esc_attr( $target_post_type ); ?>">
        <input type="hidden" name="<?php echo esc_attr( $field_key ); ?>" class="vs2-rel-ids" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
        <div class="vs2-rel-chips">
          <?php foreach ( $ids as $id ) :
              $p = get_post( $id );
              if ( ! $p ) continue;
          ?>
            <span class="vs2-rel-chip" data-id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?> <button type="button" class="vs2-rel-remove">&times;</button></span>
          <?php endforeach; ?>
        </div>
        <input type="text" class="vs2-rel-search" placeholder="Search…" autocomplete="off">
        <div class="vs2-rel-results"></div>
      </div>
      <?php if ( $hint ) : ?><p class="vs2-mb-hint"><?php echo esc_html( $hint ); ?></p><?php endif; ?>
    </div>
    <?php
}

function vs2_save_relational_field( $post_id, $meta_key, $field_name ) {
    if ( ! isset( $_POST[ $field_name ] ) ) return;
    $ids = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_POST[ $field_name ] ) ) ) );
    update_post_meta( $post_id, $meta_key, implode( ',', $ids ) );
}

function vs2_get_related_posts( $ids_csv ) {
    $ids = array_filter( array_map( 'absint', explode( ',', (string) $ids_csv ) ) );
    if ( ! $ids ) return [];
    $posts = [];
    foreach ( $ids as $id ) {
        $p = get_post( $id );
        if ( $p && $p->post_status === 'publish' ) $posts[] = $p;
    }
    return $posts;
}

function vs2_ajax_search_posts() {
    check_ajax_referer( 'vs2_rel_picker', 'nonce' );
    $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';
    $term      = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
    $allowed   = [ 'profile', 'professional', 'organization', 'institution' ];
    if ( ! in_array( $post_type, $allowed, true ) ) wp_send_json( [] );

    $q = new WP_Query( [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        's'              => $term,
        'posts_per_page' => 12,
        'fields'         => 'ids',
    ] );

    $results = [];
    foreach ( $q->posts as $id ) {
        $results[] = [ 'id' => $id, 'title' => get_the_title( $id ) ];
    }
    wp_send_json( $results );
}
add_action( 'wp_ajax_vs2_search_posts', 'vs2_ajax_search_posts' );

function vs2_rel_picker_assets( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
    global $post;
    if ( ! $post || ! in_array( $post->post_type, [ 'profile', 'professional', 'organization', 'institution', 'post' ], true ) ) return;
    $nonce = wp_create_nonce( 'vs2_rel_picker' );
    ?>
    <style>
      .vs2-rel-picker{position:relative;border:1px solid #ddd;border-radius:4px;padding:8px;background:#fff;}
      .vs2-rel-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:6px;}
      .vs2-rel-chips:empty{margin-bottom:0;}
      .vs2-rel-chip{background:#f0f0f1;border-radius:3px;padding:4px 8px;font-size:12px;display:inline-flex;align-items:center;gap:6px;}
      .vs2-rel-chip button{border:none;background:none;cursor:pointer;color:#888;font-size:14px;line-height:1;padding:0;}
      .vs2-rel-search{width:100%;box-sizing:border-box;}
      .vs2-rel-results{position:relative;border:1px solid #ddd;border-top:none;max-height:180px;overflow-y:auto;background:#fff;}
      .vs2-rel-results:empty{border:none;}
      .vs2-rel-result{padding:6px 10px;cursor:pointer;font-size:13px;}
      .vs2-rel-result:hover{background:#f0f0f1;}
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      var nonce = <?php echo wp_json_encode( $nonce ); ?>;
      document.querySelectorAll('.vs2-rel-picker').forEach(function(picker){
        var postType = picker.dataset.postType;
        var idsInput = picker.querySelector('.vs2-rel-ids');
        var chips    = picker.querySelector('.vs2-rel-chips');
        var search   = picker.querySelector('.vs2-rel-search');
        var results  = picker.querySelector('.vs2-rel-results');
        var timer;

        function currentIds(){
          return idsInput.value ? idsInput.value.split(',').filter(Boolean) : [];
        }
        function addChip(id, title){
          var ids = currentIds();
          if (ids.indexOf(String(id)) !== -1) return;
          ids.push(String(id));
          idsInput.value = ids.join(',');
          var chip = document.createElement('span');
          chip.className = 'vs2-rel-chip';
          chip.dataset.id = id;
          chip.textContent = title + ' ';
          var btn = document.createElement('button');
          btn.type = 'button';
          btn.innerHTML = '&times;';
          btn.addEventListener('click', function(){
            chip.remove();
            idsInput.value = currentIds().filter(function(x){ return x !== String(id); }).join(',');
          });
          chip.appendChild(btn);
          chips.appendChild(chip);
        }
        chips.querySelectorAll('.vs2-rel-remove').forEach(function(btn){
          btn.addEventListener('click', function(){
            var chip = btn.closest('.vs2-rel-chip');
            var id = chip.dataset.id;
            chip.remove();
            idsInput.value = currentIds().filter(function(x){ return x !== String(id); }).join(',');
          });
        });

        search.addEventListener('input', function(){
          clearTimeout(timer);
          var term = search.value.trim();
          if (!term) { results.innerHTML = ''; return; }
          timer = setTimeout(function(){
            var url = ajaxurl + '?action=vs2_search_posts&nonce=' + nonce + '&post_type=' + encodeURIComponent(postType) + '&q=' + encodeURIComponent(term);
            fetch(url).then(function(r){ return r.json(); }).then(function(items){
              results.innerHTML = '';
              items.forEach(function(item){
                var row = document.createElement('div');
                row.className = 'vs2-rel-result';
                row.textContent = item.title;
                row.addEventListener('click', function(){
                  addChip(item.id, item.title);
                  search.value = '';
                  results.innerHTML = '';
                });
                results.appendChild(row);
              });
            });
          }, 300);
        });

        document.addEventListener('click', function(e){
          if (!picker.contains(e.target)) results.innerHTML = '';
        });
      });
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'vs2_rel_picker_assets' );
