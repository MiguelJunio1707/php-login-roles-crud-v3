  </div>
<div aria-live="polite" aria-atomic="true" class="position-relative">
  <div id="toastArea" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// THEME: load from localStorage and toggle
(function() {
  const applyTheme = (t) => document.documentElement.setAttribute('data-bs-theme', t);
  const saved = localStorage.getItem('theme');
  if (saved === 'dark' || saved === 'light') { applyTheme(saved); }
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('themeToggle');
    if (btn) {
      btn.addEventListener('click', function() {
        const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
        const next = current === 'light' ? 'dark' : 'light';
        applyTheme(next);
        localStorage.setItem('theme', next);
        showToast('Tema: ' + (next === 'dark' ? 'escuro' : 'claro'), 'success');
      });
    }
  });
})();

// TOASTS: simple API + integration with PHP flash
function showToast(message, type) {
  var toastArea = document.getElementById('toastArea');
  if (!toastArea) return;
  var bg = 'text-bg-primary';
  if (type === 'success') bg = 'text-bg-success';
  else if (type === 'warning') bg = 'text-bg-warning';
  else if (type === 'danger' || type === 'error') bg = 'text-bg-danger';
  var el = document.createElement('div');
  el.className = 'toast align-items-center ' + bg;
  el.role = 'status';
  el.ariaLive = 'polite';
  el.ariaAtomic = 'true';
  el.innerHTML = '<div class="d-flex"><div class="toast-body">'+message+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
  toastArea.appendChild(el);
  var t = new bootstrap.Toast(el, { delay: 3500 });
  t.show();
}

// If server rendered a flash-toasts script, execute it
document.addEventListener('DOMContentLoaded', function() {
  var script = document.getElementById('flashToastsScript');
  if (script && script.textContent) {
    try { (new Function(script.textContent))(); } catch (e) {}
  }
});
</script>
</body>
</html>
