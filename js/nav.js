(function () {
  var menu     = document.getElementById('vs2-mobile-menu');
  var backdrop = menu && menu.querySelector('.vs2-mobile-menu__backdrop');
  var closeBtn = menu && menu.querySelector('.vs2-mobile-menu__close');
  var trigger  = document.querySelector('.vs2-hamburger');

  function openMenu() {
    if (!menu) return;
    menu.classList.add('is-open');
    menu.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
  }

  function closeMenu() {
    if (!menu) return;
    menu.classList.remove('is-open');
    menu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
  }

  if (trigger)  trigger.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (backdrop) backdrop.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
})();
