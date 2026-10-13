<footer class="vs2-footer">
  Venture<span>Stori</span> · Part of the Ariele Publishing Network · &copy; <?php echo esc_html( date( 'Y' ) ); ?>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.vs2-load-more').forEach(function(btn){
    var grid = document.getElementById(btn.dataset.grid);
    if (!grid) return;
    var originalText = btn.textContent;
    btn.addEventListener('click', function(){
      var nextPage = parseInt(btn.dataset.page, 10) + 1;
      btn.disabled = true;
      btn.textContent = 'Loading…';

      var body = new URLSearchParams();
      body.set('action', btn.dataset.action);
      body.set('nonce', btn.dataset.nonce);
      body.set('page', nextPage);
      body.set('query', btn.dataset.query);

      fetch(btn.dataset.ajaxurl, { method: 'POST', body: body })
        .then(function(r){ return r.json(); })
        .then(function(res){
          if (res.success && res.data && res.data.html) {
            grid.insertAdjacentHTML('beforeend', res.data.html);
          }
          btn.dataset.page = nextPage;
          if (!res.success || !res.data || !res.data.has_more) {
            btn.remove();
          } else {
            btn.disabled = false;
            btn.textContent = originalText;
          }
        })
        .catch(function(){
          btn.disabled = false;
          btn.textContent = originalText;
        });
    });
  });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
